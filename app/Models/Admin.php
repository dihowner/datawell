<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory;
    protected $hidden = ["password"];
    public function roles() {
        return $this->belongsTo(AdminRole::class, 'role_id');
    }
}