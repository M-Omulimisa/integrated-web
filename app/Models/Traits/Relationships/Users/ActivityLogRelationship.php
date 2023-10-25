<?php

namespace App\Models\Traits\Relationships\Users;

use App\Models\User;

/**
 * Class ActivityLogRelationship.
 */
trait ActivityLogRelationship
{   

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}