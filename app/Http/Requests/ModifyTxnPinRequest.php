<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModifyTxnPinRequest extends FormRequest
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
            "current_pin" => "required|string|min:4",
            "new_pin" =>  [
                'required', 'string', 'min:4','not_in:0000',
            ],
            "verify_new_pin" => "required|string|min:4|same:new_pin",
        ];
    }

    public function messages() {
        return [
            "current_pin.required" => "Current transaction pin is required",
            "new_pin.min" => "The new transaction pin must be at least :min characters.",
            'verify_new_pin.same' => 'The confirm transaction pin must match the new transaction pin.',
            "new_pin.not_in" => "Default transaction pin of 0000 can not be set as  new transaction pin"
        ];
    }
}