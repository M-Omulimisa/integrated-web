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
        return $this->belongsTo(OnlineCourseStudent::class, 'id');
    }

    //boot
    protected static function boot()
    {
        parent::boot();

        //creating
        static::creating(function ($onlineCourseStudent) {
            $onlineCourseStudent = self::prepare($onlineCourseStudent);
            $onlineCourseStudent->user_id = 1;
            return $onlineCourseStudent;
        });
        static::updating(function ($onlineCourseStudent) {
            $onlineCourseStudent = self::prepare($onlineCourseStudent);
            $onlineCourseStudent->user_id = $this->id;
            return $onlineCourseStudent;
        });
        static::deleting(function ($onlineCourseStudent) {
            //delete lessons
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

    //has many onlineCourseStudentLessons
    public function onlineCourseStudentLessons()
    {
        return $this->hasMany(OnlineCourseLesson::class, 'student_id');
    }


    //prepare validation
    public static function prepare($data)
    {
        //check if student is already enrolled
        $course = OnlineCourse::find($data->online_course_id);
        if ($course == null) {
            throw new Exception("Course not found.", 1);
        }

        $phone = Utils::prepare_phone_number($data->phone);

        if (!Utils::phone_number_is_valid($phone)) {
            throw new Exception("Invalid phone number. $phone", 1);
        }
        $data->phone = $phone;
        $data->instructor_id = $course->instructor_id;
        $data->online_course_category_id = $course->online_course_category_id;
        return $data;
    }

    //getter for user_id
    public function getUserIdAttribute($value)
    {
        $this->user_id = $this->id;
        return $this->id;
    }
}
