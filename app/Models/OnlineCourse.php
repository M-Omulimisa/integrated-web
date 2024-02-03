<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourse extends Model
{
    use HasFactory;

    //getDropDownList
    public static function getDropDownList()
    {
        $models = OnlineCourse::orderBy('id')->get();
        $items = [];
        foreach ($models as $model) {
            $items[$model->id] = $model->title;
        }
        return $items;
    }

    //has many students
    public function onlineCourseStudents()
    {
        return $this->hasMany(OnlineCourseStudent::class);
    }

    public function update_lessons()
    {
        $students = $this->onlineCourseStudents;
        $topics = $this->onlineCourseTopics;

        foreach ($students as $student) {
            foreach ($topics as $topic) {
                $lesson = OnlineCourseLesson::where('online_course_id', $this->id)
                    ->where('student_id', $student->id)
                    ->where('online_course_topic_id', $topic->id)
                    ->first();
                if ($lesson == null) {
                    $lesson = new OnlineCourseLesson();
                    $now = Carbon::now();
                    $lesson->sheduled_at = $now->addDays($topic->position);
                    $lesson->attended_at = null;
                    $lesson->has_error = null;
                    $lesson->error_message = null;
                    $lesson->status = 'Pending';
                }
                $lesson->online_course_id = $this->id;
                $lesson->student_id = $student->id;
                $lesson->online_course_topic_id = $topic->id;
                $lesson->instructor_id = $this->instructor_id;
                $lesson->position = $topic->position;
                $lesson->details = $topic->details;
                $lesson->save();
            }
        }
    }

    //has many topics
    public function onlineCourseTopics()
    {
        return $this->hasMany(OnlineCourseTopic::class);
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
