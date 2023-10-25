<?php


namespace App\Models\Traits\Relationships\Users;


use App\Models\User;

trait CreatedByRelationship
{
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
