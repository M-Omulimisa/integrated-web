<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseCategory extends Model
{
    use HasFactory;

    //boot 
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
        });
        //cannot delete if there is a course
        static::deleting(function ($category) {
            if ($category->onlineCourses()->count() > 0) {
                throw new \Exception("Cannot delete category because there are courses associated with it.");
            }
        });
    }

    //has many courses
    public function onlineCourses()
    {
        return $this->hasMany(OnlineCourse::class);
    } 
}
