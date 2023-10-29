<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ["product_name", "product_id", "category_id", "cost_price", "availability", "api_id"];

    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function api() {
        return $this->hasOne(Api::class, 'id', 'api_id');
    }

    public function productpricing() {
        return $this->hasOne(ProductPricing::class, 'product_id', 'product_id');
    }

    public function airtime_request() {
        return $this->belongsTo(AirtimeRequest::class);
    }
}