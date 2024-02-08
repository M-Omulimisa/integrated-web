<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\Settings\Language;

class LanguageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Languages';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Language());
        $grid->quickSearch('name');
        $grid->column('id', __('Id'))->hide();
        $grid->column('name', __('Name'))->sortable();
        /*
            $grid->column('country_id', __('Country id'));
            $grid->column('created_at', __('Created at'));
            $grid->column('updated_at', __('Updated at'));
            $grid->column('position', __('Position'));
        */

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
        $show = new Show(Language::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('country_id', __('Country id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('position', __('Position'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Language());

        $form->text('name', __('Name'));
        $form->hidden('country_id', __('Country id'))->default(1);
        $form->hidden('position', __('Position'))->default(1);

        return $form;
    }
}