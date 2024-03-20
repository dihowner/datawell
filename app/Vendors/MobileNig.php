<?php
namespace App\Vendors;

use Exception;
use Illuminate\Support\Arr;
use App\Classes\HttpRequest;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;

/**
 * This class is written based on the documentation provided by the provider.
 * Some tweaking takes place bcos we need to be calling the provider endpoint in getting cost of products
 * Kindly read clearly before modifying any unit of this code.
 *
 * Goodluck Dev
 * 0903-302-4846
 */

class MobileNig {
    use ResponseTrait;
    protected $apiService;
    private $endpoint = "https://enterprise.mobilenig.com/api/services/";

    public function processRequest($bodyRequest, $apiDetails) {

        if(isset($bodyRequest['ignoreCron']) AND $bodyRequest['ignoreCron'] === true) {
            $apiDetails['api_delivery_route'] = "instant";
        }
        
        if($apiDetails["api_delivery_route"] == "cron") {
            return $this->sendResponse("Success", json_encode([
                    "message" => "Order received for processing",
                    "delivery_status" => 0
                ]), 200);
        }
        
        $authHeader = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer ".$apiDetails['api_secret_key']
        ];
        
        // Mobile Nig needs service Id for all product purchase except airtime
        $serviceProductId = isset($bodyRequest["service"]) ? $bodyRequest["service"] : $bodyRequest["product_id"];
        $serviceId =  self::getServiceId($bodyRequest['category'], $serviceProductId);
                
        if($serviceId !== false) {
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
                    return $this->checkPurchaseResponse($processOrder);
                break;
    
                case "data":
                    $requestType = self::getRequestType($bodyRequest["product_id"]);
    
                    $requestPayload = [
                        "service_id" => $serviceId,
                        "service_type" => $requestType,
                        "beneficiary"=> $bodyRequest["phone_number"],
                        // "trans_id"=> $bodyRequest["request_id"],
                        "trans_id"=> mt_rand(11, 90) * mt_rand(1, 9),
                        "code"=> $bodyRequest["provider_service_id"],
                        "amount" => self::getPackagePrice($serviceId, $requestType, $bodyRequest["provider_service_id"], $apiDetails)
                    ];
                    $processOrder = HttpRequest::sendPost($this->endpoint, $requestPayload, $authHeader);
                    return $this->checkPurchaseResponse($processOrder);
                break;
    
                case "verifycabletv":
                    $requestPayload = [ "service_id" => $serviceId, "customerAccountId" => $bodyRequest["smart_number"] ];
    
                    $authHeader = [
                        "Content-Type" => "application/json",
                        "Authorization" => "Bearer ".$apiDetails['api_public_key']
                    ];
    
                    // Since gotv & dstv almost have same response, let's create a category for it...
                    if(in_array(strtolower($bodyRequest["service"]), ["dstv", "gotv"])) {
                        $category = "multichoice";
                    } else {
                        $category = strtolower($bodyRequest["service"]);
                    }
                    $processOrder = HttpRequest::sendPost($this->endpoint."proxy", $requestPayload, $authHeader);
                    return $this->checkVerificationResponse($processOrder, $category);
                break;
    
                case "cabletv":
                
                    if(isset($bodyRequest['amount']) AND $bodyRequest['amount'] > 0) {
                        $requestPayload = [
                            "service_id" => $serviceId,
                            "trans_id"=> $bodyRequest["request_id"],
                            "customerName" => $bodyRequest['customer_name'],
                            "smartcardNumber" => $bodyRequest["smart_number"],
                            "amount" => $bodyRequest['amount']
                        ];
                    }
                    else {
                        $requestPayload = [
                            "service_id" => $serviceId,
                            "trans_id"=> $bodyRequest["request_id"],
                            "customerName" => $bodyRequest['customer_name'],
                            "smartcardNumber" => $bodyRequest["smart_number"],
                            "productCode" => $bodyRequest['provider_service_id'],
                            "amount" => self::getPackagePrice($serviceId, "", $bodyRequest["provider_service_id"], $apiDetails)
                        ];
                    }
    
                    $requestPayload["customerNumber"] = isset($bodyRequest["customer_number"]) ? $bodyRequest["customer_number"]:$bodyRequest["smart_number"];
    
                    $processOrder = HttpRequest::sendPost($this->endpoint, $requestPayload, $authHeader);
                    return $this->checkPurchaseResponse($processOrder);
                break;
    
                case "education":
                    $requestPayload = [
                        "service_id" => $serviceId,
                        "trans_id"=> $bodyRequest["request_id"],
                        "quantity" => (int) $bodyRequest["quantity"],
                        "amount" => self::getPackagePrice($serviceId, "", "", $apiDetails)
                    ];
                    $processOrder = HttpRequest::sendPost($this->endpoint, $requestPayload, $authHeader);
                    return $this->checkPurchaseResponse($processOrder);
                break;
    
                case "verifyelectricity":                    
                    $requestPayload = [ "service_id" => $serviceId, "customerAccountId" => $bodyRequest["meter_number"] ];
    
                    $authHeader = [
                        "Content-Type" => "application/json",
                        "Authorization" => "Bearer ".$apiDetails['api_public_key']
                    ];
    
                    $processOrder = HttpRequest::sendPost($this->endpoint."proxy", $requestPayload, $authHeader);
                    return $this->checkVerificationResponse($processOrder, "electricity");
                break;
    
                case "electricity":
                    $productId = $bodyRequest['product_id'];
                    
                    $requestPayload = [
                        "service_id" => $serviceId,
                        "trans_id"=> $bodyRequest["request_id"],
                        "customerName" => $bodyRequest["customer_name"],
                        "customerReference" => $bodyRequest["meter_number"],
                        "customerAddress" => $bodyRequest["customer_address"],
                        "amount" => $bodyRequest["amount"]
                    ];
                    
                    if(strpos($productId, "phedc") !== false AND (strpos($productId, "prepaid") !== false OR strpos($productId, "postpaid") !== false)) {
                        $requestPayload["customerEmail"] = "oluwatayoadeyemi@yahoo.com";
                        $requestPayload["customerNumber"] = "09033024846";
                        $requestPayload["customerPhone"] = "09033024846";
                        $requestPayload["referenceId"] = $bodyRequest["customer_reference_id"];
                        $requestPayload["customerDetails"] = $bodyRequest["customer_details"];
                    }

                    if(strpos($productId, "ibedc") !== false AND (strpos($productId, "prepaid") !== false OR strpos($productId, "postpaid") !== false)) {
                        $requestPayload["serviceBand"] = "D8H";
                        $requestPayload["thirdPartyCode"] = 21;
                    }

                    if(strpos($productId, "ikedc") !== false AND (strpos($productId, "prepaid") !== false OR strpos($productId, "postpaid") !== false)) {
                        $requestPayload["customerAccountType"] = $bodyRequest["customer_account_type"];
                        $requestPayload["customerDtNumber"] = $bodyRequest["customer_dt_number"];
                        $requestPayload["meterNumber"] = $bodyRequest["meter_number"];
                        $requestPayload["phoneNumber"] = "09033024846";
                        $requestPayload["contactType"] = "TENANT";
                        $requestPayload["email"] = "oluwatayoadeyemi@yahoo.com";
                        
                        Arr::forget($requestPayload, "customerReference");
                    }
                    else if(strpos($productId, "kedc") !== false AND (strpos($productId, "prepaid") !== false OR strpos($productId, "postpaid") !== false)) {
                        $requestPayload["tariffCode"] = $bodyRequest['customer_tariff_code'];
                    }

                    if(strpos($productId, "kaedc") !== false AND (strpos($productId, "prepaid") !== false OR strpos($productId, "postpaid") !== false)) {
                        $requestPayload["tariff"] = "R2S";
                        $requestPayload["meterNumber"] = $bodyRequest["meter_number"];
                        $requestPayload["customerMobileNumber"] = "09033024846";
                        Arr::forget($requestPayload, "customerReference");
                    }

                    if(strpos($productId, "jedc") !== false AND (strpos($productId, "prepaid") !== false OR strpos($productId, "postpaid") !== false)) {
                        $requestPayload["accessCode"] = $bodyRequest["customer_access_code"];
                        $requestPayload["customerPhoneNumber"] = "09033024846";
                        Arr::forget($requestPayload, "customerAddress");
                    }

                    if(strpos($productId, "ekedc") !== false AND (strpos($productId, "prepaid") !== false OR strpos($productId, "postpaid") !== false)) {
                        $requestPayload["customerDistrict"] = "FESTAC";
                        $requestPayload["meterNumber"] = $bodyRequest["meter_number"];
                        Arr::forget($requestPayload, "customerReference");
                    }

                    $processOrder = HttpRequest::sendPost($this->endpoint, $requestPayload, $authHeader);
                    return $this->checkPurchaseResponse($processOrder);
    
                break;

                case "verifyorder":
                    $reference = $bodyRequest["request_id"];
                    $verifyOrder = self::verifyOrder($reference, $authHeader);
                    return self::checkPurchaseResponse($verifyOrder);
                break;
            }
        }
        
        return $this->sendError("Product service code does not exist", [], 404);        

    }

    private function verifyOrder($reference, $authHeader) {
        return HttpRequest::sendGet($this->endpoint."query", http_build_query(["trans_id" => $reference]), $authHeader);
    }

    /**
     * This method is used to merge all service ID together for accessing them...
     */
    private function getServiceId($category, $productId) {
        switch ($category) {
            case "airtime":
            case "verifyorder":
                $serviceId = true;
            break;
            case "data":
                $serviceId = self::getDataServiceId($productId);
            break;
            case "verifycabletv":
            case "cabletv":
                $serviceId = self::getCableTvServiceId($productId);
            break;
            case "education":
                $serviceId = self::getEduServiceId($productId);
            break;
            case "verifyelectricity":
            case "electricity":
                $serviceId = self::getElectricityServiceId($productId);
            break;
            default:
                $serviceId = false;
            break;
        }
        return $serviceId;
    } 

    private function getDataServiceId($productId) {
        if(strpos($productId, "mtn") !== false) {
            $network = "mtn";
        } else if(strpos($productId, "9mobile") !== false) {
            $network = "9mobile";
        } else if(strpos($productId, "glo") !== false) {
            $network = "glo";
        } else if(strpos($productId, "airtel") !== false) {
            $network = "airtel";
        } else {
            $network = NULL;
        }

        $serviceId = [
            "mtn" => "BCA", "9mobile" => "BCB",
            "glo" => "BCC", "airtel" => "BCD"
        ];
        
        if(isset($serviceId[$network])) { return $serviceId[$network]; }
        
        return false;
    }

    private function getCableTvServiceId($service) {
        if(strpos(strtolower($service), "gotv") !== false) {
            $provider = "gotv";
        } else if(strpos(strtolower($service), "dstv") !== false) {
            $provider = "dstv";
        } else if(strpos(strtolower($service), "startimes") !== false) {
            $provider = "startimes";
        } else {
            return false;
        }
        $cableType = [
            "gotv" => "AKA", "dstv" => "AKC",
            "startimes" => "AKB"
        ];
        
        if(isset($cableType[$provider])) { return $cableType[$provider]; }
        
        return false;
    }

    private function getEduServiceId($service) {
        if(strpos(strtolower($service), "waec") !== false) {
            $provider = "waec";
        } else if(strpos(strtolower($service), "neco") !== false) {
            $provider = "neco";
        } else if(strpos(strtolower($service), "jamb") !== false) {
            $provider = "jamb";
        } else {
            return false;
        }
        $eduType = [
            "waec" => "AJA", "neco" => "AJC",
            "jamb" => "AJB"
        ];
        
        if(isset($eduType[$provider])) { return $eduType[$provider]; }
        
        return false;
    }

    private function getElectricityServiceId($service) {
        $electricType = [
            "ibedcprepaid" => "AEA",
            "ibedcpostpaid" => "AEB",
            "phedcprepaid" => "ADB",
            "phedcpostpaid" => "ADA",
            "aedcprepaid" => "AHB",
            "aedcpostpaid" => "AHA",
            "kedcprepaid" => "AFA",
            "kedcpostpaid" => "AFB",
            "kaedcprepaid" => "AGB",
            "kaedcpostpaid" => "AGA",
            // "eedcprepaid" => "AEA",
            // "eedcpostpaid" => "AEA",
            "jedcprepaid" => "ACB",
            "jedcpostpaid" => "ACA",
            "ekedcprepaid" => "ANA",
            "ekedcpostpaid" => "ANB",
            "ikedcprepaid" => "AMA",
            "ikedcpostpaid" => "AMB",
        ];

        if(isset($electricType[$service])) { return $electricType[$service]; }
        return false;
    }

    private function getRequestType($bundleId) {
        if(strpos($bundleId, "mtn") !== false AND strpos($bundleId, "direct") !== false) {
            $network = "mtngift";
        } else if(strpos($bundleId, "mtn") !== false) {
            $network = "mtn";
        } else if(strpos($bundleId, "9mobile") !== false) {
            $network = "9mobile";
        } else if(strpos($bundleId, "glo") !== false AND (strpos($bundleId, "sme") !== false OR strpos($bundleId, "cg") !== false)) {
            $network = "glosme";
        } else if(strpos($bundleId, "glo") !== false) {
            $network = "glo";
        } else if(strpos($bundleId, "airtel") !== false AND (strpos($bundleId, "sme") !== false OR strpos($bundleId, "cg") !== false)) {
            $network = "airtelsme";
        } else if(strpos($bundleId, "airtel") !== false) {
            $network = "airtel";
        } else {
            $network = NULL;
        }

        $requestType = [
            "mtn" => "SME",
            "mtngift" => "GIFTING",
            "9mobile" => "GIFTING",
            "glo" => "GIFTING",
            "airtel" => "GIFTING",
            "airtelsme" => "SME",
            "glosme" => "SME",
        ];

        return $requestType[$network];
    }

    private function getPackagePrice($serviceId, $requestType, $providerCode, $apiDetails) {
        $getAllPackage = self::getService($serviceId, $requestType, $apiDetails["api_public_key"]);
        // return gettype($getAllPackage);
        // $decodeResponse = json_decode((string) $getAllPackage, true);
        $decodeResponse = $getAllPackage;
        $allDetails = $decodeResponse['details'];
        $packagePrice = 0;

        /**
         * This method works for WAEC, NECO
         */
        if(isset($allDetails['price'])) {
            return $allDetails['price'];
        }

        /**
         * Else , loop the product list and get the price...
         */
        foreach($allDetails as $detail) {
            if(isset($detail['code']) AND $detail['code'] === $providerCode) {
                $packagePrice = $detail['cost'];
                break;
            }
            else if(isset($detail['productCode']) AND $detail['productCode'] === $providerCode) {
                $packagePrice = $detail['price'];
                break;
            }
            else {

            }
        }
        return $packagePrice;
    }

    private function checkPurchaseResponse($apiResponse) {
        try {
            $decode_response = $apiResponse;
            // $decode_response = json_decode($apiResponse, true);

            if($decode_response['statusCode'] == "200") {
                $reformResponse = $decode_response;
                $reformResponse['delivery_status'] = "1";
                $reformResponse['transaction_reference'] = $decode_response["details"]["trans_id"];
                // return $this->sendResponse("Success", $reformResponse, 200);
            } else if($decode_response['statusCode'] == "202") {
                $reformResponse = $decode_response;
                $reformResponse['delivery_status'] = "2";
                // return $this->sendResponse("Success", $reformResponse, 200);
            } else {
                return $this->sendError("Error", $decode_response['details'], 400);
            }

            if(isset($decode_response['details']['details']['pins'])) { #education condition
                $pinsInfo = $decode_response['details']['details']['pins'];
                foreach($pinsInfo as $pinInfo) {
                    if(isset($pinInfo['serial_number'])) {
                        $serialNo[] = $pinInfo['serial_number'];
                    }

                    if(isset($pinInfo['pin'])) {
                        $pin[] = $pinInfo['pin'];
                    }
                }

                if(isset($serialNo) AND isset($pin)) {
                    $reformResponse['pin_detail'] = ["serial_number" => implode(",", $serialNo), "pin" => implode(",", $pin)];
                } else if(isset($pin)) {
                    $reformResponse['pin_detail'] = ["pin" => implode(",", $pin)];
                }

            }
            else if(isset($decode_response['details']['service']) AND self::isExemptService($decode_response['details']['service'])) {
                $token = NULL;
                if(isset($decode_response['details']['details']['token'])) {
                    $token = $decode_response['details']['details']['token'];
                } else {
                    $token = $decode_response['details']['details']['creditToken'];
                }
                $reformResponse['token_detail'] = ["token" => $token, "unit" => NULL];
            }

            return $this->sendResponse("Success", $reformResponse, 200);
        }
        catch(Exception $e) {
            return $this->sendError("Error", $e->getMessage(), 500);
        }
    }

    private function checkVerificationResponse($apiResponse, $serviceCategory) {
        try {

            $decodeResponse = json_decode($apiResponse, true);

            if($serviceCategory == "multichoice") {
                if(is_array($decodeResponse['details'])) {
                    $details = $decodeResponse['details'];
                    return $this->sendResponse("Verification successful", [
                        "customer_name" => $details['firstName'] . " ".$details['lastName'],
                        "customer_number" => $details['customerNumber'],
                        "due_date" => $details['dueDate'],
                        "decoder_status" => $details['accountStatus'],
                        "due_amount" => $details['amount']
                    ], 200);
                }
            }
            else if($serviceCategory == "startimes") {
                if(is_array($decodeResponse['details'])) {
                    $details = $decodeResponse['details'];
                    return $this->sendResponse("Verification successful", [
                        "customer_name" => $details['customerName'],
                        "customer_balance" => $details['balance'],
                        "decoder_status" => ($details['balance'] == 0) ? "Suspended":"Open"
                    ], 200);
                }
            }
            else if($serviceCategory == "electricity") {
                if(is_array($decodeResponse['details'])) {
                    $details = $decodeResponse['details'];

                    // Made the function here bcos I still need it somewhere else...
                    if(isset($details['customerDetails'])) {
                        /*
                         * created a method to deal with inconsistent data formatting
                         * 
                         * "customerDetails":"CUSTOMER Name: DAN FELICIA AGBOR-OBUN | ACCOUNT NO: 0171100024636 | MOBILE NO: null 
                         * | ADDRESS: IKOT UDUAK LAYOUT MAPR:525.51\/63061.32  | BSC_NAME: Metropolitan MAPR:525.51\/63061.32 
                         * | IBC_NAME:Paradise City Main | TARIFF CODE: C-Non MD | CURRENT AMOUNT: 0.0 | TOTAL BILL: 0"
                        */
                        $customerDetails = self::loopCustomerDetails(explode("|", $details['customerDetails'])); 
                    }
                        
                    if(isset($details['name'])) {
                        $customerName = $details['name'];
                    } else if(isset($details['customerName'])) {
                        $customerName = $details['customerName'];
                    } else if(isset($details['customerDetails'])) {
                        $customerName = $customerDetails['customer_name'];
                    } else {
                        $customerName = "";
                    }
                      
                    $minimumAmount = $outstandingAmount = 0;
                    
                    if(isset($details['minimumAmount'])) {
                        $minimumAmount = $details['minimumAmount'];
                    } else if(isset($details['minimumPurchase'])) {
                        $minimumAmount = $details['minimumPurchase'];
                    }
                    
                    if(isset($details['outstandingAmount'])) {
                        $outstandingAmount = $details['outstandingAmount'];
                    } else if(isset($details['customerArrears'])) {
                        $outstandingAmount = $details['customerArrears'];
                    } else if(isset($details['debtAmount'])) {
                        $outstandingAmount = $details['debtAmount'];
                    } else if(isset($customerDetails['total_bill'])) {
                        $outstandingAmount = $customerDetails['total_bill'];
                    }
                    
                    $customerAddress = "";
                    if(isset($details['customerAddress'])) {
                        $customerAddress = $details['customerAddress'];
                    } else if(isset($details['address'])) {
                        $customerAddress = $details['address'];
                    } else if(isset($customerDetails['address'])) {
                        $customerAddress = $customerDetails['address'];
                    }
                    
                    $responseData = [
                        "customer_name" => $customerName,
                        "customer_address" => $customerAddress,
                        "outstanding_amount" => $outstandingAmount,
                        "minimum_amount" => $minimumAmount
                    ];
                    
                    // Needed by PHEDC
                    if(isset($details['customerDetails'])) {
                        $responseData['customer_details'] = $details['customerDetails'];
                    }
                    
                    if(isset($details['referenceId'])) {
                        $responseData['customer_reference_id'] = $details['referenceId'];
                    }
                    
                    // KAEDC
                    if(isset($details['tariffCode'])) {
                        $responseData['customer_tariff_code'] = $details['tariffCode'];
                    }
                    
                    // JEDC
                    if(isset($details['accessCode'])) {
                        $responseData['customer_access_code'] = $details['accessCode'];
                    }
                    
                    // IKEDC
                    if(isset($details['customerAccountType'])) {
                        $responseData['customer_account_type'] = $details['customerAccountType'];
                    }
                    if(isset($details['customerDtNumber'])) {
                        $responseData['customer_dt_number'] = $details['customerDtNumber'];
                    }
                    
                    return $this->sendResponse("Verification successful", $responseData, 200);
                }
            }
            return $this->sendError($decodeResponse['details'], [], 422);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    private function loopCustomerDetails($arrayData) {
        $result = [];

        foreach ($arrayData as $item) {
           // Split each item by the ":" character
            $parts = explode(':', $item, 2);
            
            // Remove leading/trailing whitespace from the key and value
            $key = str_replace(array(" "), "_", trim(strtolower($parts[0])));
            $value = trim($parts[1] ?? '');
            
            // Assign the value to the key in the result array
            $result[$key] = $value;
        }
        return $result;
    }

    public function getService($serviceId, $requestType, $publicKey) {
        $authHeader = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer ".$publicKey
        ];

        if($serviceId != ""){
            $requestData["service_id"] = $serviceId;
        }
        if($requestType != ""){
            $requestData["requestType"] = $requestType;
        }

        $fetchService = HttpRequest::sendPost($this->endpoint."packages", $requestData, $authHeader);
        return $fetchService;
        return $fetchService;
    }

    private function isExemptService($service) {
        $exemptedService = [
            "prepaid", "postpaid", "token"
        ];

        foreach ($exemptedService as $keyword) {
            if (stripos(strtolower($service), $keyword) !== false) {
                return true; // Service matches an exempt keyword
                break;
            }
        }
        return false;
    }
}
?>