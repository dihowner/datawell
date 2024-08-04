<?php

namespace App\Console\Commands;

use Exception;
use App\Models\WalletOut;
use App\Vendors\MobileNig;
use App\Models\Transaction;
use App\Services\WalletService;
use App\Vendors\Smeplug;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class verifyData extends Command
{

    protected $productService, $dataRequest, $walletService;
    public function __construct(WalletService $walletService)
    {
        parent::__construct();
        $this->walletService = $walletService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is to verify data from the provider';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return self::fetchAwaitingData();
        return Command::SUCCESS;
    }

    private function fetchAwaitingData() {
        $getTransactions = Transaction::with('api.vendor')->where(['status' => '2', 'category' => 'data'])->limit(10)->get();
        if(count($getTransactions) > 0) {
            foreach($getTransactions as $orderInfo) {
                $vendorCode = strtolower($orderInfo['api']['vendor']['vendor_code']);
                if($vendorCode != 'local_server') {
                    
                    $reference = $orderInfo['reference'];
                    $theProductApi = $orderInfo['api'];
                    $extraInfo = json_decode($orderInfo['extra_info'], true);

                    $verifyData = [
                        "request_id" => $reference,
                        "product_id" => $extraInfo['product'],
                        "category" => "verifyorder",
                        "ignoreCron" => true
                    ];

                    $response = self::sendToProvider($verifyData, $theProductApi);

                    self::updateOrder($reference, $response, $vendorCode);
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
    private function sendToProvider($verifyData, $apiDetails) {
        // Get Vendor Code....
        $vendorCode = $apiDetails["vendor"]["vendor_code"];  
        
        switch($vendorCode) {
            case "mobilenig":
                // Let's prepare some key info about the delivery of the order...
                $connectVendor = app(MobileNig::class);
                $submitOrder = $connectVendor->processRequest($verifyData, $apiDetails);
            break;
            
            case "smeplug":
                // Let's prepare some key info about the delivery of the order...
                $connectVendor = app(Smeplug::class);
                $submitOrder = $connectVendor->processRequest($verifyData, $apiDetails);
            break;
        }
        return $submitOrder;
    }
}
