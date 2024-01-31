<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseLesson;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseLessonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OnlineCourseLesson';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseLesson());

        $grid->column('id', __('Id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('online_course_topic_id', __('Online course topic id'));
        $grid->column('online_course_id', __('Online course id'));
        $grid->column('student_id', __('Student id'));
        $grid->column('instructor_id', __('Instructor id'));
        $grid->column('sheduled_at', __('Sheduled at'));
        $grid->column('attended_at', __('Attended at'));
        $grid->column('status', __('Status'));
        $grid->column('has_error', __('Has error'));
        $grid->column('error_message', __('Error message'));
        $grid->column('details', __('Details'));

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
        $show = new Show(OnlineCourseLesson::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('online_course_topic_id', __('Online course topic id'));
        $show->field('online_course_id', __('Online course id'));
        $show->field('student_id', __('Student id'));
        $show->field('instructor_id', __('Instructor id'));
        $show->field('sheduled_at', __('Sheduled at'));
        $show->field('attended_at', __('Attended at'));
        $show->field('status', __('Status'));
        $show->field('has_error', __('Has error'));
        $show->field('error_message', __('Error message'));
        $show->field('details', __('Details'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourseLesson());

        $form->number('online_course_topic_id', __('Online course topic id'));
        $form->number('online_course_id', __('Online course id'));
        $form->text('student_id', __('Student id'));
        $form->text('instructor_id', __('Instructor id'));
        $form->datetime('sheduled_at', __('Sheduled at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('attended_at', __('Attended at'))->default(date('Y-m-d H:i:s'));
        $form->text('status', __('Status'))->default('Pending');
        $form->text('has_error', __('Has error'))->default('No');
        $form->textarea('error_message', __('Error message'));
        $form->textarea('details', __('Details'));

        return $form;
    }
}
