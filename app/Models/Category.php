<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function scopeRemoveParentCategory($query) {
        return $query->whereColumn('parent_category', '<>', 'category_name');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category', 'category_name');
    }

    public function apis() {
        return $this->hasMany(Api::class);
    }

    // public function products() {
    //     return $this->hasMany(Product::class);
    // }

    // public function getApiIdAttribute()
    // {
    //     // Assuming you want to retrieve the first API ID for the category
    //     $productInfo = $this->products->first();
    //     return $productInfo ? $productInfo->api_id : null;
    // }

}