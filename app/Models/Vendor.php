<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function vendor() {
        return $this->hasOne(Api::class, 'api_vendor_id', 'id');
    }
}