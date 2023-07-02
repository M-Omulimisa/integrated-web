<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\keyword;
use App\Models\Farmers\Farmer;
use App\Models\Questions\QuestionImage;
use App\Models\Questions\QuestionResponse;
use App\Models\User;
use App\Models\Extension\ExtensionOfficer;

/**
 * Class LocationRelationship.
 */
trait QuestionRelationship
{

    public function keyword()
    {
        return $this->belongsTo(keyword::class, 'keyword_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function images()
    {
        return $this->hasMany(QuestionImage::class, 'question_id');
    }

    public function responses()
    {
        return $this->hasMany(QuestionResponse::class, 'question_id')->latest('created_at');
    }
}
