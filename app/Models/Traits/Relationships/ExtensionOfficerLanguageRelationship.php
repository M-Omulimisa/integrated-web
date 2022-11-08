<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Users\Role;

use App\Models\Settings\Language;
use App\Models\Extension\ExtensionOfficer;

/**
 * Class LocationRelationship.
 */
trait ExtensionOfficerLanguageRelationship
{
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function extension_officer()
    {
        return $this->belongsTo(ExtensionOfficer::class, 'extension_officer_id');
    }
}
