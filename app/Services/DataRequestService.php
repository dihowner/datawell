<?php
namespace App\Services;

use Exception;
use App\Models\DataRequest;
use App\Classes\PaginatorHelper;
use App\Http\Traits\ResponseTrait;

class DataRequestService {

    use ResponseTrait;
    protected $utilityService;
    
    public function __construct(UtilityService $utilityService) {
        $this->utilityService = $utilityService;
    }
    
    public function getDataRequest(string $product_id) {
        try {
            $fetchRequest = DataRequest::where("product_id", $product_id)->first();
            if($fetchRequest != NULL) {
                return $fetchRequest; 
            }
            return false; 
        }
        catch(Exception $e) {
           return false;
        }
    }
    
    public function allDataRequests() {
        $cabletvProduct = DataRequest::latest('id')->get();
        
         // Map product image to the result set...
         $cabletvProduct->map(function ($products) {
            $products->image_url = $this->utilityService->getProductImage($products->product_id);
            return $products;
        });

        // Create a Paginator instance
        $paginatedRecords = PaginatorHelper::createPaginator($cabletvProduct, 20, request()->get('page'), request()->url());
        return $paginatedRecords;
    }  
    
    public function deleteRequest($id) {
        $findRequest = DataRequest::find($id);
        if($findRequest != NULL) {
            $findRequest->delete();
            return $this->sendResponse("Data Bundle request deleted successfully", [], 200);
        }
        return $this->sendResponse("Data Bundle request could not be found", [], 404);
    }    
    
    public function createDataRequest($requestData) {
        try {
            $product_id = $requestData['product_id'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            $init_code = isset($requestData['init_code']) ? $requestData['init_code'] : NULL;
            $wrap_code = isset($requestData['wrap_code']) ? $requestData['wrap_code'] : NULL;
            
            $checkRequest = DataRequest::where("product_id", $product_id)->first();

            if($checkRequest != NULL) {
                return $this->sendError("Data Bundle request code already exist", [], 400);
            }
            DataRequest::create([
                "product_id" => $product_id, "init_code" => $init_code, 
                "wrap_code" => $wrap_code, "mobilenig" => $mobilenig
            ]);
            return $this->sendResponse("Data Bundle request added successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
    public function updateDataRequest($requestData) {
        try {
            $requestId = $requestData['id'];
            $mobilenig = isset($requestData['mobilenig']) ? $requestData['mobilenig'] : NULL;
            $smeplug = isset($requestData['smeplug']) ? $requestData['smeplug'] : NULL;
            $init_code = isset($requestData['init_code']) ? $requestData['init_code'] : NULL;
            $wrap_code = isset($requestData['wrap_code']) ? $requestData['wrap_code'] : NULL;
            
            $checkRequest = DataRequest::where("id", $requestId)->first();

            if($checkRequest == NULL) {
                return $this->sendError("Data Bundle request code does not exist", [], 404);
            }
            
            DataRequest::where('id', $requestId)->update([
                "init_code" => $init_code, 
                "wrap_code" => $wrap_code,
                "mobilenig" => $mobilenig,
                "smeplug" => $smeplug
            ]);
            return $this->sendResponse("Data Bundle request updated successfully", [], 200);
        }
        catch(Exception $e) {
            // Handle the exception
            return $this->sendError("Error! Message: ".$e->getMessage(), [], 500);
        }        
    }
    
}
?>