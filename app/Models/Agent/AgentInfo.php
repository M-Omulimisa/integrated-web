<?php

namespace App\Models\Agent;

use App\Models\User;
use App\Models\Settings\District;
use Illuminate\Database\Eloquent\Model;

class AgentInfo extends Model
{
    protected $table = "agents";
    protected $fillable = ['agent_id','company_id','supervisor_id', 'gender','card_type','card_number','code', 'mobile_money_number', 'access_tool', 'district', 'subcounty', 'parish'];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

}