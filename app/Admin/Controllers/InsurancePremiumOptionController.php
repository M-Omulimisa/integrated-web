<?php

namespace App\Admin\Controllers;

use App\Models\Insurance\InsurancePremiumOption;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InsurancePremiumOptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'InsurancePremiumOption';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new InsurancePremiumOption());

        $grid->column('id', __('Id'));
        $grid->column('country_id', __('Country id'));
        $grid->column('season_id', __('Season id'));
        $grid->column('enterprise_id', __('Enterprise id'));
        $grid->column('sum_insured_per_acre', __('Sum insured per acre'));
        $grid->column('premium_per_acre', __('Premium per acre'));
        $grid->column('menu', __('Menu'));
        $grid->column('status', __('Status'));
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
        $show = new Show(InsurancePremiumOption::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('country_id', __('Country id'));
        $show->field('season_id', __('Season id'));
        $show->field('enterprise_id', __('Enterprise id'));
        $show->field('sum_insured_per_acre', __('Sum insured per acre'));
        $show->field('premium_per_acre', __('Premium per acre'));
        $show->field('menu', __('Menu'));
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
        $form = new Form(new InsurancePremiumOption());

        $form->select('country_id', __('Country'))
            ->options(\App\Models\Settings\Country::all()->pluck('name', 'id'))
            ->rules('required');
        $form->select('season_id', __('Season'))
            ->options(\App\Models\Settings\Season::all()->pluck('name', 'id'));
        $form->select('enterprise_id', __('Enterprise'))
            ->options(\App\Models\Settings\Enterprise::all()->pluck('name', 'id'));

        $form->decimal('sum_insured_per_acre', __('Sum insured per acre'))->default(0.00);
        $form->decimal('premium_per_acre', __('Premium per acre'))->default(0.00);
        $form->text('menu', __('Menu'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
