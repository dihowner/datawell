<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Services\UserService;
use App\Http\Requests\PlanRequest;
use App\Services\ProductPricingService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PlansController extends Controller
{
    protected $planService, $userService, $categoryService, $productPricingService, $responseBody;

    public function __construct(PlanService $planService, CategoryService $categoryService, ProductPricingService $productPricingService, UserService $userService) {
        $this->planService = $planService;
        $this->categoryService = $categoryService;
        $this->productPricingService = $productPricingService;
        $this->userService = $userService;
    }

    public function UpgradePlanView()
    {
        $userDetail = $this->userService->getUserById(Auth::id());
        $allPlans = $this->planService->getAllPlan();
        return view('private.upgrade-plan', compact('userDetail', 'allPlans'));
    }

    public function allPlans() {
        try {
            $allPlans = $this->planService->getAllPlan();
            // return $allPlans;
            return view('main.plan-mgt', compact('allPlans'));
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function deletePlan($planId)
    {
        $deletePlan = $this->planService->deletePlan($planId);
        $decodeResponse = json_decode($deletePlan->getContent(), true);
        if($deletePlan->getStatusCode() == 204) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }

    public function updatePlan(PlanRequest $request, $id)
    {
        $planData =  $request->validated();
        $updatePlan = $this->planService->updatePlan($planData , $id);
        $decodeResponse = json_decode($updatePlan->getContent(), true);
        if($updatePlan->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }

    public function SearchPlan(Request $request) {
        $plan = $request->input('query');
        $allPlans = $this->planService->SearchPlan($plan);

        return view('main.plan-mgt', compact("allPlans"));
    }

    public function createPlanView() {
        return view('main.create-plan');
    }

    public function createPlan(PlanRequest $request) {
        $validatedRequest = $request->validated();
        Arr::forget($validatedRequest, "id"); //Not part of the request body, will be inclusive if it's updating...
        
        // Create insert data variable
        $planName = $validatedRequest['plan_name'];
        $planAmount = $validatedRequest['upgrade_fee'];
        $planDescription = $validatedRequest['plan_description'];
        
        $createPlan = $this->planService->createPlan($planName, $planAmount, $planDescription);
        
        $decodeResponse = json_decode($createPlan->getContent(), true);
        if($createPlan->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->route('planlist');
    }
    
    public function getPlanProducts($planId) {
        $planProducts = $this->planService->getPlanProducts($planId);
        // return $planProducts;
        return view('main.sellingprice', compact("planProducts"));
    }

    public function updateProductPricing(Request $request, $planId) {
        
        $productId = $request['productId'];
        $sellingPrice = $request['sellingPrice'];
        $extraCharges = $request['extraCharges'];

        $updatePrice = $this->productPricingService->updateProductPricing($planId, $productId, $sellingPrice, $extraCharges);
        Alert::success("Success", "Price updated successfully");
        
        $planProducts = $this->planService->getPlanProducts($planId);
        return redirect()->back();
    }

}