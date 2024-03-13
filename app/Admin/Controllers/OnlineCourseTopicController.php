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
    protected $title = 'Course Lessons';

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
            $filter->equal('online_course_id', 'Course')->select(\App\Models\OnlineCourse::getDropDownList());
        });
        $grid->quickSearch('title')->placeholder('Search by title');
     
        $grid->column('position', __('Position'))
            ->sortable()
            ->editable()
            ->help('Position');
        $grid->column('title', __('Title'))->sortable();

        $grid->column('details', __('Details'))->hide();
        $grid->column('audio_url', __('Content Audio'))
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
        $grid->column('video_url', __('Quiz Question Audio'))
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

        $grid->column('summary', __('Quiz Correct Answer'))
            ->label([
                '1' => 'Press 1',
                '2' => 'Press 2',
            ])
            ->sortable();


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
        $form->select('online_course_id', __('Course'))
            ->options(\App\Models\OnlineCourse::getDropDownList())
            ->rules('required');
        $form->hidden('online_course_chapter_id')->default(1);

        $form->decimal('position', __('Position'))
            ->help('Position of this topic in the course.')
            ->rules('required');

        $form->file('audio_url', __('Content Audio'))
            ->rules('required')
            ->uniqueName()
            ->attribute(['accept' => 'audio/*']);
        $form->file('video_url', __('Quiz Question Audio'))
            ->rules('required')
            ->uniqueName()
            ->attribute(['accept' => 'audio/*']);

        $form->radio('summary', __('Quiz Correct Answer'))
            ->options([
                '1' => 'Press 1',
                '2' => 'Press 2',
                '3' => 'Press 3',
            ])
            ->rules('required');

        /*         $form->quill('details', __('Details'));
        $form->image('image', __('Image'));
 */


        return $form;
    }
}
