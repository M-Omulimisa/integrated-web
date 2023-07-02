<?php

namespace App\Admin\Controllers;

use App\Models\Training\TrainingSubtopic;
use App\Models\Training\TrainingTopic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class TrainingSubtopicController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Training Subtopics';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TrainingSubtopic());

        $grid->disableBatchActions();
        $grid->model()->where('organisation_id', Auth::user()->organisation_id);

        $grid->quickSearch('title')->placeholder('Search by title');

        $grid->column('title', __('Subtpic Title'))->sortable();
        $grid->column('topic_id', __('Topic'))->display(function ($x) {
            if ($this->topic == null) {
                return $x;
            }
            return $this->topic->topic;
        })->sortable();
        $grid->column('type', __('Type'))->label();
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
        $show = new Show(TrainingSubtopic::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('topic_id', __('Topic id'));
        $show->field('title', __('Title'));
        $show->field('type', __('Type'));
        $show->field('details', __('Details'));
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
        $form = new Form(new TrainingSubtopic());
        $form->hidden('organisation_id', __('Organisation id'))
            ->default(Auth::user()->organisation_id);

        $form->select('topic_id', __('Topic'))
            ->options(TrainingTopic::where(
                'organisation_id',
                Auth::user()->organisation_id
            )
                ->orderBy('topic', 'asc')
                ->get()->pluck('topic', 'id'))->rules('required');

        $form->text('title', __('Title'))->rules('required');
        $form->radioCard('type', __('Type'))
            ->options([
                'Subtopic' => 'Subtopic',
                'Activity' => 'Activity',
            ])->rules('required');
        $form->textarea('details', __('Details'));
        $form->hidden('status', __('Status'))->default(1);
        $form->disableReset();
        return $form;
    }
}
