<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseStudent extends Model
{
    use HasFactory;

    public function get_menu_audio_url($menu)
    {
        if ($menu == null) {
            return null;
        }
        $url = asset('storage/' . $menu->english_audio);
        try {
            $onlineCourse = $this->onlineCourse;
        } catch (\Throwable $th) {
            $onlineCourse = null;
        }
        if ($onlineCourse != null) {
            if ($onlineCourse->photo == '5548782a-449b-4483-b28a-c3c3012521ef') {
                return $url;
            }
            $menuItem = OnlineCourseMenuItem::where('online_course_menu_id', $menu->id)
                ->where('language_id', $onlineCourse->photo)
                ->first();

            if ($menuItem == null) {
                return $url;
            }
            $language = $menuItem->audio;
            if ($language  == null || $language == '') {
                return $url;
            }
            $url = asset('storage/' . $language);
        }
        return $url;
    }

    //update progress
    public function update_progress()
    {
        $lessons = $this->onlineCourseStudentLessons;
        $total = count($lessons);
        $attended = 0;
        foreach ($lessons as $lesson) {
            if ($lesson->status == 'Attended') {
                $attended++;
            }
        }
        $pecentage = 0;
        if ($total > 0) {
            $pecentage = ($attended / $total) * 100;
        }

        $this->progress = $pecentage;

        if ($this->progress >= 99) {
            $this->completion_status = 'Completed';
        }
        $this->save();
    }

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
            $onlineCourseStudent->has_listened_to_intro = 'No';
            return $onlineCourseStudent;
        });
        static::updating(function ($onlineCourseStudent) {
            $onlineCourseStudent = self::prepare($onlineCourseStudent);
            $onlineCourseStudent->user_id = $onlineCourseStudent->id;
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

            try {
                //$message = "Hello {$onlineCourseStudent->name},\nYou have been enrolled to {$onlineCourseStudent->onlineCourse->title} online course. Please call 0323200710 to start learning today. Thank you.";
                $message = $course->summary;
                $message = trim($message);
                $message = str_replace('[STUDENT_NAME]', $onlineCourseStudent->name, $message);
                $message = str_replace('STUDENT_NAME', $onlineCourseStudent->name, $message);
                $message = str_replace('[COURSE_NAME]', $onlineCourseStudent->onlineCourse->title, $message);
                $message = str_replace('COURSE_NAME', $onlineCourseStudent->onlineCourse->title, $message);
                Utils::send_sms($onlineCourseStudent->phone, $message);
            } catch (Exception $e) {
                //throw $th;
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
