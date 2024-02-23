<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourse extends Model
{
    use HasFactory;

    //getter for other_instructors 
    public function getOtherInstructorsAttribute($value)
    {
        if ($value != null)
            return json_decode($value);
    }

    //setter for other_instructors
    public function setOtherInstructorsAttribute($value)
    {
        if ($value != null)
            $this->attributes['other_instructors'] = json_encode($value);
    }

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
        $msg .= "You have been made a course instructor to the course " . $this->title . ".<br><br>";
        $msg .= "Please login into your account using the following link and login credentials to start monitoring students enrolled to your course.<br><br>";
        $msg .= "<b>Username:</b> " . $u->email . "<br>";
        $msg .= "<b>Default Password:</b> " . '4321' . "<br>";
        $msg .= "<a href='" . $url . "'>" . $url . "</a><br>";
        $msg .= "<br><br><small>This is an automated message, please do not reply.</small><br>";

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

    public static function getMyStudents($u)
    {
        $my_courses = OnlineCourse::getMyCouses($u);
        $students = [];
        foreach ($my_courses as $course) {
            $students = array_merge($students, OnlineCourseStudent::where('online_course_id', $course->id)->get()->toArray());
        }
        return $students;
    }
    public static function getMyCouses($u)
    {
        $courses = OnlineCourse::where('instructor_id', $u->id)->get();
        foreach (OnlineCourse::all() as $key => $value) {
            if ($value->other_instructors != null) {
                $instructors = $value->other_instructors;
                if (in_array($u->id, $instructors)) {
                    $courses->push($value);
                }
            }
        }
        return $courses;
    }

    //has many students
    public function students()
    {
        return $this->hasMany(OnlineCourseStudent::class);
    }
}
