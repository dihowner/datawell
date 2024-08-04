<?php
namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\UserMeta;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserService extends SettingsService {
    use ResponseTrait;
    protected $utilityService, $monnifyService, $walletService, $planService, $airtimeCashService, $transactService, $responseBody;

    public function __construct(UtilityService $utilityService, MonnifyService $monnifyService, WalletService $walletService, AirtimeToCashService $airtimeCashService, 
                                TransactionService $transactService, PlanService $planService)
    {
        $this->utilityService = $utilityService;
        $this->monnifyService = $monnifyService;
        $this->walletService = $walletService;
        $this->planService = $planService;
        $this->airtimeCashService = $airtimeCashService;
        $this->transactService = $transactService;
    }

    public function totalUser() {
        return User::count();
    }
    
    public function getUserByPhone_Username($userPhone) {
        try {
            $searchUser = User::where('username', $userPhone)
                                ->orWhere('phone_number', $userPhone)
                                ->first();

            if($searchUser == NULL) {
                return false;
            }
            return $this->getUserById($searchUser['id']);
        }
        catch(Exception $e) {
            return $this->sendError("System Error", [], 500);
        }
    }

    public function getUserById($userId) {

        $theUser = User::with('plan', 'user_meta')->find($userId);

        if($theUser != NULL) {
            $theUser['reform_email'] = $this->utilityService->reformEmailAddress($theUser->emailaddress);
            $theUser['wallet_balance'] =  $this->walletService->getUserBalance($userId);
            $theUser['transactions'] =  $this->transactService->userTransactionSummary($userId);
            $theUser['airtime_cash'] =  $this->airtimeCashService->getAirtimeCashBalance($userId);

            if(count($theUser->user_meta) > 0) {
                $userMeta = $theUser->user_meta;
                unset($theUser->user_meta);
                $theUser["user_meta"] = $this->modifyUserMeta($userMeta);
            }

            $result = $theUser;
        } else {
            $result = false;
        }
        return $result;
    }

    private function modifyUserMeta($userMeta) {

        if(count($userMeta) > 0) {

            foreach($userMeta as $index => $value) {
                $newMeta[$userMeta[$index]['name']] = $userMeta[$index]['value'];
            }

            if(isset($newMeta['monnify'])) {
                $monnify = new MonnifyService($this->utilityService);
                $userMonnify = json_decode($newMeta['monnify']);
                $index = 0;
                foreach($userMonnify as $bankCode => $accountNo) {
                    $newUserMonnify[$monnify->getVirtualBankByCode($bankCode)->bank_name] = $accountNo;
                }
                $newMeta['monnify'] = $newUserMonnify;
            }
            $this->responseBody  = $newMeta;
        }
        else {
            $this->responseBody = false;
        }
        return $this->responseBody;
    }

    /**
     * Get user meta based on need...
     */
    public function getUserMeta($userId, $metaKey) {
        $getUser = $this->getUserById($userId);
        if($getUser !== false) {
            $userMeta = $getUser["user_meta"];

            $metaData = [];
            foreach($userMeta as $metaIndex => $metaValue) {
                if($metaIndex === $metaKey) {
                    $metaData = $metaValue;
                    break;
                }
            }
            return $metaData;
        }
        return false;
    }

    /**
     * Generate a virtual account for the user..
     */
    public function GenerateUserVirtualAccount(int $userId) {
        try {
            $theAuthorizedUser = $this->getUserById($userId);
            $reservedReference = $this->utilityService->uniqueReference();

            $generateVirtualAccount = $this->monnifyService->generateVirtualAccount([
                "user_id" => $theAuthorizedUser->id,
                "username" => $theAuthorizedUser->username,
                "email_address" => $theAuthorizedUser->emailaddress,
                "reference" => $reservedReference
            ]);

            if(!is_array($generateVirtualAccount)) {
                $decodeResult = json_decode($generateVirtualAccount);
                $this->responseBody = $this->sendError("Error", $decodeResult->message, 400);
            } else {
                // Since virtual account is generated, then we need to update the old record from DB...
                if(isset($theAuthorizedUser->user_meta["monnify"])) {

                    // Remove it from monnify server...
                    $this->monnifyService->deleteReservedAccount($theAuthorizedUser->auto_funding_reference);

                    DB::beginTransaction();
                    try {
                        // Update user table...
                        User::where("id", $theAuthorizedUser->id)->update(["auto_funding_reference" => $reservedReference]);

                        // Update User Meta table...
                        $this->updateUserMeta(json_encode($generateVirtualAccount), "monnify", $theAuthorizedUser->id);

                        DB::commit();
                        $this->responseBody = $this->sendResponse("Virtual account updated successfully", $generateVirtualAccount);
                    }
                    catch(Exception $e) {
                        DB::rollback();
                        $this->responseBody = $this->sendError("Error", $e->getMessage(), 400);
                    }
                }
                else {
                    DB::beginTransaction();
                    try {
                        // Create the user meta instance...
                        $userMetaData = [
                            "user_id" => $theAuthorizedUser->id,
                            "name" => "monnify",
                            "value" => json_encode($generateVirtualAccount),
                            "date_created" => $this->utilityService->dateCreated()
                        ];
                        $this->createUserMeta($userMetaData);

                        // Update user table...
                        User::where("id", $theAuthorizedUser->id)->update(["auto_funding_reference" => $reservedReference]);
                        DB::commit();
                        $this->responseBody = $this->sendResponse("Virtual account generated successfully", $generateVirtualAccount);
                    }
                    catch(Exception $e) {
                        DB::rollback();
                        $this->responseBody = $this->sendError("Error", $e->getMessage(), 400);
                    }
                }
            }
            return $this->responseBody;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function UpdateUserBank($bankData) {
        try {
            $theAuthorizedUser = $this->getUserById(auth()->user()->id);
            $theAuthorizedUserSecretPin = $theAuthorizedUser->secret_pin;

            if($theAuthorizedUserSecretPin === $bankData['transactpin']) {
                $bankName = $bankData['bank_name'];
                $accountName = $bankData['account_name'];
                $accountNo = $bankData['account_number'];

                // Check if Account already exists for another user....
                $checkAccountExist = UserMeta::whereNot('user_id', '=', $theAuthorizedUser->id)
                            ->whereJsonContains('value->account_number', $accountNo)->get();

                if(count($checkAccountExist) > 0) {
                    return $this->sendError("Account number ($accountNo) already belong to another user", [], 400);
                }
                else {

                    $metaData = json_encode([
                        "bank_name" => $bankName,
                        "account_name" => ucwords($accountName),
                        "account_number" => $accountNo,
                    ]);

                    // Get user meta from the Authorized property
                    $userMeta = $theAuthorizedUser->user_meta;

                    // If bank_account info exists for user meta, then let's update...
                    if(isset($userMeta['bank_account'])) {
                        $createBankAccount = $this->updateUserMeta($metaData, "bank_account", $theAuthorizedUser->id);
                    }
                    else {
                        // Create the user meta instance...
                        $userMetaData = [
                            "user_id" => $theAuthorizedUser->id,
                            "name" => "bank_account",
                            "value" => $metaData,
                            "date_created" => $this->utilityService->dateCreated()
                        ];

                        $createBankAccount = $this->createUserMeta($userMetaData);
                    }

                    if($createBankAccount) {
                        return $this->sendResponse("Bank account updated successfully", []);
                    }
                    return $this->sendError("Error updating bank account", [], 500);
                }
            } else {
                return $this->sendError("Incorrect transaction pin", [], 400);
            }
            // return $this->responseBody;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function UpgradeUserPlan(array $upgradeData) {
        try {
                $theAuthorizedUser = auth()->user();
                $theUserId = $theAuthorizedUser->id;
                $newPlanId = $upgradeData['newPlan'];

                if($theAuthorizedUser['secret_pin'] == $upgradeData['transactpin']) {
                    if($theAuthorizedUser['plan_id'] == $newPlanId) {
                        return $this->sendError("You cannot upgrade to your existing plan", [], 400);
                    }

                    // Get the new Plan info...
                    $getPlan = $this->planService->getPlan($newPlanId);

                    if(method_exists($getPlan, 'getStatusCode') ) {
                        return $this->sendError("Plan does not exist or not found", [], 404);
                    }

                    $planAmount = $getPlan['amount'];
                    $currentBalance = (float) $this->walletService->getUserBalance($theUserId);

                    if($planAmount > $currentBalance) {
                        return $this->sendError("Insufficient wallet balance. Action could not be completed", [], 400);
                    }

                    DB::beginTransaction();

                    try {
                        $dateCreated = $dateCreated = $this->utilityService->dateCreated();
                        $walletReference = $this->utilityService->uniqueReference();

                        $this->walletService->createWallet('outward', [
                            "user_id" => $theUserId,
                            "description" => "Plan upgrade from ".$this->planService->getPlan($theAuthorizedUser['plan_id'])['plan_name'] . " to " .$getPlan['plan_name'],
                            "reference" => $walletReference,
                            "old_balance" => $currentBalance,
                            "amount" => $planAmount,
                            "new_balance" => (float) $currentBalance - $planAmount,
                            "remark" => json_encode(["created_by" => $theAuthorizedUser->fullname, "approved_by" => $theAuthorizedUser->fullname]),
                            "created_at" => $dateCreated,
                            "updated_at" => $dateCreated
                        ]);

                        User::where('id', $theUserId)->update(['plan_id' => $newPlanId]);
                        DB::commit();
                        return $this->sendResponse("Plan upgrade was successful", Auth::user(), 200);
                    }
                    catch(Exception) {
                        DB::rollBack();
                        return $this->sendError("Unexpected error occurred", [], 500);
                    }

                } else {
                    return $this->sendError("Incorrect transaction pin", [], 400);
                }
        }
        catch(Exception $e) {
            return $this->sendError("System Error!", [], 400);
        }
    }

    private function createUserMeta(array $userMetaData) {
        try {
            return UserMeta::create($userMetaData);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    private function updateUserMeta($metaData, $key, $userId) {
        try {
            return UserMeta::where([
                "user_id" => $userId,
                "name" => $key,
            ])->update(["value" => $metaData]);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function allUsers($searchValue = "") {

        if($searchValue != "") {
            $users = User::where("username", "like", '%' . $searchValue . '%')
                                ->orWhere("phone_number", "like", '%' . $searchValue . '%')
                                ->orWhere("emailaddress", "like", '%' . $searchValue . '%')
                                ->paginate(50);
        } else {
            $users = User::latest('id')->paginate(50);
        } 
        
        // Map some user data to the result set...
        $users->map(function ($theUser) {
            $userId = $theUser->id;
            $theUser->wallet_balance = $this->walletService->getUserBalance($userId);
            $theUser->airtime_cash = $this->airtimeCashService->getAirtimeCashBalance($userId);
            $theUser->transactions =  $this->transactService->userTransactionSummary($userId);
            $theUser->new_user_meta = $this->modifyUserMeta($theUser->user_meta);
            unset($theUser->user_meta); // For data consistency, remove relationship meta after reforming...
            return $theUser;
        });

        return $users;
    }

    public function updateUser(array $updateData) {

        $user = User::where('id', $updateData['id'])->first();
        $accessControl = json_decode($user->access_control, true);
        $accessControl['vending']['status'] = $updateData['vending_restriction'];
        
        $user->plan_id = $updateData['plan_id'];
        $user->secret_pin = $updateData['transactpin'];
        $user->access_control = json_encode($accessControl);       
        $user->save(); 
        return $this->sendResponse("User updated successfully", [], 200);
    }
    
    public function updateUserAccessControl($id, $action) {
        $user = User::find($id);
        if (!$user) {
            return $this->sendError("User does not exists", [], 404);
        }
        $isUpdated = false;        
        switch ($action) {
            case "suspend":
                $accessControl = json_decode($user->access_control, true);
                $accessControl['suspension']['status'] = 1;

                $user->access_control = json_encode($accessControl);       
                $user->save(); 
                $isUpdated = true;
            break;
            case "unsuspend":
                $accessControl = json_decode($user->access_control, true);
                $accessControl['suspension']['status'] = 0;

                $user->access_control = json_encode($accessControl);       
                $user->save(); 
                $isUpdated = true;
            break;
            default:
                return $this->sendError("Invalid action", [], 400);
        }
        
        if ($isUpdated) {
            return $this->sendResponse("User updated successfully", [], 200);
        }
        return $this->sendError("Error updating request", [], 400);
    }

}