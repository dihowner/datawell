<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "username" => "required|unique:users|string|min:3",
            "fullname" => "required|string",
            "phone_number" => "required|unique:users|digits_between:11,11",
            "emailaddress" => "required|unique:users|email",
            "password" => "required"
        ];
    }

    public function messages()
    {
        return [
            "username.required" => "Username is required",
            "username.unique" => "Username ({$this->username}) already belongs to a user",
            "phone_number.unique" => "Phone number ({$this->phone_number}) already belongs to a user",
            "emailaddress.unique" => "Email address ({$this->emailaddress}) already belongs to a user"
        ];
    }
}