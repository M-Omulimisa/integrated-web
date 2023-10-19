<?php

namespace App\Admin\Controllers;

use App\Models\Farmers\FarmerGroup;
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

        $grid->model()->where([]);
        $grid->disableBatchActions();
        $grid->column('session_date', __('Session Date'))->sortable();
        $grid->column('training.name', __('Training'));
        $grid->column('location.name', __('Location'));
        $grid->column('conducted.name', __('Conducted By'));
        $grid->column('start_date', __('Start time'));
        $grid->column('end_date', __('End time'));
        $grid->column('details', __('Details'));
        $grid->column('topics_covered', __('Topics covered'))->hide();
        //$grid->column('attendance_list_pictures', __('Attendance list pictures'));
        //$grid->column('members_pictures', __('Members pictures'));
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

        $show->field('created_at', __('Date'))->as(function ($x) {
            return date('d-m-Y', strtotime($x));
        })->sortable();
        $show->field('training_id', __('Training'))->as(function ($x) {
            if ($this->training == null) {
                return $x;
            }
            return $this->training->name;
        });

        $show->field('location_id', __('Location'))->as(function ($x) {
            if ($this->location == null) {
                return $x;
            }
            return $this->location->name;
        });
        $show->field('conducted_by', __('Conducted by'))->as(function ($x) {
            if ($this->conducted == null) {
                return $x;
            }
            return $this->conducted->name;
        });
        $show->field('session_date', __('Session date'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('details', __('Details'));
        $show->field('topics_covered', __('Topics Covered'));
        $show->field('members', __('Members Preset'))->as(function ($x) {
            $members = [];
            foreach ($this->members as $member) {
                $members[] = $member->name;
            }
            return implode(', ', $members);
        })->sortable();
        $show->field('attendance_list_pictures', __('Attendance list pictures'))->image();
        /*         
         */
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
            ->options(Training::where([])
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->rules('required');

        $form->select('location_id', __('Select Venue'))
            ->options(Location::where([])
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->rules('required');

        $form->select('conducted_by', __('Conducted By'))
            ->options(User::where([])
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->rules('required');

        $form->date('session_date', __('Session date'))->default(date('Y-m-d'))
            ->rules('required');
        $form->time('start_date', __('Session Start Time'))->default(date('H:i:s'))->rules('required');
        $form->time('end_date', __('Session End Time'))->default(date('H:i:s'))->rules('required');
        $form->textarea('details', __('Session Details'))->rules('required');
        $form->hidden('topics_covered', __('Topics covered'));
        $form->multipleImage('attendance_list_pictures', __('Attendance list pictures'));
        $form->multipleFile('members_pictures', __('Session Photos'));
        $form->text('gps_latitude', __('Gps latitude'));
        $form->text('gps_longitude', __('Gps longitude'));

        $form->divider();
        $form->html('<h3>Members Present</h3>');
        $form->select('farmer_group_id', __('Select Farmer Group'))
            ->options(FarmerGroup::where([])
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->rules('required')
            ->default(1);

        $form->listbox('members', 'Members Present')
            ->options(User::where([])
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->help("Select offences involded in this case")
            ->rules('required');

        return $form;
    }
}
