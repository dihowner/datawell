<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaystackRequest extends FormRequest
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
            'trxref' => 'required|string',
            'reference' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            "trxref.required" => "Transaction reference is required",
            "reference.required" => "Reference is required"
        ];
    }
}