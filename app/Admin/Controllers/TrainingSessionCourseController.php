<?php

namespace App\Admin\Controllers;

use App\Models\Settings\Location;
use App\Models\Training\Training;
use App\Models\TrainingSession;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class TrainingSessionCourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Training Sessions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TrainingSession());

        $grid->model()->where('organisation_id', Auth::user()->organisation_id);
        $grid->column('session_date', __('Session Date'))->sortable();
        $grid->column('training.name', __('Training'));
        $grid->column('location.name', __('Location'));
        $grid->column('conducted.name', __('Conducted By'));
        $grid->column('start_date', __('Start time'));
        $grid->column('end_date', __('End time  '));
        $grid->column('details', __('Details'));
        $grid->column('topics_covered', __('Topics covered'))->hide();
        $grid->column('attendance_list_pictures', __('Attendance list pictures'));
        $grid->column('members_pictures', __('Members pictures'));
        $grid->column('gps_latitude', __('Gps latitude'))->hide();
        $grid->column('gps_longitude', __('Gps longitude'))->hide();

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
        $show = new Show(TrainingSession::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('training_id', __('Training id'));
        $show->field('location_id', __('Location id'));
        $show->field('conducted_by', __('Conducted by'));
        $show->field('session_date', __('Session date'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('details', __('Details'));
        $show->field('topics_covered', __('Topics covered'));
        $show->field('attendance_list_pictures', __('Attendance list pictures'));
        $show->field('members_pictures', __('Members pictures'));
        $show->field('gps_latitude', __('Gps latitude'));
        $show->field('gps_longitude', __('Gps longitude'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new TrainingSession());

        $u = Auth::user();
        $form->hidden('organisation_id', __('Organisation id'))
            ->default($u->organisation_id);

        $form->select('training_id', __('Select Training'))
            ->options(Training::where(
                'organisation_id',
                Auth::user()->organisation_id
            )
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->rules('required');

        $form->select('location_id', __('Select Location'))
            ->options(Location::where([])
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->rules('required');

        $form->select('conducted_by', __('Conducted By'))
            ->options(User::where(
                'organisation_id',
                Auth::user()->organisation_id
            )
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->rules('required');

        $form->date('session_date', __('Session date'))->default(date('Y-m-d'))
            ->rules('required');
        $form->time('start_date', __('Session Start Time'))->default(date('H:i:s'))->rules('required');
        $form->time('end_date', __('Session End Time'))->default(date('H:i:s'))->rules('required');
        $form->textarea('details', __('Session Details'))->rules('required');
        $form->hidden('topics_covered', __('Topics covered'));
        $form->multipleImage('attendance_list_pictures', __('Attendance list pictures'))
            ->rules('required');
        $form->multipleFile('members_pictures', __('Session Photos'));
        $form->text('gps_latitude', __('Gps latitude'));
        $form->text('gps_longitude', __('Gps longitude'));


        $form->listbox('members', 'Members Present')
            ->options(User::where(
                'organisation_id',
                Auth::user()->organisation_id
            )
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->help("Select offences involded in this case")
            ->rules('required');

        return $form;
    }
}
