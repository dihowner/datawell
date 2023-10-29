<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\AirtimeToCash;
use App\Services\UserService;
use App\Services\UtilityService;
use App\Classes\ReferenceValidator;
use Illuminate\Support\Facades\Auth;
use App\Services\AirtimeToCashService;
use App\Http\Requests\WithdrawalRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\AirtimeCashRequest;

class AirtimeToCashController extends Controller
{
    protected $utilityService, $userService, $airtimeCash;
    public function __construct(UtilityService $utilityService, UserService $userService, AirtimeToCashService $airtimeCash) {
        $this->utilityService = $utilityService;
        $this->userService = $userService;
        $this->airtimeCash = $airtimeCash;
    }

    /**
     * Display the view for airtime to cash
     */
    public function conversionView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $conversionSettings = $this->utilityService->airtimeConversion();

        if($conversionSettings === false) {
            Alert::error("Error", "Something went wrong. Error loading page");
            return redirect()->route('user.index');
        }

        $decodeConversion = json_decode($conversionSettings, true);

        if($decodeConversion["settings"]["status"] != "1") {
            Alert::error("Error", "Conversion of Airtime to cash is currently not available. Kindly check back later.");
            return redirect()->route('user.index');
        }

        return view('private.convert-airtime-cash', compact('userDetail', 'conversionSettings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SubmitAirtimeCash(AirtimeCashRequest $request)
    {
        $submitAirtimeRequest = $this->airtimeCash->SubmitAirtimeCash($request->validated());
        $responseCode = $submitAirtimeRequest->getStatusCode();
        $responseContent = json_decode($submitAirtimeRequest->content(), true);

        if($responseCode === 200) {
            $message = $responseContent["message"];
            Alert::success("Success", $message);
        }
        else {
            $message = $responseContent["message"];
            Alert::error("Error", $message);
        }
        return redirect()->back();
    }

    /**
     * Display the view for bank withdrawal
     */
    public function bankWithdrawalView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());

        if(!isset($userDetail["user_meta"]["bank_account"])) {
            Alert::info("Info", "Kindly fill your bank account details before attempting a withdrawal");
            return redirect()->route('user.bank-account');
        }

        return view('private.bank-withdrawal', compact('userDetail'));
    }

    /**
     * Process Bank withdrawal request..
     */
    public function CreateWithdrawalRequest(WithdrawalRequest $request)
    {
        $submitWithdrawalRequest = $this->airtimeCash->CreateWithdrawalRequest($request->validated());
        $responseCode = $submitWithdrawalRequest->getStatusCode();
        $responseContent = json_decode($submitWithdrawalRequest->content(), true);

        if($responseCode === 200) {
            $message = $responseContent["message"];
            Alert::success("Success", $message);
        }
        else {
            $message = $responseContent["message"];
            Alert::error("Error", $message);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AirtimeToCash  $airtimeToCash
     * @return \Illuminate\Http\Response
     */
    public function userConversionHistory()
    {
        try {
            $userId = Auth::id();
            $userDetail = $this->userService->getUserById($userId);
            $userConversions = $this->airtimeCash->userConversionHistory($userId);

            return view("private.airtime-conv-history", compact("userDetail", "userConversions"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function searchUserConversionHistory(Request $request)
    {
        try {

            $userId = Auth::id();

            $searchRange = explode("-", $request["range"]);
            $dateFrom = date("Y-m-d 00:00", strtotime(trim($searchRange[0])));
            $dateTo = date("Y-m-d 23:59", strtotime(trim($searchRange[1])));

            $userDetail = $this->userService->getUserById($userId);

            $dateRange = [$dateFrom, $dateTo];
            $userConversions = $this->airtimeCash->userConversionHistory($userId, $dateRange);


            return view("private.airtime-conv-history", compact("userDetail", "userConversions", "dateRange"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function viewAirtimeConversion($reference) {
        try {
            // Validate the reference ID
            $validator = ReferenceValidator::ManualValidator($reference);

            if($validator !== true) {
                $decodeError = json_decode($validator->getContent(), true);
                Alert::error("Error", $decodeError['message']);
                return redirect()->back();
            }

            $txnRecord = $this->airtimeCash->viewAirtimeConversion($reference);
            
            if($txnRecord === false) {
                Alert::error("Error", "Transaction record not found. Kindly inform Admin");
                return redirect()->back();
            }

            if(Auth::check()) {
                $userId = Auth::id();
                $userDetail = $this->userService->getUserById($userId);
                return view('private.view-airtime-conv-history', compact('userDetail', 'txnRecord'));
            }
            else {
                return view('main.view-airtime-cash', compact('txnRecord'));
            }

        }
        catch(Exception $e) {
            Alert::error("Error", "Oooops! Something went wrong");
            return redirect()->back();
        }
    }

    public function airtimeCashHisories(Request $request) {
        $searchValue = "";
        if($request->filled('query')){
            $searchValue = $request->input('query');
        }
        
        $userConversions = $this->airtimeCash->userConversionHistory("", [], $searchValue);
        return view("main.airtime-cash-history", compact("userConversions"));
    }

    public function approveConversion($id) {
        $approveConversion = $this->airtimeCash->updateConversion($id, "approve");

        $decodeResponse = json_decode($approveConversion->getContent(), true);
        if($approveConversion->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }

    public function declineConversion($id) {
        $declineConversion = $this->airtimeCash->updateConversion($id, "decline");

        $decodeResponse = json_decode($declineConversion->getContent(), true);
        if($declineConversion->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }
}