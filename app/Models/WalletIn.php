<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletIn extends Model
{
    use HasFactory;
    protected $table = "wallet_in";
    public $timestamps = true;

    protected $fillable = [
        "user_id", "description", "old_balance", "amount", "remark",
        "new_balance", "reference", "channel", "status"];

    public function user() {
        return $this->belongsTo(User::class);
    }
}