<?php

namespace App\Admin\Controllers;

use App\Models\AdminRole;
use App\Models\AdminRoleUser;
use App\Models\Organisations\Organisation;
use App\Models\Settings\Country;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Schema;

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
        $u = Admin::user();
        if (!$u->isRole('administrator')) {
            $grid->model()->where('id', $u->organisation_id);
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableFilter();
        }

        $grid->column('logo', __('Logo'))->image();
        $grid->column('name', __('Organisation'))->sortable();
        $grid->column('address', __('Address'))->sortable();
        $grid->column('services', __('Services'))->hide();
        $grid->column('edit', __('Edit'))->display(function () {
            return "<a href='" . admin_url('organisations') . '/' . $this->id . "/edit'>Update Settings</a>";
        });
        $grid->disableActions();

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
        $roles = [];

        $role = AdminRole::where('slug', 'org-admin')->first();

        if ($role == null) {
            return admin_error('Organisation Admin Role not found');
        }

        $admin_role_users = AdminRoleUser::where('role_id', $role->id)->get('user_id');



        $acs = [];
        foreach (User::whereIn('id', $admin_role_users)->get() as $x) {
            $acs[$x->id] = $x->name . ' (' . $x->email . ')';
        }

        $form->text('name', __('Name'))->rules('required');
        $form->select('country_id', __('Select Country'))
            ->options(Country::pluck('name', 'id'))
            ->help('Where this organizaion is based')
            ->rules('required');

        $form->select('user_id', __('Select Organization Admin'))
            ->help('Admin of this organization')
            ->options($acs)
            ->rules('required');
        $form->image('logo', __('Organization\'s Logo'));
        $form->text('address', __('Address'));
        $form->textarea('services', __('Services'))
            ->help('Services offered by this organization');
        $cols = Schema::getColumnListing('farmers');
        $famer_fields = [];
        foreach ($cols as $col) {
            $name = str_replace("_", " ", $col);
            $name = ucwords($name);
            $famer_fields[$col] = $name;
        }
        $form->listbox('farmer_fields', __('Farmer Fields'))
            ->options($famer_fields)
            ->help('Fields that farmers in this organization are interested in')
            ->rules('required');

        $form->disableReset();
        $form->disableViewCheck();

        return $form;
    }
}
