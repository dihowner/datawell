<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserByAdminRequest extends FormRequest
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
            "id" => ["integer", $this->route('id') != null ? 'required' : 'nullable'],
            "plan_id" => "required|integer",
            "transactpin" =>  [
                'required',
                'string',
                'min:4',
                'max:4',
                'not_in:0000',
            ]
        ];
    }

    public function message() {
        return [
            "plan_id.required" => "New plan is required",
            "transactpin.min" => "Transaction pin must not be lesser than :min digit",
            "transactpin.max" => "Transaction pin must not be greater than :max digit",
            "transactpin.not_in" => "Transaction pin  must not be the default PIN of 0000"
        ];
    }

    /**
     *  prepareForValidation() is used to extract the {id} parameter from the route and set it as a request attribute.
     * Action URL => /user/2/update-user
     */
    public function prepareForValidation() {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}