<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ProductService;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\ElectricityRequest;
use App\Services\ElectricityRequestService;

class ElectricityRequestController extends Controller
{
    
    protected $electricityRequestService, $productService;
    public function __construct(ElectricityRequestService $electricityRequestService, ProductService $productService)
    {
        $this->electricityRequestService = $electricityRequestService;
        $this->productService = $productService;
    }

    public function electricityView()
    {
        $categoryId = Category::where("category_name", "Electricity")->first()->id;
        $electricityProducts = $this->productService->getAllProductByCategory($categoryId);

        $electricityRequests = $this->electricityRequestService->allElectrictyRequests();

        return view('main.electricity-request', compact('electricityRequests', 'electricityProducts'));
    }

    public function createElectricityRequest(ElectricityRequest $request) {
        $createRequest = $this->electricityRequestService->createElectricityRequest($request->validated());
        $decodeResponse = json_decode($createRequest->getContent(), true);
        if($createRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function updateRequest(ElectricityRequest $request) {
        $updateRequest = $this->electricityRequestService->updateElectricityRequest($request->validated());
        $decodeResponse = json_decode($updateRequest->getContent(), true);
        if($updateRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function deleteRequest($id) {
        $deleteRequest = $this->electricityRequestService->deleteRequest($id);
        $decodeResponse = json_decode($deleteRequest->getContent(), true);
        if($deleteRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
}