<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricing extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ["product_id", "plan_id", "selling_price", "extra_charges"];

    public function plan() {
        return $this->belongsTo(Plans::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}