<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Language;
use App\Models\Organisations\Organisation;

/**
 * Class LocationRelationship.
 */
trait KeywordRelationship
{

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }
}
