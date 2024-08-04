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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        $totalPendingPayment = $this->walletService->totalPendingPayment();
        
        if ($totalPendingPayment > 0) {
            Session::flash('info', 'You currently have '. $totalPendingPayment.' pending payment(s) awaiting approval');
        }

        // dd(Session::all());
        // return auth()->guard('admin')->user();
        // return $this->fetchDashboardStats();
        return view('main.dashboard');
    }

    public function fetchDashboardStats() {       
        
        $yesterdayTransaction = $this->transactService->transactionSummary('yesterday_total');
        $todayTransaction = $this->transactService->transactionSummary('today_total');
        $thisWeekTransaction = $this->transactService->transactionSummary('this_week');
        $lastWeekTransaction = $this->transactService->transactionSummary('last_week');
        $thisMonthTransaction = $this->transactService->transactionSummary('this_month');
        $lastMonthTransaction = $this->transactService->transactionSummary('last_month');
        
        $mtnYesterdayTransaction = $this->transactService->dataSummary('mtn', 'yesterday_total');
        $mtnTodayTransaction = $this->transactService->dataSummary('mtn', 'today_total');
        $mtnThisWeekTransaction = $this->transactService->dataSummary('mtn', 'this_week');
        $mtnThisMonthTransaction = $this->transactService->dataSummary('mtn', 'this_month');
        $mtnLastMonthTransaction = $this->transactService->dataSummary('mtn', 'last_month');
        
        $airtelYesterdayTransaction = $this->transactService->dataSummary('airtel', 'yesterday_total');
        $airtelTodayTransaction = $this->transactService->dataSummary('airtel', 'today_total');
        $airtelThisWeekTransaction = $this->transactService->dataSummary('airtel', 'this_week');
        $airtelThisMonthTransaction = $this->transactService->dataSummary('airtel', 'this_month');
        $airtelLastMonthTransaction = $this->transactService->dataSummary('airtel', 'last_month');
        
        $gloYesterdayTransaction = $this->transactService->dataSummary('glo', 'yesterday_total');
        $gloTodayTransaction = $this->transactService->dataSummary('glo', 'today_total');
        $gloThisWeekTransaction = $this->transactService->dataSummary('glo', 'this_week');
        $gloThisMonthTransaction = $this->transactService->dataSummary('glo', 'this_month');
        $gloLastMonthTransaction = $this->transactService->dataSummary('glo', 'last_month');
        
        $etiYesterdayTransaction = $this->transactService->dataSummary('9mobile', 'yesterday_total');
        $etiTodayTransaction = $this->transactService->dataSummary('9mobile', 'today_total');
        $etiThisWeekTransaction = $this->transactService->dataSummary('9mobile', 'this_week');
        $etiThisMonthTransaction = $this->transactService->dataSummary('9mobile', 'this_month');
        $etiLastMonthTransaction = $this->transactService->dataSummary('9mobile', 'last_month');
        
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
            ],
            "data" => [
                "mtn_yesterday_total" => $mtnYesterdayTransaction,
                "mtn_today_total" => $mtnTodayTransaction,
                "mtn_this_week" => $mtnThisWeekTransaction,
                "mtn_this_month" => $mtnThisMonthTransaction,
                "mtn_last_month" => $mtnLastMonthTransaction,
                
                "airtel_yesterday_total" => $airtelYesterdayTransaction,
                "airtel_today_total" => $airtelTodayTransaction,
                "airtel_this_week" => $airtelThisWeekTransaction,
                "airtel_this_month" => $airtelThisMonthTransaction,
                "airtel_last_month" => $airtelLastMonthTransaction,
                
                "glo_yesterday_total" => $gloYesterdayTransaction,
                "glo_today_total" => $gloTodayTransaction,
                "glo_this_week" => $gloThisWeekTransaction,
                "glo_this_month" => $gloThisMonthTransaction,
                "glo_last_month" => $gloLastMonthTransaction,
                
                "eti_yesterday_total" => $etiYesterdayTransaction,
                "eti_today_total" => $etiTodayTransaction,
                "eti_this_week" => $etiThisWeekTransaction,
                "eti_this_month" => $etiThisMonthTransaction,
                "eti_last_month" => $etiLastMonthTransaction
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
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
    
    public function updateBankSettings(SettingsRequest $request) {
        $updateBankSettings = $this->settingsService->updateSettings($request->validated(), "bankInformation");
                
        $decodeResponse = json_decode($updateBankSettings->getContent(), true);
        if($updateBankSettings->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
    
    public function updateFlutterwave(SettingsRequest $request) {
        $updateFlutterwave = $this->settingsService->updateSettings($request->validated(), "flutterwave");
                
        $decodeResponse = json_decode($updateFlutterwave->getContent(), true);
        if($updateFlutterwave->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
    
    public function updatePaystack(SettingsRequest $request) {
        $updatePaystack = $this->settingsService->updateSettings($request->validated(), "paystack");
                
        $decodeResponse = json_decode($updatePaystack->getContent(), true);
        if($updatePaystack->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
    
    public function updateAirtimeInfo(SettingsRequest $request) {
        $updateAirtimeInfo = $this->settingsService->updateSettings($request->validated(), "airtimeInfo");
                
        $decodeResponse = json_decode($updateAirtimeInfo->getContent(), true);
        if($updateAirtimeInfo->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
    
    public function updateAirtimeConversion(SettingsRequest $request) {
        
        $updateAirtimeConversion = $this->settingsService->updateSettings($request->validated(), "airtimeConversion");
                
        $decodeResponse = json_decode($updateAirtimeConversion->getContent(), true);
        if($updateAirtimeConversion->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
    
    public function updateKycCharge(SettingsRequest $request) {
        
        $updateAirtimeConversion = $this->settingsService->updateSettings($request->validated(), "kycCharges");
                
        $decodeResponse = json_decode($updateAirtimeConversion->getContent(), true);
        if($updateAirtimeConversion->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
    
    public function updateVendRestriction(SettingsRequest $request) {
        $updateVendingRestriction = $this->settingsService->updateSettings($request->validated(), "vendingRestriction");
        $decodeResponse = json_decode($updateVendingRestriction->getContent(), true);
        if($updateVendingRestriction->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }
    
}