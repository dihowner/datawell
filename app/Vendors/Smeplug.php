<?php
namespace App\Vendors;

use Exception;
use App\Classes\HttpRequest;
use App\Http\Traits\ResponseTrait;

/**
 * This class is written based on the documentation provided by the provider.
 * Kindly read clearly before modifying any unit of this code.
 *
 * Goodluck Dev
 * 0903-302-4846
 */

class Smeplug {
    use ResponseTrait;
    protected $apiService;
    private $endpoint = "https://smeplug.ng/api/v1/";
    
    public function processRequest($bodyRequest, $apiDetails) {

        $authHeader = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer ".$apiDetails['api_secret_key']
        ];

        switch($bodyRequest['category']) {
            case "airtime":
                $requestPayload = [
                    "network_id" => self::getNetworkIdWithProductId($bodyRequest['product_id']),
                    "customer_reference"=> $bodyRequest["request_id"],
                    "phone"=> $bodyRequest["phone_number"],
                    "amount"=> $bodyRequest['amount'],
                ];
                $processOrder = HttpRequest::sendPost($this->endpoint.'airtime/purchase', $requestPayload, $authHeader);
                
                // $processOrder = '{"status": false, "data": {"reference": "011b0400c8668b1934c4", "msg": "NGN50 airtime purchase for 09033024846"}}';
                return $this->checkPurchaseResponse($processOrder);
            break;

            case "data":
                $requestPayload = [
                    "network_id" => self::getNetworkIdWithProductId($bodyRequest['product_id']),
                    "plan_id" => $bodyRequest['provider_service_id'],
                    "phone" => $bodyRequest["phone_number"],
                    "customer_reference" => $bodyRequest["request_id"],
                    // "phone" => "08155577122",
                    // "customer_reference" => mt_rand(1111, 9090)
                ];

                $processOrder = HttpRequest::sendPost($this->endpoint.'data/purchase', $requestPayload, $authHeader);
                // $processOrder = '{"status":true,"data":{"reference":"3cc6c275b2d1466b84a8","msg":"Dear Customer, You have successfully shared 500MB Data to 2349033024846. Your SME data balance is 416.55GB expires 07\/11\/2024. Thankyou"}}';
                // $processOrder = '{"status":false, "msg":"MTN SME portal service is currently unavailable"}';
                // $processOrder = '{"status":true,"data":{"reference":"4377c95080118e36fc66","msg":"You are not sending to valid MTN number.. SME Data Balance: 461.02GB"}}';
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
            $apiResponse = is_array($apiResponse) ? json_encode($apiResponse) : $apiResponse;
            $decode_response = is_array($apiResponse) ? $apiResponse : json_decode($apiResponse, true);
            
            $orderStatus = $decode_response['status'];
            $message = $decode_response['data']['msg'] ?? $decode_response['msg'] ?? "Unknown error. Please try again, or contact support if the error persists.";

            if ($this->getStatusByMsg($message) AND $orderStatus === true) {
                if(strtolower($orderStatus) == "pending") {
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
                $decodeMsg = $decode_response['msg'] ?? $decode_response['data']['msg'] ?? "Unknown error. Please try again, or contact support if the error persists.";
                if (str_contains(strtolower($decodeMsg), "is lower") OR str_contains(strtolower($decodeMsg), "minimum") 
                    OR str_contains(strtolower($decodeMsg), "nin") OR str_contains(strtolower($decodeMsg), "barred") OR str_contains(strtolower($decodeMsg), "not") 
                    OR str_contains(strtolower($decodeMsg), "error") OR str_contains(strtolower($decodeMsg), "invalid")) {
                    return $this->sendError($decodeMsg, [], 400);
                }
                return $this->sendError($decodeMsg, [], 400);
            }
        }
        catch(Exception $e) {
            return $this->sendError("Error", $e->getMessage(), 500);
        }
    }

    private function getStatusByMsg($message) {
        try {
            $message = strtolower($message);
            if (str_contains($message, "is lower") OR str_contains($message, "minimum") 
                OR str_contains($message, "nin") OR str_contains($message, "barred") OR str_contains($message, "not") 
                OR str_contains($message, "error") OR str_contains($message, "invalid")) {
                return false;
            }
            return true;
        } catch(Exception $e) {
            throw $e;
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