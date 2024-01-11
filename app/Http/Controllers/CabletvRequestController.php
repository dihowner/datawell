<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ProductService;
use App\Http\Requests\CableTvRequest;
use App\Services\CabletvRequestService;
use RealRashid\SweetAlert\Facades\Alert;

class CabletvRequestController extends Controller
{
    protected $cabletvRequestService, $productService;
    public function __construct(CabletvRequestService $cabletvRequestService, ProductService $productService)
    {
        $this->cabletvRequestService = $cabletvRequestService;
        $this->productService = $productService;
    }

    public function cableTvView()
    {
        $categoryIds = Category::where('category_name', 'like', '%Gotv%')
                        ->orWhere('category_name', 'like', '%Dstv%')
                        ->orWhere('category_name', 'like', '%Startimes%')
                        ->get()->pluck('id');
        
        $cabletvProducts = $this->productService->getProductByMultipleCategory($categoryIds);

        $cabletvRequests = $this->cabletvRequestService->allCabletvRequests();

        return view('main.cabletv-request', compact('cabletvRequests', 'cabletvProducts'));
    }

    public function updateRequest(CableTvRequest $request) {
        $updateRequest = $this->cabletvRequestService->updateCableTvRequest($request->validated());
        $decodeResponse = json_decode($updateRequest->getContent(), true);
        if($updateRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function deleteRequest($id) {
        $deleteRequest = $this->cabletvRequestService->deleteRequest($id);
        $decodeResponse = json_decode($deleteRequest->getContent(), true);
        if($deleteRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }

    public function createCabletvRequest(CableTvRequest $request) {
        $createRequest = $this->cabletvRequestService->createCabletvRequest($request->validated());
        $decodeResponse = json_decode($createRequest->getContent(), true);
        if($createRequest->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->back();
    }
}