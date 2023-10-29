<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AirtimeCashRequest extends FormRequest
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
            "amount" => "integer|required|min:1000|max:5000000",
            "phone_number" => "string|required|min:11|max:11",
            "airtime_sender" => "string|required|min:11|max:11",
            "network" => "string|required",
            "additional_note" => "string",
        ];
    }

    public function messages()
    {
        return [
            "amount.integer" => "Airtime amount field must be a whole number and not a decimal number",
            "amount.required" => "Airtime amount field is required",
            "amount.min" => "Minimum Airtime amount is N1000",
            "amount.max" => "Maximum Airtime amount is N5000000",
            "phone_number.required" => "Mobile number field is required",
            "airtime_sender.required" => "Airtime Sender Mobile number field is required",
            "network.required" => "Network MSIDN field is required",
        ];
    }
}