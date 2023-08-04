<?php

namespace App\Admin\Controllers;

use App\Models\FarmerQuestionAnswer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FarmerQuestionAnswerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Farmer Question Answers';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new FarmerQuestionAnswer());

        $grid->column('id', __('Id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('user_id', __('User id'));
        $grid->column('farmer_question_id', __('Farmer question id'));
        $grid->column('verified', __('Verified'));
        $grid->column('body', __('Body'));
        $grid->column('audio', __('Audio'));
        $grid->column('photo', __('Photo'));
        $grid->column('video', __('Video'));
        $grid->column('document', __('Document'));

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
        $show = new Show(FarmerQuestionAnswer::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('user_id', __('User id'));
        $show->field('farmer_question_id', __('Farmer question id'));
        $show->field('verified', __('Verified'));
        $show->field('body', __('Body'));
        $show->field('audio', __('Audio'));
        $show->field('photo', __('Photo'));
        $show->field('video', __('Video'));
        $show->field('document', __('Document'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new FarmerQuestionAnswer());

        $form->text('user_id', __('User id'))->default('1');
        $form->number('farmer_question_id', __('Farmer question id'))->default(1);
        $form->text('verified', __('Verified'))->default('no');
        $form->textarea('body', __('Body'));
        $form->textarea('audio', __('Audio'));
        $form->textarea('photo', __('Photo'));
        $form->textarea('video', __('Video'));
        $form->textarea('document', __('Document'));

        return $form;
    }
}
