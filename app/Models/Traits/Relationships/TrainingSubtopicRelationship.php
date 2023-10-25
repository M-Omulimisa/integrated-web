<?php

namespace App\Models\Traits\Relationships;

use App\Models\Training\TrainingTopic;

/**
 * Class LocationRelationship.
 */
trait TrainingSubtopicRelationship
{
    public function topic()
    {
        return $this->belongsTo(TrainingTopic::class, 'topic_id');
    }
}
