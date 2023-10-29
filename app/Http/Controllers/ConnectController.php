<?php

namespace App\Http\Controllers;

use App\Services\ConnectService;
use Illuminate\Http\Request;

class ConnectController extends Controller
{
    protected $connectService;
    public function __construct(ConnectService $connectService)
    {
        $this->connectService = $connectService;
    }
    
    public function index($serverId) {
        return $this->connectService->index($serverId);
    } 
    
    public function calling($serverId) {
        return $this->connectService->calling($serverId);
    } 
    
    public function process(Request $request) {
        $reference = $request->query('id');
        return $this->connectService->process($reference);
    } 
    
    public function screen($serverId, Request $request) {
        $reference = $request->query('id');
        $response = $request->query('message');
        return $this->connectService->updateOrder($serverId, $reference, $response);
    } 
    
    public function report($serverId, Request $request) {
        $reference = $request->query('id');
        $response = $request->query('message');
        return $this->connectService->updateOrder($serverId, "", $response);
    } 
}