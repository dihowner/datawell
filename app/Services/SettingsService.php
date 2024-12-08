<?php
namespace App\Services;

use App\Http\Traits\ResponseTrait;
use App\Models\Settings;
use Exception;

class SettingsService  {
    use ResponseTrait;
    protected $responseBody;

    public function getAllSettings() {
        $allSettings = Settings::all();
        if($allSettings->count() > 0) {
            foreach($allSettings as $index => $value) {
                $feedback[$value['name']] = $value['content'];
            }
            $this->responseBody = (object)($feedback);
        } else {
            $this->responseBody = false;
        }
        return $this->responseBody;
    }

    public function updateSettings(array $settingsData, $updateCase) {
        try {
            switch($updateCase) {
                case "monnify":
                    $monnifyContent = json_decode(Settings::where('name', 'monnify')->first()->content, true);
                    $newMonnify = [
                        "baseUrl" => $monnifyContent["baseUrl"],
                        "apiKey" => $settingsData["apiKey"],
                        "secKey" => $settingsData["secKey"],
                        "contractCode" => $settingsData["contractCode"],
                        "chargestype" => $settingsData["chargestype"],
                        "charges" => $settingsData["charges"],
                        "percent" => $settingsData["percent"],
                        "deposit_amount" => $settingsData["deposit_amount"],
                    ];
                    
                    $updateMonnify = Settings::where('name', 'monnify')->update(["content" => json_encode($newMonnify)]);
    
                    if($updateMonnify) {
                        return $this->sendResponse("Monnify settings updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating monnify", [], 400);
                break;
                
                case "bankInformation":
                    $bankCharges = [
                        "min_wallet" => $settingsData["min_wallet"],
                        "min_stamp" => $settingsData["min_stamp"],
                        "stamp_duty_charge" => $settingsData["stamp_duty_charge"]
                    ];
                    
                    $updateBank = Settings::where('name', 'bank_details')->update(["content" => $settingsData["account_information"]]);
                    $updateCharges = Settings::where('name', 'bank_charges')->update(["content" => json_encode($bankCharges)]);
    
                    if($updateBank AND $updateCharges) {
                        return $this->sendResponse("Banking settings updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating bank information", [], 400);
                break;
                
                case "flutterwave":
                
                    $flutterwaveData = [
                        "status" => isset($settingsData["status"]) ? "active" : "inactive", 
                        "public_key" => $settingsData["public_key"],
                        "secret_key" => $settingsData["secret_key"],
                        "charges" => $settingsData["charges"],
                        "chargesType" => $settingsData["chargestype"]
                    ];
                    $updateFlutterwave = Settings::where('name', 'flutterwave')->update(["content" => json_encode($flutterwaveData)]);
    
                    if($updateFlutterwave) {
                        return $this->sendResponse("Flutterwave settings updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating flutterwave settings", [], 400);
                break;
                
                case "paystack":
                
                    $paystackData = [
                        "status" => isset($settingsData["status"]) ? "active" : "inactive", 
                        "public_key" => $settingsData["public_key"],
                        "secret_key" => $settingsData["secret_key"],
                        "charges" => $settingsData["charges"],
                        "chargesType" => $settingsData["chargestype"]
                    ];
                    $updatePaystack = Settings::where('name', 'paystack')->update(["content" => json_encode($paystackData)]);
    
                    if($updatePaystack) {
                        return $this->sendResponse("Paystack settings updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating paystack settings", [], 400);
                break;
                
                case "airtimeInfo":
                
                    $airtimeData = [
                        "min_value" => $settingsData["min_value"],
                        "max_value" => $settingsData["max_value"]
                    ];
                    $updateAirtimeInfo = Settings::where('name', 'airtimeInfo')->update(["content" => json_encode($airtimeData)]);
    
                    if($updateAirtimeInfo) {
                        return $this->sendResponse("Airtime Info settings updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating airtime info settings", [], 400);
                break;
                
                case "airtimeConversion":
                
                    $conversionData = [
                        "mtn" => [
                            "status" => isset($settingsData["mtnStatus"]) ? 1 : 0,
                            "percentage" => $settingsData["mtn_percentage"],
                            "receiver" => $settingsData["mtn_receiver"],
                        ],
                        "airtel" => [
                            "status" => isset($settingsData["airtelStatus"]) ? 1 : 0,
                            "percentage" => $settingsData["airtel_percentage"],
                            "receiver" => $settingsData["airtel_receiver"],
                        ],
                        "glo" => [
                            "status" => isset($settingsData["gloStatus"]) ? 1 : 0,
                            "percentage" => $settingsData["glo_percentage"],
                            "receiver" => $settingsData["glo_receiver"],
                        ],
                        "9mobile" => [
                            "status" => isset($settingsData["etiStatus"]) ? 1 : 0,
                            "percentage" => $settingsData["eti_percentage"],
                            "receiver" => $settingsData["eti_receiver"],
                        ],
                        "settings" => [
                            "status" => isset($settingsData["status"]) ? 1 : 0,
                        ]
                    ];
                    
                    $updateAirtimeConversion = Settings::where('name', 'airtime_conversion')->update(["content" => json_encode($conversionData)]);
    
                    if($updateAirtimeConversion) {
                        return $this->sendResponse("Airtime Conversion settings updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating airtime Conversion settings", [], 400);
                break;
                
                case "kyc":
                
                    $kycSettings = [
                        "bvn" => $settingsData["bvn_charges"],
                        "nin" => $settingsData["nin_charges"],
                        "verification_type" => $settingsData["verification_type"]
                    ];
                    $updateKyc = Settings::where('name', 'kycSettings')->update(["content" => json_encode($kycSettings)]);
    
                    if($updateKyc) {
                        return $this->sendResponse("KYC settings updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating kyc settings", [], 400);
                break;
                
                case "vendingRestriction":

                    $status = $settingsData["status"];
                    $unverified_purchase = $settingsData["unverified_purchase"];
                
                    if ($unverified_purchase <= 0 AND $status == 'enable') {
                        return $this->sendError("Purchase amount must be greater than 0 if status is enabled", [], 400);
                    }

                    $restrictData = [
                        "unverified_purchase" => $settingsData["unverified_purchase"],
                        "status" => $settingsData["status"],
                    ];
                    
                    $updateVendingRestriction = Settings::where('name', 'vending_restriction')->update(["content" => json_encode($restrictData)]);
    
                    if($updateVendingRestriction) {
                        return $this->sendResponse("Vending restriction settings updated successfully", [], 200);
                    }
                    return $this->sendError("Error updating vending restriction settings", [], 400);
                break;
            }
        }
        catch(Exception $e) {
            return $this->sendError("Error ".$e->getMessage(), [], 500);
        }
    }

    public function getSettingsByName($keyName) {
        $settings = Settings::where('name', $keyName)->first();
        if ($settings) {
            return $settings;
        } 
        return false;
    }    
}