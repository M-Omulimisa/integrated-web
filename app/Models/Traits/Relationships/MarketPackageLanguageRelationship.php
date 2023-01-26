<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Language;
use App\Models\Market\MarketPackage;

/**
 * Class MarketPackageLanguageRelationship.
 */
trait MarketPackageLanguageRelationship
{

    public function peckage()
    {
        return $this->belongsTo(MarketPackage::class, 'package_id');
    }
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
