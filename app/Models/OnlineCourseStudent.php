<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseStudent extends Model
{
    use HasFactory;

    //belongs to
    public function onlineCourse()
    {
        return $this->belongsTo(OnlineCourse::class);
    }

    //belongs to student
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //boot
    protected static function boot()
    {
        parent::boot();

        //creating
        static::creating(function ($onlineCourseStudent) {
            $onlineCourseStudent = self::prepare($onlineCourseStudent);
            return $onlineCourseStudent;
        });
        static::updating(function ($onlineCourseStudent) {
            $onlineCourseStudent = self::prepare($onlineCourseStudent);
            return $onlineCourseStudent;
        });
        static::deleting(function ($onlineCourseStudent) {
            throw new \Exception('You cannot delete this resource directly. It is being used by other resources.');
            $onlineCourseStudent->onlineCourseStudentLessons()->delete();
        });

        //created
        static::created(function ($onlineCourseStudent) {
            $course = OnlineCourse::find($onlineCourseStudent->online_course_id);
            if ($course != null) {
                $course->update_lessons();
            }
        });

        //updated
        static::updated(function ($onlineCourseStudent) {
            $course = OnlineCourse::find($onlineCourseStudent->online_course_id);
            if ($course != null) {
                $course->update_lessons();
            }
        });
    }


    //prepare validation
    public static function prepare($data)
    {
        //check if student is already enrolled
        $onlineCourseStudent = OnlineCourseStudent::where('online_course_id', $data->online_course_id)
            ->where('user_id', $data->user_id)->first();
        if ($onlineCourseStudent != null) {
            if ($onlineCourseStudent->user_id != $data->user_id) {
                throw new Exception("User already subscribed to this course.", 1);
            }
        }
        $course = OnlineCourse::find($data->online_course_id);
        if ($course == null) {
            throw new Exception("Course not found.", 1);
        }
        $data->online_course_category_id = $course->online_course_category_id;
        return $data;
    }
}
