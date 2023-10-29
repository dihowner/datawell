<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppServerRequest extends FormRequest
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
            "id" => $this->has('id') ? "integer|required":"nullable",
            "serverId" => "string|required|min:5|max:10",
            "category" => "string|required",
            "calling_time" => "string|required|digits_between:0,5",
            "color_scheme" => "numeric|required|digits_between:0,5",
            "auth_code" => "string|required|min:5|max:15",
        ];
    }
    
    public function messages()
    {
        return [
            "serverId.required" => "Please enter server id",
            "serverId.min" => "Server Id requires a minimum of :min characters",
            "serverId.max" => "Server Id requires a maximum of :max characters",
            "category.required" => "Please select category",
            "calling_time.required" => "Please enter a calling time",
            "calling_time.digits_between" => "Calling time must be between :min and :max minutes",
            "color_scheme.required" => "Please select app color scheme",
            "color_scheme.digits_between" => "Color scheme must be between :min and :max",
            "auth_code.required" => "Please enter an authorization code",
            "auth_code.min" => "Please enter an authorization code",
            "auth_code.min" => "Authorization code requires a minimum of :min characters",
            "auth_code.max" => "Authorization code requires a maximum of :max characters",
        ];
    }
    
    public function prepareForValidation() {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}