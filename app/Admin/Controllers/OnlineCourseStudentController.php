<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseStudent;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseStudentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Students of Online Courses';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseStudent());

        $grid->column('id', __('Id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('online_course_id', __('Online course id'));
        $grid->column('user_id', __('User id'));
        $grid->column('online_course_category_id', __('Online course category id'));
        $grid->column('status', __('Status'));
        $grid->column('completion_status', __('Completion status'));
        $grid->column('position', __('Position'));
        $grid->column('progress', __('Progress'));

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
        $show = new Show(OnlineCourseStudent::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('online_course_id', __('Online course id'));
        $show->field('user_id', __('User id'));
        $show->field('online_course_category_id', __('Online course category id'));
        $show->field('status', __('Status'));
        $show->field('completion_status', __('Completion status'));
        $show->field('position', __('Position'));
        $show->field('progress', __('Progress'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourseStudent());

        $form->number('online_course_id', __('Online course id'));
        $form->number('user_id', __('User id'));
        $form->number('online_course_category_id', __('Online course category id'))->default(1);
        $form->text('status', __('Status'))->default('pending');
        $form->text('completion_status', __('Completion status'))->default('incomplete');
        $form->number('position', __('Position'))->default(1);
        $form->decimal('progress', __('Progress'))->default(0.00);

        return $form;
    }
}
