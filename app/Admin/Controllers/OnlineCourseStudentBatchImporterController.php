<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseStudentBatchImporter;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseStudentBatchImporterController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Student Batch Importer for Online Courses';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseStudentBatchImporter());
        $grid->model()->orderBy('id', 'desc');
        $grid->column('created_at', __('Date'))
            ->display(function ($created_at) {
                return date('d M Y', strtotime($created_at));
            })
            ->sortable();
        $grid->column('file_path', __('Download file'))
            ->display(function ($file_path) {
                $url = url('storage/' . $file_path);
                return "<a href='" . $url . "' download>Download</a>";
            }); //->downloadable();
        $grid->column('online_course_id', __('Course'))
            ->display(function ($online_course_id) {
                $C =  \App\Models\OnlineCourse::find($online_course_id);
                if ($C != null) {
                    return $C->title;
                }
                $this->delete();
                return 'Deleted';
            })
            ->sortable();
        $grid->column('status', __('Status'));
        $grid->column('success', __('Success Students'))->sortable();
        $grid->column('failed', __('Failed Students'))->sortable();
        $grid->column('total', __('Total Students'))->sortable();
        $grid->column('error_message', __('Error message'));
        $grid->column('action', __('Action'))
            ->display(function () {
                $url = url('course-student-batch-importer?id=' . $this->id);
                return "<a target=\"_blank\" class\"btn btn-success\" href='" . $url . "'>Import Data</a>";
            });

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
        $show = new Show(OnlineCourseStudentBatchImporter::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('file_path', __('File path'));
        $show->field('online_course_id', __('Online course id'));
        $show->field('status', __('Status'));
        $show->field('error_message', __('Error message'));
        $show->field('total', __('Total'));
        $show->field('success', __('Success'));
        $show->field('failed', __('Failed'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourseStudentBatchImporter());

        //show alert that explains the file should be in .csv format
        $form->html('<div class="alert alert-success" role="alert">Only .csv files are allowed<br>
        Organize your data in the following format:<br>
        <table class="table">
        <thead>
            <tr>
            <th scope="col">Full name</th>
            <th scope="col">Phone number</th> 
            </tr>
        </thead>
        </table> 
        </div>');

        $form->file('file_path', __('Select Excel file to import'))
            ->rules('required')
            ->attribute(['accept' => '.csv'])
            ->help('Only .csv files are allowed');

        $form->select('online_course_id', __('Select Course'))
            ->options(\App\Models\OnlineCourse::all()->pluck('title', 'id'))
            ->rules('required');
        return $form;
    }
}
