<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Language;

/**
 * Class LocationRelationship.
 */
trait TrainingResourceLanguageRelationship
{
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}
