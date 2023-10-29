<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAuth extends FormRequest
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
            "username" => "string|required",
            "password" => "string|required|min:5"
        ];
    }

    public function messages()
    {
        return [
            "username.required" => "Username is required",
            "username.string" => "Username must be a string",
            "password.required" => "Password is required",
            "password.string" => "Password must be a string",
            "password.string" => "Password must be a minimum of 5 character"
        ];
    }
}