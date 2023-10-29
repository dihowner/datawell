<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "plan",
        "description",
        "extra_info",
        "destination",
        "old_balance",
        "amount",
        "new_balance",
        "costprice",
        "category",
        "transaction_reference",
        "reference",
        "pin_details",
        "token_details",
        "response",
        "memo",
        "status",
        "api_id",
        "ussd_code",
        "channel"  
    ];

    public function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function api() {
        return $this->belongsTo(Api::class, "api_id", "id");
    }
}