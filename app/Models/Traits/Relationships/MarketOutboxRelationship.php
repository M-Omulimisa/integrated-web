<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Settings\Language;

/**
 * Class LocationRelationship.
 */
trait MarketOutboxRelationship
{
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
