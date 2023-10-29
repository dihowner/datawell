<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaystackService;
use App\Http\Requests\PaystackRequest;
use RealRashid\SweetAlert\Facades\Alert;

class PaystackController extends Controller
{
    protected $paystackService;
    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    public function GeneratePaystackLink($referenceId) {
        $getLink = $this->paystackService->GeneratePaystackLink($referenceId);

        if (method_exists($getLink, 'getStatusCode') AND $getLink->getStatusCode() != 200) {
            $responseContent = json_decode($getLink->content());
            $message = $responseContent->message;
            Alert::error("Error", $message);
            // redirect the user back to funding view page
            return redirect()->route('user.fund-wallet-view');
        }
        return redirect()->away($getLink);
    }

    public function ApprovePayment(PaystackRequest $request) {
        $approvePayment = $this->paystackService->ApprovePayment($request->validated());
        $responseCode = $approvePayment->getStatusCode();
        $responseContent = json_decode($approvePayment->content());
        $message = $responseContent->message;
        if($responseCode === 200) {
            Alert::success("Success", $message);
        } else {
            Alert::error("Error", $message);
        }
        return redirect()->route('user.fund-wallet-view');
    }
}