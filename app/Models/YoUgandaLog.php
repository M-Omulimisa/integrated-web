<?php

namespace App\Models;

use App\Models\Market\MarketSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoUgandaLog extends Model
{
    use HasFactory;

    //boot created
    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            //get market 
            $market_sub = MarketSubscription::where(['payment_reference_id' => $model->external_ref])->first();
            if ($market_sub != null) {
                $market_sub->check_payment_status();
                $market_sub = MarketSubscription::find($market_sub->id);
                if ($market_sub != null) {
                    if ($market_sub->is_paid == 'PAID') {
                        $market_sub->send_renew_message();
                    }
                }
            }
        });
    }
}
