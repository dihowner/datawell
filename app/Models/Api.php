<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ["api_name", "api_vendor_id", "api_username", "api_password", "api_public_key", "api_private_key", "api_secret_key", "api_delivery_route"];

    public function vendor() {
        return $this->hasOne(Vendor::class, 'id', 'api_vendor_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}