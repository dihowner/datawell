<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Requests\ApiRequest;
use App\Services\CategoryService;
use RealRashid\SweetAlert\Facades\Alert;

class ApiController extends Controller
{
    protected $apiService, $categoryService, $productService;
    public function __construct(ApiService $apiService, CategoryService $categoryService, ProductService $productService)
    {
        $this->apiService = $apiService;
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }
    
    public function index(ApiService $apiService)
    {
        $allApis = $this->apiService->getAllApi();
        return view('main.api-management', compact('allApis'));
    }

    public function deleteApi($apiId) {
        $deleteApi = $this->apiService->deleteApi($apiId);
        $decodeResponse = json_decode($deleteApi->getContent(), true);
        if($deleteApi->getStatusCode() == 204) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }

    public function createApiView() {
        $allVendors = $this->apiService->getAllVendor();
        return view('main.create-api', compact('allVendors'));
    }

    public function getVendor($vendorId) {
        return $this->apiService->getVendor($vendorId);
    }

    public function getApi($apiId) {
        
        $apiInfo = $this->apiService->getApi($apiId);
         // this is needed because I am working with ajax and I need it to populate
        $apiInfo['allVendors'] = $this->apiService->getAllVendor();
        return $apiInfo;
    }

    public function createApi(ApiRequest $request) {
        $createData = $request->validated();
        $createApi = $this->apiService->createApi($createData);
        $decodeResponse = json_decode($createApi->getContent(), true);
        if($createApi->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->route('api-index');
    }

    public function updateApi(ApiRequest $request) {
        $updateData = $request->validated();
        $updateApi = $this->apiService->updateApi($updateData);
        
        $decodeResponse = json_decode($updateApi->getContent(), true);
        if($updateApi->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->route('api-index');
    }

    public function switchVendors() {
        $allApis = $this->apiService->getAllApi();
        $allCategories = $this->categoryService->allCategoriesSwitch();
        
        // return $allCategories;
        return view('main.api-switch', compact('allApis', 'allCategories'));
    }

    public function updateApiSwitchSettings(ApiRequest $request) {
        $newApiId = $request->validated()['categoryApi'];
        $updateApi = $this->apiService->updateApiSwitchSettings($newApiId);
        
        $decodeResponse = json_decode($updateApi->getContent(), true);
        Alert::success("Success", $decodeResponse['message']);
        return redirect()->back();
    }

    public function testApi() {
        return $this->apiService->testApi();
    }
}