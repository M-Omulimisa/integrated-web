<?php

namespace App\Models;

use App\Models\Market\MarketSubscription;
use App\Models\Weather\WeatherSubscription;
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
            self::process_things($model);
        });
    }

    public static function process_things($model)
    {
        //get market 
        $market_sub = MarketSubscription::where(['payment_reference_id' => $model->external_ref])->first();
        if ($market_sub != null) {
            $market_sub->check_payment_status();
            $market_sub = MarketSubscription::find($market_sub->id);
            if ($market_sub != null) {
                if ($market_sub->is_paid == 'PAID') {
                    $market_sub->process_subscription();
                }
            }
        }

        $weather_sub = WeatherSubscription::where(['payment_reference_id' => $model->external_ref])->first();
        if ($weather_sub != null) {
            $weather_sub->check_payment_status();
            $weather_sub = WeatherSubscription::find($weather_sub->id);
            if ($weather_sub != null) {
                if ($weather_sub->is_paid == 'PAID') {
                    $weather_sub->process_subscription();
                }
            }
        }
    }
}
