<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\WalletOut;
use App\Models\Transaction;
use App\Http\Traits\ResponseTrait;
use App\Services\AppServerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ConnectService  {
    use ResponseTrait;
    
    protected $appServerService, $walletService, $utilityService;
    public function __construct(UtilityService $utilityService, WalletService $walletService, AppServerService $appServerService)
    {
        $this->appServerService = $appServerService;
        $this->utilityService = $utilityService;
        $this->walletService = $walletService;
    }
    
    public function index($serverId) : array {
        $fetchServer = $this->appServerService->getAppServer($serverId);
        $appUrl = Config::get('app.url');
        
        if($fetchServer) {
            return [
                "call_url" => $appUrl."connection/$serverId/calling.php",
                "processed_url" => $appUrl."connection/$serverId/process.php",
                "screen_url" => $appUrl."connection/$serverId/screen.php",
                "report_url" => $appUrl."connection/$serverId/report.php",
                "server_id" => $serverId,
                "calling_time" => (string) $fetchServer->calling_time,
                "color" => (string) $fetchServer->app_color_scheme,
                "auth_code" => $fetchServer->auth_code
            ];
        }
        return [];
    } 
    
    public function calling($serverId) {
        $fetchServer = $this->appServerService->getAppServer($serverId);
        
        if($fetchServer) {
            $findTxn = $this->getSearchQuery($fetchServer->category);
            
            if($findTxn) {
                $extraInfo = json_decode($findTxn->extra_info);
                if($findTxn->category == 'airtime') {
                    $appDescription = $extraInfo->network. " N". $extraInfo->amount. " for ".$findTxn->destination;
                } else {
                    $appDescription = $extraInfo->product. " for ".$findTxn->destination;
                }
                $orderData = [
                    "id" => $findTxn->reference,
                    "ussd" => $findTxn->ussd_code, 
                    "phoneno" => $findTxn->destination,
                    "description" => strtoupper($appDescription)
                ];
                return json_encode($orderData, JSON_PRETTY_PRINT);
            }            
        }
        return [];
    } 

    public function process($reference) {
        return Transaction::where(['reference' => $reference, 'status' => '0'])->update(['status' => '2']);
    }

    public function updateOrder($serverId, $reference, $response) {
        
        $fetchServer = $this->appServerService->getAppServer($serverId);

        if($fetchServer) {
            $explodeResponse = explode(" ", $response);

            $mobileNumber = NULL;
            
            foreach($explodeResponse as $splitResponse) {
                $splitResponse = str_replace(array(" ", ",","."), "", $splitResponse);
                if(is_numeric($splitResponse) && substr($splitResponse,0, 3) == "234") {
                   $mobileNumber[] = $splitResponse;
                } else if(is_numeric($splitResponse) && strlen($splitResponse) == 11) {
                   $mobileNumber[] = $splitResponse;
                }
            }
            
            $mobileNumber = $this->utilityService->reformMobileNumber($mobileNumber);

            $findOrder = Transaction::where(['destination' => $mobileNumber, 'status' => '2']);
            
            if($reference != "") {
                $findOrder->where('reference', $reference);
            }
            
            $txnInfo = $findOrder->first();

            $reference = $reference != "" ? $reference : $txnInfo->reference;
            
            $lowerResponse = strtolower($response);
            $validForRefund = $updateQuery = false;
            
            if(strpos($lowerResponse, "receiver account not found") !== false OR strpos($lowerResponse, "transaction failed") !== false 
                OR strpos($lowerResponse, "not sending") !== false OR strpos($lowerResponse, "wrong command") !== false
                OR strpos($lowerResponse, "transaction rejected") !== false OR strpos($lowerResponse, "unsuccessful") !== false
                OR strpos($lowerResponse, "subscriber not found") !== false OR strpos($lowerResponse, "insufficient") !== false 
                OR strpos($lowerResponse, "not a valid") !== false       
            ) {
                $updateTxn = Transaction::where(['destination' => $mobileNumber, 'status' => '2']);
                $validForRefund = true;
            }
            else if(strpos($lowerResponse, "graceno" ) !== false OR strpos($lowerResponse, "oops, looks like" ) !== false
                 OR strpos($lowerResponse, "respond in time" ) !== false OR strpos($lowerResponse, "connection" ) !== false
                 OR strpos($lowerResponse, "invalid action" ) !== false OR strpos($lowerResponse, "activation of" ) !== false
                 OR strpos($lowerResponse, "service is currently unavailable" ) !== false OR strpos($lowerResponse, "failed to run" ) !== false 
                 OR strpos($lowerResponse, "invalid token" ) !== false OR strpos($lowerResponse, "user input" ) !== false
                 OR strpos($lowerResponse, "operation failed" ) !== false OR strpos($lowerResponse, "service is down" ) !== false
                 OR strpos($lowerResponse, "failed" ) !== false
            ) {
                $updateQuery = Transaction::where(['destination' => $mobileNumber, 'status' => '2', 'reference' => $reference]);
                $updateQuery->update(["status" => '0']);
                
                return true;
            }
            else if(strpos($lowerResponse, "topped" ) !== false OR strpos($lowerResponse, "successfully" ) !== false OR strpos($lowerResponse, "bal" ) !== false) {
                $updateTxn = Transaction::where(['destination' => $mobileNumber, 'status' => '2']);
            }
            else {
                
            }
            
            if($validForRefund) {
                $updateTxn->update(["status" => '3', "response" => $response]);

                // Let's credit the user wallet back...
                $extraInfo = json_decode($txnInfo['extra_info'], true);
                
                if($txnInfo['extra_info'] == "airtime") {
                    $description = "Refund of ".strtoupper($extraInfo['network']. " ".$extraInfo['amount']). " for ".$extraInfo['destination'];
                }
                else {
                    $description = "Refund of ".strtoupper($extraInfo['product']). " for ".$extraInfo['destination'];
                } 

                $amountCharged = (float) $txnInfo['amount'];
                $userId = $txnInfo->user_id;
                
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
                    "status" => '1',
                    'remark' => json_encode(['approved_by' => 'System Refund'])
                ];
                
                $this->walletService->createWallet('inward', $inwardData);
                WalletOut::where(['status' => '0', 'reference' => $reference])->update(["status" => "3"]);

                DB::commit();
                return $this->sendResponse("Order Refunded", [], 200);
            }
            $updateTxn->update(["status" => '1', "response" => $response]);
            
            return true;
        }
        return [];
    }

    private function getSearchQuery($category) {
        if($category == "mtnairtime") {
            $runQuery = Transaction::where(['category' => 'airtime', 'status' => '0'])->where('description', 'like', '%mtn%')->first();
        } else if ($category == "gloairtime") {
            $runQuery = Transaction::where(['category' => 'airtime', 'status' => '0'])->where('description', 'like', '%glo%')->first();
        } else if ($category == "etiairtime") {
            $runQuery = Transaction::where(['category' => 'airtime', 'status' => '0'])->where('description', 'like', '%9mobile%')->first();
        } else if ($category == "airtelairtime") {
            $runQuery = Transaction::where(['category' => 'airtime', 'status' => '0'])->where('description', 'like', '%airtel%')->first();
        } else if( $category == "mtnsme_gift") {
            $runQuery = Transaction::where(['category' => 'data', 'status' => '0'])->where('description', 'like', '%mtn%')->where('description', 'not like', '%direct%')->first();
        } else if ($category == "mtndirect") {
            $runQuery = Transaction::where(['category' => 'data', 'status' => '0'])->where('description', 'like', '%mtn%')->where('description', 'like', '%direct%')->first();
        } else if ($category == "glodata") {
            $runQuery = Transaction::where(['category' => 'data', 'status' => '0'])->where('description', 'like', '%glo%')->first();
        } else if ($category == "airteldata") {
            $runQuery = Transaction::where(['category' => 'data', 'status' => '0'])->where('description', 'like', '%airtel%')->first();
        } else {
            $runQuery = Transaction::where(['category' => 'data', 'status' => '0'])->where('description', 'like', '%9mobile%')->first();
        }
        return $runQuery;
    }
    
}