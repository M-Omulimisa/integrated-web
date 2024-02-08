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
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->equal('online_course_id', 'Course')->select(\App\Models\OnlineCourse::getDropDownList());
            
            $filter->equal('status', 'Status')->select([
                'active' => 'Active',
                'inactive' => 'Inactive'
            ]);
            $filter->equal('completion_status', 'Completion')->select([
                'completed' => 'Completed',
                'incomplete' => 'Incomplete'
            ]);
        });

        $grid->column('created_at', __('Date enrolled'))
            ->display(function ($created_at) {
                return date('d M Y', strtotime($created_at));
            })
            ->sortable()
            ->hide();
        $grid->column('user_id', __('Student'))
            ->display(function ($user_id) {
                $u = $this->user;
                if ($u != null) {
                    return $u->name;
                }
                $this->delete();
                return 'Deleted';
            })
            ->sortable();
        $grid->column('online_course_id', __('Course'))
            ->display(function ($online_course_id) {
                return \App\Models\OnlineCourse::find($online_course_id)->title;
            })
            ->sortable();
        $grid->column('status', __('Status'))
            ->label([
                'active' => 'success',
                'inactive' => 'danger'
            ])
            ->sortable();

        $grid->column('completion_status', __('Completion'))
            ->label([
                'completed' => 'success',
                'incomplete' => 'danger'
            ])
            ->sortable()
            ->filter([
                'completed' => 'Completed',
                'incomplete' => 'Incomplete'
            ]);

        $grid->column('progress', __('Progress'))
            ->progressBar(['width' => 100, 'height' => 20, 'textFormat' => 'percent'])
            ->filter('range')
            ->sortable();
        $grid->column('position', __('Position'))->hide();
        $grid->column('has_listened_to_intro', __('Listened to Intro'))
            ->sortable() 
            ->editable('select', [
                'Yes' => 'Yes',
                'No' => 'No'
            ]);
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


        $form->text('name', __('Student Name'))
            ->rules('required');
        $form->hidden('has_listened_to_intro', __('Student Name'))
            ->rules('required')
            ->default('No');

        $form->text('phone', __('Student Phone Number'))
            ->rules('required');

        $form->select('online_course_id', __('Select Course'))
            ->options(\App\Models\OnlineCourse::all()->pluck('title', 'id'))
            ->rules('required');

        $form->radioCard('status', __('Status'))
            ->options(['active' => 'Active', 'inactive' => 'Inactive'])
            ->default('active');



        return $form;
    }
}
