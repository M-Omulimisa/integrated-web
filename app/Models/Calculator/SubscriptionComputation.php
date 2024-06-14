<?php

namespace App\Models\Calculator;

use App\Models\Agent\Subscription;
use Illuminate\Database\Eloquent\Model;

class SubscriptionComputation extends Model
{
    protected $table = "subscription_calculation";
    protected $fillable = ['subscription_id','calculator_id'];

    public function Item()
    {
        return $this->belongsTo(Subscription::class);
    }
}

