<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BankAccountRequest extends FormRequest
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
            "bank_name" => "required|string",
            "account_name" => "required|string",
            "account_number" => "required|numeric|min:10",
            "transactpin" =>  [
                'required',
                'string',
                'min:4',
                'max:4',
                'not_in:0000',
            ]
        ];
    }
    
    public function messages()
    {
        return [
            "bank_name.required" => "Please enter bank name",
            "account_name.required" => "Please enter account name",
            "account_number.required" => "Please enter account number",
            "transactpin.required" => "Please enter transaction pin",
            "account_number.numeric" => "Only numeric characters allowed",
            "account_number.min" => "Account number must not be lesser than :min digits",
            "account_number.max" => "Account number must not be lesser than :max digits",
            "transactpin.min" => "Transaction pin must not be lesser than :min digit",
            "transactpin.max" => "Transaction pin must not be greater than :max digit",
            "transactpin.not_in" => "Transaction pin  must not be the default PIN of 0000"
        ];
    }
}