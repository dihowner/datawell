<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KYC extends Model
{
    use HasFactory;

    protected $table = 'kyc';

    protected $fillable = [
        'user_id', 'bvn_number', 'bvn_name', 'bvn_status', 'bvn_response', 'bvn_data', 'bvn_phone', 'bvn_date_verified', 
        'nin_number', 'nin_name', 'nin_status', 'nin_response', 'nin_data', 'nin_phone', 'nin_date_verified'
    ];
    
    public $timestamps = true;

    protected $casts = [
        'nin_status' => 'string',
    ];
}
