<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Settings\Language;
use App\Models\DistrictModel;
use App\Models\SubcountyModel;
use App\Models\ParishModel;
use App\Models\Organisations\Organisation;
use App\Models\Farmers\Farmer;
use App\Models\Weather\WeatherSubscription;
use App\Models\Payments\SubscriptionPayment;

/**
 * Class LocationRelationship.
 */
trait WeatherSubscriptionRelationship
{
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function district()
    {
        return $this->belongsTo(DistrictModel::class, 'district_id');
    }

    public function subcounty()
    {
        return $this->belongsTo(SubcountyModel::class, 'subcounty_id');
    }

    public function parish()
    {
        return $this->belongsTo(ParishModel::class, 'parish_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function renew()
    {
        return $this->belongsTo(WeatherSubscription::class, 'renewal_id');
    }

    public function payment()
    {
        return $this->belongsTo(SubscriptionPayment::class, 'payment_id');
    }
}
