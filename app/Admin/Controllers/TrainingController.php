<?php

namespace App\Admin\Controllers;

use App\Models\Settings\Location;
use App\Models\Training\Training;
use App\Models\Training\TrainingSubtopic;
use App\Models\Training\TrainingTopic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class TrainingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Trainings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Training());
        $grid->disableBatchActions();
        // $grid->column('village_agent_id', __('Village agent id'));
        // $grid->column('extension_officer_id', __('Extension officer id'));
        // $grid->column('user_id', __('User id'));
        $grid->column('training_topic.topic', __('Topic'))->sortable();
        $grid->column('name', __('Theme'))->sortable();
        $grid->column('sub_topics', __('Sub Topics'))->display(function ($x) {
            return count($this->sub_topics);
        });
        $grid->column('sessions', __('Sessions'))->display(function ($x) {
            return count($this->sessions);
        });
        $grid->column('date', __('Date'))->sortable();
        $grid->column('time', __('Time'));
        $grid->column('venue', __('Venue'));
        // $grid->column('location_id', __('Location'));
        // $grid->column('latitude', __('Latitude'));
        // $grid->column('longitude', __('Longitude'));
        // $grid->column('status', __('Status'));
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Training::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('village_agent_id', __('Village agent id'));
        $show->field('extension_officer_id', __('Extension officer id'));
        $show->field('user_id', __('User id'));
        $show->field('training_topic_id', __('Subtopic id'));
        $show->field('details', __('Details'));
        $show->field('date', __('Date'));
        $show->field('time', __('Time'));
        $show->field('venue', __('Venue'));
        $show->field('location_id', __('Location id'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Training());

        $u = Auth::user();
        $form->hidden('organisation_id', __('Organisation id'))
            ->default($u->organisation_id);
        $form->hidden('user_id', __('user_id'))
            ->default($u->id);
        $form->hidden('village_agent_id', __('village_agent_id'))
            ->default($u->id);

        $form->hidden('extension_officer_id', __('extension_officer_id'))
            ->default($u->id);


        $form->text('name', __('Training Theme'))->rules('required');

        $form->select('training_topic_id', __('Select Training Topic'))
            ->options(TrainingTopic::where(
                'organisation_id',
                Auth::user()->organisation_id
            )
                ->orderBy('topic', 'asc')
                ->get()->pluck('topic', 'id'))
            ->rules('required');


        $form->date('date', __('Training Date'))
            ->default(date('Y-m-d'))
            ->rules('required');
        $form->time('time', __('Training Time'))
            ->default(date('H:i:s'))
            ->rules('required');
        $form->text('venue', __('Venue'))->rules('required');

        $form->select('location_id', __('Select Venue'))
            ->options(Location::where([])
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'));

        $form->text('latitude', __('G.P.S Latitude'));
        $form->text('longitude', __('G.P.S Longitude'));

        $form->quill('details', __('Training Details'))->rules('required');
        $form->hidden('status', __('Status'))->default('Pending');

        $form->divider();
        $form->morphMany('sub_topics', 'Click on new to add a Sub-Topic', function (Form\NestedForm $form) {
            $u = Admin::user();
            $form->hidden('organisation_id')->default($u->organisation_id);
            $form->text('title', __('Sub-topic Title'))->rules('required');
            $form->quill('details', __('Sub-topic Details'))->rules('required');
        });
        return $form;
    }
}
