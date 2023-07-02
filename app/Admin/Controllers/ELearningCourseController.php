<?php

namespace App\Admin\Controllers;

use App\Models\Elearning\ELearningCourse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class ELearningCourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'E-Learning Courses';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ELearningCourse());
        $grid->model()->where('organisation_id', Auth::user()->organisation_id);

        $grid->column('title', __('Title'))->sortable();
        $grid->column('summary', __('Summary'));
        $grid->column('description', __('Description'));
        $grid->column('content', __('Content'));
        $grid->column('audience', __('Audience'));
        $grid->column('outcomes', __('Outcomes'));
        $grid->column('user_id', __('User id'));
        $grid->column('image_banner', __('Image banner'));
        $grid->column('video_url', __('Video url'));
        $grid->column('about_certificates', __('About certificates'));
        $grid->column('start_date', __('Start date'));
        $grid->column('start_time', __('Start time'));
        $grid->column('end_date', __('End date'));
        $grid->column('end_time', __('End time'));
        $grid->column('duration_in_days', __('Duration in days'));
        $grid->column('duration_in_weeks', __('Duration in weeks'));
        $grid->column('team', __('Team'));
        $grid->column('operations', __('Operations'));
        $grid->column('logo', __('Logo'));
        $grid->column('brochure', __('Brochure'));
        $grid->column('status', __('Status'));
        $grid->column('read_only_mode', __('Read only mode'));
        $grid->column('enrollment_status', __('Enrollment status'));
        $grid->column('code', __('Code'));
        $grid->column('certificate_url', __('Certificate url'));
        $grid->column('status_archived_at', __('Status archived at'));
        $grid->column('enrollment_closed_at', __('Enrollment closed at'));
        $grid->column('lecture_type', __('Lecture type'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(ELearningCourse::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('summary', __('Summary'));
        $show->field('description', __('Description'));
        $show->field('content', __('Content'));
        $show->field('audience', __('Audience'));
        $show->field('outcomes', __('Outcomes'));
        $show->field('user_id', __('User id'));
        $show->field('image_banner', __('Image banner'));
        $show->field('video_url', __('Video url'));
        $show->field('about_certificates', __('About certificates'));
        $show->field('start_date', __('Start date'));
        $show->field('start_time', __('Start time'));
        $show->field('end_date', __('End date'));
        $show->field('end_time', __('End time'));
        $show->field('duration_in_days', __('Duration in days'));
        $show->field('duration_in_weeks', __('Duration in weeks'));
        $show->field('team', __('Team'));
        $show->field('operations', __('Operations'));
        $show->field('logo', __('Logo'));
        $show->field('brochure', __('Brochure'));
        $show->field('status', __('Status'));
        $show->field('read_only_mode', __('Read only mode'));
        $show->field('enrollment_status', __('Enrollment status'));
        $show->field('code', __('Code'));
        $show->field('certificate_url', __('Certificate url'));
        $show->field('status_archived_at', __('Status archived at'));
        $show->field('enrollment_closed_at', __('Enrollment closed at'));
        $show->field('lecture_type', __('Lecture type'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ELearningCourse());

        $u = Auth::user();
        $form->hidden('organisation_id', __('Organisation id'))
            ->default($u->organisation_id);
        $form->hidden('user_id', __('user_id'))
            ->default($u->id); 

        $form->text('title', __('Title'))->rules('required');
        $form->text('code', __('Course Code'))->rules('required');
        $form->textarea('summary', __('Course Summary'))->rules('required');
        $form->textarea('description', __('Course Description'))->rules('required');
        $form->textarea('content', __('Content'));
        $form->textarea('audience', __('Audience'));
        $form->textarea('outcomes', __('Outcomes')); 
        $form->text('image_banner', __('Image banner'));
        $form->text('video_url', __('Video url'));
        $form->textarea('about_certificates', __('About certificates'));
        $form->date('start_date', __('Start date'))->default(date('Y-m-d'));
        $form->text('start_time', __('Start time'));
        $form->date('end_date', __('End date'))->default(date('Y-m-d'));
        $form->text('end_time', __('End time'));
        $form->number('duration_in_days', __('Duration in days'));
        $form->number('duration_in_weeks', __('Duration in weeks'));
        $form->textarea('team', __('Team'));
        $form->textarea('operations', __('Operations'));
        $form->text('logo', __('Logo'));
        $form->text('brochure', __('Brochure'));
        $form->text('status', __('Status'))->default('Open');
        $form->switch('read_only_mode', __('Read only mode'));
        $form->text('enrollment_status', __('Enrollment status'))->default('Current');

        $form->text('certificate_url', __('Certificate url'));
        $form->datetime('status_archived_at', __('Status archived at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('enrollment_closed_at', __('Enrollment closed at'))->default(date('Y-m-d H:i:s'));
        $form->text('lecture_type', __('Lecture type'));

        return $form;
    }
}
