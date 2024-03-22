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
        $grid->column('name', __('Name'));
        $grid->column('brief', __('Brief'));
        $grid->column('capital', __('Capital'));
        $grid->column('area_km', __('Area (km)'));
        $grid->column('area_mil', __('Area (mil)'));
        $grid->column('elevation', __('Elevation'));
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
        $show->field('capital', __('Capital'));
        $show->field('area_km', __('Area (km)'));
        $show->field('area_mil', __('Area (mil)'));
        $show->field('elevation', __('Elevation'));
        $show->field('brief', __('Brief'));
        $show->field('menu_status', __('Menu Status'));
        $show->field('menu_name', __('Menu Name'));
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
        $form->text('capital', __('Capital'))->rules('required');
        $form->text('area_km', __('Area (km)'))->rules('required');
        $form->text('area_mil', __('Area (mil)'))->rules('required');
        $form->text('elevation', __('Elevation'))->rules('required');
        $form->textarea('brief', __('Brief'))->rules('required');
        $form->switch('menu_status', __('Menu Status'))->default(0);
        $form->text('menu_name', __('Menu Name'));

        return $form;
    }
}
