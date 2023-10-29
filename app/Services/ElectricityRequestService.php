<?php
namespace App\Services;

use Exception;
use App\Classes\PaginatorHelper;
use App\Http\Traits\ResponseTrait;
use App\Models\ElectricityRequest;

class ElectricityRequestService {

    use ResponseTrait;
    protected $utilityService;
    
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }
    
    public function getElectricityRequest(string $product_id) {
        try {
            $fetchRequest = ElectricityRequest::where("product_id", $product_id)->first();
            if($fetchRequest != NULL) {
                return $fetchRequest; 
            }
            return false; 
        }
        catch(Exception $e) {
           return false;
        }
    }

    public function allElectrictyRequests() {
        $electricityProduct = ElectricityRequest::latest('id')->get();
        
         // Map product image to the result set...
         $electricityProduct->map(function ($products) {
            $products->image_url = $this->utilityService->getProductImage($products->product_id);
            return $products;
        });

        // Create a Paginator instance
        $paginatedRecords = PaginatorHelper::createPaginator($electricityProduct, 20, request()->get('page'), request()->url());
        return $paginatedRecords; 
    } 
    
    public function createElectricityRequest($requestData) {
        try {
            $product_id = $requestData['product_id'];
            $init_code = $requestData['init_code'];
            $wrap_code = $requestData['wrap_code'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            
            $checkRequest = ElectricityRequest::where("product_id", $product_id)->first();

            if($checkRequest != NULL) {
                return $this->sendError("Electricity request code already exist", [], 400);
            }
            ElectricityRequest::create([
                "product_id" => $product_id, "init_code" => $init_code,
                "wrap_code" => $wrap_code, "mobilenig" => $mobilenig
            ]);
            return $this->sendResponse("Electricity request added successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
    public function updateElectricityRequest($requestData) {
        try {
            $requestId = $requestData['id'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            
            $checkRequest = ElectricityRequest::where("id", $requestId)->first();

            if($checkRequest == NULL) {
                return $this->sendError("Electricity request code does not exist", [], 404);
            }
            
            ElectricityRequest::where('id', $requestId)->update([
                "mobilenig" => $mobilenig
            ]);
            return $this->sendResponse("Electricity request updated successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
    public function deleteRequest($id) {
        $findRequest = ElectricityRequest::find($id);
        if($findRequest != NULL) {
            $findRequest->delete();
            return $this->sendResponse("Electricity request deleted successfully", [], 200);
        }
        return $this->sendResponse("Electricity request could not be found", [], 404);
    }
    
}
?>