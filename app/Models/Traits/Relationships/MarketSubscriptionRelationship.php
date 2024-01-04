<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Settings\Language;
use App\Models\Payments\SubscriptionPayment;
use App\Models\Market\MarketOutbox;
use App\Models\RegionModel;

/**
 * Class LocationRelationship.
 */
trait MarketSubscriptionRelationship
{
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->belongsTo(SubscriptionPayment::class, 'payment_id');
    }

    public function messages()
    {
        return $this->hasMany(MarketOutbox::class, 'subscription_id');
    }

    public function region()
    {
        return $this->belongsTo(RegionModel::class, 'region_id');
    }
}
