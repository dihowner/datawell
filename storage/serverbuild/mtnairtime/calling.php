<?php

// namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

$result = DB::table('transactions')->where(['category' => 'data', 'status' => '0'])->where('description', 'like', '%mtn%')->first();

if($result != NULL) {
    $formUssd = [
        "id" => $result['reference'],
        "ussd" => $result['ussd_code'], 
        "phoneno" => $result['destination'],
        "description" => $result['description']
    ];
    echo json_encode($formUssd, JSON_PRETTY_PRINT);
}