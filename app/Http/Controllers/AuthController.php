<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\ForgotPassRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\ModifyTxnPinRequest;
use App\Http\Requests\ModifyPasswordRequest;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function submitForgetPasswordForm(ForgotPassRequest $request) {
        $requestData = $request->validated();
        $emailaddress = $requestData['emailaddress'];
        
        $createRequest = $this->authService->createForgotRequest($emailaddress);
        $statusCode = $createRequest->getStatusCode();
        $decodeResponse = json_decode($createRequest->content(), true);
        $message = $decodeResponse['message'];
        
        if($statusCode === 200) {
            Alert::success('Success', $message);
        }
        else {
            Alert::error('Error', $message);
        }
        return redirect()->back();
    }

    public function resetUserPassword(ModifyPasswordRequest $request) {
        $resetPassword = $this->authService->ModifyUserPassword($request->validated());
        $statusCode = $resetPassword->getStatusCode();
        
        if($statusCode === 200) {
            Alert::success('Success', "Password changed successfully. Kindly login with your new password");
            return redirect()->route('get.login');
        }        
        
        $responseContent = json_decode($resetPassword->content());
        Alert::error("Error", $responseContent->message);
        return redirect()->back();
    }

    public function resetPasswordForm($token) {
        $validateToken = $this->authService->validateToken($token);

        if($validateToken == NULL) {
            Alert::error('Error', "Reset token has already been used or does not exist. Kindly request for a new reset token");
            return redirect()->route('forgot-password-form');
        }
        
        $expirationTime = Carbon::parse($validateToken->expires_on);
        
        if ($expirationTime->isFuture()) {
            $emailaddress = $validateToken->email;
            return view('reset-password', compact('emailaddress', 'token'));
        }
        Alert::error('Error', "Password reset link has expired. Kindly request for a new one");
        return redirect()->route('forgot-password-form');
    }

    public function verifyUserAccount($code) {
        $verifyUserAccount = $this->authService->verifyUserAccount($code);
        $statusCode = $verifyUserAccount->getStatusCode();
        
        if($statusCode === 200) {
            return redirect()->route('user.index');
        }        
        
        $responseContent = json_decode($verifyUserAccount->content());
        Alert::error("Error", $responseContent->message);
        return redirect()->route('get.login');
    }

    public function createAccount(RegisterRequest $request) {
        $createRequest = $this->authService->createAccount($request->validated());
        $statusCode = $createRequest->getStatusCode();
        $decodeResponse = json_decode($createRequest->content(), true);
        
        $message = $decodeResponse['message'];
        
        if($statusCode === 200) {
            Alert::success('Success', 'Your registration was successful. Kindly check your email inbox or spam folder to complete your registration');
            return redirect()->route('get.login');
        }
        else {
            Alert::error('Error', $message);
            return redirect()->back();
        }
    }

    public function loginAccount(LoginRequest $request) {
        $createRequest = $this->authService->loginAccount($request->validated());
        $statusCode = $createRequest->getStatusCode();
        
        if($statusCode === 200) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        
        $decodeResponse = json_decode($createRequest->content(), true);
        
        $message = $decodeResponse['message'];
        Alert::error('Error', $message);
        return redirect()->back();
    }

    public function ModifyUserPassword(ModifyPasswordRequest $request) {
        $createRequest = $this->authService->ModifyUserPassword($request->validated());
        $statusCode = $createRequest->getStatusCode();
        
        $responseContent = json_decode($createRequest->content());
        $message = $responseContent->message;
        
        if($statusCode === 200) {
            Alert::success('Success', $message);
        } 
        else {
            Alert::error("Error", $message);
        }
        return redirect()->back();
    }

    public function ModifyUserTxnPin(ModifyTxnPinRequest $request) {
        $createRequest = $this->authService->ModifyUserTxnPin($request->validated());

        $statusCode = $createRequest->getStatusCode();
        
        $responseContent = json_decode($createRequest->content());
        $message = $responseContent->message;
        
        if($statusCode === 200) {
            Alert::success('Success', $message);
        } 
        else {
            Alert::error("Error", $message);
        }
        return redirect()->back();
    }
    
    public function logOut(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}