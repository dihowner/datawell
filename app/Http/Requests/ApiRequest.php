<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
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
        if(Route::currentRouteName() == "update-api-settings") {
            return [
                "categoryApi" => "required"
            ];
        }
        else {
            return [
                "apiId" => $this->has('apiId') ? "integer|required":"nullable",
                "api_name" => ["string","required",
                    Rule::unique('apis')->where(function ($query) {
                        // Check if ID is provided, exclude the current ID if available
                        if ($this->has('apiId')) {
                            $query->where('id', '!=', $this->input('apiId'));
                        }
                    })
                ],
                "api_delivery_route" => "string|required",
                "vendor_id" => "integer|required",
                "api_username" => $this->has('api_username') ? "string|required":"nullable",
                "api_password" => $this->has('api_password') ? "string|required":"nullable",
                "api_public_key" => $this->has('api_public_key') ? "string|required":"nullable",
                "api_private_key" => $this->has('api_private_key') ? "string|required":"nullable",
                "api_secret_key" => $this->has('api_secret_key') ? "string|required":"nullable"
            ];            
        }
    }
}