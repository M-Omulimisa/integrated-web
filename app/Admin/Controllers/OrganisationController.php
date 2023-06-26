<?php

namespace App\Admin\Controllers;

use App\Models\Organisations\Organisation;
use App\Models\Settings\Country;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrganisationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Organisations';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Organisation());
        $grid->disableBatchActions();

        $grid->column('id', __('Id'))->hide();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('logo', __('Logo'));
        $grid->column('address', __('Address'));
        $grid->column('services', __('Services'));

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
        $show = new Show(Organisation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('logo', __('Logo'));
        $show->field('address', __('Address'));
        $show->field('services', __('Services'));
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
        $form = new Form(new Organisation());

        $acs = [];
        foreach (User::all() as $x) {
            $acs[$x->id] = $x->name;
        }
        $form->text('name', __('Name'))->rules('required');
        $form->select('country_id', __('Select Country'))
            ->options(Country::pluck('name', 'id'))
            ->help('Where this organizaion is based')
            ->rules('required');

        $form->select('user_id', __('Select Organization Owner'))
            ->help('Admin of this organization')
            ->options($acs)
            ->rules('required');
        $form->image('logo', __('Organization\'s Logo'));
        $form->text('address', __('Address'));
        $form->textarea('services', __('Services'));

        $form->disableReset();
        $form->disableViewCheck();

        return $form;
    }
}
