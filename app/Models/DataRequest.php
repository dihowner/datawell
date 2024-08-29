<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        "product_id", "init_code", "wrap_code", "ipay", "mobilenig", "smeplug"
    ];
    
    public $timestamps = false;
    
    public function product() {
        return $this->hasOne(Product::class, "product_id", "product_id");
    }
}