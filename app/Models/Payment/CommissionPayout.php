<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payment\MtnApi;

class CommissionPayout extends Model
{
    protected $table = "commission_payouts";
    protected $fillable = [
    			'agent_code',
    			'agent_id',
    			'agent_mm_msisdn',
    			'api_provider',
                'api_request_status',
                'provider_code',
                'transfer_account_username',
                'transaction_id',
                'refrence_id',
                'transaction_status',
                'transaction_error_message',
                'transaction_amount',
                'initiated_by_user_id',
                'initiation_approved_by_phonenumber',
                'initiation_approved_by_email',
                'approved_status',
                'authorities',
                'api_id'
                ];

    public function payapi()
    {
        return $this->belongsTo(MtnApi::class, 'api_id', 'id');
    }

}