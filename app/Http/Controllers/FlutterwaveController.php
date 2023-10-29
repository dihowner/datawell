<?php

namespace App\Http\Controllers;

use App\Services\FlutterwaveService;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\FlutterwaveRequest;

class FlutterwaveController extends Controller
{
    protected $flutterwaveService;
    public function __construct(FlutterwaveService $flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
    }

    public function GenerateFlutterwaveLink($referenceId) {
        $getLink = $this->flutterwaveService->GenerateFlutterwaveLink($referenceId);
        
        if (method_exists($getLink, 'getStatusCode')) {
            $responseContent = json_decode($getLink->content());
            $message = $responseContent->message;
            Alert::error("Error", $message);
            // redirect the user back to funding view page
            return redirect()->route('user.fund-wallet-view');
        }
        return redirect()->away($getLink);
    }

    public function ApprovePayment(FlutterwaveRequest $request) {        
        $approvePayment = $this->flutterwaveService->ApprovePayment($request->validated());
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