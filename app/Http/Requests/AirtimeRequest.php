<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class AirtimeRequest extends FormRequest
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
        if(in_array(Route::currentRouteName(), ["create-airtime-request", "update-airtime-request"])) {
            return [
                "product_id" => $this->has('product_id') !== false ? "string|required" : "",
                "init_code" => "string|nullable",
                "wrap_code" => "string|nullable",
                "mobilenig" => "nullable",
                "smeplug" => "nullable",   
                "ipay" => "nullable",   
                "id" => "excludeif:create-airtime-request,create-airtime-request",          
            ];
        }
        else {
            return [
                "id" => $this->route('id') != null ? 'required' : '',
                "amount" => $this->has('amount') !== false ? "integer|required|min:10|max:5000000" : "",
                "phone_number" => $this->has('phone_number') !== false ? "string|required|min:11|max:11" : "",
                "network" => $this->has('network') !== false ? "string|required" : "",
                "transactpin" => $this->has('transactpin') !== false ? "string|required|min:4|max:4|not_in:0000" : ""
            ];
        }
    }

    public function messages()
    {
        return [
            "amount.integer" => "Airtime amount field must be a whole number and not a decimal number",
            "amount.required" => "Airtime amount field is required",
            "phone_number.required" => "Mobile number field is required",
            "network.required" => "Network MSIDN field is required",
            "amount.min" => "Minimum Airtime amount is N10",
            "amount.max" => "Maximum Airtime amount is N5000000",
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