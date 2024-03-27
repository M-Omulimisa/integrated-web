<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseLesson extends Model
{
    use HasFactory;

    //boot
    protected static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            if ($model->status != 'Attended') {
                $model->attended_at = null;
            }else{
                $model->attended_at = now();
            }
        });

        //updated
        static::updated(function ($model) {
            $student = $model->student;
            if ($student != null) {
                $student->update_progress();
            }
        });

        //deleted
        static::deleted(function ($model) {
            $student = $model->student;
            if ($student != null) {
                $student->update_progress();
            }
        });
    }

    //belongs to student_id
    public function student()
    {
        return $this->belongsTo(OnlineCourseStudent::class, 'student_id');
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
