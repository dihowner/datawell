<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
    public function rules() {  
        return [
            "id" => ["integer", $this->route('id') != null ? 'required' : 'nullable'],
            "plan_name" => ["string","required", Rule::unique('plans')->ignore($this->id)],
            "upgrade_fee" => ['numeric', 'required', 'regex:/^\d+(\.\d{1,2})?$/'],
            "plan_description" => "string|required",
        ];
    }

    public function message() {
        return [
            "plan_name.required" => "Plan name is required",
            "transactpin.min" => "Transaction pin must not be lesser than :min digit",
            "transactpin.max" => "Transaction pin must not be greater than :max digit",
            "transactpin.not_in" => "Transaction pin  must not be the default PIN of 0000"
        ];
    }

    /**
     *  prepareForValidation() is used to extract the {id} parameter from the route and set it as a request attribute.
     * Action URL => /plan/2/update
     */
    public function prepareForValidation() {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

}