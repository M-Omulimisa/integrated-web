<?php

namespace App\Admin\Controllers;

use App\Models\Ussd\UssdAdvisoryQuestion;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Ussd\UssdAdvisoryTopic;

class UssdAdvisoryQuestionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UssdAdvisoryQuestion';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UssdAdvisoryQuestion());

        $grid->column('id', __('Id'));
        $grid->column('question', __('Question'));
        $grid->column('ussd_advisory_topic_id', __('Ussd advisory topic'))->display(function ($ussd_advisory_topic) {
            if ($this->topic == 'null') {
                return $ussd_advisory_topic;
            }
            return $this->topic->topic;
        });;
        $grid->column('position', __('Position'));   
        $grid->column('created_at', __('Created at'));

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
        $show = new Show(UssdAdvisoryQuestion::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('question', __('Question'));
        $show->field('ussd_advisory_topic_id', __('Ussd advisory topic id'));
        $show->field('position', __('Position'));
        $show->field('deleted_at', __('Deleted at'));
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
        $form = new Form(new UssdAdvisoryQuestion());

        $form->textarea('question', __('Question'));

        $form->select('ussd_advisory_topic_id', 'Select topic')->options(UssdAdvisoryTopic::all()->pluck('topic', 'id'));

        $form->number('position', __('Position'));

        return $form;
    }
}
