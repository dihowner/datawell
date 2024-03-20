<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ConnectController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\FlutterwaveController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AirtimeToCashController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function() {
    
    Route::get('/', function () {
        return redirect('login');
    });
    
    Route::get('/about', function () {
        return view('about');
    })->name('about-us');
    
    Route::get('/contact', function () {
        return view('contactus');
    });    

    Route::get('/login', function () {
        return view('login');
    })->name('get.login');

    Route::get('/create-account', function () {
        return view('register');
    })->name('get.register');

    Route::get('/pricing', function () {
        return view('pricing');
    })->name('get.pricing');

    Route::get('/forgot-password', function () {
        return view('forgot-password');
    })->name('forgot-password-form');

    Route::controller(ApiController::class)->group(function () {
        Route::get('test', 'TestApi');
    });
    
});

Route::controller(AuthController::class)->group(function() {

    Route::post('forget-password', 'submitForgetPasswordForm')->name('forgot-password-request');
    Route::get('verify-account/{code}', 'verifyUserAccount')->name('verify-account');
    Route::get('reset-password/{token}', 'resetPasswordForm')->name('reset-password');
    Route::put('reset-password', 'resetUserPassword')->name('submit-reset-password');
    
    Route::post('joinus', 'createAccount')->name('joinus');
    Route::post('sign-in-account', 'loginAccount')->name('sign-in-account');

    Route::get('/user/logout', 'logOut')->name('user.sign-out');

    // Authentication....i.e change of password, transaction pin...
    Route::middleware(['auth'])->prefix('user')->group(function() {
        Route::post('/edit-user-password', 'ModifyUserPassword')->name('user.edit-user-password');
        Route::post('/modify-txn-pin', 'ModifyUserTxnPin')->name('user.modify-txn-pin');
    });
});

Route::middleware(['auth'])->prefix('user')->group(function() {

    Route::controller(UserController::class)->group(function() {
        Route::get('/dashboard', 'index')->name('user.index');
        Route::get('/generate-virtual-account', 'GenerateUserVirtualAccount')->name('user.generate-va');
        Route::get('/my-profile', 'MyProfile')->name('user.profile');
        Route::get('/bank-account', 'BankInfoView')->name('user.bank-account');
        Route::get('/change-pin-password', 'PinPassView')->name('user.pin-password-view');

        Route::post('/edit-bank-account', 'UpdateUserBank')->name('user.edit-bank-account');
        Route::put('/submit-upgrade-plan', 'UpgradePlan')->name('user.submit-upgrade-plan');
    });

    Route::controller(WalletController::class)->group(function() {
        Route::get('/fund-wallet', 'fundWalletView')->name('user.fund-wallet-view');
        Route::get('/proceed-payment/{id}', 'ProceedPaymentView')->name('user.proceed-payment');
        Route::get('/share-wallet', 'shareWalletView')->name('user.share-wallet-view');
        Route::get('/wallet-history', 'getUserInwardHistory')->name('user.wallet-history');
        Route::get('/wallet-history/search', 'searchUserInwardHistory')->name('user.search-inward-txn');

        Route::post("/create-wallet-request", "createWalletRequest")->name('user.create-wallet-request');
        Route::post("/submit-share-wallet", "ShareWallet")->name('user.submit-share-wallet');
    });

    Route::controller(PlansController::class)->group(function() {
        Route::get('/upgrade-plan', 'UpgradePlanView')->name('user.upgrade-plan-view');
    });

    Route::controller(FlutterwaveController::class)->group(function() {
        Route::get("/generate-flutterwave-link/{id}", "GenerateFlutterwaveLink")->name('user.generate-flutterwave-link');
        Route::get("/approve-flutterwave-payment", "ApprovePayment")->name('user.approve-flutterwave-payment');
    });

    Route::controller(PaystackController::class)->group(function() {
        Route::get("/generate-paystack-link/{id}", "GeneratePaystackLink")->name('user.generate-paystack-link');
        Route::get("/approve-paystack-payment", "ApprovePayment")->name('user.approve-paystack-payment');
    });

    // Purchase of Service...
    Route::controller(PurchaseController::class)->group(function() {
        Route::get('/data-menu', 'dataMenu')->name('user.data-menu');
        Route::get('/datamenu', 'dataMenus')->name('user.datamenu');
        Route::get('/datamenu/{category}', 'fetchDataBundle')->name('user.fetchdata');
        Route::get('/electricity-menu', 'electricityMenu')->name('user.electricity-menu');
        Route::get('/cabletv-menu', 'cabletvMenu')->name('user.cabletv-menu');

        Route::get('/buy-airtime', 'buyAirtimeView')->name('user.buy-airtime');
        Route::get('/buy-mtn-data', 'buyMTNDataView')->name('user.buy-mtn-data');
        Route::get('/buy-airtel-data', 'buyAirtelDataView')->name('user.buy-airtel-data');
        Route::get('/buy-glo-data', 'buyGloDataView')->name('user.buy-glo-data');
        Route::get('/buy-9mobile-data', 'buy9mobileDataView')->name('user.buy-9mobile-data');

        Route::get('/buy-dstv', 'buyDstvView')->name('user.buy-dstv');
        Route::get('/buy-gotv', 'buyGotvView')->name('user.buy-gotv');
        Route::get('/buy-startimes', 'buyStartimesView')->name('user.buy-startimes');

        Route::get('/buy-waec', 'buyWaecView')->name('user.buy-waec');
        Route::get('/buy-neco', 'buyNecoView')->name('user.buy-neco');

        Route::get('/buy-ibedc-bills', 'buyIBEDCView')->name('user.buy-ibedc-bills');
        Route::get('/buy-phedc-bills', 'buyPHEDCView')->name('user.buy-phedc-bills');
        Route::get('/buy-aedc-bills', 'buyAEDCView')->name('user.buy-aedc-bills');
        Route::get('/buy-kedc-bills', 'buyKEDCView')->name('user.buy-kedc-bills');
        Route::get('/buy-kaedc-bills', 'buyKAEDCView')->name('user.buy-kaedc-bills');
        Route::get('/buy-eedc-bills', 'buyEEDCView')->name('user.buy-eedc-bills');
        Route::get('/buy-jedc-bills', 'buyJEDCView')->name('user.buy-jedc-bills');
        Route::get('/buy-ekedc-bills', 'buyEKEDCView')->name('user.buy-ekedc-bills');
        Route::get('/buy-ikedc-bills', 'buyIKEDCView')->name('user.buy-ikedc-bills');

        Route::post('/submit-airtime-request', 'purchaseAirtime')->name('user.submit-airtime-request');
        Route::post('/submit-data-request', 'purchaseData')->name('user.submit-data-request');
        Route::post('/submit-cabletv-request', 'purchaseCableTv')->name('user.submit-cabletv-request');
        Route::post('/submit-education-request', 'purchaseEducation')->name('user.submit-education-request');
        Route::post('/submit-electricity-request', 'purchaseElectricity')->name('user.submit-electricity-request');
    });

    Route::controller(AirtimeToCashController::class)->group(function() {
        Route::get('/airtime-to-cash', 'conversionView')->name('user.airtime-to-cash');
        Route::get('/bank-withdrawal', 'bankWithdrawalView')->name('user.bank-withdrawal');
        Route::get('/airtime-conv-history', 'userConversionHistory')->name('user.airtimeconv-history');
        Route::get('/airtime-conv-history/search', 'searchUserConversionHistory')->name('user.search-airtime-conv');
        Route::get('/view-airtime-conversion/{reference}', 'viewAirtimeConversion')->name('view-airtime');

        Route::post('/submit-airtime-cash-request', 'SubmitAirtimeCash')->name('user.submit-airtime-cash-request');
        Route::post('/submit-withdrawal-request', 'CreateWithdrawalRequest')->name('user.submit-withdrawal-request');
    });

    Route::controller(TransactionController::class)->group(function() {
        Route::get('/transactions', 'getUserPurchaseHistory')->name('user.transactions');
        Route::get('/transactions/search', 'searchUserPurchaseHistory')->name('user.search-transactions');
        Route::get('/transactions/{reference}', 'viewTransaction')->name('user-view-transaction');
    });

    Route::controller(WithdrawalController::class)->group(function() {
        Route::get('/withdrawals', 'index')->name('user.withdrawals-history');
        Route::get('/convert-airtime-wallet', 'convertAirtimeWalletView')->name('user.convert-airtimewallet-view');
        Route::get('/withdrawals/{reference}', 'viewWithdrawal')->name('view-withdrawal');
        // Route::get('/withdrawals/search', 'searchUserWithdrawals')->name('user.search-transactions');
        
        Route::post('/convert-airtime-wallet', 'convertAirtimeWallet')->name('user.convert-airtime-wallet');
    });

});

Route::controller(ProductController::class)->group(function() {
    Route::get('/get-product-category/{category}', 'fetchProductCategory')->name('search-product');

});

Route::controller(UserController::class)->group(function() {
    Route::get('/search-user/{userPhone}', 'findUser')->name('search-user');
});

// Verification Route...
Route::controller(PurchaseController::class)->group(function() {
    Route::get('/verify-cable-tv', 'verifyDecoder');
    Route::get('/verify-electricity', 'verifyMeterNumber');
});

Route::controller(ConnectController::class)->prefix('connection/{server}')->name('connection.')->group(function () {
    Route::get('index.php', 'index')->name('process');
    Route::get('calling.php', 'calling')->name('calling');
    Route::get('process.php', 'process')->name('process');
    Route::get('screen.php', 'screen')->name('screen');
    Route::get('report.php',  'report')->name('report');
});

Route::controller(ConnectController::class)->prefix('fetch/{server}')->name('connection.')->group(function () {
    Route::get('index.php', 'index')->name('process');
    Route::get('calling.php', 'calling')->name('calling');
    Route::get('process.php', 'process')->name('process');
    Route::get('screen.php', 'screen')->name('screen');
    Route::get('report.php',  'report')->name('report');
});

Route::prefix('webhook')->group(function () {
    Route::controller(WalletController::class)->prefix('monnify')->group(function () {
        Route::post('approve', 'approveMonnifyPayment');
    });
});

Route::controller(TestController::class)->prefix('test')->group(function () {
    Route::get('mobilenig', 'fetchService');
    Route::get('smeplug/data', 'fetchSmeplugDataService');
});

// Admin Routing...
require __DIR__."/admin.php";