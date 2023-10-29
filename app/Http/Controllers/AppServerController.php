<?php

namespace App\Http\Controllers;

use App\Services\AppServerService;
use App\Http\Requests\AppServerRequest;
use RealRashid\SweetAlert\Facades\Alert;

class AppServerController extends Controller
{
    protected $appServerService;
    public function __construct(AppServerService $appServerService)
    {
        $this->appServerService = $appServerService;
    }
    
    public function index() {
        $allServers = $this->appServerService->getAllAppServer();
        return view('main.app-server-mgt', compact('allServers'));
    }   

    public function createAppServerView() {
        return view('main.create-app-server');
    }

    public function createAppServer(AppServerRequest $request) {
        $appData = $request->validated();
        $createAppServer = $this->appServerService->createAppServer($appData);
        $decodeResponse = json_decode($createAppServer->getContent(), true);
        if($createAppServer->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
            return redirect()->route('app-server');
        } else {
            Alert::error("Error", $decodeResponse['message']);
            return redirect()->back();
        }
    }

    public function deleteAppServer($appId) {
        $deleteServer = $this->appServerService->deleteAppServer($appId);
        $decodeResponse = json_decode($deleteServer->getContent(), true);
        if($deleteServer->getStatusCode() == 204) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }

    public function updateAppServer(AppServerRequest $request) {
        $appData = $request->validated();
        $updateAppServer = $this->appServerService->updateAppServer($appData);
        $decodeResponse = json_decode($updateAppServer->getContent(), true);
        if($updateAppServer->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message']);
        } else {
            Alert::error("Error", $decodeResponse['message']);
        }
        return redirect()->back();
    }

}