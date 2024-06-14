<?php

namespace App\Models\Agent;

use Illuminate\Database\Eloquent\Model;

class SubscriptionCalculator extends Model
{
    protected $table = "subscription_calculation";
    protected $fillable = [
                'subscription_id',
                'calculator_id', ];

}