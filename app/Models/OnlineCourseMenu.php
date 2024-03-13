<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseMenu extends Model
{
    use HasFactory;

    //has many items
    public function onlineCourseMenuItems()
    {
        return $this->hasMany(OnlineCourseMenuItem::class);
    }
}
