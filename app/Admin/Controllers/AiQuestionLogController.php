<?php

namespace App\Admin\Controllers;

use App\Models\AiQuestionLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AiQuestionLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'AiQuestionLog';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AiQuestionLog());
        $grid->model()->orderBy('id', 'desc');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return date('Y-m-d H:i:s', strtotime($created_at));
            })->sortable();
        $grid->column('question', __('Question'))->filter('like')->sortable();
        $grid->column('answer', __('Answer'))->filter('like')->sortable();
        $grid->column('audio', __('Audio'))
            ->display(function ($audio) {
                return "<audio controls><source src='$audio' type='audio/mpeg'></audio>";
            })->sortable();
        $grid->column('phone_number', __('Phone number'))->filter('like')->sortable();
        $grid->column('conversation_id', __('Conversation'))
            ->filter('like')->sortable();

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
        $show = new Show(AiQuestionLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('question', __('Question'));
        $show->field('answer', __('Answer'));
        $show->field('audio', __('Audio'));
        $show->field('phone_number', __('Phone number'));
        $show->field('conversation_id', __('Conversation id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AiQuestionLog());

        $form->textarea('question', __('Question'));
        $form->textarea('answer', __('Answer'));
        $form->textarea('audio', __('Audio'));
        $form->textarea('phone_number', __('Phone number'));
        $form->textarea('conversation_id', __('Conversation id'));

        return $form;
    }
}
