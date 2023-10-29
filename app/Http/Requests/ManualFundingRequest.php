<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManualFundingRequest extends FormRequest
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
            "funding_method" => "required",
            "amount" => "required|numeric|digits_between:1,1000000000|min:50"
        ];
    }
    
    public function messages()
    {
        return [
            "funding_method.required" => "Please select a funding method",
            "amount.required" => "Amount is required",
            "amount.numeric" => "Amount must be a numeric value",
            "amount.digits_between" => "Amount  must be between :min and :max digits" ,
            "amount.min" => "Minimum funding amount is N50"  
        ];
    }
}