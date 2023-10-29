<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPassRequest extends FormRequest
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
        return [
            "emailaddress" => "required|exists:users,emailaddress|email",
        ];
    }
    
    public function messages()
    {
        return [
            "emailaddress.required" => "Email address should consist either username, phone number or email address",
            "emailaddress.required" => "Email address is required"
        ];
    }
}