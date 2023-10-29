<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
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
        if(in_array(Route::currentRouteName(), ["create-education-request", "update-education-request"])) {
            return [
                "product_id" => $this->has('product_id') !== false ? "string|required" : "",
                "mobilenig" => "nullable",   
                "id" => "excludeif:create-education-request,create-education-request"         
            ];
        }
        else {
            return [
                "quantity" => "numeric|required",
                "serviceType" => "string|required",
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
            "quantity.required" => "Quantity field is required",
            "serviceType.required" => "Service field is required",
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