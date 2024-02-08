<?php

namespace App\Models;

use App\Http\Controllers\Elearning\ChapterController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseTopic extends Model
{
    use HasFactory;

    //boot 
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($topic) {
            $topic = self::prepareData($topic);
            return $topic;
        });
        //updating
        static::updating(function ($topic) {
            $topic = self::prepareData($topic);
            return $topic;
        });

        //created
        static::created(function ($topic) {
            $course = OnlineCourse::find($topic->online_course_id);
            if ($course == null) {
                throw new \Exception("Course not found 2.");
            }
            $course->update_lessons();
        });

        //cannot delete if there is a course
        static::deleted(function ($topic) {
            //delete onlineCourseTopicLessons
            $topic->onlineCourseTopicLessons()->delete();
            $course = OnlineCourse::find($topic->online_course_id);
            if ($course == null) {
                throw new \Exception("Course not found 3.");
            }
            $course->update_lessons();
        });
    }

    //has many OnlineCourseLesson
    public function onlineCourseTopicLessons()
    {
        return $this->hasMany(OnlineCourseLesson::class);
    }

    public static function prepareData($data)
    {

        $course = OnlineCourse::find($data->online_course_id);
        if ($course == null) {
            throw new \Exception("Course not found.");
        }

        $data->online_course_category_id = $course->online_course_category_id;
        $data->online_course_chapter_id = 1;

        //check if position is unique for this topic in this course
        $position = $data->position;
        $topic = OnlineCourseTopic::where('online_course_id', $data->online_course_id)
            ->where('position', $position)
            ->first();

        if ($topic != null) {
            if ($topic->id != $data->id) {
                throw new \Exception("Position must be unique for this topic in this course.");
            }
        }

        return $data;
    }
}
