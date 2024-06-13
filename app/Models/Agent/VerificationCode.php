<?php

namespace App\Models\Agent;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $table = "agent_verification";
    protected $fillable = ['user_id','code','status'];    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}