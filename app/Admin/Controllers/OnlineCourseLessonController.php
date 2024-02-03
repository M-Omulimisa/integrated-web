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
    protected $title = 'Lessons';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseLesson());
        //$grid->disableActions();
        $grid->disableCreateButton();
        $grid->model()->orderBy('id', 'desc');
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
        });

        $grid->column('student_id', __('Student'))
            ->display(function ($student_id) {
                $item = \App\Models\User::find($student_id);
                if ($item != null) {
                    return $item->name;
                }
                return 'Deleted';
            })
            ->sortable();

        $grid->column('online_course_id', __('Course'))
            ->display(function ($online_course_id) {
                $item = \App\Models\OnlineCourse::find($online_course_id);
                if ($item != null) {
                    return $item->title;
                }
                return 'Deleted';
            })
            ->sortable();
        $grid->column('online_course_topic_id', __('Topic'))
            ->display(function ($online_course_topic_id) {
                $item = \App\Models\OnlineCourseTopic::find($online_course_topic_id);
                if ($item != null) {
                    return $item->title;
                }
                return 'Deleted';
            })
            ->sortable();




        $grid->column('instructor_id', __('Instructor'))
            ->display(function ($instructor_id) {
                $item = \App\Models\User::find($instructor_id);
                if ($item != null) {
                    return $item->name;
                }
                return 'Deleted';
            })
            ->sortable();
        $grid->column('sheduled_at', __('Sheduled'))
            ->display(function ($sheduled_at) {
                return date('d M Y H:i', strtotime($sheduled_at));
            })
            ->sortable();
        $grid->column('attended_at', __('Attended'))
            ->display(function ($attended_at) {
                if ($attended_at == null || strlen($attended_at) < 2) {
                    return 'Not attended';
                }
                return date('d M Y H:i', strtotime($attended_at));
            })
            ->sortable();
        $grid->column('status', __('Status'))
            
            ->sortable()
            ->filter([
                'Pending' => 'Pending',
                'Attended' => 'Attended',
            ])
            ->editable(
                'select',
                [
                    'Pending' => 'Pending',
                    'Attended' => 'Attended',
                ]
            );

        $grid->column('has_error', __('Has error'))
            ->label([
                'No' => 'success',
                'Yes' => 'danger'
            ])
            ->sortable()
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes'
            ])->hide();
        $grid->column('error_message', __('Error message'))
            ->display(function ($error_message) {
                if ($error_message == null || strlen($error_message) < 2) {
                    return 'No error';
                }
                return $error_message;
            })
            ->sortable()
            ->hide();
        $grid->column('details', __('Details'))->hide();
        $grid->column('position', __('Position'))->sortable();

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
        $form->radio('status', __('Status'))
            ->options([
                'Pending' => 'Pending',
                'Attended' => 'Attended',
            ])->default('Pending');
        return $form;
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