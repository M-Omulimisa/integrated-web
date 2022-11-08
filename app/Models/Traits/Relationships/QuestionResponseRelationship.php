<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Country;
use App\Models\User;
use App\Models\Extension\ExtensionOfficer;
use App\Models\Questions\Question;

/**
 * Class LocationRelationship.
 */
trait QuestionResponseRelationship
{

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function extension_officer()
    {
        return $this->belongsTo(ExtensionOfficer::class, 'extension_officer_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
