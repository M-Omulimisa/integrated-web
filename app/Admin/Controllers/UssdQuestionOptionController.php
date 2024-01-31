<?php

namespace App\Admin\Controllers;

use App\Models\Ussd\UssdQuestionOption;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Ussd\UssdAdvisoryQuestion;

class UssdQuestionOptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UssdQuestionOption';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UssdQuestionOption());

        $grid->column('id', __('Id'));
        $grid->column('option', __('Option'));
        $grid->column('ussd_advisory_question_id', __('Advisory question'))->display(function ($ussd_question) {
            if ($this->question == 'null') {
                return $ussd_question;
            }
            return $this->question->question;
        });
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
        $show = new Show(UssdQuestionOption::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('option', __('Option'));
        $show->field('ussd_advisory_question_id', __('Ussd advisory question id'));
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
        $form = new Form(new UssdQuestionOption());

        $form->textarea('option', __('Option'));
        $form->select('ussd_advisory_question_id', 'Select Advisory Question')->options(UssdAdvisoryQuestion::all()->pluck('question', 'id'));
        $form->number('position', __('Position'));

        return $form;
    }
}
