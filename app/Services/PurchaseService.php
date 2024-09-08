<?php
namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Vendors\Ipay;
use App\Vendors\Smeplug;
use App\Vendors\MobileNig;
use App\Models\Transaction;
use App\Vendors\LocalServer;
use App\Services\WalletService;
use App\Services\UtilityService;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Services\ProductPricingService;

class PurchaseService {
    use ResponseTrait;

    private $exemptVendor, $uniqueReference, $dateCreated;
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
        $this->exemptVendor = false;

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
            $vendingRestriction = json_decode($this->utilityService->vendingRestriction(), true);
            $totalPurchase = self::getUserTotalPurchase($theUserId);
            $theUserAccessControl = json_decode($theUser["access_control"], true);
            $theUserVendingStatus = $theUserAccessControl['vending']['status'];
            $theUserSuspensionStatus = $theUserAccessControl['suspension']['status'];

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

            $unverifiedPurchaseBlc = (float) ($vendingRestriction["unverified_purchase"] - $totalPurchase);

            if ($theUserVendingStatus != "offlimit" AND $vendingRestriction['status'] == 'enable' AND $unverifiedPurchaseBlc < $sellingPrice) {
                $theUserAccessControl['suspension'] = ['status' => '1', 'date' => Carbon::now()];
                User::where(['id' => $theUserId])->update(['access_control' => json_encode($theUserAccessControl)]);
                return $this->sendError("You are unverified to perform such transaction. Kindly notify Admin", [], 400);
            }

            if ($theUserSuspensionStatus == "1") {
                return $this->sendError("You are currently suspended from making purchase. Kindly notify Admin", [], 400);
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
            $vendingRestriction = json_decode($this->utilityService->vendingRestriction(), true);
            $totalPurchase = self::getUserTotalPurchase($theUserId);
            $theUserAccessControl = json_decode($theUser["access_control"], true);
            $theUserVendingStatus = $theUserAccessControl['vending']['status'];
            $theUserSuspensionStatus = $theUserAccessControl['suspension']['status'];

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

            $unverifiedPurchaseBlc = (float) ($vendingRestriction["unverified_purchase"] - $totalPurchase);

            if ($theUserVendingStatus != "offlimit" AND $vendingRestriction['status'] == 'enable' AND $unverifiedPurchaseBlc < $sellingPrice) {
                $theUserAccessControl['suspension'] = ['status' => '1', 'date' => Carbon::now()];
                User::where(['id' => $theUserId])->update(['access_control' => json_encode($theUserAccessControl)]);
                return $this->sendError("You are unverified to perform such transaction. Kindly notify Admin", [], 400);
            }

            if ($theUserSuspensionStatus == "1") {
                return $this->sendError("You are currently suspended from making purchase. Kindly notify Admin", [], 400);
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
    

    private function getUserTotalPurchase($userId) {
        $totalPurchase = Transaction::where(['user_id' => $userId, 'status' => '1'])->orWhere(['status' => 0])->sum('amount');
        return $totalPurchase;
    }

    /**
     * purchaseAirtime is used for sending airtime purchase to the vendor
     */
    public function purchaseAirtime($network, $phoneNumber, $amount, $transactPin) {
        try {
            $theUser = Auth::user();
            $theUserId = $theUser["id"];
            $theUserPlanId = $theUser["plan_id"];
            $vendingRestriction = json_decode($this->utilityService->vendingRestriction(), true);
            $totalPurchase = self::getUserTotalPurchase($theUserId);
            $theUserAccessControl = json_decode($theUser["access_control"], true);
            $theUserVendingStatus = $theUserAccessControl['vending']['status'];
            $theUserSuspensionStatus = $theUserAccessControl['suspension']['status'];

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

            $unverifiedPurchaseBlc = (float) ($vendingRestriction["unverified_purchase"] - $totalPurchase);

            if ($theUserVendingStatus != "offlimit" AND $vendingRestriction['status'] == 'enable' AND $unverifiedPurchaseBlc < $sellingPrice) {
                $theUserAccessControl['suspension'] = ['status' => '1', 'date' => Carbon::now()];
                User::where(['id' => $theUserId])->update(['access_control' => json_encode($theUserAccessControl)]);
                return $this->sendError("You are unverified to perform such transaction. Kindly notify Admin", [], 400);
            }

            if ($theUserSuspensionStatus == "1") {
                return $this->sendError("You are currently suspended from making purchase. Kindly notify Admin", [], 400);
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
            $vendingRestriction = json_decode($this->utilityService->vendingRestriction(), true);
            $totalPurchase = self::getUserTotalPurchase($theUserId);
            $theUserAccessControl = json_decode($theUser["access_control"], true);
            $theUserVendingStatus = $theUserAccessControl['vending']['status'];
            $theUserSuspensionStatus = $theUserAccessControl['suspension']['status'];

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

            $unverifiedPurchaseBlc = (float) ($vendingRestriction["unverified_purchase"] - $totalPurchase);

            if ($theUserVendingStatus != "offlimit" AND $vendingRestriction['status'] == 'enable' AND $unverifiedPurchaseBlc < $sellingPrice) {
                $theUserAccessControl['suspension'] = ['status' => '1', 'date' => Carbon::now()];
                User::where(['id' => $theUserId])->update(['access_control' => json_encode($theUserAccessControl)]);
                return $this->sendError("You are unverified to perform such transaction. Kindly notify Admin", [], 400);
            }

            if ($theUserSuspensionStatus == "1") {
                return $this->sendError("You are currently suspended from making purchase. Kindly notify Admin", [], 400);
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
            $vendingRestriction = json_decode($this->utilityService->vendingRestriction(), true);
            $totalPurchase = self::getUserTotalPurchase($theUserId);
            $theUserAccessControl = json_decode($theUser["access_control"], true);
            $theUserVendingStatus = $theUserAccessControl['vending']['status'];
            $theUserSuspensionStatus = $theUserAccessControl['suspension']['status'];

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

            $unverifiedPurchaseBlc = (float) ($vendingRestriction["unverified_purchase"] - $totalPurchase);

            if ($theUserVendingStatus != "offlimit" AND $vendingRestriction['status'] == 'enable' AND $unverifiedPurchaseBlc < $sellingPrice) {
                $theUserAccessControl['suspension'] = ['status' => '1', 'date' => Carbon::now()];
                User::where(['id' => $theUserId])->update(['access_control' => json_encode($theUserAccessControl)]);
                return $this->sendError("You are unverified to perform such transaction. Kindly notify Admin", [], 400);
            }

            if ($theUserSuspensionStatus == "1") {
                return $this->sendError("You are currently suspended from making purchase. Kindly notify Admin", [], 400);
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
        // Retrieve product and associated API/vendor data
        $theProduct = $this->productService->getProductById($productId);
        
        if (!$theProduct) {
            return $this->sendError("Product not found", [], 404);
        }
    
        $theProductApi = $theProduct["api"];
        $vendorCode = $theProductApi["vendor"]["vendor_code"];
        $purchaseCategory = strtolower($purchaseData["category"]);
    
        // Check for exempt category and local server vendor code
        if (self::isExemptCategory($theProduct['category']['category_name']) && $vendorCode === "local_server") {
            return $this->sendError("Unable to fulfil request", [], 422);
        }
    
        if ($theProduct["availability"] == "0") {
            return $this->sendError("Product is currently unavailable", [], 400);
        }
    
        if ($vendorCode === "not_available") {
            return $this->sendError("Delivery degradation. Try again later", [], 400);
        }
    
        // Retrieve vendor request based on purchase category
        $vendorRequest = $this->getVendorRequestByCategory($purchaseCategory, $productId);
        
        // Handle exempt vendor logic for certain categories
        if ($vendorRequest === false && self::isExemptCategory($purchaseData['product_id'])) {
            $this->exemptVendor = true;
        }
        
        // Specific handling for electricity category
        if ($purchaseCategory == "electricity") {
            $vendorRequest = $this->electrictyiRequest->getElectricityRequest($productId);

            // If vendor request not found and exempted, set the exemptVendor flag
            if ($vendorRequest === false && self::isExemptCategory($purchaseCategory)) {
                $this->exemptVendor = true;
            }
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

        // Handle cases where vendor request is not found or exempt
        if (!$vendorRequest && !self::isExemptCategory($purchaseCategory)) {
            return $this->sendError("Unexpected error. Vendor request not found", [], 500);
        }
    
        if ($vendorCode !== "local_server" && !$vendorRequest[$vendorCode]) {
            return $this->sendError("Unexpected error. Vendor code missing", [], 500);
        }
    
        // Calculate balances
        $userBalance = (float) $transactionData['user_balance'];
        $sellingPrice = (float) $transactionData['selling_price'];
        $newUserBalance = $userBalance - $sellingPrice;
    
        // Prepare wallet out transaction data
        $walletOut = [
            "user_id" => $transactionData["user_id"],
            "description" => $transactionData["description"],
            "old_balance" => $userBalance,
            "amount" => $sellingPrice,
            "status" => "1",
            "new_balance" => $newUserBalance,
            "reference" => $this->uniqueReference,
        ];

        // Prepare the transaction data
        $ussdString = $this->getUSSDString($purchaseCategory, $vendorRequest, $purchaseData, $theProduct);
        
        $transactData = [
            "user_id" => $transactionData["user_id"],
            "plan" => $transactionData["user_plan_name"],
            "description" => $transactionData["description"],
            "extra_info" => $transactionData["extra_info"] ?? NULL,
            "destination" => $transactionData["recipient"] ?? NULL,
            "old_balance" => $userBalance,
            "amount" => $sellingPrice,
            "new_balance" => $newUserBalance,
            "costprice" => (float) $transactionData["cost_price"],
            "category" => $purchaseCategory,
            "transaction_reference" => NULL,
            "reference" => $this->uniqueReference,
            "status" => "0",
            "ussd_code" => $ussdString,
            "api_id" => $theProductApi["id"],
            "channel" => "website"
        ];
        
        // Start database transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Charge the user wallet
            $this->walletService->createWallet("outward", $walletOut);
    
            // Create transaction record
            $this->transactService->createTransaction($transactData);
    
            // Send the request to the provider
            $sendToProvider = $this->sendToProvider($purchaseData, $theProductApi);
    
            // Attempt to update the order based on provider response
            $result = $this->updateOrder($this->uniqueReference, $sendToProvider);
    
            DB::commit(); // Commit transaction on success
            return $result;
            
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on failure
            // Log the exception for debugging
            Log::error('Error in updating request by product ID: ' . $e->getMessage(), ['product_id' => $productId, 'exception' => $e]);
    
            return $this->sendError("Something unexpected went wrong", [], 500);
        }
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

            case "ipay":
                // Let's prepare some key info about the delivery of the order...
                $connectVendor = app(Ipay::class);
                $submitOrder = $connectVendor->processRequest($purchaseData, $apiDetails);
            break;
        }

        return $submitOrder;
    }

    private function updateOrder($reference, $providerResponse) {
        $decodeResponse = json_decode($providerResponse->getContent(), true)['data'];
        DB::beginTransaction();
        
        try {
            // Decode the provider response...
            $decodeResponse = json_decode($providerResponse->getContent(), true)['data'];
            
            // Retrieve the transaction...
            $transaction = Transaction::where('reference', $reference)->firstOrFail();

            // Determine the transaction reference...
            $transactReference = $decodeResponse['transaction_reference'] ?? 
                                $decodeResponse['ref'] ?? 
                                $decodeResponse['reference'] ?? 
                                null;

            // If successful...
            if ($providerResponse->getStatusCode() === 200) {
                $transaction->update([
                    'transaction_reference' => $transactReference,
                    'status' => $decodeResponse['delivery_status'] ?? '0',
                    'pin_details' => isset($decodeResponse['pin_detail']) ? json_encode($decodeResponse['pin_detail']) : null,
                    'token_details' => isset($decodeResponse['token_detail']) ? json_encode($decodeResponse['token_detail']) : null,
                    'response' => json_encode($decodeResponse)
                ]);

                DB::commit();
                
                return $this->sendResponse('Success', [
                    'reference' => $reference,
                    'amount_charged' => $transaction->amount,
                    'description' => $transaction->description,
                    'message' => $transaction->description . ' was successful.',
                    'new_wallet' => $transaction->new_balance
                ]);
            }

            // Update the transaction as refunded...
            $transaction->update([
                'status' => '3',
                'response' => json_encode($decodeResponse)
            ]);

            $userId = $transaction->user_id;
            $userBalance = $this->walletService->getUserBalance($userId);
            $amountRefund = $transaction->amount;
            $newUserBalance = $userBalance + $amountRefund;

            $description = 'Refund of ' . str_replace('Purchase of ', '', $transaction->description);
            $refundReference = 'refund-' . $reference;

            // Create wallet refund...
            $walletOut = [
                'user_id' => $userId,
                'description' => $description,
                'old_balance' => $userBalance,
                'amount' => $amountRefund,
                'status' => '1',
                'new_balance' => $newUserBalance,
                'reference' => $refundReference,
                'channel' => 'website'
            ];
            $this->walletService->createWallet('inward', $walletOut);

            // Refund the transaction...
            $transactData = [
                'user_id' => $userId,
                'plan' => $transaction->plan,
                'description' => $description,
                'destination' => $transaction->destination,
                'old_balance' => $userBalance,
                'amount' => $amountRefund,
                'new_balance' => $newUserBalance,
                'costprice' => $amountRefund,
                'category' => $transaction->category,
                'transaction_reference' => $transactReference,
                'reference' => $refundReference,
                'status' => '4',
                'api_id' => $transaction->api_id,
                'channel' => 'website',
                'response' => 'Wallet refunded'
            ];
            $this->transactService->createTransaction($transactData);

            DB::commit();
            return $this->sendError($decodeResponse, [], 400);
            
        } catch (Exception $e) {
            DB::rollBack();
            // Log the error for debugging
            Log::error('Order Update Failed', ['error' => $e->getMessage(), 'reference' => $reference]);
            return $this->sendError('An error occurred while updating the order.', [], 500);
        }
    }
        
    private function getVendorRequestByCategory($category, $productId) {
        switch ($category) {
            case 'airtime':
                return $this->airtimeRequest->getAirtimeRequest($productId);
            case 'data':
                return $this->dataRequest->getDataRequest($productId);
            case 'cabletv':
                return $this->cabletvRequest->getCabletvRequest($productId);
            case 'education':
                return $this->eduRequest->getEducationRequest($productId);
            case 'electricity':
                return $this->electrictyiRequest->getElectricityRequest($productId);
            default:
                return false;
        }
    }
    
    private function getUSSDString($category, $vendorRequest, $purchaseData, $theProduct) {
        if ($category === 'airtime') {
            return self::formUSSDString($category, $vendorRequest, [
                "phone_number" => $purchaseData["phone_number"],
                "amount" => $purchaseData["amount"],
                "product_id" => $theProduct["product_id"]
            ]);
        } elseif ($category === 'data') {
            return self::formUSSDString($category, $vendorRequest, [
                "phone_number" => $purchaseData["phone_number"],
                "product_id" => $theProduct["product_id"]
            ]);
        }

        return null;
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