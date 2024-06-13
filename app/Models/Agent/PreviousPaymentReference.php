<?php

namespace App\Models\Agent;

use Illuminate\Database\Eloquent\Model;

class PreviousPaymentReference extends Model
{
    protected $table = "previous_payment_reference";
    protected $fillable = [
                'subscription_id',
                'payment_refrence',
                'payment_status',
                'dated' ];

}