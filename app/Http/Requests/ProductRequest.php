<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            "id" => ["numeric", $this->route('id') != null ? 'required' : 'nullable'],
            "product_name" => ["string", Rule::unique('products')->ignore($this->id), $this->has('product_name') !== false ? 'required' : 'nullable'],
            "product_id" => ["string", Rule::unique('products')->ignore($this->id), $this->has('product_id') !== false ? 'required' : 'nullable'],
            "category_id" => ["exists:categories,id", $this->has('category_id') !== false ? 'required' : 'nullable'],
            "cost_price" => ["numeric", $this->has('cost_price') !== false ? 'required' : 'nullable'],
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

    public function prepareForValidation() {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}