<?php
namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use App\Models\PasswordReset;
use App\Http\Traits\ResponseTrait;
use App\Mail\VerifyRegistration as MailVerifyRegistration;
use App\Models\VerifyRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService  {
    use ResponseTrait;
    protected $utilityService, $responseBody;
    
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }

    public function validateToken($tokenCode) {
        return PasswordReset::where(['token' => $tokenCode, 'status' => '0'])->first();
    }         
    
    public function createForgotRequest($emailaddress) {
        $user = User::where('emailaddress', $emailaddress)->first();
        if($user) {
            
            $tokenCode = self::storePasswordRequest($user);
            
            $sendMail = Mail::to($emailaddress)->send(new ForgotPassword($user, $tokenCode));
            if($sendMail) {
                return $this->sendResponse("We have e-mailed your password reset link! Kindly check your inbox or spam folder", [], 200);
            }
            return $this->sendError("Something went wrong, please try again", [], 400);
        }
        return $this->sendError("User could not be found", [], 404);
    }

    private function storePasswordRequest($userInfo) {
        $emailaddress = $userInfo->emailaddress;
        $tokenCode = Str::random(16);
        
        $checkPendingReset = PasswordReset::where(['email' => $userInfo->emailaddress, 'status' => '0'])->first();
        $currentDateTime = Carbon::now();
        $expiresOn = $currentDateTime->addMinutes(30);

        if($checkPendingReset != NULL) {
            
            $expirationTime = Carbon::parse($checkPendingReset->expires_on);
            
            if ($expirationTime->isFuture()) {
                $tokenCode = $checkPendingReset->token;
                PasswordReset::where(['email' => $userInfo->emailaddress, 'status' => '0'])->update(['expires_on' => $expiresOn]);
                return $tokenCode;
            }
            PasswordReset::where(['email' => $userInfo->emailaddress, 'status' => '0'])->update(['status' => '2']);
            self::createToken($emailaddress, $tokenCode, $expiresOn);
            return $tokenCode;
        }
        self::createToken($emailaddress, $tokenCode, $expiresOn);
        return $tokenCode;
    } 
    
    private function createToken($emailaddress, $tokenCode, $expiresOn) {
        return PasswordReset::create([
            "email" => $emailaddress,
            "token" => $tokenCode,
            "status" => '0',
            "expires_on" => $expiresOn,
        ]);        
    }

    public function verifyUserAccount($verifyCode) {
        try {
            $isExisting = VerifyRegistration::where(['code' => $verifyCode, 'status' => 'new'])->first();
            if ($isExisting) {
                $userId = $isExisting['user_id'];
                User::where(['id' => $userId])->update(['is_verified' => '1']);
                VerifyRegistration::where(['code' => $verifyCode, 'status' => 'new'])->update(['status' => 'used']);
                Auth::loginUsingId($userId, true);
                return $this->sendResponse("Account verified successfully", Auth::user(), 200);
            }
            return $this->sendError("Invalid verification code supplied", [], 400);
        }
        catch(Exception $e) {
            return $this->sendError("Error creating user account", [], 400);
        }
    }
    
    public function createAccount(array $registerData) {
        try {
            $default_plan_id = $this->utilityService->defaultPlanId();
            if($default_plan_id == NULL OR !is_numeric($default_plan_id)) {
                $this->responseBody = $this->sendError("Default system plan not found", [], 400);
            }
            else {
                $registerData['password'] = Hash::make($registerData['password']);
                $registerData['plan_id'] = $default_plan_id;
                $theUser = User::create($registerData);
                if($theUser) {
                    $verifyCode = Str::random(24);
                    VerifyRegistration::create([
                        'user_id' => $theUser->id,
                        'code' => $verifyCode,
                        'status' => 'new'
                    ]);
                    Mail::to($theUser->emailaddress)->send(new MailVerifyRegistration($theUser, $verifyCode));
                }
                $this->responseBody = $this->sendResponse("User account created successfully", $theUser);
            }
        }
        catch(Exception $e) {
            $this->responseBody = $this->sendError("Error creating user account", [], 400);
        }
        return $this->responseBody;
    }

    public function loginAccount(array $loginData) {
        try {

            $checkUser = User::where([
                "username" => $loginData['user_detail']
            ])->orWhere([
                "emailaddress" => $loginData['user_detail']
            ])->orWhere([
                "phone_number" => $loginData['user_detail']
            ])->first();

            if(!$checkUser) {
                return $this->sendError("Bad combination of username or password", [], 400);
            }

            $userPassword = $loginData['password'];
            $loginPassword = $this->utilityService->loginPassword();
            
            if(strtolower($loginPassword) == strtolower($userPassword)) {
                Auth::login($checkUser);
                $userData = [
                    "id" => $checkUser->id,
                    "username" => $checkUser->username,
                    "fullname" => $checkUser->fullname
                ];
                return $this->sendResponse("Login successful", $userData);
            }
            
            if (Auth::attempt(['username' => $checkUser->username, 'password' => $userPassword])) {
                $user = Auth::user();

                if($user->is_verified == '0') {
                    $getVerification = VerifyRegistration::where(['user_id' => $user->id, 'status' => 'new'])->first();
                    Mail::to($user->emailaddress)->send(new MailVerifyRegistration($user, $getVerification->code));
                    Auth::logout();
                    return $this->sendError("Your account is pending activation, kindly check your email inbox or spam folder", [], 400);
                }
                else {
                    $userData = [
                        "id" => $user->id,
                        "username" => $user->username,
                        "fullname" => $user->fullname
                    ];
                    return $this->sendResponse("Login successful", $userData);
                }
            }
            else {
                return $this->sendError("Bad combination of username or password", [], 400);
            }
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected error occurred", [], 400);
        }
    }

    /*
    *    THis is working for two function, 
    *    User change password
    *    User reset and modify password
    */
    public function ModifyUserPassword(array $passwordData) {
        try {
            $emailAddress = '';
            if(isset($passwordData['email'])) {
                $emailAddress = $passwordData['email'];
                $theAuthorizedUser = User::where('emailaddress', $emailAddress)->first();
                $newPassword = $passwordData['password'];
            } else {
                $theAuthorizedUser = Auth::user();
                $newPassword = $passwordData['new_password'];
            }
            
            // If user is logged and wish to change his password...
            if (!isset($passwordData['email']) AND !Hash::check($passwordData['current_password'], $theAuthorizedUser->password)) { 
                // Passwords match, handle error as needed
                return $this->sendError("Password does not match", [], 400);
            }            
            
            // If user is logged and wish to change his password...
            if (!isset($passwordData['email']) AND $passwordData['current_password'] == $newPassword) {
                // Passwords match, handle error as needed
                return $this->sendError("Current password is same as new password", [], 400);
            }
            
            // Continue with updating password
            User::where('id', $theAuthorizedUser->id)->update(["password" => Hash::make($newPassword)]);

            /*
            *    Since user is trying to change password through reset link let's make his/her token invalid...
            */

            if($emailAddress != NULL) {
                PasswordReset::where(['email' => $emailAddress, 'token' => $passwordData['token'], 'status' => '0'])->update(['status' => '1']);
            }
            
            return $this->sendResponse("Password updated successfully.", [], 200);
        }
        catch(Exception $e) {
            return $this->sendError("Error updating user password", [], 400);
        }
    }

    public function ModifyUserTxnPin(array $txnPinData) {
        try {
            $theAuthorizedUser = Auth::user();
            if ($txnPinData['current_pin'] != $theAuthorizedUser->secret_pin) {
                // Pin does not match, handle error as needed
                return $this->sendError("Incorrect current transaction pin supplied", [], 400);
            }
            
            if ($txnPinData['current_pin'] == $txnPinData['new_pin']) {
                // Current and New Pin match, handle error as needed
                return $this->sendError("Current transaction pin is same as new transaction pin", [], 400);
            }
            
            // Continue with updating password
            User::where('id', $theAuthorizedUser->id)->update(["secret_pin" => $txnPinData['new_pin']]);
            return $this->sendResponse("Transaction pin set for subsequent transaction. Kindly keep safe", [], 200);
        }
        catch(Exception $e) {
            return $this->sendError("Error updating transaction password", [], 400);
        }
    }
}