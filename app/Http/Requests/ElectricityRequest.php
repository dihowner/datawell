<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class ElectricityRequest extends FormRequest
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
        if(in_array(Route::currentRouteName(), ["create-electricity-request", "update-electricity-request"])) {
            return [
                "product_id" => $this->has('product_id') !== false ? "string|required" : "",
                "mobilenig" => "nullable",   
                "id" => "excludeif:create-electricity-request,create-electricity-request"         
            ];
        }
        else {
            return [
                "serviceType" => "string|required",
                "amount" => "numeric|required|digits_between:1,10000|min:50|max:10000",
                "meter_number" => "numeric|required",
                "customer_name" => "nullable|string",
                "customer_address" => "nullable|string",
                "customer_details" => "nullable|string",
                "customer_reference_id" => "string",
                "customer_tariff_code" => "string",
                "customer_access_code" => "string",
                "customer_dt_number" => "string",
                "customer_account_type" => "string",
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
            "serviceType.required" => "Service field is required",
            "amount.required" => "Amount is required",
            "amount.numeric" => "Amount must be a numeric value",
            "amount.digits_between" => "Amount  must be between :min and :max digits" ,
            "amount.min" => "Minimum amount is N50"  ,
            "amount.max" => "Maximum amount is N10000",
            "meter_number.required" => "Meter number is required",
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