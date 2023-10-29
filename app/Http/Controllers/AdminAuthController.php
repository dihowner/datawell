<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\AdminAuth;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function LoginAccount(AdminAuth $request) {
        try {
            $adminData = $request->validated();
            $username = $adminData["username"];
            $password = $adminData["password"];

            if(Auth::guard('admin')->attempt(['username' => $username,  'password' => $password])){
                // Get logged admin info...
                // $user = Auth::guard('admin')->user();
                return redirect()->route('admin-dashboard');
            } else {
                return back()->with('error','Whoops! Invalid credentails supplied.');
            }

        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    } 
    
    public function logOut(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->back();
    }
}