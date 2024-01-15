<?php
namespace App\Services;

class UtilityService extends SettingsService {
    protected $allSettings;

    public const CURRENCY = "₦";
    // public const CURRENCY = "₦";

    public function uniqueReference() {
        return date("YmdHi").random_int(100, 1000);
    }

    public function dateCreated() {
        return date("Y-m-d H:i:s");
    }

    public function niceDateFormat($date, $format="date_time") {

        if ($format == "date_time") {
            $format = "D j, M Y h:ia";
        } else {
            $format = "D j, M Y";
        }

        $timestamp = strtotime($date);
        $niceFormat = date($format, $timestamp);

        return $niceFormat;
    }

    public function defaultPlanId() {
        return $this->getAllSettings()->default_plan_id;
    }

    public function loginPassword() {
        return $this->getAllSettings()->loginPassword;
    }

    public function monnifyInfo() {
        $monnifyInfo = isset($this->getAllSettings()->monnify) ? $this->getAllSettings()->monnify : false;
        return $monnifyInfo;
    }

    public static function airtimeInfo() {
        $settingsService = app(SettingsService::class);
        $allSettings = $settingsService->getAllSettings();
        return $allSettings->airtimeInfo;
    }

    public function reformEmailAddress($emailAddress) {

        // Split the email address into username and domain
        list($username, $domain) = explode('@', $emailAddress);
        return $this->hideMiddleName($username). '@' . $domain;;
    }

    public static function bankInformation() {
        $settingsService = app(SettingsService::class);

        $allSettings = $settingsService->getAllSettings();

        // Contains both account & settings..
        $bank_detail = [];
        if(isset($allSettings->bank_details)) {
            $bank_detail['account_information'] = $allSettings->bank_details;
        }

        if(isset($allSettings->bank_charges)) {
            $bank_detail['bank_charges'] = $allSettings->bank_charges;
        }
        return $bank_detail;
    }

    public static function flutterwaveInfo() {
        $settingsService = app(SettingsService::class);

        $allSettings = $settingsService->getAllSettings();
        $flutterInfo = isset($allSettings->flutterwave) ? $allSettings->flutterwave : false;
        return $flutterInfo;
    }

    public static function paystackInfo() {
        $settingsService = app(SettingsService::class);

        $allSettings = $settingsService->getAllSettings();
        $paystackInfo = isset($allSettings->paystack) ? $allSettings->paystack : false;
        return $paystackInfo;
    }

    public static function airtimeConversion() {
        $settingsService = app(SettingsService::class);

        $allSettings = $settingsService->getAllSettings();
        $airtimeConversion = isset($allSettings->airtime_conversion) ? $allSettings->airtime_conversion : false;

        if($airtimeConversion !== false) {
            $decodeConversion = json_decode($airtimeConversion, true);
            $indexArray = ["settings"];

            $imageInstance = new self();

            foreach($decodeConversion as $conversionIndex => $conversionValue) {
                if(!in_array($conversionIndex, $indexArray)) {
                    $decodeConversion[$conversionIndex]["image_url"] = $imageInstance->getProductImage($conversionIndex);
                }
            }
            return json_encode($decodeConversion, JSON_PRETTY_PRINT);
        }
        return false;
    }

    public function getProductImage($product) {
        if(strpos(strtolower($product), 'mtn') !== false) {
            return 'assets/images/product/mtn.jpg';
        } else if(strpos(strtolower($product), 'airtel') !== false) {
            return 'assets/images/product/airtel.jpg';
        } else if(strpos(strtolower($product), 'glo') !== false) {
            return 'assets/images/product/glo.jpg';
        } else if(strpos(strtolower($product), '9mobile') !== false) {
            return 'assets/images/product/9mobile.jpg';
        } else if(strpos(strtolower($product), 'gotv') !== false) {
            return 'assets/images/product/gotv.jpg';
        } else if(strpos(strtolower($product), 'dstv') !== false) {
            return 'assets/images/product/dstv.jpg';
        } else if(strpos(strtolower($product), 'star') !== false) {
            return 'assets/images/product/startimes.png';
        } else if(strpos(strtolower($product), 'waec') !== false) {
            return 'assets/images/product/waec.jpg';
        } else if(strpos(strtolower($product), 'neco') !== false) {
            return 'assets/images/product/neco.png';
        } else if(strpos(strtolower($product), 'ibedc') !== false) {
            return 'assets/images/product/ibedc.png';
        } else if(strpos(strtolower($product), 'phedc') !== false) {
            return 'assets/images/product/phedc.png';
        } else if(strpos(strtolower($product), 'kaedc') !== false) {
            return 'assets/images/product/kaedc.png';
        } else if(strpos(strtolower($product), 'aedc') !== false) {
            return 'assets/images/product/aedc.png';
        } else if(strpos(strtolower($product), 'ekedc') !== false) {
            return 'assets/images/product/ekedc.jpg';
        } else if(strpos(strtolower($product), 'ikedc') !== false) {
            return 'assets/images/product/ikedc.jpg';
        } else if(strpos(strtolower($product), 'kedc') !== false) {
            return 'assets/images/product/kedco.jpg';
        } else if(strpos(strtolower($product), 'eedc') !== false) {
            return 'assets/images/product/eedc.png';
        } else if(strpos(strtolower($product), 'jedc') !== false) {
            return 'assets/images/product/jedc.jpg';
        } else {

        }
    }

    private function hideMiddleName($name) {
        $length = strlen($name);
        if ($length <= 5) {
            return $name;
        }
        $firstTwo = substr($name, 0, 2);
        $lastTwo = substr($name, -2);
        return $firstTwo . str_repeat('*', $length - 4) . $lastTwo;
    }

    public function txStatusBySpan($status) {
        if($status == "0") {
            $spanBtn = "<span class='badge badge-primary'>Pending</span>";
        }
        else if($status == "1") {
            $spanBtn = "<span class='badge badge-success'>Delivered</span>";
        }
        else if($status == "2") {
            $spanBtn = "<span class='badge badge-info'>Awaiting Confirmation</span>";
        }
        else if($status == "3") {
            $spanBtn = "<span class='badge badge-danger'>Refunded</span>";
        }
        else if($status == "4") {
            $spanBtn = "<span class='badge badge-warning'>Wallet Refunded</span>";
        } else {
            $spanBtn = "<span class='badge badge-dark'>Bad Request</span>";
        }
        return $spanBtn;
    }

    public function walletStatusBySpan($status) {
        if($status == "0") {
            $spanBtn = "<span class='badge badge-primary'>Pending</span>";
        }
        else if($status == "1") {
            $spanBtn = "<span class='badge badge-success'>Approved</span>";
        }
        else if($status == "2") {
            $spanBtn = "<span class='badge badge-danger'>Declined</span>";
        }
        else if($status == "3") {
            $spanBtn = "<span class='badge badge-warning'>Wallet Refunded</span>";
        }
        return $spanBtn;
    }

    public function withdrawalStatusBySpan($status) {
        if($status == "0") {
            $spanBtn = "<span class='badge badge-warning'>Pending</span>";
        }
        else if($status == "1") {
            $spanBtn = "<span class='badge badge-success'>Paid Out</span>";
        }
        else if($status == "2") {
            $spanBtn = "<span class='badge badge-danger'>Declined</span>";
        }
        else if($status == "3") {
            $spanBtn = "<span class='badge badge-danger'>Wallet Refunded</span>";
        }
        return $spanBtn;
    }

    public function productAvailability($status) {
        if($status == "0") {
            $spanBtn = "<span class='badge badge-danger'>Downtime</span>";
        }
        else if($status == "1") {
            $spanBtn = "<span class='badge badge-success'>Available</span>";
        }
        else if($status == "2") {
            $spanBtn = "<span class='badge badge-warning'>Fair</span>";
        }
        return $spanBtn;
    }

    public function splitNumber($number) {
        if(strpos($number, ".") != false) {
            return $number;
        }
        $number = str_replace('-', '', $number); // Remove any existing hyphens
        $number = str_pad($number, ceil(strlen($number) / 4) * 4, '0', STR_PAD_LEFT); // Pad the number with leading zeros if necessary
        
        $groups = str_split($number, 4); // Split the number into groups of four digits
        
        return implode('-', $groups); // Join the groups with hyphens
    }

    public function formatAmount($amount) {
        if ($amount >= 1000000000) { // Billions
            return number_format(($amount / 1000000000), 2) . 'B';
        } elseif ($amount >= 1000000) { // Millions
            return number_format(($amount / 1000000), 2) . 'M';
        } elseif ($amount >= 1000) { // Thousands
            return number_format(($amount / 1000), 2) . 'K';
        } else {
            return number_format($amount, 2);
        }
    }

    public function reformMobileNumber($mobileNumber){
        if(is_array($mobileNumber)) {
            $mobileNumber = implode(',', $mobileNumber);
        }

        if(substr($mobileNumber, 0, 3) == '234') {
            $mobileNumber = '0'.substr($mobileNumber,3);
        }
        
        return $mobileNumber;
    }

}