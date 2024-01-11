<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PricingRequest;
use App\Http\Requests\ProductRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\ReferenceIDRequest;

class ProductController extends Controller
{
    use ResponseTrait;
    protected $productService, $categoryService;
    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    public function allProducts() {
        $products = $this->productService->allProducts();
        $allProduct = $products[0];
        $totalProduct = $products[1];
        return view('main.product-list', compact('allProduct', 'totalProduct'));
    }

    public function fetchProductCategory($category) {
        if(Auth::check()) {
            $user = Auth::user();
            return $this->productService->getAllProductPriceWithPlanAndCategory($user['plan_id'], $category);
        } else {
            return $this->sendResponse("Unauthorized Access", [], 401);
        }
    }

    public function createProductView() {
        $allCategories = $this->categoryService->getCategoriesWithoutParent();
        return view('main.create-product', compact('allCategories'));
    }

    public function deleteProduct(ProductRequest $request)
    {
        $deleteProduct = $this->productService->deleteProduct($request->validated()['id']);
        
        $decodeResponse = json_decode($deleteProduct->getContent(), true);
        if($deleteProduct->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->route('product-list');
    }

    public function createProduct(ProductRequest $request)
    {
        $productData = $request->validated();
        Arr::forget($productData, "id");

        $productName = $productData["product_name"];
        $productId = $productData["product_id"];
        $categoryId = $productData["category_id"];
        $costPrice = $productData["cost_price"];
        $createProduct = $this->productService->createProduct($productName, $productId, $categoryId, $costPrice);

        $decodeResponse = json_decode($createProduct->getContent(), true);
        if($createProduct->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", $decodeResponse['message'])->autoClose(10000);
        }
        return redirect()->route('product-list');
    }

    public function editProductView() {
        $allCategories = $this->categoryService->getMainCategory();
        $products = [];
        return view('main.edit-product', compact('allCategories', 'products'));
    }

    public function getAllProductByCategory(ReferenceIDRequest $request) {
        $categoryId = $request->validated()["category_id"];
        $products = $this->productService->getAllProductByCategory($categoryId);
        $allCategories = $this->categoryService->getMainCategory();
        return view('main.edit-product', compact('allCategories','products'));
    }

    public function editCostPrice(PricingRequest $request)
    {
        $pricingData = $request->validated();
        $id = $pricingData["id"];
        $costPrices = $pricingData["costPrice"];
        $availability = $pricingData["availability"];
        
        $updateCostPrice = $this->productService->updateCostPrice($id, $costPrices, $availability);
        
        $decodeResponse = json_decode($updateCostPrice->getContent(), true);
        if($updateCostPrice->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", "Bad Request");
        }
        return redirect()->route('product-list');
    }

    public function searchProduct(Request $request) {
        $product = $request->input('query');
        $allProduct = $this->productService->searchProduct($product);

        return view('main.product-list', compact("allProduct"));
    }
}