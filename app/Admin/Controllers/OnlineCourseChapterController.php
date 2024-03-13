<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseChapter;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseChapterController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course Chapters';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseChapter());

        $grid->column('id', __('ID'))->sortable()->hide();
     

        $grid->column('title', __('Title'))->sortable();
        $grid->column('summary', __('Summary'))->hide();
        $grid->column('details', __('Details'))->hide();

        //count topics
        $grid->column('topics', __('Topics'))
            ->display(function ($topics) {
                $count = count($topics);
                return $count;
            }); 

        $grid->column('video_url', __('Video url'))->hide();
        $grid->column('audio_url', __('Audio'))
            ->display(function ($audio_url) {
                if ($audio_url) {
                    //check if not null and not empty
                    if($audio_url == null || $audio_url == ''){
                        return 'N/A';
                    } 
                    $url = asset('storage/' . $audio_url); 
                    return '<audio controls>
                    <source src="' . $url . '" type="audio/mpeg">
                    Your browser does not support the audio element.
                    </audio>';
                }
            })->hide();

        $grid->column('online_course_id', __('Course'))
            ->display(function ($online_course_id) {
                $course = \App\Models\OnlineCourse::find($online_course_id);
                if ($course) {
                    return $course->title;
                }
            })
            ->sortable();


        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return date('d-m-Y', strtotime($created_at));
            })->sortable()->hide();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(OnlineCourseChapter::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('title', __('Title'));
        $show->field('summary', __('Summary'));
        $show->field('details', __('Details'));
        $show->field('image', __('Image'));
        $show->field('video_url', __('Video url'));
        $show->field('audio_url', __('Audio url'));
        $show->field('online_course_id', __('Online course id'));
        $show->field('online_course_category_id', __('Online course category id'));
        $show->field('online_course_chapter_id', __('Online course chapter id'));
        $show->field('position', __('Position'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourseChapter());

        $form->text('title', __('Chapter Title'))
            ->rules('required');

        $form->select('online_course_id', __('Course'))
            ->options(\App\Models\OnlineCourse::pluck('title', 'id'))
            ->rules('required');



        $form->image('image', __('Image'));

        $form->quill('details', __('Details'));
        $form->file('audio_url', __('Audio'))
            ->rules('mimes:mpga,wav')
            ->help('Upload audio file')
            ->attribute(['accept' => 'audio/*']);

        $form->file('video_url', __('Video'))
            ->rules('mimes:mp4,ogx,oga,ogv,ogg,webm')
            ->help('Upload video file')
            ->attribute(['accept' => 'video/*']);


        return $form;
    }
}
