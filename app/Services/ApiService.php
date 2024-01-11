<?php
namespace App\Services;

use Exception;
use App\Models\Api;
use App\Models\Vendor;
use App\Models\Product;
use App\Http\Traits\ResponseTrait;

class ApiService  {
    use ResponseTrait;
    protected $utilityService, $categoryService, $responseBody;
    
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    
    public function getAllApi() {
        try {
            $allAPi = Api::with('vendor')->latest('id')->get();
            return $allAPi;
        } catch(Exception $e) {
            return $this->sendError("Error! ".$e->getMessage(), [], 500);
        }
    }
    
    public function getApi($apiId) {
        try {
            $getAPi = Api::with('vendor')->where('id', $apiId)->first();
            return $getAPi;
        } catch(Exception $e) {
            return $this->sendError("Error! ".$e->getMessage(), [], 500);
        }
    }

    public function deleteApi($apiId) {
        $findApi = Api::find($apiId);
        if($findApi) {
            $findApi->delete();
            return $this->sendResponse("API ($findApi->api_name) deleted successfully. Service relying on this API won't be available for purchase", [], 204);
        } 
        return $this->sendError("Error! API does not exist or not found", [], 404);
    }
    
    public function getAllVendor() {
        try {
            $allVendors = Vendor::latest('id')->get();
            return $allVendors;
        } catch(Exception $e) {
            return $this->sendError("Error! ".$e->getMessage(), [], 500);
        }
    }
    
    public function getVendor($vendorId) {
        try {
            $getVendor = Vendor::where('id', $vendorId)->first();
            if($getVendor == NULL) {            
                return $this->sendError("Vendor could not be found", [], 404);
            }
            return $getVendor;
        } catch(Exception $e) {
            return $this->sendError("Error! ".$e->getMessage(), [], 500);
        }
    }

    public function createApi($createData) {
        try {
            $apiName = $createData['api_name'];
            $createApi = Api::create([
                'api_name' => $apiName,
                'api_vendor_id' => $createData['vendor_id'],
                'api_username' => array_key_exists("api_username", $createData) ? $createData['api_username'] : NULL,
                'api_password' => array_key_exists("api_password", $createData) ? $createData['api_password'] : NULL,
                'api_public_key' => array_key_exists("api_public_key", $createData) ? $createData['api_public_key'] : NULL,
                'api_private_key' => array_key_exists("api_private_key", $createData) ? $createData['api_private_key'] : NULL,
                'api_secret_key' => array_key_exists("api_secret_key", $createData) ? $createData['api_secret_key'] : NULL,
            ]);

            if(!$createApi) {
                return $this->sendError("Error creating API", [], 400);
            }
            return $this->sendResponse("API ($apiName) created successfully", [], 200);
        }
        catch(Exception $e) {
            return $this->sendError("Error! ".$e->getMessage(), [], 500);
        }
    }

    public function updateApi($updateData) {

        $findApi = Api::find($updateData['apiId']);
        if($findApi) {
            $findApi->api_name = $updateData["api_name"];
            $findApi->api_vendor_id = $updateData["vendor_id"];
            $findApi->api_username = array_key_exists("api_username", $updateData) ? $updateData['api_username'] : NULL;
            $findApi->api_password = array_key_exists("api_password", $updateData) ? $updateData['api_password'] : NULL;
            $findApi->api_public_key = array_key_exists("api_public_key", $updateData) ? $updateData['api_public_key'] : NULL;
            $findApi->api_private_key = array_key_exists("api_private_key", $updateData) ? $updateData['api_private_key'] : NULL;
            $findApi->api_secret_key = array_key_exists("api_secret_key", $updateData) ? $updateData['api_secret_key'] : NULL;
            $findApi->api_delivery_route = $updateData['api_delivery_route'];
            if($findApi->update()) {
                return $this->sendResponse("API updated successfully", [], 200);
            }
            return $this->sendError("Error updating API", [], 400);
        }
        return $this->sendError("Error! API does not exist or not found", [], 404);
    }

    public function updateApiSwitchSettings(array $categoryApi) {
        $newApiId = $categoryApi;
        $allCategories = $this->categoryService->allCategoriesSwitch();

        $updateApiCount = 0;

        for($i = 0; $i < count($newApiId); $i++) {            
            $category = $allCategories[$i];
            
            // So as to limit what we are updating, let's just update those that Admin wishes to change only...
            if($category['current_api_id'] != $newApiId[$i]) {
                if($category['id'] == NULL) { //Because I created those category and they are a Product on their own
                    $categoryName = strtolower($category['category_name']);
                    if(strpos($categoryName, '9mobile') !== false AND strpos($categoryName, 'data') !== false) {
                        $categoryId = $this->categoryService->getCategoryByName('Data Bundle')->id;
                        Product::where(['category_id' => $categoryId])->where('product_name', 'like', '%9mobile%')->update(["api_id" => $newApiId[$i]]);
                    } else {
                        Product::where(["product_name" => $category['category_name']])->update(["api_id" => $newApiId[$i]]);
                    }
                }
                else {
                    Product::where(["category_id" => $category['id']])->update(['api_id' => $newApiId[$i]]);
                }  
                $updateApiCount++; 
            }
        }
        
        if($updateApiCount > 0) {
            return $this->sendResponse($updateApiCount. " API(s) were updated successfully", [], 200); 
        }
        return $this->sendResponse("Nothing to update", [], 200);
    }

    public function testApi() {
        $data = [
            "product" => "mtn",
            "category" => "airtime",
            "ignoreCron" => true
        ];
        
        $apiData = Api::with('vendor')->find('3');
        
        if(isset($data['ignoreCron']) AND $data['ignoreCron'] === true) {
            $apiData['api_delivery_route'] = "instant";
        }

        if($apiData['api_delivery_route'] == "cron") {
            return $apiData;
        }
        return [];
    }

}