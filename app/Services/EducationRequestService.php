<?php
namespace App\Services;

use Exception;
use App\Http\Traits\ResponseTrait;
use App\Models\EducationRequest;

class EducationRequestService {

    use ResponseTrait;
    protected $utilityService;
    
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }
    
    public function getEducationRequest(string $product_id) {
        try {
            $fetchRequest = EducationRequest::where("product_id", $product_id)->first();
            if($fetchRequest != NULL) {
                return $fetchRequest; 
            }
            return false; 
        }
        catch(Exception $e) {
           return false;
        }
    }
    
    public function allEducationRequests() {
        $educationProduct = EducationRequest::latest('id')->get();
        
         // Map product image to the result set...
         $educationProduct->map(function ($products) {
            $products->image_url = $this->utilityService->getProductImage($products->product_id);
            return $products;
        });
        return $educationProduct;
    }    
    
    public function creatEducationRequest($requestData) {
        try {
            $product_id = $requestData['product_id'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            
            $checkRequest = EducationRequest::where("product_id", $product_id)->first();

            if($checkRequest != NULL) {
                return $this->sendError("Education request code already exist", [], 400);
            }
            EducationRequest::create([
                "product_id" => $product_id, "mobilenig" => $mobilenig
            ]);
            return $this->sendResponse("Education request added successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
    public function deleteRequest($id) {
        $findRequest = EducationRequest::find($id);
        if($findRequest != NULL) {
            $findRequest->delete();
            return $this->sendResponse("Education request deleted successfully", [], 200);
        }
        return $this->sendResponse("Education request could not be found", [], 404);
    }
    
    public function updateEducationRequest($requestData) {
        try {
            $requestId = $requestData['id'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            
            $checkRequest = EducationRequest::where("id", $requestId)->first();

            if($checkRequest == NULL) {
                return $this->sendError("Education request code does not exist", [], 404);
            }
            
            EducationRequest::where('id', $requestId)->update([
                "mobilenig" => $mobilenig
            ]);
            return $this->sendResponse("Education request updated successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
}
?>