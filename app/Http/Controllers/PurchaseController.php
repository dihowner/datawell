<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\ProductService;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AirtimeRequest;
use App\Http\Requests\CableTvRequest;
use App\Http\Requests\EducationRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\ElectricityRequest;
use App\Http\Requests\DataPurchaseRequest;
use App\Http\Requests\ServiceVerifyRequest;
use App\Http\Controllers\CategoryController;
use App\Services\UtilityService;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{
    protected $userService, $productService, $categoryController, $purchaseService, $utilityService;
    public function __construct(UserService $userService, ProductService $productService, CategoryController $categoryController, PurchaseService $purchaseService,
                                 UtilityService $utilityService){
        $this->userService = $userService;
        $this->productService = $productService;
        $this->purchaseService = $purchaseService;
        $this->utilityService = $utilityService;
        $this->categoryController = $categoryController;
    }

    public function dataMenu()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        return view('private.data-menu', compact('userDetail'));
    }

    public function dataMenus()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $mtnDataCategories = $this->categoryController->getSubCategory('MTN Data Bundle');
        $airtelDataCategories = $this->categoryController->getSubCategory('Airtel Data Bundle');
        $gloDataCategories = $this->categoryController->getSubCategory('Glo Data Bundle');
        
        $allCatWithSub = $mtnDataCategories->merge($airtelDataCategories)->merge($gloDataCategories);
        
        $get9mobile = $this->categoryController->getCategory("Data Bundle");

        $array1 = json_decode($allCatWithSub, true);
        $array2 = json_decode($get9mobile, true);
        
        $dataCategories = collect(array_merge($array1, [$array2]));

        $categoryWithImages = $dataCategories->map(function ($category) {
            $categoryName = strtolower($category['category_name']) == 'data bundle' ? '9mobile' : $category['category_name'];
            $category['image'] = $this->utilityService->getProductImage($categoryName);
            return $category;
        });
        
        return view('private.datamenu', compact('userDetail', 'categoryWithImages'));
    }

    public function fetchDataBundle($category) {
        $category = str_replace("-" , " ", $category);
        
        $userDetail = $this->userService->getUserById(Auth::id());
        $getDataVolumes = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], $category);
        
        return view('private.buy-data', compact('userDetail', 'getDataVolumes'));
    }

    public function electricityMenu()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        return view('private.electricity-menu', compact('userDetail'));
    }

    public function cabletvMenu()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        return view('private.cabletv-menu', compact('userDetail'));
    }

    /**
     * Airtime service routing
     * purchaseAirtime method is used for airtime services
     */
    public function buyAirtimeView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $airtimeProducts = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail['plan_id'], 'Airtime Topup');

        if($airtimeProducts === false) {
            Alert::error("Error", "Your plan does not have an active Airtime Package linked");
            return redirect()->route('user.index');
        }
        return view('private.buy-airtime', compact('userDetail', 'airtimeProducts'));
    }

    public function purchaseAirtime(AirtimeRequest $request)
    {
        $airtimeData = $request->validated();

        $phoneNumber = $airtimeData['phone_number'];
        $network = $airtimeData['network'];
        $amount = $airtimeData['amount'];
        $transactPin = $airtimeData['transactpin'];

        $processAirtime = $this->purchaseService->purchaseAirtime($network, $phoneNumber, $amount, $transactPin);

        // return $processAirtime;

        $responseCode = $processAirtime->getStatusCode();
        $responseContent = json_decode($processAirtime->content(), true);

        if($responseCode === 200) {
            $message = $responseContent["data"]["message"];
            $message = $message. " Kindly check your balance";
            Session::flash('rate_us', true);
            Alert::success("Success", $message)->autoClose(10000);
        }
        else {
            $message = $responseContent["message"];
            Alert::error("Error", $message)->autoClose(10000);
        }
        return redirect()->back();
    }

    /**
     * Data Bundle service routing
     * purchaseData method is used for data bundle services
     */
    public function buyMTNDataView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $mtnDataCategories = $this->categoryController->getSubCategory('MTN Data Bundle');

        if(method_exists($mtnDataCategories, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch MTN Data Services");
            return redirect()->route('user.index');
        }
        return view('private.buy-mtn-data', compact('userDetail', 'mtnDataCategories'));
    }

    public function buyAirtelDataView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $airtelDataCategories = $this->categoryController->getSubCategory('Airtel Data Bundle');

        if(method_exists($airtelDataCategories, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch Airtel Data Services");
            return redirect()->route('user.index');
        }
        return view('private.buy-airtel-data', compact('userDetail', 'airtelDataCategories'));
    }

    public function buyGloDataView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $gloDataCategories = $this->categoryController->getSubCategory('Glo Data Bundle');

        if(method_exists($gloDataCategories, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch Glo Data Services");
            return redirect()->route('user.index');
        }
        return view('private.buy-glo-data', compact('userDetail', 'gloDataCategories'));
    }

    public function buy9mobileDataView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $getDataVolumes = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "Data Bundle");

        // return $getDataVolumes->getStatusCode();;

        // if(method_exists($getDataVolumes, 'getStatusCode')) {
        //     Alert::error("Error", "Something went wrong. Unable to fetch 9mobile Data Services");
        //     return redirect()->route('user.index');
        // }

        return view('private.buy-9mobile-data', compact('userDetail', 'getDataVolumes'));
    }

    public function purchaseData(DataPurchaseRequest $request)
    {
        $dataData = $request->validated();

        $phoneNumber = $dataData['phone_number'];
        $productId = $dataData['dataVolume'];
        $transactPin = $dataData['transactpin'];

        $processData = $this->purchaseService->purchaseData($productId, $phoneNumber, $transactPin);
        
        // return $processData;

        $responseCode = $processData->getStatusCode();
        $responseContent = json_decode($processData->content(), true);

        if($responseCode === 200) {
            $message = $responseContent["data"]["message"];
            $message = $message. " Kindly check your balance";
            Session::flash('rate_us', true);
            Alert::success("Success", $message)->autoClose(10000);
        }
        else {
            $message = $responseContent["message"];
            Alert::error("Error", $message)->autoClose(10000);
        }
        return redirect()->back();
    }

    /**
     * Cable TV service routing
     * verifyDecoder is used in verifying
     * purchaseCableTv method is used for cable tv services
     */
    public function buyDstvView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $dstvCategories = $this->categoryController->getSubCategory('Dstv');

        if($dstvCategories == NULL OR method_exists($dstvCategories, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch Dstv Services");
            return redirect()->route('user.index');
        }
        return view('private.buy-dstv', compact('userDetail', 'dstvCategories'));
    }

    public function buyGotvView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $gotvCategories = $this->categoryController->getSubCategory('Gotv');

        if($gotvCategories == NULL OR method_exists($gotvCategories, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch Gotv Services");
            return redirect()->route('user.index');
        }
        return view('private.buy-gotv', compact('userDetail', 'gotvCategories'));
    }

    public function buyStartimesView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $startimesPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "Startimes");

        if($startimesPackages == NULL OR method_exists($startimesPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch Startimes Services");
            return redirect()->route('user.index');
        }
        // return $startimesPackages;
        return view('private.buy-startimes', compact('userDetail', 'startimesPackages'));
    }

    public function verifyDecoder(ServiceVerifyRequest $request) {
        if (!$request->validated()) {
            $errors = $request->validator->getMessageBag()->toArray();
            return response()->json(['errors' => $errors], 422);
        }
        return $this->purchaseService->verifyDecoder($request->validated());
    }

    public function purchaseCableTv(CableTvRequest $request)
    {

        $cableData = $request->validated();

        $smartNumber = $cableData['smartcard_no'];
        $productId = $cableData['packageOption'];
        $transactPin = $cableData['transactpin'];
        $optionalData['amount'] = isset($cableData['amount']) ? $cableData['amount'] : 0;
        $optionalData['customer_name'] = $cableData['customer_name'];
        if(isset($cableData['customer_number'])) {
            $optionalData['customer_number'] = $cableData['customer_number'];
        }

        $processCableTv = $this->purchaseService->purchaseCableTv($productId, $smartNumber, $transactPin, $optionalData);
        $responseCode = $processCableTv->getStatusCode();
        
        $responseContent = json_decode($processCableTv->content(), true);

        if($responseCode === 200) {
            $message = $responseContent["data"]["message"];
            $message = $message;
            Session::flash('rate_us', true);
            Alert::success("Success", $message)->autoClose(10000);
        }
        else {
            $message = $responseContent["message"];
            Alert::error("Error", $message)->autoClose(10000);
        }
        return redirect()->back();
    }

    /**
     * Education service routing
     * purchaseEducation method is used for education services
     */
    public function buyWaecView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $waecPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "Waec");

        if($waecPackages == NULL OR method_exists($waecPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch WAEC Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-waec', compact('userDetail', 'waecPackages'));
    }

    public function buyNecoView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $necoPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "Neco");

        if($necoPackages == NULL OR method_exists($necoPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch NECO Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-neco', compact('userDetail', 'necoPackages'));
    }

    public function purchaseEducation(EducationRequest $request)
    {
        $eduData = $request->validated();
        $quantity = $eduData['quantity'];
        $productId = $eduData['serviceType'];
        $transactPin = $eduData['transactpin'];

        $processEducation = $this->purchaseService->purchaseEducation($productId, $quantity, $transactPin);

        $responseCode = $processEducation->getStatusCode();
        $responseContent = json_decode($processEducation->content(), true);

        if($responseCode === 200) {
            $message = $responseContent["data"]["message"];
            $message = $message;
            Session::flash('rate_us', true);
            Alert::success("Success", $message)->autoClose(10000);
        }
        else {
            $message = $responseContent["message"];
            Alert::error("Error", $message)->autoClose(10000);
        }
        return redirect()->back();
    }

    /**
     * Education service routing
     * purchaseElectricity method is used for education services
     */
    public function buyIBEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $ibedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "IBEDC (IBADAN)");

        if($ibedcPackages == NULL OR method_exists($ibedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch IBEDC (IBADAN) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-ibedc-bills', compact('userDetail', 'ibedcPackages'));
    }

    public function buyPHEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $phedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "PHEDC (PORTHARCOURT)");

        if($phedcPackages == NULL OR method_exists($phedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch PHEDC (PORTHARCOURT) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-phedc-bills', compact('userDetail', 'phedcPackages'));
    }

    public function buyAEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $aedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "AEDC (ABUJA)");

        if($aedcPackages == NULL OR method_exists($aedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch AEDC (ABUJA) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-aedc-bills', compact('userDetail', 'aedcPackages'));
    }

    public function buyKEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $kedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "KEDC (KANO)");

        if($kedcPackages == NULL OR method_exists($kedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch KEDC (KANO) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-kedc-bills', compact('userDetail', 'kedcPackages'));
    }

    public function buyKAEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $kaedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "KAEDC (KADUNA)");

        if($kaedcPackages == NULL OR method_exists($kaedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch KAEDC (KADUNA) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-kaedc-bills', compact('userDetail', 'kaedcPackages'));
    }

    public function buyEEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $eedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "EEDC (ENUGU)");

        if($eedcPackages == NULL OR method_exists($eedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch EEDC (ENUGU) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-eedc-bills', compact('userDetail', 'eedcPackages'));
    }

    public function buyJEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $jedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "JEDC (JOS)");

        if($jedcPackages == NULL OR method_exists($jedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch JEDC (JOS) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-jedc-bills', compact('userDetail', 'jedcPackages'));
    }

    public function buyEKEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $ekedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "EKEDC (EKO)");

        if($ekedcPackages == NULL OR method_exists($ekedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch EKEDC (EKO) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-ekedc-bills', compact('userDetail', 'ekedcPackages'));
    }

    public function buyIKEDCView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $ikedcPackages = $this->productService->getAllProductPriceWithPlanAndCategory($userDetail["plan_id"], "IKEDC (IKEJA)");

        if($ikedcPackages == NULL OR method_exists($ikedcPackages, 'getStatusCode')) {
            Alert::error("Error", "Something went wrong. Unable to fetch IKEDC (IKEJA) Services");
            return redirect()->route('user.index');
        }

        return view('private.buy-ikedc-bills', compact('userDetail', 'ikedcPackages'));
    }

    public function verifyMeterNumber(ServiceVerifyRequest $request) {
        if (!$request->validated()) {
            $errors = $request->validator->getMessageBag()->toArray();
            return response()->json(['errors' => $errors], 422);
        }
        return $this->purchaseService->verifyMeterNumber($request->validated());
    }

    public function purchaseElectricity(ElectricityRequest $request)
    {
        $electricData = $request->validated();

        $amount = $electricData['amount'];
        $productId = $electricData['serviceType'];
        $meterNumber = $electricData['meter_number'];
        $transactPin = $electricData['transactpin'];

        $optionalData['customer_name'] = $electricData['customer_name'];
        $optionalData['customer_address'] = $electricData['customer_address'];

        if(isset($electricData['customer_details'])) {
            $optionalData['customer_details'] = $electricData['customer_details'];
        }

        if(isset($electricData['customer_reference_id'])) {
            $optionalData['customer_reference_id'] = $electricData['customer_reference_id'];
        }

        if(isset($electricData['customer_tariff_code'])) {
            $optionalData['customer_tariff_code'] = $electricData['customer_tariff_code'];
        }

        if(isset($electricData['customer_access_code'])) {
            $optionalData['customer_access_code'] = $electricData['customer_access_code'];
        }

        if(isset($electricData['customer_dt_number'])) {
            $optionalData['customer_dt_number'] = $electricData['customer_dt_number'];
        }

        if(isset($electricData['customer_account_type'])) {
            $optionalData['customer_account_type'] = $electricData['customer_account_type'];
        }

        $processElectricity = $this->purchaseService->purchaseElectricity($productId, $amount, $meterNumber, $transactPin, $optionalData);

        $responseCode = $processElectricity->getStatusCode();
        $responseContent = json_decode($processElectricity->content(), true);

        if($responseCode === 200) {
            $message = $responseContent["data"]["message"];
            $message = $message;
            Session::flash('rate_us', true);
            Alert::success("Success", $message)->autoClose(10000);
        }
        else {
            $message = $responseContent["message"];
            Alert::error("Error", $message)->autoClose(10000);
        }
        return redirect()->back();
    }
}