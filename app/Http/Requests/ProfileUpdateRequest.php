<?php

namespace App\Http\Requests;

use App\Rules\NoSpaces;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class ProfileUpdateRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if(Route::currentRouteName() == 'user.submit-kyc') {
            $verificationMethod = $this->input('verification_method');
            if ($verificationMethod == "bvn") {
                return [
                    "verification_method" => "required|string",
                    "fullName" => "required|string",
                    "bvnNumber" => "required|string|digits_between:11,11",
                    "bvnPhoneNumber" => "required|string|digits_between:11,11",
                    "dateOfBirth" => "required|date"
                ];
            } else {
                return [
                    "verification_method" => "required|string",
                    "ninNumber" => "required|string",
                    "fullName" => "required|string",
                    "ninPhoneNumber" => "required|string|digits_between:11,11",
                    "dateOfBirth" => "required|date"
                ];
            }
        }
    }
}
