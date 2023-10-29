<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PricingRequest extends FormRequest
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
            "id" => [$this->has('id') != null ? 'required' : 'nullable'],
            "costPrice" => ['between:0,99999.99', $this->has('costPrice') != null ? 'required' : 'nullable'],
            "selling_price" => ['between:0,99999.99', $this->has('selling_price') != null ? 'required' : 'nullable'],
            "extra_charges" => ['between:0,99999.99', $this->has('extra_charges') != null ? 'required' : 'nullable'],
            "availability" => [$this->has('availability') != null ? 'required' : 'nullable'],
        ];
    }
}