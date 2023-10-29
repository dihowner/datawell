<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class ModifyPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if(in_array(Route::currentRouteName(), ["submit-reset-password"])) { #use this for reset password...
            return [
                "email" => "required|email",
                "token" => "required|string|min:5",
                "password" => "required|string|min:5",
                "confirm_password" => "required|string|min:5|same:password",
            ];
        }
        else {
            return [
                "current_password" => "required|string|min:5",
                "new_password" => "required|string|min:5",
                "verify_password" => "required|string|min:5|same:new_password",
            ];            
        }
    }

    public function messages() {
        return [
            "current_password.required" => "Current password is required",
            "new_password.min" => "The new password must be at least 8 characters.",
            'confirm_password.same' => 'The confirm password must match the new password.'
        ];
    }
}