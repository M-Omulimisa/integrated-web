<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class ApprovalCodes extends Model
{
    protected $table = "payouts_approvals";
    protected $fillable = [
    			'payout_id',
    			'authority_id',
    			'code',
    			'message_status',
    			'user_action',                
    		];

}