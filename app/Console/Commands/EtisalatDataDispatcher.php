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

class EtisalatDataDispatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etisalatdata:dispatcher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For dispatching 9mobile Data Only';

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
        $fetchPendings = Transaction::where(['category' => 'data', 'status' => '0'])->where('description', 'like', '%9mobile%')->limit(1)->get();
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
                        self::updateOrder($uniqueReference, self::sendToProvider($purchaseData, $theProductApi));          
                    }
                }
            }
        }
    }

    private function updateOrder($reference, $providerResponse) {
        try {
            $decodeResponse = json_decode($providerResponse->getContent(), true)["data"];

            if($providerResponse->getStatusCode() === 200) {
                // decode the provider response...
                $txStatus = $decodeResponse['delivery_status'];
                
                Transaction::where(['status' => '0', 'reference' => $reference])->update(["status" => $txStatus, "response" => json_encode($decodeResponse)]);
                WalletOut::where(['status' => '0', 'reference' => $reference])->update(["status" => $txStatus]);
            } else {
                // Let's find the transaction and perform a refund if it fails...
                $findTxn = Transaction::where(['status' => '0', 'reference' => $reference])->first();

                if($findTxn) {
                    $extraInfo = json_decode($findTxn['extra_info'], true);
                    $description = "Refund of ".strtoupper($extraInfo['network']). " N".$extraInfo['amount']. " for ".$extraInfo['destination'];
                    $amountCharged = $findTxn->amount;
                    $userId = $findTxn->user_id;
        
                    $currentBalance = $this->walletService->getUserBalance($userId);
                    $newBalance = (float) $currentBalance + $amountCharged;
        
                    DB::beginTransaction();
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

                    Transaction::create([
                        "user_id" => $userId,
                        "plan" => $findTxn->plan,
                        "description" => $description,
                        "destination" => $findTxn->destination,
                        "old_balance" => $currentBalance,
                        "amount" => $amountCharged,
                        "new_balance" => $newBalance,
                        "costprice" => $amountCharged,
                        "category" => $findTxn->category,
                        "reference" => $findTxn->reference,
                        "response" => "Wallet Refunded",
                        "status" => "4",
                        "channel" => "website",
                        "api_id" => $findTxn->api_id
                    ]);
                    
                    $this->walletService->createWallet('inward', $inwardData);
                    $findTxn->status = '3';
                    $findTxn->response = $decodeResponse;
                    $findTxn->save(); 
                    WalletOut::where(['status' => '0', 'reference' => $reference])->update(["status" => "3"]);
                    DB::commit();
                    return;
                }

                DB::rollBack();
                Log::channel('daily')->info($reference ." => Reference ($reference) not found");
                return;
            }
        }
        catch(Exception $e) {
            DB::rollBack();
            Log::channel('daily')->info($e->getMessage());
        }
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
        }
        return $submitOrder;
    }
    
}