<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseChapter extends Model
{
    use HasFactory;


    //get dropdown list
    public static function getDropDownList()
    {
        $models = OnlineCourseChapter::orderBy('id')->get();
        $items = [];
        foreach ($models as $model) {
            $onlineCourse = $model->onlineCourse;
            $items[$model->id] = $model->title;
            if($onlineCourse != null) {
                $items[$model->id] .= ' (' . $onlineCourse->title . ')';
            } 
        }
        return $items;
    } 

    //boot
    protected static function boot()
    {
        parent::boot();

        //creating
        static::creating(function ($onlineCourseChapter) {
            $onlineCourseChapter->position = 1;
        });

        static::deleting(function ($onlineCourseChapter) {
            throw new \Exception('You cannot delete this resource directly. It is being used by other resources.');
            $onlineCourseChapter->onlineCourseChapterLessons()->delete();
        });
    }

    //has many topics
    public function topics()
    {
        return $this->hasMany(OnlineCourseTopic::class);
    }

    //belongs to course
    public function onlineCourse()
    {
        return $this->belongsTo(OnlineCourse::class);
    } 

    public static function prepareData($data)
    {

        /* 
                $form->number('online_course_category_id', __('Online course category id'))->default(1);
        $form->number('online_course_chapter_id', __('Online course chapter id'))->default(1);
        $form->number('position', __('Position'));
        */
        $data['instructor_id'] = 1;
        return $data;
    }
}
