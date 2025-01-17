<?php

namespace App\Console\Commands;

use Exception;
use App\Models\WalletOut;
use App\Vendors\MobileNig;
use App\Models\Transaction;
use App\Services\WalletService;
use Illuminate\Console\Command;
use App\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\DataRequestService;
use App\Vendors\Smeplug;

class MtnDataDispatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mtndata:dispatcher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For dispatching MTN Data Only';

    protected $productService, $dataRequest, $walletService;
    public function __construct(ProductService $productService, DataRequestService $dataRequest, WalletService $walletService)
    {
        parent::__construct();
        $this->productService = $productService;
        $this->dataRequest = $dataRequest;
        $this->walletService = $walletService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        self::fetchData();
    }

    private function fetchData() {
        $fetchPendings = Transaction::where(['category' => 'data', 'status' => '0'])->where('description', 'like', '%mtn%')->limit(10)->get();
        if(count($fetchPendings) > 0) {
            foreach($fetchPendings as $pendingIndex => $pendingOrder) {
                $extraInfo = json_decode($pendingOrder['extra_info'], true);
                $product = $extraInfo['product'];
                $destination = $extraInfo['destination'];
                $uniqueReference = $pendingOrder['reference'];

                // We need to know information about the product if available or not.
                $theProduct = $this->productService->getProductWithLike($product);
                if($theProduct !== false) {
                    $productId = $theProduct->product_id;
                    
                    // Get the dataRequest code of the vendor...
                    $vendorRequest = $this->dataRequest->getDataRequest($productId);
                    
                    $vendorCode = $theProduct->api->vendor->vendor_code;
                    $theProductApi = $theProduct->api;
                    
                    if($vendorCode != 'localserver') {
                        $purchaseData = [
                            "product_id" => $productId,
                            "phone_number" => $destination,
                            "request_id" => $uniqueReference,
                            "provider_service_id" => $vendorRequest[$vendorCode],
                            "category" => "data",
                            "ignoreCron" => true
                        ];
                        self::updateOrder($uniqueReference, self::sendToProvider($purchaseData, $theProductApi), $vendorCode);          
                    }
                }
            }
        }
    }

    private function updateOrder($reference, $providerResponse, $vendorCode) {
        DB::transaction(function () use ($reference, $providerResponse, $vendorCode) {
            try {
                $decodedResponse = json_decode($providerResponse->getContent(), true)["data"];
                
                if ($providerResponse->getStatusCode() === 200) {
                    // Update transaction and wallet status on successful response
                    $txStatus = $decodedResponse['delivery_status'];
                    Transaction::where(['status' => '0', 'reference' => $reference])->update([
                        "status" => $txStatus, 
                        "response" => json_encode($decodedResponse)
                    ]);
                    WalletOut::where(['status' => '0', 'reference' => $reference])->update([
                        "status" => $txStatus
                    ]);
                } else {
                    // Handle failed transaction by performing a refund
                    $transaction = Transaction::where(['status' => '0', 'reference' => $reference])->first();
    
                    if ($transaction) {
                        if ($vendorCode != 'mobilenig') {
                            $extraInfo = json_decode($transaction['extra_info'], true);
                            $description = "Refund of " . strtoupper($extraInfo['network']) . " N" . $extraInfo['amount'] . " for " . $extraInfo['destination'];
                            $amountCharged = $transaction->amount;
                            $userId = $transaction->user_id;
        
                            $currentBalance = $this->walletService->getUserBalance($userId);
                            $newBalance = (float)$currentBalance + $amountCharged;
        
                            $inwardData = [
                                "user_id" => $userId,
                                "description" => $description,
                                "old_balance" => $currentBalance,
                                "amount" => $amountCharged,
                                "new_balance" => $newBalance,
                                "reference" => $reference,
                                "status" => '3',
                                'remark' => json_encode(['approved_by' => 'System Refund'])
                            ];
        
                            // Create refund transaction
                            Transaction::create([
                                "user_id" => $userId,
                                "plan" => $transaction->plan,
                                "description" => $description,
                                "destination" => $transaction->destination,
                                "old_balance" => $currentBalance,
                                "amount" => $amountCharged,
                                "new_balance" => $newBalance,
                                "costprice" => $amountCharged,
                                "category" => $transaction->category,
                                "reference" => $transaction->reference,
                                "response" => "Wallet Refunded",
                                "status" => "4",
                                "channel" => "website",
                                "api_id" => $transaction->api_id
                            ]);
        
                            $this->walletService->createWallet('inward', $inwardData);
                            
                            // Update the original transaction and wallet out status
                            $transaction->update([
                                "status" => '3',
                                "response" => json_encode($decodedResponse)
                            ]);
                            WalletOut::where(['status' => '0', 'reference' => $reference])->update(["status" => "3"]);
                        } else {
                            Transaction::where(['status' => '0', 'reference' => $reference])->update([
                                "status" => '2', 
                                "response" => json_encode($decodedResponse)
                            ]);
                            WalletOut::where(['status' => '0', 'reference' => $reference])->update([
                                "status" => '2'
                            ]);
                        }
                    } else {
                        Log::channel('daily')->info("Reference ($reference) not found");
                    }
                }
            } catch (Exception $e) {
                Log::channel('daily')->error("Error updating order for reference ($reference): " . $e->getMessage());
                throw $e;
            }
        });
    }
    
    // Let's send the request to the provider
    private function sendToProvider($purchaseData, $apiDetails) {
        // Get Vendor Code....
        $vendorCode = $apiDetails["vendor"]["vendor_code"];        
        switch($vendorCode) {
            case "mobilenig":
                // Let's prepare some key info about the delivery of the order...
                $connectVendor = app(MobileNig::class);
                $submitOrder = $connectVendor->processRequest($purchaseData, $apiDetails);
            break;
            
            case "smeplug":
                // Let's prepare some key info about the delivery of the order...
                $connectVendor = app(Smeplug::class);
                $submitOrder = $connectVendor->processRequest($purchaseData, $apiDetails);
            break;
        }
        return $submitOrder;
    }   
}