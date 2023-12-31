<?php
namespace App\Services;

use Exception;
use App\Models\Plans;
use App\Models\Product;
use App\Classes\PaginatorHelper;
use App\Http\Traits\ResponseTrait;
use App\Models\ProductPricing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PlanService {
    use ResponseTrait;

    protected $utilityService;
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }

    public function totalPlan() {
        return Plans::count();
    }

    public function getAllPlan() {
        return Plans::withCount('product_pricing')->latest("id")->paginate(20);
    }

    public function getPlanByName($planName) {
        try {
            $getPlan = Plans::where("plan_name", $planName)->first();

            if($getPlan != NULL) {
                return $this->getPlan($getPlan->id);
            }
            return false;
        }
        catch(Exception $e) {
            return false;
        }
    }

    public function getPlan($planId) {
        try {
            $getPlan = Plans::where("id", $planId)->first();

            if($getPlan != NULL) {
                return $getPlan;
            }
            return $this->sendError("Error! Plan does not exist or not found", [], 404);
        }
        catch(Exception $e) {
            return false;
        }
    }

    public function deletePlan($id) {
        if($this->utilityService->defaultPlanId() == $id) {
            return $this->sendError("Default plan can not be deleted", [], 400);
        }
        
        $findPlan = Plans::find($id);
        if($findPlan) {
            $findPlan->delete();
            return $this->sendResponse("Plan ($findPlan->plan_name) and Product Pricing deleted successfully", [], 204);
        } 
        return $this->sendError("Error! Plan does not exist or not found", [], 404);
    }

    public function updatePlan($planData , $id)
    {
        $findPlan = Plans::find($id);
        if($findPlan) {
            $planRemark = json_decode($findPlan->remarks, true);
            $findPlan->plan_name = $planData["plan_name"];
            $findPlan->amount = (float) $planData["upgrade_fee"];
            $findPlan->plan_description = $planData["plan_description"];
            $findPlan->remarks = json_encode(["created_by" => $planRemark["created_by"], "updated_by" => Auth::guard('admin')->user()->fullname]);
            if($findPlan->update()) {
                return $this->sendResponse("Plan updated successfully", [], 200);
            }
            return $this->sendError("Error updating plan", [], 400);
        }
        return $this->sendError("Error! Plan does not exist or not found", [], 404);
    }

    public function SearchPlan($planName)
    {
        $searchPlan = Plans::where('plan_name', 'like', '%' . $planName . '%')->get();

        if($searchPlan != NULL) {
            // Create a Paginator instance
            $paginatedRecords = PaginatorHelper::createPaginator($searchPlan, 20, request()->get('page'), request()->url());
            return $paginatedRecords;
        }
        return $this->sendError("Search query ($planName) not found", [], 404);
    }

    public function createPlan($planName, $planAmount, $planDescription) {
        try {
            // Start a database transaction
            DB::beginTransaction();
            
            $createPlan = Plans::create([
                "plan_name" => $planName,
                "amount" => (float) $planAmount,
                "plan_description" => $planDescription,
                "remarks" => json_encode(["created_by" => Auth::guard('admin')->user()->fullname])
            ]);
            
            // Get all products
            $allProducts = Product::all();
            if(count($allProducts) > 0) {
                foreach($allProducts as $product) {
                    ProductPricing::create([
                        "product_id" => $product["product_id"],
                        "plan_id" => $createPlan->id,
                        "selling_price" => (float) 0,
                        "extra_charges" => (float) 0,
                    ]); 
                }                
            }
            DB::commit();
            return $this->sendResponse("Plan ($planName) created successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            DB::rollback();
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 400);
        }        
    }

    public function getPlanProducts($planId) {
        $products = ProductPricing::with('product.category')->where('plan_id', $planId)->get();
    
        // Map product image to the result set...
        $products->transform(function ($product) {
            $productName = isset($product->product->product_name) ? $product->product->product_name : null;
            $product->image_url = $productName ? $this->utilityService->getProductImage($productName) : null;
    
            return $product;
        });
    
        // Filter out null products
        $products = $products->filter(function ($product) {
            return !is_null($product->image_url);
        });
    
        return $products;
    }
    

}