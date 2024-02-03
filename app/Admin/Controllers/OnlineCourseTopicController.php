<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseTopic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseTopicController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course Topics';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseTopic());
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('online_course_chapter_id', 'Course Chapter')->select(\App\Models\OnlineCourseChapter::getDropDownList());
        });
        $grid->quickSearch('title')->placeholder('Search by title');
      /*   $grid->column('image', __('Image'))
            ->lightbox(['width' => 50, 'height' => 50,])
            ->sortable()
            ->width(50); */
        $grid->column('title', __('Title'))->sortable();
        $grid->column('summary', __('Summary'))->hide();
        $grid->column('details', __('Details'))->hide();
        $grid->column('video_url', __('Video'))->hide();
        $grid->column('audio_url', __('Audio'))
            ->display(function ($audio_url) {
                if ($audio_url) {
                    //check if not null and not empty
                    if ($audio_url == null || $audio_url == '') {
                        return 'N/A';
                    }
                    $url = asset('storage/' . $audio_url);
                    return '<audio controls>
                    <source src="' . $url . '" type="audio/mpeg">
                    Your browser does not support the audio element.
                    </audio>';
                }
            })->sortable();
        $grid->column('online_course_id', __('Course'))
            ->display(function ($online_course_id) {
                $course = \App\Models\OnlineCourse::find($online_course_id);
                if ($course) {
                    return $course->title;
                }
            })
            ->sortable();
        $grid->column('online_course_category_id', __('Online course category id'))->hide();
        $grid->column('online_course_chapter_id', __('Online course chapter id'))->hide();
        $grid->column('position', __('Position'))
            ->sortable()
            ->editable()
            ->help('Position');

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
        $show = new Show(OnlineCourseTopic::findOrFail($id));

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
        $form = new Form(new OnlineCourseTopic());


        $form->text('title', __('Title'))->rules('required');
        $form->select('online_course_chapter_id', __('Course chapter'))
            ->options(\App\Models\OnlineCourseChapter::getDropDownList())
            ->rules('required');

        $form->decimal('position', __('Position'))
            ->help('Position of this topic in the course.')
            ->rules('required');

        $form->file('audio_url', __('Audio'))
            ->rules('required')
            ->uniqueName()
            ->attribute(['accept' => 'audio/*']);

        $form->textarea('summary', __('Summary'));
        $form->quill('details', __('Details'));
        $form->image('image', __('Image'));
        $form->file('video_url', __('Video url'));



        return $form;
    }
}