<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course Categories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseCategory());
        $grid->disableFilter();
        $grid->quickSearch('name', 'description')->placeholder('Search by name or description');
 
        $grid->column('name', __('Name'))->sortable();

        //coulimn for number of courses
        $grid->column('courses', __('Courses'))->display(function () {
            return $this->onlineCourses()->count();
        });

        $grid->column('description', __('Description'))->hide();
        $grid->column('created_at', __('Created'))->display(function ($created_at) {
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
        $show = new Show(OnlineCourseCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('photo', __('Photo'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourseCategory());
        $form->text('name', __('Course Category Name'))->required();
        return $form;
        $form->image('photo', __('Photo'));
        $form->quill('description', __('Description'));

        return $form;
    }
}
