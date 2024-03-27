<?php

namespace App\Admin\Controllers;

use App\Models\Settings\Region;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RegionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Regions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Region());
        $grid->column('id', __('Id'));
        $grid->column('name', __('Region Name'))->sortable();
        $grid->column('menu_status', __('Status'));
        $grid->column('created_at', __('Created At'))->hide();
        $grid->column('updated_at', __('Updated At'))->hide();

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
        $show = new Show(Region::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('menu_status', __('Status'));
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
        $form = new Form(new Region());
        $form->text('name', __('Name'))->rules('required');
        $form->switch('menu_status', __('Status'))->default(0);

        return $form;
    }
}
