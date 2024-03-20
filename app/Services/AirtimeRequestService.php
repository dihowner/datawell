<?php
namespace App\Services;

use Exception;
use App\Models\AirtimeRequest;
use App\Http\Traits\ResponseTrait;

class AirtimeRequestService {

    use ResponseTrait;
    protected $utilityService;
    
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }
    
    public function deleteRequest($id) {
        $findRequest = AirtimeRequest::find($id);
        if($findRequest != NULL) {
            $findRequest->delete();
            return $this->sendResponse("Airtime request deleted successfully", [], 200);
        }
        return $this->sendResponse("Airtime request could not be found", [], 404);
    }

    public function allAiritmeRequests() {
        $airtimeProduct = AirtimeRequest::latest('id')->get();
        
         // Map product image to the result set...
         $airtimeProduct->map(function ($products) {
            $products->image_url = $this->utilityService->getProductImage($products->product_id);
            return $products;
        });
        return $airtimeProduct;  
    } 
    
    public function getAirtimeRequest(string $product_id) {
        try {
            $fetchRequest = AirtimeRequest::where("product_id", $product_id)->first();
            if($fetchRequest != NULL) {
                return $fetchRequest; 
            }
            return false; 
        }
        catch(Exception $e) {
            return false;
        }
    }
    
    public function createAirtimeRequest($requestData) {
        try {
            $product_id = $requestData['product_id'];
            $init_code = $requestData['init_code'];
            $wrap_code = $requestData['wrap_code'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            
            $checkRequest = AirtimeRequest::where("product_id", $product_id)->first();

            if($checkRequest != NULL) {
                return $this->sendError("Airtime request code already exist", [], 400);
            }
            AirtimeRequest::create([
                "product_id" => $product_id, "init_code" => $init_code,
                "wrap_code" => $wrap_code, "mobilenig" => $mobilenig
            ]);
            return $this->sendResponse("Airtime request added successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
    public function updateAirtimeRequest($requestData) {
        try {
            $requestId = $requestData['id'];
            $init_code = $requestData['init_code'];
            $wrap_code = $requestData['wrap_code'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            $smeplug = isset($requestData['smeplug']) ? $requestData['smeplug'] : NULL;
            
            $checkRequest = AirtimeRequest::where("id", $requestId)->first();

            if($checkRequest == NULL) {
                return $this->sendError("Airtime request code does not exist", [], 404);
            }
            
            AirtimeRequest::where('id', $requestId)->update([
                "init_code" => $init_code, "wrap_code" => $wrap_code, 
                "mobilenig" => $mobilenig, "smeplug" => $smeplug
            ]);
            return $this->sendResponse("Airtime request updated successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }

}
?>