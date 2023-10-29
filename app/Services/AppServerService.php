<?php
namespace App\Services;

use Exception;
use App\Http\Traits\ResponseTrait;
use App\Models\AppServer;

class AppServerService  {
    use ResponseTrait;
    protected $utilityService, $responseBody;
    
    public function getAllAppServer() {
        try {
            $allAppServer = AppServer::latest('id')->get();

            // Map product image to the result set...
            $allAppServer->map(function ($appServer) {
                $appServer->color_name = $this->appColorScheme($appServer->app_color_scheme);
                return $appServer;
            });

            return $allAppServer;

        } catch(Exception $e) {
            return $this->sendError("Error! ".$e->getMessage(), [], 500);
        }
    }
    
    public function getAppServer($apiId) {
        try {
            $getAPi = AppServer::where('id', $apiId)->orWhere('server_id', $apiId)->first();
            return $getAPi;
        } catch(Exception $e) {
            return $this->sendError("Error! ".$e->getMessage(), [], 500);
        }
    }

    public function createAppServer($appData) {
        try {
            $serverId = strtolower($appData['serverId']);
            $category = $appData['category'];
            $calling_time = (int) $appData['calling_time'];
            $color_scheme = (int) $appData['color_scheme'];
            $auth_code = strtolower($appData['auth_code']);
            
            $createApp = AppServer::create([
                "server_id" => $serverId,
                "category" => $category,
                "app_color_scheme" => $color_scheme,
                "calling_time" => $calling_time,
                "auth_code" => $auth_code,
            ]); 

            if($createApp) {
                return $this->sendResponse("App server ($serverId) created successfully", [], 200);
            }
            return $this->sendError("Unable to create server id", [], 400);
        } catch(Exception $e) {
            return $this->sendError("Error! ".$e->getMessage(), [], 500);
        }
    }

    public function deleteAppServer($appId) {
        $findApp = AppServer::find($appId);
        if($findApp) {
            $findApp->delete();
            return $this->sendResponse("Server ($findApp->server_id) deleted successfully. Service relying on this server won't be deliver orders", [], 204);
        } 
        return $this->sendError("Error! Server does not exist or not found", [], 404);
    }

    public function updateAppServer($updateData) {
        $id = $updateData['id'];
        $serverId = strtolower($updateData['serverId']);
        $category = $updateData['category'];
        $calling_time = (int) $updateData['calling_time'];
        $color_scheme = (int) $updateData['color_scheme'];
        $auth_code = strtolower($updateData['auth_code']);
        
        $serverExist = AppServer::where('server_id', $serverId)->whereNot('id', $id)->first();
        
        if($serverExist != NULL) {
            return $this->sendError("Error! Server ID ($serverId) already exist", [], 400);
        }
        
        $findServer = AppServer::find($updateData['id']);
        
        if($findServer) {
            $findServer->server_id = $serverId;
            $findServer->category = $category;
            $findServer->app_color_scheme = $color_scheme;
            $findServer->calling_time = $calling_time;
            $findServer->auth_code = $auth_code;
            if($findServer->update()) {
                return $this->sendResponse("App server updated successfully", [], 200);
            }
            return $this->sendError("Error updating App Server", [], 400);
        }
        return $this->sendError("Error! App Server does not exist or not found", [], 404);
    }
    
    private function appColorScheme($colorCode) {
        switch($colorCode) {
            case 0: 
                $colorName = 'Red';
            break;
            case 1: 
                $colorName = 'Blue';
            break;
            case 2: 
                $colorName = 'Green';
            break;
            case 3: 
                $colorName = 'Yellow';
            break;
            case 4: 
                $colorName = 'Purple';
            break;
            case 5: 
                $colorName = 'Grey';
            break;            
        }
        return $colorName;
    }
    
}