<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PayoutAuthority extends Model
{
    protected $table = "payout_authorities";
    protected $fillable = [
    			'user_id',
    			'phonenumber',
    			'email',
    			'can_approve',
                'can_initiate',
                'status',
                ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



}