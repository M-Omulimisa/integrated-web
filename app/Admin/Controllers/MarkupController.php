<?php

namespace App\Admin\Controllers;

use App\Models\Insurance\Markup;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MarkupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Insurance Markups';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Markup());
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('amount', __('Amount'));
        $grid->column('status', __('Status'))->using([
            0 => 'Inactive',
            1 => 'Active',
        ]);
        $grid->column('created_at', __('Created At'));

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
        $show = new Show(Markup::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('amount', __('Amount'));
        $show->field('status', __('Status'))->using([
            0 => 'Inactive',
            1 => 'Active',
        ]);
        $show->field('created_at', __('Created At'));
        $show->field('updated_at', __('Updated At'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Markup());
        $form->text('name', __('Name'))->rules('required');
        $form->decimal('amount', __('Amount'))->rules('required|numeric|min:0');
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}