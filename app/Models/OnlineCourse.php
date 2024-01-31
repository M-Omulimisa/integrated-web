<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourse extends Model
{
    use HasFactory;

    //has many students
    public function onlineCourseStudents()
    {
        return $this->hasMany(OnlineCourseStudent::class);
    } 

    public function update_lessons(){
        
    }

    //boot
    protected static function boot()
    {
        parent::boot();

        //creating
        static::creating(function ($onlineCourse) {
            //$onlineCourse->instructor_id = 1;
        });

        static::deleting(function ($onlineCourse) {
            throw new \Exception('You cannot delete this resource directly. It is being used by other resources.'); 
            $onlineCourse->onlineCourseStudents()->delete();
            $onlineCourse->onlineCourseTopics()->delete();
            $onlineCourse->onlineCourseChapters()->delete();
            $onlineCourse->onlineCourseLessons()->delete();
        });
    } 
}
