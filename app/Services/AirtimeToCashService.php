<?php
namespace App\Services;

use Exception;
use App\Models\Withdrawal;
use Illuminate\Support\Str;
use App\Models\AirtimeToCash;
use App\Classes\PaginatorHelper;
use App\Services\UtilityService;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;

class AirtimeToCashService {

    use ResponseTrait;
    private $uniqueReference, $dateCreated;
    protected $responseBody, $utilityService, $userService;

    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
        // $this->userService = $userService;
        $this->uniqueReference = $this->utilityService->uniqueReference();
    }


    public function updateConversion($id, $action) {
        try {
            
            switch($action) {
                
                case "approve":
                    $findPayment = AirtimeToCash::findOrFail($id);
                    
                    if($findPayment->status != "0") {
                        return $this->sendError("Airitme conversion has already been treated", [], 400);
                    }

                    // Get user current wallet...
                    $currentBalance = (float) $this->getAirtimeCashBalance($findPayment->user_id);

                    $newBalance = (float) $currentBalance + $findPayment->amount;
                    
                    $findPayment->old_balance = $currentBalance;
                    $findPayment->new_balance = $newBalance;
                    $findPayment->status = "1";
                    $findPayment->remark = json_encode(["approved_by" => Auth::guard('admin')->user()->fullname]);
                    if($findPayment->update()) {
                        return $this->sendResponse("Airtime conversion approved successfully", [], 200);
                    }
                    return $this->sendError("Error approving airtime conversion", [], 400);
                break;
                
                case "decline":
                    $findPayment = AirtimeToCash::findOrFail($id);                    
                    
                    if($findPayment->status != "0") {
                        return $this->sendError("Airtime conversion has been already been treated", [], 400);
                    }

                    $findPayment->status = "2";
                    $findPayment->remark = json_encode(["approved_by" => Auth::guard('admin')->user()->fullname]);
                    if($findPayment->update()) {
                        return $this->sendResponse("Airtime conversion declined successfully", [], 200);
                    }
                    return $this->sendError("Error declining airtime conversion ", [], 400);
                break;
            }
            
        }
        catch(Exception $e) {
            return $this->sendError("Error!. ".$e->getMessage(), [], 500);
        }
    }
    
    public function viewAirtimeConversion($reference) {
        $readConversion = AirtimeToCash::where("reference", $reference)->first();

        if($readConversion == NULL) {
            return false;
        }
        return $readConversion;
    }

    public function userConversionHistory($userId = "", $dateRange = [], $searchValue = "") {
        $query = AirtimeToCash::whereNotNull('user_id')->orderByDesc('created_at');

        if($userId != "") {
            $query->where('user_id', $userId);
        }

        if (count($dateRange) > 0) {
            $query->whereBetween('created_at', [$dateRange[0], $dateRange[1]]);
        }

        if ($searchValue != "") {
            $query->where("airtime_reciever", "like", '%' . $searchValue . '%')
                    ->orWhere("airtime_sender", "like", '%' . $searchValue .  '%')
                    ->orWhere("reference", "like", '%' . $searchValue . '%')
                    ->orWhere("airtime_amount", "like", '%' . $searchValue . '%');
        }

        $convHistories = $query->get();

        // Create a LengthAwarePaginator instance
        $paginatedRecords = PaginatorHelper::createPaginator($convHistories, 20, request()->get('page'), request()->url());

        return $paginatedRecords;
    }

    public function SubmitAirtimeCash($airtimeData) {
        try {
            $conversionSettings = $this->utilityService->airtimeConversion();

            if($conversionSettings === false) {
                return $this->sendError("Conversion settings could not be found", [], 404);
            }

            $decodeConversion = json_decode($conversionSettings, true);

            // Get conversion settings based on network selected...
            $networkConversionSettings = $decodeConversion[$airtimeData["network"]];

            if($decodeConversion["settings"]["status"] == 0 OR $networkConversionSettings["status"] == 0) {
                return $this->sendError("Airtime Conversion is currently unavailable", [], 400);
            }

            $convertPercentage = $networkConversionSettings["percentage"];

            $airtimeAmount = (float) $airtimeData["amount"];
            $airtimeAmountValue = (float) ($airtimeAmount * $convertPercentage) / 100;

            $createRequest = AirtimeToCash::create([
                "user_id" => Auth::id(),
                "description" => "Conversion of ".Str::ucfirst($airtimeData['network']." ".number_format($airtimeAmount)),
                "airtime_reciever" => $airtimeData["phone_number"],
                "airtime_sender" => $airtimeData["airtime_sender"],
                "airtime_amount" => $airtimeAmount,
                "amount" => $airtimeAmountValue,
                "network" => $airtimeData["network"],
                "additional_note" => isset($airtimeData["additional_note"]) ? $airtimeData["additional_note"] : NULL,
                "status" => "0",
                "reference" => $this->uniqueReference
            ]);

            if(!$createRequest) {
                return $this->sendError("Error creating airtime cash request", [], 400);
            }
            return $this->sendResponse("Airtime to cash request has been created successfully. Kindly proceed with your airtime transfer", [], 200);
        }
        catch(Exception $e) {
            // return $e->getMessage();
            return $this->sendError("Something went wrong. ".$e->getMessage(), [], 500);
        }
    }

    public function CreateWithdrawalRequest($withdrawalData) {
        try {
            $theAuthorizedUser = Auth::user();
            $theUserId = $theAuthorizedUser["id"];
            $withdrawalAmount = (float) $withdrawalData["amount"];

            if($theAuthorizedUser["secret_pin"] != $withdrawalData["transactpin"]) {
                return $this->sendError("Incorrect transaction pin supplied", [], 400);
            }

            $airtimeCashBalance = self::getAirtimeCashBalance($theUserId);

            if($airtimeCashBalance < $withdrawalAmount) {
                return $this->sendError("Insufficient wallet balance", [], 400);
            }

            $createRequest = Withdrawal::create([
                    "description" => "Withdrawal of N".$withdrawalAmount,
                    "reference" => $this->uniqueReference,
                    "user_id" => $theUserId,
                    "old_balance" => $airtimeCashBalance,
                    "amount" => $withdrawalAmount,
                    "new_balance" => (float) $airtimeCashBalance - $withdrawalAmount,
                    "bank_info" => app(UserService::class)->getUserMeta($theUserId, "bank_account")
                ]);

            if(!$createRequest) {
                return $this->sendError("Error creating withdrawal request", [], 400);
            }
            return $this->sendResponse("Withdrawal request has been created successfully", [], 200);
        }
        catch(Exception $e) {
            // return $e->getMessage();
            return $this->sendError("Something went wrong. ".$e->getMessage(), [], 500);
        }
    }

    public function getAirtimeCashBalance($userId) {
        $balanceLeft = $this->sumAirtimeIn($userId) - $this->sumWithdrawalMade($userId);
        if($balanceLeft <= 0) {
            $balanceLeft = (float) (0);
        } else {
            $balanceLeft = (float) $balanceLeft;
        }
        return $balanceLeft;
    }

    //Wallet In Method
    private function sumAirtimeIn($userId = "") {
        $query = AirtimeToCash::whereNotNull("user_id")->where(["status" => "1"]);

        if($userId != "") {
            $query->where('user_id', $userId);
        }
        $sumWallet = $query->sum("amount");
        return $sumWallet;
    }

    private function sumWithdrawalMade($userId = "") {
        $query = Withdrawal::whereNotNull("user_id")->where(["status" => "1"]);

        if($userId != "") {
            $query->where('user_id', $userId);
        }
        $sumWallet = $query->sum("amount");
        return $sumWallet;
    }
}
?>