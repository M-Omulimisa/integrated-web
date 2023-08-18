<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_role_permissions','role_id','permission_id');    
    }
}
