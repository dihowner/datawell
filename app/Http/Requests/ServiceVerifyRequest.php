<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceVerifyRequest extends FormRequest
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
        $serviceCategory = $this->get('category');
        if ($serviceCategory == 'cabletv') {     
            return [
                "service" => "required|string",
                "smart_number" => "required|numeric",
            ];
        } 
        else if ($serviceCategory == 'electricity') {
            return [
                "service" => "required|string",
                "meter_number" => "required|numeric",
            ];
        }
    }

    public function messages()
    {
        return [
            "service.required" => "Service type is required",
            "smart_number.numeric" => "Smart card number must be number",
            "meter_number.numeric" => "Meter number must be number"
        ];
    }
    
}