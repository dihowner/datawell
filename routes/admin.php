<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\DataRequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AirtimeToCashController;
use App\Http\Controllers\AirtimeRequestController;
use App\Http\Controllers\AppServerController;
use App\Http\Controllers\CabletvRequestController;
use App\Http\Controllers\EducationRequestController;
use App\Http\Controllers\ElectricityRequestController;

Route::prefix('main')->group(function() {

    // Redirect to login page if Route does not exists...
    Route::fallback(function () {
        return redirect()->route('adminLogin');
    });

    Route::get('/', function () {
        return redirect('/main/index');
    });

    Route::get('/index', function () {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin-dashboard');
        }
        return view('main.login');
    })->name('adminLogin');

    Route::controller(AdminAuthController::class)->group(function() {
        Route::post('processAdminLogin', 'LoginAccount')->name('sign-in-admin');
        Route::get('logout', 'logOut')->name('admin.sign-out');

    });

    Route::middleware(["adminauth"])->group(function() {

        Route::controller(AdminController::class)->group(function() {
            Route::get("/dashboard", "index")->name('admin-dashboard');
            Route::get("/system-settings", "systemSettings")->name('system-settings');

            Route::put('/update-monnify', "updateMonnify")->name('update-monnify');
            Route::put('/update-bank-settings-charges', "updateBankSettings")->name('update-bank-settings-charges');
            Route::put('/update-flutterwave', "updateFlutterwave")->name('update-flutterwave');
            Route::put('/update-paystack', "updatePaystack")->name('update-paystack');
            Route::put('/update-airtime-info', "updateAirtimeInfo")->name('update-airtime-info');
            Route::put('/update-airtime-conversion', "updateAirtimeConversion")->name('update-airtime-conversion');
        });

        Route::controller(UserController::class)->group(function() {
            Route::get("/userlist", "usersList")->name('userlist');
            Route::get("/user-mgt", "userMgt")->name('user-mgt');

            Route::prefix('user')->group(function() {
                Route::get("/{id}/generate-va", "GenerateUserVirtualAccount");
                Route::put("/{id}/update-user", "updateUser")->name('update-user');
            });
            
        });

        Route::prefix('airtime-cash')->controller(AirtimeToCashController::class)->group(function() {
            Route::get("/", "airtimeCashHisories")->name('airtimecash-admin-history');
            Route::get("/view-airtime-conversion/{reference}", "viewAirtimeConversion")->name('view-airtime-conv');
            Route::get("/{id}/approve", "approveConversion");
            Route::get("/{id}/decline", "declineConversion");
        });

        Route::prefix('bank-withdrawals')->controller(WithdrawalController::class)->group(function() {
            Route::get("/", "withdrawalHistories")->name('bank-withdrawals');
            Route::get("/{id}/approve", "approveWithdrawal");
            Route::get("/{id}/decline", "declineWithdrawal");
        });

        Route::prefix('transactions')->controller(TransactionController::class)->group(function() {
            Route::get('/', 'getAllPurchaseHistory')->name('admin-transactions-histories');
            Route::get('/successful', 'getSuccessfulPurchaseHistory')->name('admin-successful-transactions-histories');
            Route::get('/awaiting', 'getAwaitingPurchaseHistory')->name('admin-awaiting-transactions-histories');
            Route::get('/pending', 'getPendingPurchaseHistory')->name('admin-pending-transactions-histories');

            Route::post('/process-transaction', 'ProcessTransaction')->name('process-transaction');
        });
        
        Route::prefix('api')->controller(ApiController::class)->group(function() {
            Route::get('/setup', 'index')->name('api-index');
            Route::get('/api-switch', 'switchVendors')->name('api-switch');
            Route::get('/create-api', 'createApiView')->name('createapi-view');
            Route::get('/vendor/{vendorId}', 'getVendor');
            Route::get('/{apiId}', 'getApi');
            Route::get('/{id}/delete', 'deleteApi');
            
            Route::post('/createapi', 'createApi')->name('createapi');
            Route::put('/update-api', 'updateApi')->name('update-api');
            Route::put('/update-api-settings', 'updateApiSwitchSettings')->name('update-api-settings');

        });
        
        Route::prefix('app')->controller(AppServerController::class)->group(function() {
            Route::get('/app', 'index')->name('app-server');
            Route::get('/create-server', 'createAppServerView')->name('createapp-serverview');
            Route::get('/{id}/delete', 'deleteAppServer');
            
            Route::post('/create-server', 'createAppServer')->name('createapp-server');
            Route::put('/app-server/{id}/update', 'updateAppServer')->name('update-app-server');

        });
                
        Route::controller(PlansController::class)->group(function() {
            Route::get("/createplan", "createPlanView")->name('createplan-view');
            
            Route::prefix('plans')->group(function() {
                Route::get("/", "allPlans")->name('planlist');
                Route::get("/{id}/delete", "deletePlan")->name('delete-plan');
                Route::get('/search', 'searchPlan')->name('search-plan');
            });

            Route::post("/createplan", "createPlan")->name('createplan');

            Route::get("/setprice/{id}", "getPlanProducts")->name('setPrice');
            Route::put('updateProductPricing/{id}', 'updateProductPricing')->name('update-product-plan');

            Route::put("/plan/{id}/update", "updatePlan")->name("update-plan");
        });

        Route::controller(ProductController::class)->group(function() {
            Route::get("/product-list", "allProducts")->name('product-list');
            Route::get("/createproduct", "createProductView")->name('createproduct-view');
            Route::get("/editproduct-view", "editProductView")->name('editproduct-view');
            Route::get("/get-category-product", "getAllProductByCategory")->name("get-category-product");
            Route::get('/product/search', 'searchProduct')->name('search-product');

            Route::post("/createproduct", "createProduct")->name('createproduct');
            Route::put("/editCostPrice", "editCostPrice")->name('update-cost-price');
        });

        Route::controller(WalletController::class)->group(function() {
            Route::get("/payment-history", "paymentHistory")->name('payment-history');
            Route::get("/payment/{id}/approve", "approvePayment");
            Route::get("/payment/{id}/decline", "declinePayment");
        });

        Route::prefix('airtime-request')->controller(AirtimeRequestController::class)->group(function() {
            Route::get("/", "airtimeView")->name('airtime-request');
            Route::get("/{id}/delete", "deleteRequest");

            Route::post("/create-airtime-request", "createAirtimeRequest")->name("create-airtime-request");
            Route::put("/{id}/update", "updateRequest")->name("update-airtime-request");
        });

        Route::prefix('electricity-request')->controller(ElectricityRequestController::class)->group(function() {
            Route::get("/", "electricityView")->name('electricity-request');
            Route::get("/{id}/delete", "deleteRequest");

            Route::post("/create-electricity-request", "createElectricityRequest")->name("create-electricity-request");
            Route::put("/{id}/update", "updateRequest")->name("update-electricity-request");
        });

        Route::prefix('education-request')->controller(EducationRequestController::class)->group(function() {
            Route::get("/", "educationView")->name('education-request');
            Route::get("/{id}/delete", "deleteRequest");

            Route::post("/create-education-request", "createEducationRequest")->name("create-education-request");
            Route::put("/{id}/update", "updateRequest")->name("update-education-request");
        });

        Route::prefix('cabletv-request')->controller(CabletvRequestController::class)->group(function() {
            Route::get("/", "cableTvView")->name('cabletv-request');
            Route::get("/{id}/delete", "deleteRequest");

            Route::post("/create-cabletv-request", "createCabletvRequest")->name("create-cabletv-request");
            Route::put("/{id}/update", "updateRequest")->name("update-cabletv-request");
        });

        Route::prefix('data-request')->controller(DataRequestController::class)->group(function() {
            Route::get("/", "dataView")->name('data-request');
            Route::get("/{id}/delete", "deleteRequest");

            Route::post("/create-data-request", "createDataRequest")->name("create-data-request");
            Route::put("/{id}/update", "updateRequest")->name("update-data-request");
        });

    });

    Route::controller(AdminController::class)->group(function()  {
        Route::get("/dashboard-counter", "fetchDashboardStats");
    });
    
    Route::controller(ProductController::class)->group(function() {
        Route::get("/product/{id}/delete", "deleteProduct");
    });
});