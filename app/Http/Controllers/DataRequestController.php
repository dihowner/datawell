<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ProductService;
use App\Services\DataRequestService;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\DataPurchaseRequest;

class DataRequestController extends Controller
{
    protected $dataRequestService, $productService;
    public function __construct(DataRequestService $dataRequestService, ProductService $productService)
    {
        $this->dataRequestService = $dataRequestService;
        $this->productService = $productService;
    }
    
    public function dataView()
    {
        $categoryIds = Category::where('category_name', 'like', '%data%')->get()->pluck('id');
        $dataProducts = $this->productService->getProductByMultipleCategory($categoryIds);
        
        $dataRequests = $this->dataRequestService->allDataRequests();;
        
        return view('main.data-request', compact('dataRequests', 'dataProducts'));
    }

    public function deleteRequest($id) {
        $deleteRequest = $this->dataRequestService->deleteRequest($id);
        $decodeResponse = json_decode($deleteRequest->getContent(), true);
        if($deleteRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function createDataRequest(DataPurchaseRequest $request) {
        $createRequest = $this->dataRequestService->createDataRequest($request->validated());
        $decodeResponse = json_decode($createRequest->getContent(), true);
        if($createRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function updateRequest(DataPurchaseRequest $request) {
        $updateRequest = $this->dataRequestService->updateDataRequest($request->validated());
        $decodeResponse = json_decode($updateRequest->getContent(), true);
        if($updateRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
}