<?php

namespace App\Admin\Controllers;

use App\Models\Market\MarketPackageMessage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MarketPackageMessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Market Package Messages';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MarketPackageMessage());

        $grid->column('id', __('Id'));
        $grid->column('package_id', __('Package id'));
        $grid->column('language_id', __('Language id'));
        $grid->column('menu', __('Menu'));
        $grid->column('message', __('Message'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(MarketPackageMessage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('package_id', __('Package id'));
        $show->field('language_id', __('Language id'));
        $show->field('menu', __('Menu'));
        $show->field('message', __('Message'));
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
        $form = new Form(new MarketPackageMessage());

        $form->text('package_id', __('Package id'));
        $form->text('language_id', __('Language id'));
        $form->text('menu', __('Menu'));
        $form->textarea('message', __('Message'));

        return $form;
    }
}
