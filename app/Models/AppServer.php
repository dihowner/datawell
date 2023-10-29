<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppServer extends Model
{
    use HasFactory;

    protected $fillable = ["server_id", "category", "calling_time", "app_color_scheme", "auth_code"];
}