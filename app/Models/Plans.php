<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = ["plan_name", "amount", "plan_description", "remarks"];

    public function users() {
        return $this->hasMany(User::class);
    }
    
    public function product_pricing() {
        return $this->hasMany(ProductPricing::class, 'plan_id', 'id');
    }
    
}