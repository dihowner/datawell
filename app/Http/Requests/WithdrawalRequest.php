<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawalRequest extends FormRequest
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
        $routeName = Route::currentRouteName();
        
        if($routeName == 'user.convert-airtime-wallet') {
            return [
                "walletType" => "required|string",
                "amount" => "numeric|required|digits_between:1,10000|min:500|max:100000",
                "transactpin" =>  [
                    'required',
                    'string',
                    'min:4',
                    'max:4',
                    'not_in:0000',
                ]
            ];
        }
        else {
            return [
                "amount" => "numeric|required|digits_between:1,10000|min:500|max:100000",
                "transactpin" =>  [
                    'required',
                    'string',
                    'min:4',
                    'max:4',
                    'not_in:0000',
                ]
            ];
        }
    }
    
    public function messages()
    {
        return [
            "amount.required" => "Amount is required",
            "amount.numeric" => "Amount must be a numeric value",
            "amount.digits_between" => "Amount  must be between :min and :max digits" ,
            "amount.min" => "Minimum withdrawal amount is N500"  ,
            "amount.max" => "Maximum withdrawal amount is N100000",
            "transactpin.min" => "Transaction pin must not be lesser than :min digit",
            "transactpin.max" => "Transaction pin must not be greater than :max digit",
            "transactpin.not_in" => "Transaction pin  must not be the default PIN of 0000"
        ];
    }
}