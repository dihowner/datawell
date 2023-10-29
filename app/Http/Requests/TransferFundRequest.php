<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferFundRequest extends FormRequest
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
            "amount" => "numeric|required|digits_between:1,10000|min:50|max:10000",
            "userNamePhone" => "string|required",
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
            "amount.required" => "Amount is required",
            "amount.numeric" => "Amount must be a numeric value",
            "amount.digits_between" => "Amount  must be between :min and :max digits" ,
            "amount.min" => "Minimum transferrable amount is N50"  ,
            "amount.max" => "Maximum transferrable amount is N10000",
            "userNamePhone.required" => "User phone number or username is required",
            "transactpin.min" => "Transaction pin must not be lesser than :min digit",
            "transactpin.max" => "Transaction pin must not be greater than :max digit",
            "transactpin.not_in" => "Transaction pin  must not be the default PIN of 0000"
        ];
    }
}