<?php

namespace App\Models;

use App\Models\Training\TrainingResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceCategory extends Model
{
    use HasFactory;

    protected $appends = [
        'post_count'
    ];

    public function getPostCountAttribute()
    {
        return TrainingResource::where('resource_category_id', $this->id)->count();
    }
}
