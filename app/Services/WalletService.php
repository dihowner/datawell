<?php
namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\WalletIn;
use App\Models\WalletOut;
use App\Classes\HttpRequest;
use App\Services\UserService;
use App\Classes\PaginatorHelper;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WalletService {
    use ResponseTrait;

    protected $utilityService, $responseBody;
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }

    public function walletCredited($duration) {
        switch($duration) {
            case "this_month":
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i');
                $endDate = Carbon::now()->endOfMonth()->endOfDay()->format('Y-m-d H:i');
            break;
            
            case "last_month":
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
            break;
        }
        
        $sumWalletIn = WalletIn::where(['status' => '1'])->whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $amount = (float) $sumWalletIn <= 0 ? 0 : $sumWalletIn;
        return $amount;
    }

    public function getUserBalance($userId = "") {
        $balanceLeft = $this->sumWalletIn($userId) - $this->sumWalletOut($userId);
        $balanceLeft = (float) $balanceLeft <= 0 ? 0 : $balanceLeft;
        return $balanceLeft;
    }

    //Wallet In Method
    private function sumWalletIn($userId = '') {
        $statusIn = ['1', '3'];
        if($userId == "") {
            $sumWallet = WalletIn::whereIn('status', $statusIn)->sum('amount');
        } else {
            $sumWallet = WalletIn::where(['user_id' => $userId])->whereIn('status', $statusIn)->sum('amount');
        }
        return $sumWallet;
    }

    //Wallet Out Method
    private function sumWalletOut($userId = '') {
        $statusOut = ['0', '1', '2', '3'];
        if($userId == "") {
            $sumWallet = WalletOut::whereIn('status', $statusOut)
                                    ->sum('amount');
        } else {
            $sumWallet = WalletOut::where('user_id', $userId)
                                ->whereIn('status', $statusOut)
                                ->sum('amount');
        }
        return $sumWallet;
    }

    public function createWalletRequest(array $fundingData) {
        try {
            $amount = (float) $fundingData['amount'];
            $theAuthorizedUser = Auth::user();
            $walletReference = $this->utilityService->uniqueReference();
            $fundingChannel = $this->fundingChannel($fundingData['funding_method']);
            $data = [
                "user_id" => $theAuthorizedUser->id,
                "description" => "Wallet funding request of ".$amount,
                "reference" => $walletReference,
                "channel" => $fundingChannel,
                "amount" => $fundingChannel == "bank" ? $this->calculateManualVAT($amount) : $amount
            ];

            $this->createWallet("inward", $data);
            return $this->sendResponse("Wallet funding request created successfully", ["reference" => $walletReference, "amount" => $amount]);
        }
        catch(Exception $e) {
            return "Error: ".$e->getMessage();
        }
    }

    public function createWallet(string $walletType, array $data) {
        switch($walletType) {
            case "inward":
                return WalletIn::create($data);
            break;

            case "outward":
                return WalletOut::create($data);
            break;
        }
    }

    private function calculateManualVAT($amount) {
        $bankInfo = $this->utilityService->bankInformation();
        if(isset($bankInfo['bank_charges'])) {

            $bankCharges = json_decode($bankInfo['bank_charges'], true);
            $stampDuty = $bankCharges['stamp_duty_charge'];
            $minStampAmount = $bankCharges['min_stamp'];

            if($amount > $minStampAmount) {
                $amount = (float) $amount - $stampDuty;
                return $amount;
            }
            return (float) $amount;
        }

        return (float) $amount;
    }

    public function viewPaymentHistory($wallet_type = "wallet_in" , $referenceId) {
        try {

            if($wallet_type == "wallet_in") {
                $getWallet = WalletIn::where('reference', $referenceId)
                                        ->orWhere('external_reference', $referenceId)
                                        ->first();
            }
            else {
                $getWallet = WalletIn::where('reference', $referenceId)
                                        ->orWhere('external_reference', $referenceId)
                                        ->first();
            }
            if($getWallet != NULL) {
                $getWallet['user'] = app(UserService::class)->getUserById($getWallet->user_id);
                if($getWallet->status == "0") {
                    $getWallet['amount_vat_inclusive'] = $this->calculateAmount($getWallet['amount'], $getWallet['channel']);
                }
                return $getWallet;
            }
            else {
                return $this->sendError('Reference Id could not be found', [], 404);
            }
        }
        catch(Exception $e) {
            return $this->sendError('Reference Id could not be found', [], 404);
        }
    }

    private function calculateAmount($amount, $channel) {
        $amountToPay = $amount;

        if($channel == "paystack") {
            $paystackInfo = $this->utilityService->paystackInfo();
            if($paystackInfo !== false) {
                $decodePaystack = json_decode($paystackInfo, true);
                $charges = (float) $decodePaystack['charges'];
                $chargesType = $decodePaystack['chargesType'];

                if($chargesType == 'percentage') {
                    $amountToPay = (float) $amount + (($amount * $charges)/100);
                } else {
                    $amountToPay = (float) $amount + $charges;
                }
            }
        }
        else if($channel == "flutterwave") {
            $flutterwaveInfo = $this->utilityService->flutterwaveInfo();
            if($flutterwaveInfo !== false) {
                $decodeFlutterwave = json_decode($flutterwaveInfo, true);
                $charges = (float) $decodeFlutterwave['charges'];
                $chargesType = $decodeFlutterwave['chargesType'];

                if($chargesType == 'percentage') {
                    $amountToPay = (float) $amount + (($amount * $charges)/100);
                } else {
                    $amountToPay = (float) $amount + $charges;
                }
            }
        }
        else if($channel == "monnify") {
            $monnifyInfo = $this->utilityService->monnifyInfo();
            if($monnifyInfo !== false) {
                $decodeMonnify = json_decode($monnifyInfo, true);
                $charges = (float) $decodeMonnify['charges'];
                $chargesType = $decodeMonnify['chargestype'];
                $percent = $decodeMonnify['percent'];
                $deposit_amount = $decodeMonnify['deposit_amount'];

                if($chargesType == 'percentage') {
                    $amountToPay = (float) $amount + (($amount * $charges)/100);
                } else {
                    if($amount >= $deposit_amount) {
                        $percentageCharge = ($amount * $percent)/100;
                        $amountLeft = $amount + $percentageCharge;
                        $amountToPay = $amountLeft + $charges;
                    }
                    else {
                        $amountToPay = (float) $amount + $charges;
                    }
                }
            }
        }
        return $amountToPay;
    }

    public function updateWalletIn($txType, $referenceId) {
        try {
            switch($txType) {
                case "approve_payment":
                    $getPayment = WalletIn::where('reference', $referenceId)->first();

                    if($getPayment['status'] == "0") {
                        $theUserId = $getPayment['user_id'];
                        $currentBalance = $this->getUserBalance($theUserId);

                        $updateWallet = WalletIn::where(['reference' => $referenceId, 'status' => '0'])
                                                ->update([
                                                    'status' => '1',
                                                    'old_balance' => $currentBalance,
                                                    'new_balance' => (float) $currentBalance + $getPayment['amount']
                                                ]);
                        if($updateWallet) {
                            return $this->sendResponse("Wallet updated successfully", [], 200);
                        }
                        return $this->sendError("Error updating wallet", [], 500);
                    }
                    return $this->sendError("Error updating wallet", [], 500);
                break;
                case "cancel_payment":
                    $updateWallet = WalletIn::where('reference', $referenceId)
                            ->where('status', '0')
                            ->update(['status' => '2']);

                    if($updateWallet) {
                        return $this->sendResponse("Wallet updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating wallet", [], 500);
                break;
            }
        }
        catch(Exception $e) {
            return $this->sendError('Reference Id could not be found', [], 404);
        }
    }

    private function fundingChannel($fundingMethod) {
        switch($fundingMethod) {
            case "instant_funding":
                $method = "monnify";
            break;

            case "flutterwave":
                $method = "flutterwave";
            break;

            case "paystack":
                $method = "paystack";
            break;

            default:
                $method = "bank";
        }
        return $method;
    }

    public function ShareWallet(array $sharingData) {
        try {

            // Instantiate user service class...
            $userService = app(UserService::class);

            $amount = $sharingData['amount'];
            $userNamePhone = $sharingData['userNamePhone'];
            $transactpin = $sharingData['transactpin'];

            // We need to get logged user id...
            $theAuthorizedUser = Auth::user();
            $theUserId = $theAuthorizedUser->id;

            // Checked if the user info provided is a phone number...
            if($this->checkSenderId($userNamePhone) AND $userNamePhone == $theAuthorizedUser->phone_number) {
                return $this->sendError("You cannot share fund to yourself", [], 400);
            }

            if($userNamePhone == $theAuthorizedUser->username) {
                return $this->sendError("You cannot share fund to yourself", [], 400);
            }

            if($theAuthorizedUser->secret_pin != $transactpin) {
                return $this->sendError("Incorrect transaction pin supplied", [], 400);
            }

            // Get Sender balance
            $senderCurrentBalance = $this->getUserBalance($theUserId);
            $newSenderBalance = (float) $senderCurrentBalance - $amount;

            if($amount > $senderCurrentBalance) {
                return $this->sendError("Insufficient wallet balance", [], 400);
            }

            $theReceiver = $userService->getUserByPhone_Username($userNamePhone);

            if($theReceiver == NULL) {
                return $this->sendError("Receiver account could not be found", [], 404);
            }

            $receiverCurrentBalance = $this->getUserBalance($theReceiver->id);
            $newReceiverBalance = (float) $receiverCurrentBalance + $amount;

            $walletReference = $this->utilityService->uniqueReference();

            $dateCreated = $this->utilityService->dateCreated();

            DB::beginTransaction();
            try {

                // Create wallet out for sender
                $this->createWallet("outward", [
                    "user_id" => $theAuthorizedUser->id,
                    "description" => "Outward transfer of ".$amount,
                    "reference" => $walletReference,
                    "old_balance" => $senderCurrentBalance,
                    "amount" => $amount,
                    "new_balance" => $newSenderBalance,
                    "status" => '1',
                    "remark" => json_encode(["created_by" => $theAuthorizedUser->fullname, "approved_by" => $theAuthorizedUser->fullname]),
                    "created_at" => $dateCreated,
                    "updated_at" => $dateCreated
                ]);

                // Create wallet in for receiver
                $this->createWallet("inward", [
                    "user_id" => $theReceiver->id,
                    "description" => "Inward transfer of ".$amount,
                    "reference" => $walletReference,
                    "channel" => "website",
                    "old_balance" => $receiverCurrentBalance,
                    "amount" => $amount,
                    "new_balance" => $newReceiverBalance,
                    "status" => '1',
                    "remark" => json_encode(["created_by" => $theAuthorizedUser->fullname, "approved_by" => $theAuthorizedUser->fullname]),
                    "created_at" => $dateCreated,
                    "updated_at" => $dateCreated
                ]);
                DB::commit();
                return $this->sendResponse('Fund transfer was successful', [], 200);
            }
            catch(Exception $e) {
                DB::rollBack();
                return $this->sendError('System Error', [], 500);
            }
        }
        catch(Exception $e) {
            return $this->sendError('System Error', [], 500);
        }
    }

    private function checkSenderId($userNamePhone) {
        if(is_numeric($userNamePhone) AND strlen($userNamePhone) === 11) {
            return true;
        }
        return false;
    }

    public function userInwardHistory($userId = "", $dateFrom = "", $dateTo = "") {
    
        $query = WalletIn::whereNotNull('user_id')->orderByDesc('created_at');
        
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

    public function updatePayment($id, $action) {
        try {
            
            switch($action) {
                case "approve":
                    $findPayment = WalletIn::findOrFail($id);
                    
                    if($findPayment->status != "0") {
                        return $this->sendError("Payment has already been treated", [], 400);
                    }

                    // Get user current wallet...
                    $currentBalance = (float) $this->getUserBalance($findPayment->user_id);
                    $newBalance = (float) $currentBalance + $findPayment->amount;
        
                    $findPayment->old_balance = $currentBalance;
                    $findPayment->new_balance = $newBalance;
                    $findPayment->status = "1";
                    $findPayment->remark = json_encode(["approved_by" => Auth::guard('admin')->user()->fullname]);
                    if($findPayment->update()) {
                        return $this->sendResponse("Payment approved successfully", [], 200);
                    }
                    return $this->sendError("Error approving payment", [], 400);
                break;
                
                case "decline":
                    $findPayment = WalletIn::findOrFail($id);                    
                    
                    if($findPayment->status != "0") {
                        return $this->sendError("Payment has been already been treated", [], 400);
                    }

                    $findPayment->status = "2";
                    $findPayment->remark = json_encode(["approved_by" => Auth::guard('admin')->user()->fullname]);
                    if($findPayment->update()) {
                        return $this->sendResponse("Payment declined successfully", [], 200);
                    }
                    return $this->sendError("Error declining payment", [], 400);
                break;
            }
            
        }
        catch(Exception $e) {
            return $this->sendError("Error!. ".$e->getMessage(), [], 500);
        }
    }

    public function totalPendingPayment() {
        return WalletIn::where(['status' => '0', 'channel' => 'bank'])->count();
    }
}