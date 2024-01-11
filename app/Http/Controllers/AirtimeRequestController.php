<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\AirtimeRequest;
use App\Services\AirtimeRequestService;
use RealRashid\SweetAlert\Facades\Alert;
use App\Services\ProductService;

class AirtimeRequestController extends Controller
{
    protected $airtimeRequestService, $productService;
    public function __construct(AirtimeRequestService $airtimeRequestService, ProductService $productService)
    {
        $this->airtimeRequestService = $airtimeRequestService;
        $this->productService = $productService;
    }

    public function airtimeView()
    {
        $categoryId = Category::where("category_name", "Airtime Topup")->first()->id;
        $airtimeProducts = $this->productService->getAllProductByCategory($categoryId);

        $airtimeRequests = $this->airtimeRequestService->allAiritmeRequests();

        return view('main.airtime-request', compact('airtimeRequests', 'airtimeProducts'));
    }

    public function deleteRequest($id) {
        $deleteRequest = $this->airtimeRequestService->deleteRequest($id);
        $decodeResponse = json_decode($deleteRequest->getContent(), true);
        if($deleteRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function createAirtimeRequest(AirtimeRequest $request) {
        $createRequest = $this->airtimeRequestService->createAirtimeRequest($request->validated());
        $decodeResponse = json_decode($createRequest->getContent(), true);
        if($createRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function updateRequest(AirtimeRequest $request) {
        $updateRequest = $this->airtimeRequestService->updateAirtimeRequest($request->validated());
        $decodeResponse = json_decode($updateRequest->getContent(), true);
        if($updateRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
    
}