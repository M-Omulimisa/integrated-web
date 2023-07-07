<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRoleUser extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($m) {
            $r = AdminRoleUser::where([
                'user_id' => $m->user_id,
                'role_id' => $m->role_id,
            ])->first();
            if ($r != null) {
                return false;
            }
            return $m;
        });
    }
    public function role()
    {
        return $this->belongsTo(AdminRole::class);
    }
    protected $fillable = ['role_id', 'user_id',];
}
