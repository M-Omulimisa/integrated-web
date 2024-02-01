<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseLesson extends Model
{
    use HasFactory;

    //belongs to student_id
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    //belongs to onlineCourse
    public function onlineCourse()
    {
        return $this->belongsTo(OnlineCourse::class);
    }

    //belongs to instructor_id
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    
    //belongs to online_course_topic_id
    public function onlineCourseTopic()
    {
        return $this->belongsTo(OnlineCourseTopic::class);
    } 
}
