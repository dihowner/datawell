<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Services\PlanService;
use App\Services\UserService;
use App\Services\WalletService;
use App\Services\ProductService;
use App\Services\SettingsService;
use App\Http\Requests\SettingsRequest;
use App\Services\TransactionService;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{

    protected $settingsService, $userService, $walletService, $productService, $planService, $transactService;
    public function __construct(UserService $userService, WalletService $walletService, ProductService $productService,
                     PlanService $planService, TransactionService $transactService,  SettingsService $settingsService)
    {
        $this->settingsService = $settingsService; 
        $this->userService = $userService;   
        $this->walletService = $walletService;   
        $this->productService = $productService;  
        $this->planService = $planService;   
        $this->transactService = $transactService;   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return auth()->guard('admin')->user();
        return view('main.dashboard');
    }

    public function fetchDashboardStats() {       
        
        $yesterdayTransaction = $this->transactService->transactionSummary('yesterday_total');
        $todayTransaction = $this->transactService->transactionSummary('today_total');
        $thisWeekTransaction = $this->transactService->transactionSummary('this_week');
        $lastWeekTransaction = $this->transactService->transactionSummary('last_week');
        $thisMonthTransaction = $this->transactService->transactionSummary('this_month');
        $lastMonthTransaction = $this->transactService->transactionSummary('last_month');
        
        return [
            "users" => $this->userService->totalUser(),
            "wallet" => [
                "user" => $this->walletService->getUserBalance(),
                "this_month" => $this->walletService->walletCredited('this_month'),
                "last_month" => $this->walletService->walletCredited('last_month')
            ], 
            "products" => $this->productService->totalProduct(),
            "plan" => $this->planService->totalPlan(),
            "transactions" => [
                "yesterday_total" => $yesterdayTransaction->total_sales,
                "today_total" => $todayTransaction->total_sales,
                "yesterday_profit" => $yesterdayTransaction->profit,  
                "today_profit" => $todayTransaction->profit,  
                "this_week_profit" => $thisWeekTransaction->profit,  
                "last_week_profit" => $lastWeekTransaction->profit,  
                "this_month_profit" => $thisMonthTransaction->profit,  
                "last_month_profit" => $lastMonthTransaction->profit,
            ]
        ];
    }
    
    public function systemSettings() {
        return view('main.settings');
    }
    
    public function updateMonnify(SettingsRequest $request) {
        $updateMonnify = $this->settingsService->updateSettings($request->validated(), "monnify");

        $decodeResponse = json_decode($updateMonnify->getContent(), true);
        if($updateMonnify->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }
    
    public function updateBankSettings(SettingsRequest $request) {
        $updateBankSettings = $this->settingsService->updateSettings($request->validated(), "bankInformation");
                
        $decodeResponse = json_decode($updateBankSettings->getContent(), true);
        if($updateBankSettings->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }
    
    public function updateFlutterwave(SettingsRequest $request) {
        $updateFlutterwave = $this->settingsService->updateSettings($request->validated(), "flutterwave");
                
        $decodeResponse = json_decode($updateFlutterwave->getContent(), true);
        if($updateFlutterwave->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }
    
    public function updatePaystack(SettingsRequest $request) {
        $updatePaystack = $this->settingsService->updateSettings($request->validated(), "paystack");
                
        $decodeResponse = json_decode($updatePaystack->getContent(), true);
        if($updatePaystack->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }
    
    public function updateAirtimeInfo(SettingsRequest $request) {
        $updateAirtimeInfo = $this->settingsService->updateSettings($request->validated(), "airtimeInfo");
                
        $decodeResponse = json_decode($updateAirtimeInfo->getContent(), true);
        if($updateAirtimeInfo->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }
    
    public function updateAirtimeConversion(SettingsRequest $request) {
        
        $updateAirtimeConversion = $this->settingsService->updateSettings($request->validated(), "airtimeConversion");
                
        $decodeResponse = json_decode($updateAirtimeConversion->getContent(), true);
        if($updateAirtimeConversion->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }
    
}