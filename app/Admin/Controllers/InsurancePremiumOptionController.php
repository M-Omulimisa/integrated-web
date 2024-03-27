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

        $grid->column('id', __('Id'))->hide();
        $grid->column('country_id', __('Country'))->display(function ($country_data) {
            if ($this->country == 'null') {

                return $country_data;

            }
            return $this->country->name;
        });
        
        $grid->column('enterprise_id', __('Enterprise'))->display(function ($enterprise_data) {
            if ($this->enterprise == 'null') {

                return $enterprise_data;

            }
            return $this->enterprise->name;
        });
        $grid->column('sum_insured_per_acre', __('Sum insured per acre'));
        $grid->column('premium_per_acre', __('Premium %age per acre'));
        $grid->column('menu', __('Position'))->hide();
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

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
        $show->field('enterprise_id', __('Enterprise id'));
        $show->field('sum_insured_per_acre', __('Sum insured per acre'));
        $show->field('premium_per_acre', __('Premium %age per acre'));
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
 
        $form->select('enterprise_id', __('Enterprise'))
            ->options(\App\Models\Settings\Enterprise::all()->pluck('name', 'id'));

        $form->decimal('sum_insured_per_acre', __('Sum insured per acre'))->default(0.00);
        $form->decimal('premium_per_acre', __('Premium %age per acre'))->default(0.00);
        $form->text('menu', __('Position'));
        $form->switch('status', __('Status'))->default(1);

        return $form;
    }
}
