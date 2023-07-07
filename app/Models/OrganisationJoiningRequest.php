<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisationJoiningRequest extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::updating(function ($m) {
            if ($m->status == 'Accepted') {
                if ($m->user == null) {
                    return;
                }
                $m->user->organisation_id = $m->organisation_id;
                $m->user->save();
            }
        });
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
