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


    public function send_inspector_notification()
    {
        $u = User::find($this->instructor_id);
        if ($u == null) {
            throw new \Exception("Instructor not found.");
        }

        //check if $u->email is valid email
        if (!Utils::email_is_valid($u->email)) {
            $u->email = 'mubahood360@gmail.com';
        }

        /*         $u->intro = rand(100000, 999999);
        $u->save(); */
        $data['email'] = $u->email;
        $email = $data['email'];
        if ($email == null || $email == "") {
            throw new \Exception("Email is required.");
        }

        $url = admin_url('online-courses/');
        $msg = "Dear " . $u->name . ",<br>";
        $msg .= "You be made a course instructor to the course " . $this->title . ".<br>";
        $msg .= "Please login to your account using the following link and start feeding the course content.<br>";
        $msg .= "<a href='" . $url . "'>" . $url . "</a><br>";
        $msg .= "<br><small>This is an automated message, please do not reply.</small><br>";

        $data['body'] = $msg;
        //$data['view'] = 'mails/mail-1';
        $data['data'] = $data['body'];
        $data['name'] = $u->name;
        $data['mail'] = $u->email;
        $data['subject'] = "M-Omulimisa - Course Instructor Notification";
        try {
            Utils::mail_sender($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
