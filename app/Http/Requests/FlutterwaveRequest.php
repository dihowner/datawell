<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlutterwaveRequest extends FormRequest
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
            "tx_ref" => "required|string",
            'status' => 'required|string',
            "transaction_id" => 'string',
        ];
    }

    public function messages()
    {
        return [
            "tx_ref.required" => "Transaction reference is required",
            "status.required" => "Transaction status is required"
        ];
    }
}