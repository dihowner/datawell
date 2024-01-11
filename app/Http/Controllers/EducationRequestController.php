<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ProductService;
use App\Http\Requests\EducationRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Services\EducationRequestService;

class EducationRequestController extends Controller
{
    protected $educationRequestService, $productService;
    public function __construct(EducationRequestService $educationRequestService, ProductService $productService)
    {
        $this->educationRequestService = $educationRequestService;
        $this->productService = $productService;
    }

    public function educationView()
    {
        $categoryIds = Category::whereIn("category_name", ["Waec", "Neco"])->get()->pluck('id');
        $educationProducts = $this->productService->getProductByMultipleCategory($categoryIds);

        $educationRequests = $this->educationRequestService->allEducationRequests();

        return view('main.education-request', compact('educationRequests', 'educationProducts'));
    }

    public function createEducationRequest(EducationRequest $request) {
        $createRequest = $this->educationRequestService->creatEducationRequest($request->validated());
        $decodeResponse = json_decode($createRequest->getContent(), true);
        if($createRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function deleteRequest($id) {
        $deleteRequest = $this->educationRequestService->deleteRequest($id);
        $decodeResponse = json_decode($deleteRequest->getContent(), true);
        if($deleteRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function updateRequest(EducationRequest $request) {
        $updateRequest = $this->educationRequestService->updateEducationRequest($request->validated());
        $decodeResponse = json_decode($updateRequest->getContent(), true);
        if($updateRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
}