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
                    "service_id" => $bodyRequest["provider_service_id"],
                    "trans_id"=> $bodyRequest["request_id"],
                    "service_type"=> "STANDARD",
                    "phoneNumber"=> $bodyRequest["phone_number"],
                    "amount"=> $bodyRequest['amount'],
                ];
                    
                $processOrder = HttpRequest::sendPost($this->endpoint, $requestPayload, $authHeader);
                return $this->checkPurchaseResponse($processOrder->body());
            break;

            case "data":
                $requestPayload = [
                    "network_id" => self::getNetworkIdWithProductId($bodyRequest['product_id']),
                    "plan_id" => $bodyRequest['provider_service_id'],
                    "phone"=> $bodyRequest["phone_number"],
                    "customer_reference"=> $bodyRequest["request_id"]
                    // "phone"=> "08155577122",
                    // "customer_reference"=> mt_rand(1111, 9090)
                ];

                $processOrder = HttpRequest::sendPost($this->endpoint.'data/purchase', $requestPayload, $authHeader);
                return $this->checkPurchaseResponse($processOrder->body());
            break;
            
            case "verifyorder":
                $reference = $bodyRequest["request_id"];
                $verifyOrder = self::verifyOrder($reference, $authHeader);
                return self::checkPurchaseResponse($verifyOrder);
            break;
        }
        
        return $this->sendError("Product service code does not exist", [], 404);        

    }

    private function checkPurchaseResponse($apiResponse) {
        try {
            $decode_response = json_decode($apiResponse, true);
            
            if(strtolower($decode_response['status']) == "success") {
                $reformResponse = $decode_response;
                $reformResponse['delivery_status'] = "1";
                return $this->sendResponse("Success", $reformResponse, 200);
            }
            else if(strtolower($decode_response['status']) == "pending" OR $decode_response['status'] === true) {
                $reformResponse = $decode_response;
                $reformResponse['delivery_status'] = "2";
                return $this->sendResponse("Success", $reformResponse, 200);
            }
            else {
                return $this->sendError("Error", $decode_response, 400);
            }
        }
        catch(Exception $e) {
            return $e->getMessage();
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
        return $fetchService->body();
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