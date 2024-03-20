<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Traits\ResponseTrait;
use App\Classes\ReferenceValidator;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactRequest;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    use ResponseTrait;
    protected $userService, $transactService;
    public function __construct(UserService $userService, TransactionService $transactService) {
        $this->userService = $userService;
        $this->transactService = $transactService;
    }

    public function getUserPurchaseHistory() {
        try {
            $userId = Auth::id();
            $userPurchases = $this->transactService->userPurchaseHistory($userId);
            $userDetail = $this->userService->getUserById($userId);

            return view("private.transactions", compact("userDetail", "userPurchases"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function searchUserPurchaseHistory(Request $request) {
        try {
            $validatedData = $request->validate([
                'query' => 'required|string|max:50'
            ]);

            $searchValue = $validatedData['query'];
            $userId = Auth::id();
            $userPurchases = $this->transactService->userPurchaseHistory($userId, $searchValue);
            $userDetail = $this->userService->getUserById($userId);

            return view("private.transactions", compact("userDetail", "userPurchases"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function viewTransaction($reference) {
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
            $txnRecord = $this->transactService->viewTransaction($userId, $reference);
            
            if($txnRecord === false) {
                Alert::error("Error", "Transaction record not found. Kindly inform Admin");
                return redirect()->back();
            }
            return view('private.view-transaction', compact('userDetail', 'txnRecord'));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function getAllPurchaseHistory(Request $request) {
        try {
            $searchValue = "";
            if($request->filled('query')){
                $validatedData = $request->validate([
                    'query' => 'required|string|max:50'
                ]);
                $searchValue = $validatedData['query'];
            }

            $userPurchases = $this->transactService->userPurchaseHistory("", $searchValue);
            
            return view("main.transactions", compact("userPurchases"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function getSuccessfulPurchaseHistory(Request $request) {
        try {
            $searchValue = "";
            if($request->filled('query')){
                $searchValue = $request->input('query');
            }

            $userPurchases = $this->transactService->userPurchaseHistory("", $searchValue, "1");
            
            return view("main.successful-transact", compact("userPurchases"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function getAwaitingPurchaseHistory(Request $request) {
        try {
            $searchValue = "";
            if($request->filled('query')){
                $searchValue = $request->input('query');
            }

            $userPurchases = $this->transactService->userPurchaseHistory("", $searchValue, "2");
            
            return view("main.processed-transact", compact("userPurchases"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function getPendingPurchaseHistory(Request $request) {
        try {
            
            $searchValue = "";
            if($request->filled('query')){
                $searchValue = $request->input('query');
            }

            if($searchValue != "") {
                $userPurchases = $this->transactService->userPurchaseHistory("", $searchValue, ["0", "2"]);
            } else {
                $userPurchases = $this->transactService->userPurchaseHistory("", "", ["0", "2"]);
            }

            // return $userPurchases;
            
            return view("main.pending-transact", compact("userPurchases"));
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected Error! Message: ". $e->getMessage(), [], 500);
        }
    }

    public function ProcessTransaction(TransactRequest $request) {
        $processData = $request->validated();
        $transIDs = $processData['pending_transact'];
        $action = $processData['action'];

        $processTxn = $this->transactService->processTransaction($action, $transIDs);

        // return $processTxn;

        $responseCode = $processTxn->getStatusCode();
        $responseContent = json_decode($processTxn->content(), true);

        if($responseCode === 200) {
            Alert::success("Success", $responseContent['message']);
        }
        else {
            Alert::error("Error", $responseContent['message']);
        }
        return redirect()->back();
    }
    
}