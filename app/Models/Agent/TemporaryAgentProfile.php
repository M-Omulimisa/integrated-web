<?php

namespace App\Models\Agent;

use Illuminate\Database\Eloquent\Model;

class TemporaryAgentProfile extends Model
{
    protected $table = "temporary_agent_profiles";
    protected $fillable = ['name','gender','email', 'phonenumber','mobile_money_number','status','company', 'supervisor', 'card_type', 'card_number', 'access_tool', 'district', 'subcounty', 'parish', 'type', 'transfered', 'has_error', 'error_msg', 'user_id'];

}