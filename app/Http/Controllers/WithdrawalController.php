<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\UserService;
use App\Http\Traits\ResponseTrait;
use App\Classes\ReferenceValidator;
use App\Services\WithdrawalService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WithdrawalRequest;
use RealRashid\SweetAlert\Facades\Alert;

class WithdrawalController extends Controller
{
    use ResponseTrait;
    protected $withdrawalService, $userService;
    public function __construct(WithdrawalService $withdrawalService, UserService $userService)
    {
        $this->withdrawalService = $withdrawalService;
        $this->userService = $userService;
    }
    
    /**
     * Display history of withdrawals made..
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            $userDetail = $this->userService->getUserById(Auth::id());
            $userWithdrawal = $this->withdrawalService->userWithdrawal($userId);
            return view("private.withdrawals", compact("userDetail", "userWithdrawal"));
        }
        catch(Exception $e){
            return $this->sendError("Unexpected Error! Message: ".$e->getMessage(), [], 500);
        } 
    } 

    public function convertAirtimeWalletView() {
        try {
            $userId = Auth::id();
            $userDetail = $this->userService->getUserById(Auth::id());
            return view("private.convertAirtimeWallet", compact("userDetail"));
        }
        catch(Exception $e){
            return $this->sendError("Unexpected Error! Message: ".$e->getMessage(), [], 500);
        } 
    }

    public function convertAirtimeWallet(WithdrawalRequest $request) {
        try {

            $convertData = $request->validated();
            $amount = (float) $convertData['amount'];
            $walletType = $convertData['walletType'];
            $transactpin = $convertData['transactpin'];
            $processConversion = $this->withdrawalService->convertAirtimeWallet($walletType, $amount, $transactpin);
            
            $responseCode = $processConversion->getStatusCode();
            $responseContent = json_decode($processConversion->content());
            $message = $responseContent->message;

            if($responseCode === 200) {
                Alert::success("Success", $message)->autoClose(10000);
            } else {
                Alert::error("Error", $message)->autoClose(10000);
            }
            return redirect()->back();
        }
        catch(Exception $e){
            return $this->sendError("Unexpected Error! Message: ".$e->getMessage(), [], 500);
        } 
    }

    public function viewWithdrawal($reference) {
        try {
            // Validate the reference ID
            $validator = ReferenceValidator::ManualValidator($reference);
            
            if($validator !== true) {
                $decodeError = json_decode($validator->getContent(), true);
                Alert::error("Error", $decodeError['message']);
                return redirect()->back();
            }
            
            $userId = Auth::id();
            $userDetail = $this->userService->getUserById($userId);
            $txnRecord = $this->withdrawalService->viewWithdrawal($userId, $reference);
            
            if($txnRecord === false) {
                Alert::error("Error", "Transaction record not found. Kindly inform Admin");
                return redirect()->back();
            }
            return view("private.view-withdrawal", compact("userDetail", "txnRecord"));
        }
        catch(Exception $e){
            return $this->sendError("Unexpected Error! Message: ".$e->getMessage(), [], 500);
        }
    }

    public function withdrawalHistories() {
        try {
            $allWithdrawals = $this->withdrawalService->userWithdrawal();
            return view("main.bank-withdrawals", compact("allWithdrawals"));
        }
        catch(Exception $e){
            return $this->sendError("Unexpected Error! Message: ".$e->getMessage(), [], 500);
        } 
    }

    public function approveWithdrawal($id) {
        $approveWithdrawal = $this->withdrawalService->updateWithdrawal($id, "approve");
        $decodeResponse = json_decode($approveWithdrawal->getContent(), true);
        if($approveWithdrawal->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function declineWithdrawal($id) {
        $declineWithdrawal = $this->withdrawalService->updateWithdrawal($id, "decline");
        $decodeResponse = json_decode($declineWithdrawal->getContent(), true);
        if($declineWithdrawal->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
}