<?php
namespace App\Services;

use Exception;
use App\Vendors\MobileNig;
use App\Vendors\LocalServer;
use App\Services\WalletService;
use App\Services\UtilityService;
use App\Services\TransactionService;
use App\Services\ProductPricingService;
use App\Http\Traits\ResponseTrait;
use App\Vendors\Smeplug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseService {
    use ResponseTrait;

    private $exemptVendor = false;
    private $uniqueReference, $dateCreated;
    protected $utilityService, $productService, $productPriceService, $walletService, $airtimeRequest, $dataRequest,
              $cabletvRequest, $eduRequest, $electrictyiRequest, $transactService, $responseBody;

    public function __construct(UtilityService $utilityService, ProductService $productService, ProductPricingService $productPriceService, WalletService $walletService,
                                    AirtimeRequestService $airtimeRequest, DataRequestService $dataRequest, CabletvRequestService $cabletvRequest,
                                    EducationRequestService $eduRequest, ElectricityRequestService $electrictyiRequest, TransactionService $transactService) {

        $this->utilityService = $utilityService;
        $this->productService = $productService;
        $this->productPriceService = $productPriceService;
        $this->walletService = $walletService;
        $this->airtimeRequest = $airtimeRequest;
        $this->dataRequest = $dataRequest;
        $this->cabletvRequest = $cabletvRequest;
        $this->eduRequest = $eduRequest;
        $this->electrictyiRequest = $electrictyiRequest;
        $this->transactService = $transactService;

        $this->uniqueReference = $this->utilityService->uniqueReference();
        $this->dateCreated = $this->utilityService->dateCreated();
    }

    /**
     * verifyMeterNumber handle meter number verification for all distribution holder in Nigeria
     * local_server simply means in-house production and this can't be use for verification
     * because the service is an external service not in-house
     *
     * purchaseElectricity is used for sending electricity purchase to the vendor
     */

    public function verifyMeterNumber($validateData) {

        $service = $validateData['service'];

        $getProduct = $this->productService->getProductById($service);

        if($getProduct === false) {
            return $this->sendError("Service verification ($service) failed", [], 422);
        }

        if($getProduct['api']['vendor']['vendor_code'] == 'local_server') {
            return $this->sendError("Service provider is currently unavailable", [], 422);
        }

        $validateData['category'] = 'verifyelectricity';

        // Validation of API can't be on CRON
        $getProduct['api']["api_delivery_route"] = "instant";

        return self::sendToProvider($validateData, $getProduct['api']);

    }

    public function purchaseElectricity($productId, $amount, $meterNumber, $transactPin, $optionalData = [])
    {
        try {
            $theUser = Auth::user();
            $theUserId = $theUser["id"];
            $theUserPlanId = $theUser["plan_id"];

            if($theUser["secret_pin"] != $transactPin) {
                return $this->sendError("Incorrect transaction pin supplied", [], 400);
            }

            $productPricing = $this->productPriceService->getProductPrice($theUserPlanId, $productId, $amount);
            if($productPricing === false) {
                return $this->sendError("Pricing error, contact Admin", [], 500);
            }

            $currentUserBalance = $this->walletService->getUserBalance($theUserId);
            $sellingPrice = $productPricing["selling_price"];

            if($currentUserBalance < $sellingPrice) {
                return $this->sendError("Insufficient wallet balance. Kindly topup your wallet to complete your request", [], 400);
            }

            // Send this to provider...
            $purchaseData = [
                "product_id" => $productId,
                "meter_number" => $meterNumber,
                "amount" => $amount,
                "customer_name" => $optionalData["customer_name"],
                "customer_address" => $optionalData["customer_address"],
                "request_id" => $this->uniqueReference,
                "category" => "electricity"
            ];

            if(isset($optionalData['customer_details'])) {
                $purchaseData['customer_details'] = $optionalData['customer_details'];
            }

            if(isset($optionalData['customer_reference_id'])) {
                $purchaseData['customer_reference_id'] = $optionalData['customer_reference_id'];
            }

            if(isset($optionalData['customer_tariff_code'])) {
                $purchaseData['customer_tariff_code'] = $optionalData['customer_tariff_code'];
            }

            if(isset($optionalData['customer_access_code'])) {
                $purchaseData['customer_access_code'] = $optionalData['customer_access_code'];
            }

            if(isset($optionalData['customer_dt_number'])) {
                $purchaseData['customer_dt_number'] = $optionalData['customer_dt_number'];
            }

            if(isset($optionalData['customer_account_type'])) {
                $purchaseData['customer_account_type'] = $optionalData['customer_account_type'];
            }

            // Using this for database record
            $transactParams = [
                "user_id" => $theUserId,
                "user_plan_name" => $theUser["plan"]["plan_name"],
                "description" => "Purchase of ".strtoupper($productPricing["product_name"]) ." N".$amount." for ".$meterNumber,
                "recipient" => $meterNumber,
                "user_balance" => $currentUserBalance,
                "selling_price" => $sellingPrice,
                "cost_price" => $productPricing["cost_price"],
            ];

            // Do the final task
            $processRequest = $this->updateRequestByProductId($productId, $purchaseData, $transactParams);

            return $processRequest;


        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * verifyDecoder handle cable tv verification for
     * both gotv, dstv and startimes
     * local_server simply means in-house production and this can't be use for verification
     * because the service is an external service not in-house
     *
     * purchaseCableTv is used for sending cable tv purchase to the vendor
     */

    public function verifyDecoder($validateData) {

        $service = $validateData['service'];

        $getProduct = $this->productService->getProductWithLike($service);

        if($getProduct === false) {
            return $this->sendError("Service verification ($service) failed", [], 422);
        }

        if($getProduct['api']['vendor']['vendor_code'] == 'local_server') {
            return $this->sendError("Service provider is currently unavailable", [], 422);
        }

        $validateData['category'] = 'verifycabletv';

        // Validation of API can't be on CRON
        $getProduct['api']["api_delivery_route"] = "instant";

        return self::sendToProvider($validateData, $getProduct['api']);

    }

    public function purchaseCableTv($productId, $smartNumber, $transactPin, $optionalData = [])
    {
        try {
            $theUser = Auth::user();
            $theUserId = $theUser["id"];
            $theUserPlanId = $theUser["plan_id"];

            if($theUser["secret_pin"] != $transactPin) {
                return $this->sendError("Incorrect transaction pin supplied", [], 400);
            }

            $topupAmount = isset($optionalData['amount']) ? $optionalData['amount'] : 0;
            if(strpos($productId, 'top') !== false AND $topupAmount == 0) {
                return $this->sendError("Please provide amount you wish to topup", [], 400);
            }

            $productPricing = $this->productPriceService->getProductPrice($theUserPlanId, $productId, $topupAmount);

            if($productPricing === false) {
                return $this->sendError("Pricing error, contact Admin", [], 500);
            }

            $currentUserBalance = $this->walletService->getUserBalance($theUserId);
            $sellingPrice = $productPricing["selling_price"];
            if($currentUserBalance < $sellingPrice) {
                return $this->sendError("Insufficient wallet balance. Kindly topup your wallet to complete your request", [], 400);
            }

            // Send this to provider...
            $purchaseData = [
                "product_id" => $productId,
                "smart_number" => $smartNumber,
                "amount" => $topupAmount,
                "customer_name" => $optionalData["customer_name"],
                "request_id" => $this->uniqueReference,
                "category" => "cabletv"
            ];

            if(isset($optionalData["customer_number"])) {
                $purchaseData['customer_number'] = $optionalData["customer_number"];
            }

            // Using this for database record
            $transactParams = [
                "user_id" => $theUserId,
                "user_plan_name" => $theUser["plan"]["plan_name"],
                "description" => "Purchase of ".strtoupper($productPricing["product_name"]) ." for ".$smartNumber,
                "extra_info" => json_encode(["product" => strtoupper($productPricing["product_name"]), "destination" => $smartNumber]),
                "recipient" => $smartNumber,
                "user_balance" => $currentUserBalance,
                "selling_price" => $sellingPrice,
                "cost_price" => $productPricing["cost_price"],
            ];

            // Do the final task
            $processRequest = $this->updateRequestByProductId($productId, $purchaseData, $transactParams);

            return $processRequest;

        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * purchaseAirtime is used for sending airtime purchase to the vendor
     */
    public function purchaseAirtime($network, $phoneNumber, $amount, $transactPin) {
        try {
            $theUser = Auth::user();
            $theUserId = $theUser["id"];
            $theUserPlanId = $theUser["plan_id"];

            if($theUser["secret_pin"] != $transactPin) {
                return $this->sendError("Incorrect transaction pin supplied", [], 400);
            }

            $currentUserBalance = $this->walletService->getUserBalance($theUserId);
            $airtimeInfo = json_decode($this->utilityService->airtimeInfo(), true);

            if($amount < $airtimeInfo["min_value"]) {
                return $this->sendError("Minimum airtime purchase is N".$airtimeInfo["min_value"], [], 400);
            }

            $productPricing = $this->productPriceService->getProductPrice($theUserPlanId, $network, $amount);

            if($productPricing === false) {
                return $this->sendError("Pricing error, contact Admin", [], 500);
            }
            
            $sellingPrice = $productPricing["selling_price"];
            
            if($currentUserBalance < $sellingPrice) {
                return $this->sendError("Insufficient wallet balance. Kindly topup your wallet to complete your request", [], 400);
            }

            // Send this to provider...
            $purchaseData = [
                "product_id" => $network,
                "network" => $network,
                "phone_number" => $phoneNumber,
                "amount" => $amount,
                "request_id" => $this->uniqueReference,
                "category" => "airtime"
            ];

            // Using this for database record
            $transactParams = [
                "user_id" => $theUserId,
                "user_plan_name" => $theUser["plan"]["plan_name"],
                "description" => "Purchase of ".strtoupper($network) . " N".number_format($amount)." for ".$phoneNumber,
                "extra_info" => json_encode(["network" => $network, "amount" => $amount, "destination" => $phoneNumber]),
                "recipient" => $phoneNumber,
                "user_balance" => $currentUserBalance,
                "selling_price" => $sellingPrice,
                "cost_price" => $productPricing["cost_price"],
            ];

            // Do the final task
            $processRequest = $this->updateRequestByProductId($network, $purchaseData, $transactParams);

            return $processRequest;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * purchaseData is used for sending data purchase to the vendor
     */
    public function purchaseData($productId, $phoneNumber, $transactPin) {
        try {

            $theUser = Auth::user();
            $theUserId = $theUser["id"];
            $theUserPlanId = $theUser["plan_id"];

            if($theUser["secret_pin"] != $transactPin) {
                return $this->sendError("Incorrect transaction pin supplied", [], 400);
            }

            $productPricing = $this->productPriceService->getProductPrice($theUserPlanId, $productId, "");

            if($productPricing === false) {
                return $this->sendError("Pricing error, contact Admin", [], 500);
            }

            $currentUserBalance = $this->walletService->getUserBalance($theUserId);
            
            $sellingPrice = $productPricing["selling_price"];
            if($currentUserBalance < $sellingPrice) {
                return $this->sendError("Insufficient wallet balance. Kindly topup your wallet to complete your request", [], 400);
            }

            // Send this to provider...
            $purchaseData = [
                "product_id" => $productId,
                "phone_number" => $phoneNumber,
                "request_id" => $this->uniqueReference,
                "category" => "data"
            ];

            // Using this for database record
            $transactParams = [
                "user_id" => $theUserId,
                "user_plan_name" => $theUser["plan"]["plan_name"],
                "extra_info" => json_encode(["product" => $productPricing["product_name"], "destination" => $phoneNumber]),
                "description" => "Purchase of ".strtoupper($productPricing["product_name"]) ." for ".$phoneNumber,
                "recipient" => $phoneNumber,
                "user_balance" => $currentUserBalance,
                "selling_price" => $sellingPrice,
                "cost_price" => $productPricing["cost_price"],
            ];

            // Do the final task
            $processRequest = $this->updateRequestByProductId($productId, $purchaseData, $transactParams);

            return $processRequest;
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * purchaseEducation is used for sending education purchase to the vendor
     */
    public function purchaseEducation($productId, $quantity = 1, $transactPin)
    {
        try {
            $theUser = Auth::user();
            $theUserId = $theUser["id"];
            $theUserPlanId = $theUser["plan_id"];

            if($theUser["secret_pin"] != $transactPin) {
                return $this->sendError("Incorrect transaction pin supplied", [], 400);
            }

            $productPricing = $this->productPriceService->getProductPrice($theUserPlanId, $productId, "");

            if($productPricing === false) {
                return $this->sendError("Pricing error, contact Admin", [], 500);
            }

            $currentUserBalance = $this->walletService->getUserBalance($theUserId);
            $sellingPrice = $productPricing["selling_price"];
            if($currentUserBalance < $sellingPrice) {
                return $this->sendError("Insufficient wallet balance. Kindly topup your wallet to complete your request", [], 400);
            }

            // Send this to provider...
            $purchaseData = [
                "product_id" => $productId,
                "quantity" => $quantity,
                "request_id" => $this->uniqueReference,
                "category" => "education"
            ];

            // Using this for database record
            $transactParams = [
                "user_id" => $theUserId,
                "user_plan_name" => $theUser["plan"]["plan_name"],
                "description" => "Purchase of ". $quantity."piece of ".strtoupper($productPricing["product_name"]),
                "user_balance" => $currentUserBalance,
                "selling_price" => $sellingPrice,
                "cost_price" => $productPricing["cost_price"],
            ];

            // Do the final task
            $processRequest = $this->updateRequestByProductId($productId, $purchaseData, $transactParams);

            return $processRequest;

        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * updateRequestByProductId is responsible for fixing product property based on product id
     * to the product the user is vending
     *
     * Also, it's responsible for sending request to the provider.
     */
    private function updateRequestByProductId($productId, $purchaseData, $transactionData = []) {

        $theProduct = $this->productService->getProductById($productId);
        $theProductApi = $theProduct["api"];
        $vendorCode = $theProductApi["vendor"]["vendor_code"];
        $purchaseCategory = $purchaseData["category"];

        // Some services or product can't use localserver as vendor bcos we have no right over them or delivery means
        if(self::isExemptCategory($theProduct['category']['category_name']) AND $vendorCode == "local_server") {
            return $this->sendError("Something went wrong. Unable to fulfil request", [], 422);
        }

        if($theProduct === false) {
            return $this->sendError("Product not found", [], 404);
        }

        if($theProduct["availability"] == "0") {
            return $this->sendError("Product is currently unavailable", [], 400);
        }

        if($vendorCode == "not_available") {
            return $this->sendError("Error", "We are currently experiencing delivery degradation. Kindly try again in few minutes time", 400);
        }

        if($purchaseCategory == "airtime") {
            // Get the airtimeRequest code of the vendor...
            $vendorRequest = $this->airtimeRequest->getAirtimeRequest($productId);
        } else if($purchaseCategory == "data") {
            // Get the dataRequest code of the vendor...
            $vendorRequest = $this->dataRequest->getDataRequest($productId);
        } else if($purchaseCategory == "cabletv" OR $purchaseCategory == "education") {
            if($purchaseCategory == "cabletv") {
                // Get the cabletvRequest code of the vendor...
                $vendorRequest = $this->cabletvRequest->getCabletvRequest($productId);
            } else if($purchaseCategory == "education") {
                // Get the cabletvRequest code of the vendor...
                $vendorRequest = $this->eduRequest->getEducationRequest($productId);
            } else if($purchaseCategory == "electricity") {
                // Get the cabletvRequest code of the vendor...
                $vendorRequest = $this->electrictyiRequest->getElectricityRequest($productId);
            }

            /**
             * Vendor request is not found
             * In case of cable tv, education some provider do not have vendor code for topup
            */
            if($vendorRequest === false AND self::isExemptCategory($purchaseData['product_id'])) {
                $this->exemptVendor = true;
            }
        } else if($purchaseCategory == "electricity") {
            // Get the electricity code of the vendor...
            $vendorRequest = $this->electrictyiRequest->getElectricityRequest($productId);

            /**
             * Vendor request is not found
             * In case of electricity, some provider do not product code so we exclude it with category...
            */
            if($vendorRequest === false AND self::isExemptCategory($purchaseCategory)) {
                $this->exemptVendor = true;
            }
        }

        /**
         * Vendor Request for airtime, data, cabletv, education and electricity failed or not found...
         * Vendor is not exempted from the search query
        */
        if($vendorRequest === false AND $this->exemptVendor === false) {
            return $this->sendError("Something unexpected went wrong", [], 500);
        }

        if($vendorCode != "local_server") {
            /**
             * Category is not exempted, why is vendor code missing ???
             * why ??? Return 500 error
             */
            if($this->exemptVendor === false AND $vendorRequest[$vendorCode] == NULL) {
                return $this->sendError("Something unexpected went wrong", [], 500);
            }
            if($vendorRequest !== false) {
                $purchaseData["provider_service_id"] = $vendorRequest[$vendorCode];
            }
        }

        // Send it to the provider...
        $sendToProvider = $this->sendToProvider($purchaseData, $theProductApi);

        $transactionData["vendorRequest"] = $vendorRequest;
        $transactionData["purchase"] = $purchaseData;
        $transactionData["productInfo"] = $theProduct;

        // Make attempt to submit the record to the database if true otherwise, handle exception...
        return $this->createPurchase($sendToProvider, $transactionData);
    }

    // Let's send the request to the provider
    private function sendToProvider($purchaseData, $apiDetails) {
        // Get Vendor Code....
        $vendorCode = $apiDetails["vendor"]["vendor_code"];
        switch($vendorCode) {
            case "local_server":
                $connectVendor = app(LocalServer::class);
                $submitOrder = $connectVendor->processRequest();
            break;

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

    /**
     * Store the transaction record
     * Charges the user after a successful transaction is made.
     */
    private function createPurchase($providerResponse, $transactionData) {
        try {
            DB::beginTransaction();

            // decode the provider response...
            $decodeResponse = json_decode($providerResponse->getContent(), true)["data"];

            // If Successful...
            if($providerResponse->getStatusCode() === 200) {

                $purchaseData = $transactionData["purchase"];
                $theProduct = $transactionData["productInfo"];
                $theProductApi = $theProduct["api"];

                $ussdString = NULL;
                $purchaseCategory = $purchaseData["category"];

                if($purchaseCategory == "airtime") {
                    $ussdString = self::formUSSDString($purchaseCategory, $transactionData["vendorRequest"], [
                        "phone_number" => $transactionData["recipient"],
                        "amount" => $purchaseData["amount"],
                        "product_id" => $theProduct["product_id"]
                    ]);
                }
                else if($purchaseCategory == "data") {
                    $ussdString = self::formUSSDString($purchaseCategory, $transactionData["vendorRequest"], [
                        "phone_number" => $transactionData["recipient"],
                        "product_id" => $theProduct["product_id"]
                    ]);
                }
                
                $userBalance = (float) $transactionData["user_balance"];
                $sellingPrice = (float) $transactionData["selling_price"];
                $newUserBalance = (float) $userBalance - $sellingPrice;

                if(isset($decodeResponse["transaction_reference"])) {
                    $transactReference = $decodeResponse["transaction_reference"];
                } else if(isset($decodeResponse["ref"])) {
                    $transactReference = $decodeResponse["ref"];
                }  else if(isset($decodeResponse["reference"])) {
                    $transactReference = $decodeResponse["reference"];
                } else {
                    $transactReference = NULL;
                }

                // Transaction history record...
                $transactData = [
                    "user_id" => $transactionData["user_id"],
                    "plan" => $transactionData["user_plan_name"],
                    "description" => $transactionData["description"],
                    "extra_info" => isset($transactionData["extra_info"]) ? $transactionData["extra_info"] : NULL,
                    "destination" => isset($transactionData["recipient"]) ? $transactionData["recipient"] : NULL,
                    "old_balance" => (float) $userBalance,
                    "amount" => (float) $sellingPrice,
                    "new_balance" => (float) $newUserBalance,
                    "costprice" => (float) $transactionData["cost_price"],
                    "category" => $purchaseCategory,
                    "transaction_reference" => $transactReference,
                    "reference" => $this->uniqueReference,
                    "pin_details" => isset($decodeResponse["pin_detail"]) ? json_encode($decodeResponse["pin_detail"]) : NULL,
                    "token_details" => isset($decodeResponse["token_detail"]) ? json_encode($decodeResponse["token_detail"]) : NULL,
                    "response" => json_encode($decodeResponse),
                    "memo" => NULL,
                    "status" => isset($decodeResponse["delivery_status"]) ? $decodeResponse["delivery_status"] : "0",
                    "ussd_code" => $ussdString,
                    "api_id" => $theProductApi["id"],
                    "channel" => "website"
                ];
                
                $walletOut = [
                    "user_id" => $transactionData["user_id"],
                    "description" => $transactionData["description"],
                    "old_balance" => (float) $userBalance,
                    "amount" => $sellingPrice,
                    "status" => isset($decodeResponse["delivery_status"]) ? $decodeResponse["delivery_status"] : "0",
                    "new_balance" => (float) $newUserBalance,
                    "reference" => $this->uniqueReference,
                ];

                // Charge the user wallet...
                $this->walletService->createWallet("outward", $walletOut);

                // Create transaction record...
                $this->transactService->createTransaction($transactData);

                DB::commit();
                
                return $this->sendResponse("Success", [
                        "reference" => $this->uniqueReference,
                        "amount_charged" => $sellingPrice,
                        "description" => $transactionData["description"],
                        "message" => $transactionData["description"]." was successful.",
                        "new_wallet" => $newUserBalance
                    ]
                );
            }
            DB::rollBack();
            return $this->sendError($decodeResponse, [], 500);
        }
        catch(Exception $e) {
            // DB::rollBack();
            return $e->getMessage();
        }
    }

    // Creation of USSD String for services with USSD String
    private function formUSSDString($category, $vendorRequest, $purchaseData = [])
    {
        $initcode = $vendorRequest["init_code"];
        $wrapcode = $vendorRequest["wrap_code"];
        $productId = strtolower($purchaseData["product_id"]);
        $phone_number = $purchaseData["phone_number"];

        $ussdCode = NULL;

        if($category == "airtime") {
            $amount_topup = $purchaseData["amount"];
            if($productId == "mtn" OR $productId == "9mobile") {
                $ussdCode = $initcode.$amount_topup."*".$phone_number.$wrapcode;
            }
            else if($productId == "glo" OR $productId == "airtel") {
                $ussdCode = $initcode.$phone_number."*".$amount_topup.$wrapcode;
            }
        }
        else if($category == "data") {
            $ussdCode = $initcode.$phone_number.$wrapcode; // *461*2000*08155577122*1010#
        }

        return $ussdCode;
    }

    private function isExemptCategory($category) {
        $exemptedCategory = [
            "dstv", "gotv","waec","neco","electricity"
        ];

        foreach ($exemptedCategory as $keyword) {
            if (stripos(strtolower($category), $keyword) !== false) {
                return true; // Category matches an exempt keyword
                break;
            }
        }
        return false;
    }
}