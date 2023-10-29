<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = ["description", "user_id", "old_balance", "reference", "amount", "new_balance", "bank_info", "status", "remark"];
    
    public function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}