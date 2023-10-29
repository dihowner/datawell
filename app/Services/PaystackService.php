<?php
namespace App\Services;

use Exception;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Classes\HttpRequest;

class PaystackService {
    use ResponseTrait;

    private $endpoint = "https://api.paystack.co";

    private $paystackInfo, $publicKey, $secretKey, $charges, $chargesType, $headerParams;
    protected $utilityService, $responseBody;
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;

        $this->paystackInfo = $this->utilityService->paystackInfo();
        if($this->paystackInfo !== false) {
            $decodePaystack = json_decode($this->paystackInfo, true);
            $this->publicKey = $decodePaystack['public_key'];
            $this->secretKey = $decodePaystack['secret_key'];
            $this->charges = (float) $decodePaystack['charges'];
            $this->chargesType = $decodePaystack['chargesType'];
            $this->headerParams = [
                "Authorization" => "Bearer ".$this->secretKey,
                "Content-Type" => "application/json"
            ];
        }
    }

    public function GeneratePaystackLink($referenceId) {
        try {
            $paymentAmount = (float) app(WalletService::class)->viewPaymentHistory('wallet_in', $referenceId)->amount;

            $calculatedAmount = $this->calculateAmount($paymentAmount);

            // Let's send the request to Paystack...
            $reserveResult = HttpRequest::sendPost($this->endpoint."/transaction/initialize", [
                "amount" => ($calculatedAmount * 100),
                "email" => Auth::user()->emailaddress,
                "currency" => "NGN",
                "reference" => $referenceId,
                "callback_url" => route('user.approve-paystack-payment'),
                "channels" => ["bank", "ussd", "card"]
            ], $this->headerParams);

            // Decode the response gotten....
            $decodeReserve = json_decode((string) $reserveResult, true);

            if($decodeReserve['status'] === true) {
                $authorizedUrl = $decodeReserve['data']['authorization_url'];

                if (!filter_var($authorizedUrl, FILTER_VALIDATE_URL)) {
                    return $this->sendError('Invalid URL', [], 422);
                }
                return $authorizedUrl;
            }

            // Since Paystack is not permitting this payment request, let's cancel the payment...
            app(WalletService::class)->updateWalletIn("cancel_payment", $referenceId);
            return $this->sendError($decodeReserve['message'], [], 500);
        }
        catch(Exception $e) {
            return $this->sendError("System Error!", [], 400);
        }
    }

    public function ApprovePayment(array $paymentData) {
        try {
            $trxref = $paymentData['trxref'];
            $referenceId = $paymentData['reference'];
            $verifyPayment = $this->verifyPayment($referenceId);

            if($verifyPayment) {
                return app(WalletService::class)->updateWalletIn('approve_payment', $referenceId);
            }
            return $this->sendError("Error approving payment or reference not found", [], 500);
        }
        catch(Exception $e){
            return $this->sendResponse("System Error!", [], 500);
        }
    }

    private function verifyPayment($referenceId) {
        $verifyPayment = HttpRequest::sendGet($this->endpoint."/transaction/verify/".$referenceId, "", [
            "Authorization" => "Bearer ".$this->secretKey,
            "Content-Type" => "application/json",
            "Cache-Control: no-cache",
        ]);

        $decodePayment = json_decode($verifyPayment, true);

        if($decodePayment['status'] === true) {
            if($decodePayment['data']['status'] === "success" AND $decodePayment['data']['reference'] == $referenceId) {
                return true;
            }
            return false;
        }
        return false;
    }

    private function calculateAmount($amount) {
        $sumAmount = $amount;

        if($this->paystackInfo !== false) {
            if($this->chargesType === 'percentage') {
                $percentRate = (float) ($amount * $this->charges)/100;
                $sumAmount = $amount + $percentRate;
            }
            else {
                $sumAmount = (float) $amount + $this->charges;
            }
        }
        return $sumAmount;
    }
}
