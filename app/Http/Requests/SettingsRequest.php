<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
        // dd($this->has('updateKycCharge'));
        if($this->has('updateMonnify')) { // Updating of monnify settings...
            return [
                "apiKey" => "required|string",
                "secKey" => "required|string",
                "contractCode" => "required|numeric",
                "chargestype" => "required|string",
                "charges" => "required|numeric",
                "percent" => "required|numeric",
                "deposit_amount" => "required|numeric",
            ];
        }
        else if($this->has('updateBank')) { // Updating of bank account and charges settings...
            return [
                "account_information" => "required|string",
                "min_wallet" => "required|numeric",
                "min_stamp" => "required|numeric",
                "stamp_duty_charge" => "required|numeric"
            ];
        }
        else if($this->has('updateFlutterwave') OR $this->has('updatePaystack')) { // Updating of bank account and charges settings...
            return [
                "public_key" => "required|string",
                "secret_key" => "required|string",
                "chargestype" => "required|string",
                "charges" => "required|numeric",
                "status" => $this->has('status') ? "required|string":""
            ];
        }
        else if($this->has('updateAiritmeInfo')) { // Updating of bank account and charges settings...
            return [
                "min_value" => "required|numeric",
                "max_value" => "required|numeric"
            ];
        }
        else if($this->has('updateVendRestriction')) { // Updating of vending restriction settings...
            return [
                "unverified_purchase" => "required|numeric",
                "status" => "required|string"
            ];
        }
        else if($this->has('updateAiritmeConversion')) { // Updating of bank account and charges settings...
            return [
                "mtn_receiver" => "required|digits_between:11,11",
                "airtel_receiver" => "required|digits_between:11,11",
                "glo_receiver" => "required|digits_between:11,11",
                "eti_receiver" => "required|digits_between:11,11",
                
                "mtn_percentage" => "required|numeric|digits_between:0,100",
                "airtel_percentage" => "required|numeric|digits_between:0,100",
                "glo_percentage" => "required|numeric|digits_between:0,100",
                "eti_percentage" => "required|numeric|digits_between:0,100",
                
                "status" => $this->has('status') ? "required|string":"",
                "mtnStatus" => $this->has('mtnStatus') ? "required|string":"",
                "airtelStatus" => $this->has('airtelStatus') ? "required|string":"",
                "gloStatus" => $this->has('gloStatus') ? "required|string":"",
                "etiStatus" => $this->has('etiStatus') ? "required|string":"",
            ];
        }
        else if($this->has('updateKycCharge')) { // Updating of bank account and charges settings...
            return [
                "nin" => "required|numeric",
                "bvn" => "required|numeric"
            ];
        }
    }
}