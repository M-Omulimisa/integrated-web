<?php

namespace App\Models\Traits\Relationships;

use App\Models\Training\TrainingSubtopic;

/**
 * Class LocationRelationship.
 */
trait TtrainingTopicRelationship
{
    public function subtopics()
    {
        return $this->hasMany(TrainingSubtopic::class, 'topic_id', 'id');
    }
}
