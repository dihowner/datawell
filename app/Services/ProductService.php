<?php
namespace App\Services;

use Exception;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPricing;
use App\Classes\PaginatorHelper;
use App\Http\Traits\ResponseTrait;
use App\Models\AirtimeRequest;
use App\Models\Api;
use App\Models\CabletvRequest;
use App\Models\DataRequest;
use App\Models\EducationRequest;
use App\Models\ElectricityRequest;
use App\Models\Plans;
use Illuminate\Support\Facades\DB;

class ProductService {
    use ResponseTrait;

    protected $utilityService;
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }

    public function totalProduct() {
        return Product::count();
    }

    public function searchProduct($product)
    {
        $searchProduct = Product::where('product_name', 'like', '%' . $product . '%')->get();
        
        if($searchProduct != NULL) {
            // Create a Paginator instance
            $paginatedRecords = PaginatorHelper::createPaginator($searchProduct, 20, request()->get('page'), request()->url());
            return $paginatedRecords;
        }
        return $this->sendError("Search query ($product) not found", [], 404);
    }

    public function updateCostPrice(array $ids, array $costPrices, array $productStatus) {

        foreach($costPrices as $costIndex => $costPrice) {
            Product::where("id", $ids[$costIndex])->update(["cost_price"=> $costPrice, "availability"=> $productStatus[$costIndex]]);
        }
        return $this->sendResponse("Product updated successfully", [], 200);
    }
    
    public function getAllProductByCategory($categoryId) {
        $category = Category::find($categoryId);

        $subCategories = Category::where("parent_category", $category->category_name)->get();

        if(count($subCategories) > 0) {
            $categoryId = $subCategories->pluck("id");
        } else {
            $categoryId = [$categoryId];
        }

        $products = Product::whereIn("category_id", $categoryId)->orderBy('product_name', 'asc')->get();
        
        // Map product image to the result set...
        $products->map(function ($products) {
            $products->image_url = $this->utilityService->getProductImage($products->product_name);
            return $products;
        });

        return $products;
    }

    public function getProductByMultipleCategory($categoryId) {
        
        $products = Product::whereIn("category_id", $categoryId)->orderBy('product_name', 'asc')->get();
        
        // Map product image to the result set...
        $products->map(function ($products) {
            $products->image_url = $this->utilityService->getProductImage($products->product_name);
            return $products;
        });

        return $products;
    }

    public function createProduct($productName, $productId, $categoryId, $costPrice) {
        try {
            // Start a database transaction
            DB::beginTransaction();

            Product::create([
                "product_name" => $productName,
                "product_id" => strtolower($productId),
                "category_id" => $categoryId,
                "cost_price" => $costPrice,
                "availability" => "1",
                "api_id" => Api::first()->id
            ]);

            $allPlans = Plans::all();
            if(count($allPlans) > 0) {
                foreach($allPlans as $plan) {
                    ProductPricing::create([
                        "product_id" => $productId,
                        "plan_id" => $plan->id,
                        "selling_price" => (float) 0,
                        "extra_charges" => (float) 0,
                    ]);
                }
            }
            DB::commit();
            return $this->sendResponse("Product ($productName) created successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            DB::rollback();
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 400);
        }
    }

    public function deleteProduct($id) {
        try {
            $findProduct = Product::find($id);
            if($findProduct != NULL) {
                
                // Start a database transaction
                DB::beginTransaction();
                
                $productId = $findProduct->product_id;
                
                /**
                 * If parent category is null, then consider category name as main category to use 
                 * in deleting the data off from other related table not using foregin key
                 */
                $category =  $findProduct->category->parent_category == NULL ? $findProduct->category : $findProduct->category->parent_category;
                self::deleteProductRequestCode($productId, $category); #delete product vendor request codes...
                ProductPricing::where("product_id", $productId)->delete(); #delete product pricing...
                $findProduct->delete();
                
                DB::commit();
                return $this->sendResponse("Product deleted successfully", [], 200);
            }
            return $this->sendError("Product could not be found", [], 404);
        }
        catch(Exception $e) {
            DB::rollBack();
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 400);
        } 
    }

    private function deleteProductRequestCode($productId, $category) {
        $category = strtolower($category);
        if(strpos($category,"airtime") !== false) {
            return AirtimeRequest::where("product_id", $productId)->delete();
        }
        else if(strpos($category,"electricity") !== false) {
            return ElectricityRequest::where("product_id", $productId)->delete();
        }
        else if(strpos($category,"data") !== false) {
            return DataRequest::where("product_id", $productId)->delete();
        }
        else if(in_array($category, ['dstv', 'gotv', 'startimes'])) {
            return CabletvRequest::where("product_id", $productId)->delete();
        }
        else if(in_array($category, ['neco', 'waec'])) {
            return EducationRequest::where("product_id", $productId)->delete();
        }
    }

    public function allProducts() {
        $products = Product::orderByDesc("id")->get();

        if(count($products) > 0) {
            // Map product image to the result set...
            $products->map(function ($products) {
                $products->image_url = $this->utilityService->getProductImage($products->product_name);
                return $products;
            });
            $paginatedRecords = PaginatorHelper::createPaginator($products, 20, request()->get('page'), request()->url());
            return $paginatedRecords;
        }

        return false;
    }

    // Get a single product
    public function getProductById($productId) {
        try {
            $getProduct = Product::with('category', 'api.vendor')->where('product_id', $productId)->first();
            if($getProduct == NULL) {
                return false;
            }
            return $getProduct;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // Get a product with Like condition...
    public function getProductWithLike($keyword) {
        try {
            $getProduct = Product::with('category', 'api.vendor')->where('product_name', 'like', '%' . $keyword . '%')
                                    ->orWhere('product_id', 'like', '%' . $keyword . '%')->first();
            if($getProduct == NULL) {
                return false;
            }
            return $getProduct;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // Get all product in product pricing table with plan id or category
    public function getAllProductPriceWithPlanAndCategory($planId, $category = '') {
        try {
            if($category == '') {
                $products = Product::with('productpricing')->whereHas('productpricing', function ($query) use ($planId) {
                                $query->where('plan_id', $planId);
                            })->orderBy('id')->get();
            }
            else {
                // Get the category id because we are sending category name...
                $categoryId = Category::where('category_name', $category)->pluck('id');
                if(count($categoryId) == 0) {
                    return $this->sendError("Product with category ($category) does not exist", [], 404);
                }

                $products = Product::with('productpricing')->where('category_id', $categoryId)->whereHas('productpricing', function ($query) use ($planId) {
                    $query->where('plan_id', $planId);
                })->orderBy('id')->get();
            }

            if(count($products) == 0) {
                return false;
            }

            // Map product image to the result set...
            $products->map(function ($products) {
                $products->image_url = $this->utilityService->getProductImage($products->product_name);
                return $products;
            });

            return $products;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // get all product pricing with plan id without plan information...
    public function getProductPriceWithPlan($planId) {
        try {
            // Get All product in a plan...
            $products = ProductPricing::whereHas('plan', function($query) use ($planId) {
                $query->where('plan_id', $planId);
            })->get();

            if(count($products) == 0) {
                return false;
            }
            return $products;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function getProductWithCategoryId_ProductName($categoryId = "", $productName = "") {
        if($categoryId != "") {
            $products = Product::where("category_id", $categoryId)->first();
        } else {
            $products = Product::where("product_name", $productName)->first();
        }
        

        return $products;
    }
    
}