<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class CableTvRequest extends FormRequest
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
        if(in_array(Route::currentRouteName(), ["create-cabletv-request", "update-cabletv-request"])) {
            return [
                "product_id" => $this->has('product_id') !== false ? "string|required" : "",
                "mobilenig" => "nullable",   
                "id" => "excludeif:create-cabletv-request,create-cabletv-request"         
            ];
        }
        else {
            return [
                "packageCategory" => "string",
                "packageOption" => "string|required",
                "smartcard_no" => "numeric|required",
                "customer_name" => "string|required",
                "customer_number" => "numeric",
                "amount" => "numeric|digits_between:1,10000|min:50|max:100000", //Optional, only for Top-Up
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
            "customer_name.required" => "Customer name is required",
            "packageCategory.required" => "Package Category is required",
            "packageOption.required" => "Package Option is required",
            "smartcard_no.numeric" => "Smartcard number must be a numeric value",
            "amount.numeric" => "Amount must be a numeric value",
            "customer_number.numeric" => "Customer validation is required",
            "amount.digits_between" => "Amount  must be between :min and :max digits" ,
            "amount.min" => "Minimum topup amount is N50"  ,
            "amount.max" => "Maximum topup amount is N100000",
            "transactpin.min" => "Transaction pin must not be lesser than :min digit",
            "transactpin.max" => "Transaction pin must not be greater than :max digit",
            "transactpin.not_in" => "Transaction pin  must not be the default PIN of 0000"
        ];
    }

    public function prepareForValidation() {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}