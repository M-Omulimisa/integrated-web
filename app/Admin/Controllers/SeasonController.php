<?php

namespace App\Admin\Controllers;

use App\Models\Settings\Season;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SeasonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Seasons';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Season());

        $grid->column('id', __('Id'))->hide();
        $grid->column('country_id', __('Country id'))->hide();
        $grid->column('name', __('Name'));
        $grid->column('start_date', __('Start date'));
        $grid->column('end_date', __('End date'));
        $grid->column('cut_off_date', __('Cut Off date'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'))->hide();

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
        $show = new Show(Season::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('country_id', __('Country id'));
        $show->field('name', __('Name'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('cut_off_date', __('Cut Off date'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Season());

        $form->select('country_id', __('Country'))
            ->options(\App\Models\Settings\Country::all()->pluck('name', 'id'))
            ->rules('required');
        $form->text('name', __('Name'));
        $form->date('start_date', __('Start date'))->default(date('Y-m-d'));
        $form->date('cut_off_date', __('Cut off date'))->default(date('Y-m-d'));
        $form->date('end_date', __('End date'))->default(date('Y-m-d'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
