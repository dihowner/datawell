<?php
namespace App\Services;

use Exception;
use App\Models\Withdrawal;
use App\Models\AirtimeToCash;
use App\Classes\PaginatorHelper;
use App\Http\Traits\ResponseTrait;
use App\Models\WalletIn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WithdrawalService {
    use ResponseTrait;

    private $uniqueReference;
    protected $airtimeCashService, $utilityService, $walletService;
    
    public function __construct(UtilityService $utilityService, WalletService $walletService, AirtimeToCashService $airtimeCashService)
    {
        $this->airtimeCashService = $airtimeCashService;
        $this->utilityService = $utilityService;
        $this->walletService = $walletService;
        $this->uniqueReference = $this->utilityService->uniqueReference();
    }

    public function convertAirtimeWallet($walletType = 'main', $amount, $transactPin) {
        try {
            $theUser = Auth::user();
            $userId = $theUser->id;

            $airtimeCashBalance = (float) $this->airtimeCashService->getAirtimeCashBalance($userId);
            
            if($airtimeCashBalance < $amount) {
                return $this->sendError("Error", "Insufficient airtime wallet balance", 400);
            }
            
            if($theUser["secret_pin"] != $transactPin) {
                return $this->sendError("Incorrect transaction pin supplied", [], 400);
            }

            DB::beginTransaction();

            switch($walletType) {
                case "main" :
                    $oldBalance = (float) $this->walletService->getUserBalance($userId);
                    $newBalance = (float) $oldBalance + $amount;

                    Withdrawal::create([
                        "user_id" => $userId,
                        "description" => "Conversion of N".$amount ." to Main Wallet",
                        "reference" => $this->uniqueReference,
                        "old_balance" => (float) $airtimeCashBalance,
                        "amount" => (float) $amount,
                        "new_balance" => (float) ($airtimeCashBalance - $amount),
                        "bank_info" => NULL,
                        "remark" => json_encode(["approved_by" => $theUser->fullname]),
                        "status" => "1"
                    ]);

                    WalletIn::create([
                        "user_id" => $userId,
                        "description" => "Conversion of N".$amount." from Airtime Cash wallet to Main Wallet",
                        "reference" => $this->uniqueReference,
                        "old_balance" => (float) $oldBalance,
                        "amount" => (float) $amount,
                        "new_balance" => (float) ($newBalance),
                        "channel" => 'conversion',
                        "wallet_type" => "wallet_in",
                        "remark" => json_encode(["approved_by" => $theUser->fullname]),
                        "status" => "1"
                    ]);

                    DB::commit();

                    return $this->sendResponse("Your request was successful", [], 200);
                break;
                
                default:
                    DB::rollBack();
                    return $this->sendError("Error", "Invalid Request", 400);
                    
            }
        }
        catch(Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function userWithdrawal($userId = "") {
        $query = Withdrawal::whereNotNull('user_id')->limit(100)->orderByDesc('created_at');
        
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }

        if (!empty($dateFrom) && !empty($dateTo)) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $walletInRecords = $query->get();                            
        
        // Create a Paginator instance
        $paginatedRecords = PaginatorHelper::createPaginator($walletInRecords, 20, request()->get('page'), request()->url());
        
        return $paginatedRecords;
    }

    public function viewWithdrawal($userId = "", $reference) {
        $query = Withdrawal::where("reference", $reference)->whereNotNull('user_id');
        
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }
        $viewRecord = $query->first();
        if($viewRecord == NULL) {
            return false;
        }
        return $viewRecord;
    }

    public function updateWithdrawal($id, $action) {
        try {
            
            switch($action) {
                case "approve":
                    $findWithdrawal = Withdrawal::findOrFail($id);
                    
                    if($findWithdrawal->status != "0") {
                        return $this->sendError("Withdrawal has already been treated", [], 400);
                    }
                    
                    $findWithdrawal->remark = json_encode(["approved_by" => Auth::guard('admin')->user()->fullname]);
                    $findWithdrawal->status = "1";
                    if($findWithdrawal->update()) {
                        return $this->sendResponse("Withdrawal approved successfully", [], 200);
                    }
                    return $this->sendError("Error approving withdrawal request", [], 400);
                break;
                
                case "decline":
                    DB::beginTransaction();
                    
                    $findWithdrawal = Withdrawal::findOrFail($id); 
                    $currentBalance = (float) $this->airtimeCashService->getAirtimeCashBalance($findWithdrawal->user->id);
                    
                    $newBalance = (float) $currentBalance + $findWithdrawal->amount;
                    
                    $findWithdrawal->old_balance = $currentBalance;
                    $findWithdrawal->new_balance = $newBalance;
                    
                    if($findWithdrawal->status != "0") {
                        return $this->sendError("Withdrawal has been already been treated", [], 400);
                    }
                    
                    $findWithdrawal->status = "2";
                    $findWithdrawal->remark = json_encode(["approved_by" => Auth::guard('admin')->user()->fullname]);
                    
                    if($findWithdrawal->update()) {
                        AirtimeToCash::create([
                            "user_id" => $findWithdrawal->user_id,
                            "description" => "Withdrawal refund of ".number_format($findWithdrawal->amount),
                            "old_balance" => $currentBalance,
                            "amount" => $findWithdrawal->amount,
                            "new_balance" => $newBalance,
                            "network" => "refund",
                            "status" => "1",
                            "reference" => $this->uniqueReference
                        ]);     
                        DB::commit();                   
                        return $this->sendResponse("Withdrawal declined successfully", [], 200);
                    }
                    DB::rollBack();
                    return $this->sendError("Error declining withdrawal request", [], 400);
                break;
            }
            
        }
        catch(Exception $e) {
            return $this->sendError("Error!. ".$e->getMessage(), [], 500);
        }
    }
}