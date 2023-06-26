<?php

namespace App\Admin\Controllers;

use App\Models\Settings\Country;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CountryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Countries';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Country());
        $grid->disableBatchActions();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('iso_code', __('Iso Code'))->sortable();
        $grid->column('dialing_code', __('Dialing Code'))->sortable();
        $grid->column('nationality', __('Nationality'))->hide();
        $grid->column('longitude', __('Longitude'))->hide();
        $grid->column('latitude', __('Latitude'))->hide();
        $grid->column('length', __('Phone No. Length'))->hide();

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
        $show = new Show(Country::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('iso_code', __('Iso code'));
        $show->field('dialing_code', __('Dialing code'));
        $show->field('nationality', __('Nationality'));
        $show->field('longitude', __('Longitude'));
        $show->field('latitude', __('Latitude'));
        $show->field('length', __('Length'));
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
        $form = new Form(new Country());

        $form->text('name', __('Name'))->required();
        $form->text('iso_code', __('Iso code'));
        $form->text('dialing_code', __('Dialing code'));
        $form->text('nationality', __('Nationality'));
        $form->text('longitude', __('Longitude'));
        $form->text('latitude', __('Latitude'));
        $form->number('length', __('Length'));

        return $form;
    }
}
