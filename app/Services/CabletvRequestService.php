<?php
namespace App\Services;

use Exception;
use App\Models\CabletvRequest;
use App\Classes\PaginatorHelper;
use App\Http\Traits\ResponseTrait;

class CabletvRequestService {

    use ResponseTrait;
    protected $utilityService;
    
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }
    
    public function getCabletvRequest(string $product_id) {
        try {
            $fetchRequest = CableTvRequest::where("product_id", $product_id)->first();
            if($fetchRequest != NULL) {
                return $fetchRequest; 
            }
            return false;
        }
        catch(Exception $e) {
           return false;
        }
    }
    
    public function allCabletvRequests() {
        $cabletvProduct = CableTvRequest::latest('id')->get();
        
         // Map product image to the result set...
         $cabletvProduct->map(function ($products) {
            $products->image_url = $this->utilityService->getProductImage($products->product_id);
            return $products;
        });

        // Create a Paginator instance
        $paginatedRecords = PaginatorHelper::createPaginator($cabletvProduct, 20, request()->get('page'), request()->url());
        return $paginatedRecords;
    }  
    
    public function updateCableTvRequest($requestData) {
        try {
            $requestId = $requestData['id'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            
            $checkRequest = CableTvRequest::where("id", $requestId)->first();

            if($checkRequest == NULL) {
                return $this->sendError("Cable TV request code does not exist", [], 404);
            }
            
            CableTvRequest::where('id', $requestId)->update([
                "mobilenig" => $mobilenig
            ]);
            return $this->sendResponse("Cable TV request updated successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
    public function deleteRequest($id) {
        $findRequest = CableTvRequest::find($id);
        if($findRequest != NULL) {
            $findRequest->delete();
            return $this->sendResponse("Cable TV request deleted successfully", [], 200);
        }
        return $this->sendResponse("Cable TV request could not be found", [], 404);
    }   
    
    public function createCabletvRequest($requestData) {
        try {
            $product_id = $requestData['product_id'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            
            $checkRequest = CableTvRequest::where("product_id", $product_id)->first();

            if($checkRequest != NULL) {
                return $this->sendError("Cable TV request code already exist", [], 400);
            }
            CableTvRequest::create([
                "product_id" => $product_id, "mobilenig" => $mobilenig
            ]);
            return $this->sendResponse("Cable TV request added successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
}
?>