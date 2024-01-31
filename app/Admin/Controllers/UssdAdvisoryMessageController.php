<?php

namespace App\Admin\Controllers;

use App\Models\Ussd\UssdAdvisoryMessage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Ussd\UssdQuestionOption;

class UssdAdvisoryMessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UssdAdvisoryMessage';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UssdAdvisoryMessage());

        $grid->column('id', __('Id'));
        $grid->column('message', __('Message'));
        $grid->column('ussd_question_option_id', __('Advisory Question option'))->display(function ($ussd_question_option) {
            if ($this->option == 'null') {
                return $ussd_question_option;
            }
            return $this->option->option;
        });;

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
        $show = new Show(UssdAdvisoryMessage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('message', __('Message'));
        $show->field('ussd_question_option_id', __('Ussd question option id'));
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
        $form = new Form(new UssdAdvisoryMessage());

        $form->textarea('message', __('Message'));
        $form->select('ussd_question_option_id', 'Select Advisory Question Option')->options(UssdQuestionOption::all()->pluck('option', 'id'));

        return $form;
    }
}
