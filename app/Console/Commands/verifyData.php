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

                if($orderInfo['api']['vendor']['vendor_code'] != 'local_server') {
                    
                    $reference = $orderInfo['reference'];
                    $theProductApi = $orderInfo['api'];
                    $extraInfo = json_decode($orderInfo['extra_info'], true);

                    $verifyData = [
                        "request_id" => $reference,
                        "product_id" => $extraInfo['product'],
                        "category" => "verifyorder"
                    ];
                    
                    self::updateOrder($reference, self::sendToProvider($verifyData, $theProductApi));
                }
            }
        }
    }

    private function updateOrder($reference, $providerResponse) {
        $decodeResponse = json_decode($providerResponse->getContent(), true)["data"];
        if(isset($decodeResponse["transaction_reference"])) {
            $transactReference = $decodeResponse["transaction_reference"];
        } else if(isset($decodeResponse["ref"])) {
            $transactReference = $decodeResponse["ref"];
        }  else if(isset($decodeResponse["reference"])) {
            $transactReference = $decodeResponse["reference"];
        } else {
            $transactReference = NULL;
        }
        
        if($providerResponse->getStatusCode() === 200) {
            // decode the provider response...
            $txStatus = $decodeResponse['delivery_status'];
            
            Transaction::whereIn("status", ['0', '2'])->where(['reference' => $reference])->update([
                "status" => $txStatus, "transaction_reference" => $transactReference, "response" => json_encode($decodeResponse)
            ]);
            WalletOut::where(['status' => '0', 'reference' => $reference])->update(["status" => $txStatus]);
        } else {
            try {
                // Let's find the transaction and perform a refund if it fails...
                $findTxn = Transaction::whereIn("status", ['0', '2'])->where(['reference' => $reference])->first();
                
                if($findTxn) {
                    $extraInfo = json_decode($findTxn['extra_info'], true);
                    $description = "Refund of ".strtoupper($extraInfo['product']). " for ".$extraInfo['destination'];
                    $amountCharged = $findTxn->amount;
                    $userId = $findTxn->user_id;

                    $currentBalance = $this->walletService->getUserBalance($userId);
                    $newBalance = (float) $currentBalance + $amountCharged;
                    
                    $inwardData = [
                        "user_id" => $userId,
                        "description" => $description,
                        "old_balance" => $currentBalance,
                        "amount" => $amountCharged,
                        "new_balance" => $newBalance,
                        "reference" => $reference,
                        "status" => '1',
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
            catch(Exception $e) {
                DB::rollBack();
                Log::channel('daily')->info($reference ." => Failed to refund");
            }
        }
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
