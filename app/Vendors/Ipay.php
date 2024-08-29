<?php
namespace App\Vendors;

use Exception;
use App\Classes\HttpRequest;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

/**
 * This class is written based on the documentation provided by the provider.
 * Kindly read clearly before modifying any unit of this code.
 *
 * Goodluck Dev
 * 0903-302-4846
 */

class Ipay {
    use ResponseTrait;
    protected $apiService;
    private $endpoint = "https://ipay.com.ng/api/v2/";
    
    public function processRequest($bodyRequest, $apiDetails) {

        $authHeader = [
            "Content-Type" => "application/json"
        ];

        switch($bodyRequest['category']) {
            case "airtime":
                $requestPayload = [
                    "product_code" => $bodyRequest['provider_service_id'],
                    "customer_reference"=> $bodyRequest["request_id"],
                    "phone"=> $bodyRequest["phone_number"],
                    "amount"=> $bodyRequest['amount'],
                    "api_key" => $apiDetails['api_public_key']
                ];
                $processOrder = HttpRequest::sendPost($this->endpoint.'airtime/index.php', $requestPayload, $authHeader);
                
                return $this->checkPurchaseResponse($processOrder);
            break;

            case "data":

                $requestPayload = [
                    "product_code" => $bodyRequest['provider_service_id'],
                    "phone"=> $bodyRequest["phone_number"],
                    "customer_reference"=> $bodyRequest["request_id"],
                    "api_key" => $apiDetails['api_public_key']
                ];

                $processOrder = HttpRequest::sendPost($this->endpoint.'datashare/index.php', $requestPayload, $authHeader);
                return $this->checkPurchaseResponse($processOrder);
            break;
            
            case "verifyorder":
                $reference = $bodyRequest["request_id"];
                $verifyOrder = self::verifyOrder($reference, $authHeader);
                return $this->checkPurchaseResponse($verifyOrder);
            break;
        }
        
        return $this->sendError("Product service code does not exist", [], 404);        

    }

    private function checkPurchaseResponse($apiResponse) {
        try {

            $decode_response = is_array($apiResponse) ? $apiResponse : json_decode($apiResponse, true);
            
            $orderStatus = $decode_response['status'];
            $textStatus = $decode_response['text_status'];
            $trueResponse = $decode_response['data']['true_response'];
            if ($orderStatus === true) {
                $reformResponse['message'] = $trueResponse ?? "Transaction successful. ";
                if(str_contains(strtolower($textStatus), "pend")) {
                    $reformResponse = $decode_response;
                    $reformResponse['delivery_status'] = "2";
                    return $this->sendResponse("Success", $reformResponse, 200);
                }
                else {
                    $reformResponse = $decode_response;
                    $reformResponse['delivery_status'] = "1";
                    return $this->sendResponse("Success", $reformResponse, 200);
                }
            }
            else {
                $decodeMsg = $trueResponse ?? "Unknown error. Please try again, or contact support if the error persists.";
                if (str_contains(strtolower($decodeMsg), "is lower") OR str_contains(strtolower($decodeMsg), "minimum") 
                    OR str_contains(strtolower($decodeMsg), "nin") OR str_contains(strtolower($decodeMsg), "barred")) {
                    return $this->sendError("Error", $decodeMsg, 400);
                } 
                else if (str_contains(strtolower($decodeMsg), "msisdn")) {
                    return $this->sendError("Error", "Invalid phone number provided. Please provide a valid phone number", 400);
                }
                return $this->sendError("Error", "Transaction failed", 400);
            }
        }
        catch(Exception $e) {
            return $this->sendError("Error", $e->getMessage(), 500);
        }
    }

    private function verifyOrder($reference, $authHeader) {
        return HttpRequest::sendGet($this->endpoint."transactions/$reference", "", $authHeader);
    }
    
    public function getDataService($publicKey) {
        $authHeader = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer ".$publicKey
        ];
        
        $fetchService = HttpRequest::sendGet($this->endpoint."data/plans", "", $authHeader);
        return $fetchService;
    }

    private function getNetworkIdWithProductId($productId) {
        $productId = strtolower($productId);
        if(strpos($productId, 'mtn') !== false) {
            $networkId = 1; 
        } else if(strpos($productId, 'airtel') !== false) {
            $networkId = 2; 
        } else if(strpos($productId, '9mobile') !== false) {
            $networkId = 3; 
        } else if(strpos($productId, 'glo') !== false) {
            $networkId = 4; 
        }
        return $networkId;
    }
    
}
?>