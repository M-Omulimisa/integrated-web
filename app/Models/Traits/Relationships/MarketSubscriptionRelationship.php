<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Settings\Language;
use App\Models\Payments\SubscriptionPayment;

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
}
