<?php

namespace App\Admin\Controllers;

use App\Models\Ussd\UssdAdvisoryTopic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Ussd\UssdLanguage;

class UssdAdvisoryTopicController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UssdAdvisoryTopic';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UssdAdvisoryTopic());

        $grid->column('topic', __('Topic'));
        $grid->column('position', __('Position'));
        $grid->column('ussd_language_id', __('Language'))->display(function ($lang) {
            if ($this->language == 'null') {
                return $lang;
            }
            return $this->language->language;
        });
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
        $show = new Show(UssdAdvisoryTopic::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('topic', __('Topic'));
        $show->field('description', __('Description'));
        $show->field('position', __('Position'));
        $show->field('ussd_language_id', __('Ussd language id'));
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
        $form = new Form(new UssdAdvisoryTopic());

        $form->text('topic', __('Topic'));
        $form->number('position', __('Position'));
        $form->select('ussd_language_id', 'Select language')->options(UssdLanguage::all()->pluck('language', 'id'));

        return $form;
    }
}
