<?php
namespace App\Vendors;

use App\Http\Traits\ResponseTrait;

class LocalServer {
    use ResponseTrait;

    public function processRequest() {
        return $this->sendResponse("Success", [
            "message"=> "Order received for processing", 
            "delivery_status" => "0"
        ], 200);
    }

}
?>