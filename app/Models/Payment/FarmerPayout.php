<?php

namespace App\Models\Payment;

use App\Models\Payment\MtnApi;

use Illuminate\Database\Eloquent\Model;

class FarmerPayout extends Model
{
    protected $table = "farmer_payouts";
    protected $fillable = [
    			'subscription_id',
                'farmer_phone',
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