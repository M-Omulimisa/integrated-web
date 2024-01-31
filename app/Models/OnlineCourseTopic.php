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
        static::deleting(function ($topic) {
            if ($topic->onlineCourseTopicLessons()->count() > 0) {
                throw new \Exception("Cannot delete topic because there are lessons associated with it.");
            }
        });
    }

    public static function prepareData($data)
    {

        $chapter = OnlineCourseChapter::find($data->online_course_chapter_id);
        if ($chapter == null) {
            throw new \Exception("Chapter not found.");
        }
        $course = OnlineCourse::find($chapter->online_course_id);
        if ($course == null) {
            throw new \Exception("Course not found.");
        }
        $data->online_course_id = $chapter->online_course_id;
        $data->online_course_category_id = $chapter->online_course_category_id;

        //check if position is unique for this topic in this course
        $position = $data->position;
        $topic = OnlineCourseTopic::where('online_course_id', $data->online_course_id)
            ->where('position', $position)
            ->first();
            
        if ($topic != null) {
            if($topic->id != $data->id){
                throw new \Exception("Position must be unique for this topic in this course.");
            }

        }

        return $data;
    }
}
