<?php

namespace App\Admin\Controllers;

use App\Models\Training\TrainingTopic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class TrainingTopicController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Training Topics';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TrainingTopic());
        $grid->disableBatchActions();
        $grid->model()->where('organisation_id', Auth::user()->organisation_id);

        $grid->column('country_id', __('Country'))->display(function ($x) {
            if ($this->country == null) {
                return $x;
            }
            return $this->country->name;
        })->hide();
        $grid->quickSearch('topic')->placeholder('Search by topic');
        $grid->column('topic', __('Topic'))->sortable();

        $grid->column('trainings', __('Trainings'))->display(function ($x) {
            return count($this->trainings);
        });
        $grid->column('user_id', __('Created By'))->display(function ($x) {
            if ($this->user == null) {
                return $x;
            }
            return $this->user->name;
        })->sortable();

        $grid->column('details', __('Details'))->hide();

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
        $show = new Show(TrainingTopic::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('country_id', __('Country id'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('topic', __('Topic'));
        $show->field('details', __('Details'));
        $show->field('status', __('Status'));
        $show->field('user_id', __('User id'));
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
        $form = new Form(new TrainingTopic());
        $u = Admin::user();
        $form->hidden('organisation_id', __('Organisation id'))
            ->default($u->organisation_id);
        $form->hidden('user_id', __('Created by'))
            ->default($u->id);
        $form->hidden('status', __('Status'))->default(1);

        $form->text('topic', __('Topic Name'))->rules('required');
        $form->textarea('details', __('Topc Details'))->rules('required');

        $form->disableReset();
        return $form;
    }
}
