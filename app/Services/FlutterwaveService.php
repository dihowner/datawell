<?php
namespace App\Services;

use Exception;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Classes\HttpRequest;

class FlutterwaveService {
    use ResponseTrait;

    private $endpoint = "https://api.flutterwave.com";

    private $flutterwaveInfo, $publicKey, $secretKey, $charges, $chargesType, $headerParams;
    protected $utilityService, $responseBody;
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;

        $this->flutterwaveInfo = $this->utilityService->flutterwaveInfo();
        if($this->flutterwaveInfo !== false) {
            $decodeFlutterwave = json_decode($this->flutterwaveInfo, true);
            $this->publicKey = $decodeFlutterwave['public_key'];
            $this->secretKey = $decodeFlutterwave['secret_key'];
            $this->charges = (float) $decodeFlutterwave['charges'];
            $this->chargesType = $decodeFlutterwave['chargesType'];
            $this->headerParams = [
                "Authorization" => "Bearer ".$this->secretKey,
                "Content-Type" => "application/json"
            ];
        }
    }

    public function GenerateFlutterwaveLink($referenceId) {
        try {

            $theAuthorizedUser = Auth::user();

            $paymentAmount = (float) app(WalletService::class)->viewPaymentHistory('wallet_in', $referenceId)->amount;
            
            // Let's send the request to Flutterwave...
            $reserveResult = HttpRequest::sendPost($this->endpoint."/v3/payments", [
                "amount" => $this->calculateAmount($paymentAmount),
                "currency" => "NGN",
                "tx_ref" => $referenceId,
                "redirect_url" => route('user.approve-flutterwave-payment'),
                "customer" => [
                    "email" => $theAuthorizedUser->emailaddress,
                    "name" => $theAuthorizedUser->fullname,
                    "phonenumber" => $theAuthorizedUser->phone_number
                ]
            ], $this->headerParams);

            // Decode the response gotten....
            $decodeReserve = json_decode((string) $reserveResult, true);

            if($decodeReserve['status'] == "success") {
                $authorizedUrl = $decodeReserve['data']['link'];

                if (!filter_var($authorizedUrl, FILTER_VALIDATE_URL)) {
                    return $this->sendError('Invalid URL', [], 422);
                }
                return $authorizedUrl;
            }

            // Since Flutterwave is not permitting this payment request, let's cancel the payment...
            app(WalletService::class)->updateWalletIn("cancel_payment", $referenceId);
            return $this->sendError($decodeReserve['message'], [], 500);
        }
        catch(Exception $e) {
            return $this->sendError("System Error!", [], 400);
        }
    }

    public function ApprovePayment(array $paymentData) {
        try {

            $status = $paymentData['status'];
            $referenceId = $paymentData['tx_ref'];

            if($status == "cancelled") {
                return app(WalletService::class)->updateWalletIn('cancel_payment', $referenceId);
            }

            $transaction_id = $paymentData['transaction_id'];

            $verifyPayment = $this->verifyPayment($transaction_id, $referenceId);
            
            if($verifyPayment) {
                return app(WalletService::class)->updateWalletIn('approve_payment', $referenceId);
            }
            return $this->sendError("Error approving payment or reference not found", [], 500);
        }
        catch(Exception $e){
            return $this->sendResponse("System Error!", [], 500);
        }
    }

    private function verifyPayment($transaction_id, $referenceId) {
        $verifyPayment = HttpRequest::sendGet($this->endpoint."/v3/transactions/".$transaction_id."/verify", "", [
            "Authorization" => "Bearer ".$this->secretKey,
            "Content-Type" => "application/json",
            "Cache-Control: no-cache",
        ]);

        $decodePayment = json_decode($verifyPayment, true);

        if($decodePayment['status'] === "success") {
            if($decodePayment['data']['status'] === "successful" AND $decodePayment['data']['tx_ref'] == $referenceId) {
                return true;
            }
            return false;
        }
        return false;
    }

    private function calculateAmount($amount) {
        $sumAmount = $amount;

        if($this->flutterwaveInfo !== false) {
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