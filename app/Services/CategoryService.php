<?php
namespace App\Services;

use Exception;
use App\Models\Category;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class CategoryService  {
    use ResponseTrait;
    protected $utilityService, $productService, $responseBody;
    
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    
    public function getSubCategories($parentCategory) {
        try {
            $getSubCats = Category::where('parent_category', $parentCategory)->get();
            if(count($getSubCats) > 0) {
                return $getSubCats;
            }
            return $this->sendError("Sub-Category not found", [], 404);
        }
        catch(Exception $e) {
            return $this->sendError("Unexpected error.", $e->getMessage(), 500);
        }
    }

    public function getCategoriesWithoutParent() {
        return DB::table('categories AS c1')
                    ->leftJoin('categories AS c2', 'c1.category_name', '=', 'c2.parent_category')
                    ->whereNull('c2.category_name')
                    ->select('c1.id', 'c1.category_name', 'c1.parent_category')
                    ->get();
    }

    public function getMainCategory() {
        return Category::doesntHave("parent")->orderBy("category_name", "asc")->get();
    }

    public function getCategoryByName($categoryName) {
        return Category::where("category_name", $categoryName)->first();
    }


    public function allCategoriesSwitch() {
        
        $getParentCategories = DB::table('categories AS c1')
                                ->leftJoin('categories AS c2', 'c1.category_name', '=', 'c2.parent_category')
                                ->whereNull('c2.category_name')
                                ->select('c1.id', 'c1.category_name', 'c1.parent_category')
                                ->where('c1.category_name', '!=', 'Airtime Topup')
                                ->get();
        $airtimeCategories = [
            [
                "id" => NULL,
                "category_name" => "MTN",
                "parent_category" => "Airtime Topup"
            ],
            [
                "id" => NULL,
                "category_name" => "Airtel",
                "parent_category" => "Airtime Topup"
            ],
            [
                "id" => NULL,
                "category_name" => "Glo",
                "parent_category" => "Airtime Topup"
            ],
            [
                "id" => NULL,
                "category_name" => "9mobile",
                "parent_category" => "Airtime Topup"
            ]
        ];
        
        $decodeParentCategories = json_decode($getParentCategories, true);

        $mergeCategories = array_merge($decodeParentCategories, $airtimeCategories);

        foreach($mergeCategories as $categoryIndex => $categoryValue) {
            $categoryId = $categoryValue['id'];
            if($categoryId == NULL) {
                $product_name = $categoryValue['category_name']; //Because we hard-coded the list..
                $product = $this->productService->getProductWithCategoryId_ProductName("", $product_name);
            } else {
                $product = $this->productService->getProductWithCategoryId_ProductName($categoryId);
            }

            if ($product != NULL) {
                $mergeCategories[$categoryIndex]["current_api_id"] = $product["api_id"];
            } else {
                $mergeCategories[$categoryIndex]["current_api_id"] = 0;
            }
        }
        
        $allCategories = (object) collect($mergeCategories);

        return $allCategories;
        
    }
}