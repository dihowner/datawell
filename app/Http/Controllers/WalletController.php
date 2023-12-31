<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\WalletService;
use App\Services\PaystackService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\TransferFundRequest;
use App\Http\Requests\ManualFundingRequest;
use App\Services\MonnifyService;

class WalletController extends Controller
{
    protected $walletService, $userService, $paystackService;

    public function __construct(WalletService $walletService, UserService $userService, PaystackService $paystackService) {
        $this->walletService = $walletService;
        $this->userService = $userService;
        $this->paystackService = $paystackService;
    }

    public function fundWalletView() {
        $userDetail = $this->userService->getUserById(Auth::id());
        return view('private.fund-wallet', compact('userDetail'));
    }

    public function createWalletRequest(ManualFundingRequest $request) {
        $createWallet = $this->walletService->createWalletRequest($request->validated());
        $responseCode = $createWallet->getStatusCode();
        $responseContent = json_decode($createWallet->content());
        if($responseCode === 200) {
            $referenceId = $responseContent->data->reference;
            return $this->redirectToUrl('user/proceed-payment', $referenceId);
        }

        $message = $responseContent->message;
        Alert::error("Error", $message);
        return redirect()->back();
    }

    public function ProceedPaymentView($id) {
        $userDetail = $this->userService->getUserById(Auth::id());
        $walletHistories = $this->walletService->viewPaymentHistory('wallet_in', $id);

        if($walletHistories['status'] != "0"){
            Alert::error("Error", "Request has already been treated.");
            return redirect()->route('user.fund-wallet-view');
        }

        if (method_exists($walletHistories, 'getStatusCode')) {
            $responseContent = json_decode($walletHistories->content());
            $message = $responseContent->message;
            Alert::error("Error", $message);
            // redirect the user back to funding view page
            return redirect()->route('user.fund-wallet-view');
        }
        else {
            return view('private.proceed-payment', compact('userDetail', 'walletHistories'));
        }
    }

    public function shareWalletView() {
        $userDetail = $this->userService->getUserById(Auth::id());
        return view('private.share-wallet', compact('userDetail'));
    }

    private function redirectToUrl($path, $referenceId) {
        // Generate the URL with the reference ID
        $url = url($path, ['id' => $referenceId]);

        // Redirect the user to the URL
        return redirect($url);
    }

    public function ShareWallet(TransferFundRequest $request) {
        $transferFund = $this->walletService->ShareWallet($request->validated());
        $responseCode = $transferFund->getStatusCode();
        $responseContent = json_decode($transferFund->content());
        $message = $responseContent->message;
        if($responseCode === 200) {
            Alert::success("Success", $message);
        } else {
            Alert::error("Error", $message);
        }
        return redirect()->back();
    }

    public function getUserInwardHistory() {
        try {
            $userId = Auth::id();
            $userIncome = $this->walletService->userInwardHistory($userId);
            $userDetail = $this->userService->getUserById($userId);

            return view("private.wallet-history", compact("userDetail", "userIncome"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function searchUserInwardHistory(Request $request) {
        try {
            $searchRange = explode("-", $request["range"]);
            $dateFrom = date("Y-m-d 00:00", strtotime(trim($searchRange[0])));
            $dateTo = date("Y-m-d 23:59", strtotime(trim($searchRange[1])));

            $userId = Auth::id();
            $userIncome = $this->walletService->userInwardHistory($userId, $dateFrom, $dateTo);

            $userDetail = $this->userService->getUserById($userId);

            $dateRange = [$dateFrom, $dateTo];

            return view("private.wallet-history", compact("userDetail", "userIncome", "dateRange"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function paymentHistory()
    {
        $histories = $this->walletService->userInwardHistory();
        return view("main.payment-history", compact('histories'));
    }

    public function approvePayment($id) {
        $approvePayment = $this->walletService->updatePayment($id, "approve");
        $decodeResponse = json_decode($approvePayment->getContent(), true);
        if($approvePayment->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }

    public function declinePayment($id) {
        $declinePayment = $this->walletService->updatePayment($id, "decline");
        $decodeResponse = json_decode($declinePayment->getContent(), true);
        if($declinePayment->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }

    public function approveMonnifyPayment(Request $request) {
        $monnifyData = json_encode($request->all());
        Log::channel('daily')->info($monnifyData);

        // $monnifyData = '{"transactionReference":"MNFY|52|20231231115904|002209","paymentReference":"MNFY|52|20231231115904|002209","amountPaid":"250.00","totalPayable":"250.00","settlementAmount":"240.00","paidOn":"31\/12\/2023 11:59:04 AM","paymentStatus":"PAID","paymentDescription":"dih","transactionHash":"1d075b9e103e7b2f8e53cef7cc945d131b85f1964d7cbb147a56becc257be529b0e5fbe727a325594906cbb321f157e4fd6efe1515498749f8bda2d2a448cb89","currency":"NGN","paymentMethod":"ACCOUNT_TRANSFER","product":{"type":"RESERVED_ACCOUNT","reference":"202312311050697"},"accountDetails":{"accountName":"Monnify Limited","accountNumber":"******2190","bankCode":"001","amountPaid":"250.00","sessionId":"6G8t4MsW1j7dYBTyIlqwjo2AsrAWc8Ey","destinationAccountNumber":"6000195164","destinationBankCode":"232","destinationBankName":"Sterling bank"},"accountPayments":[{"accountName":"Monnify Limited","accountNumber":"******2190","bankCode":"001","amountPaid":"250.00","sessionId":"6G8t4MsW1j7dYBTyIlqwjo2AsrAWc8Ey","destinationAccountNumber":"6000195164","destinationBankCode":"232","destinationBankName":"Sterling bank"}],"customer":{"email":"oluwatayoadeyemi@yahoo.com","name":"Oluwatayo Adeyemi"}}';
        $approvePayment = app(MonnifyService::class)->approveMonnifyPayment($monnifyData);

        return $approvePayment;
    }
}