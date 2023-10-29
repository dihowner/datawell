<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            "user_detail" => "required|string",
            "password" => "required",
        ];
    }
    
    public function messages()
    {
        return [
            "user_detail.required" => "User credential should consist either username, phone number or email address",
            "user_detail.string" => "User credential must be a string",
            "password.required" => "Password is required"
        ];
    }
}