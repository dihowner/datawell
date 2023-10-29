<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirtimeToCash extends Model
{
    use HasFactory;

    protected $table = 'airtime_to_cash';

    protected $fillable = [
            "user_id", "description", "airtime_reciever", "airtime_sender", 
            "airtime_amount", "old_balance", "amount", "new_balance", 
            "network", "additional_note", "status", "reference", "remark"
        ];

    public function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}