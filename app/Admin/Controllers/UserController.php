<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Utils;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'System Users';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->quickSearch('name', 'email', 'phone','first_name','last_name')->placeholder('Search by name, email, phone, first name, last name'); 
        Utils::create_column(
            (new User())->getTable(),
            [
                [
                    'name' => 'has_changed_password',
                    'type' => 'String',
                    'default' => 'No',
                ],
                [
                    'name' => 'raw_password',
                    'type' => 'String',
                ],
                [
                    'name' => 'reset_password_token',
                    'type' => 'String',
                    'default' => 'No',
                ],
            ]
        );


        //photo
        $grid->column('photo', __('Photo'))
            ->image('', 100, 100)
            ->sortable();
        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('organisation_id', __('Organisation'))
            ->display(function ($x) {
                if ($this->organisation == null) {
                    return $x;
                }
                return $this->organisation->name;
            });
        $grid->column('phone', __('Phone'))->sortable();
        $grid->column('email', __('Email'))->sortable();
        $grid->column('roles', trans('admin.roles'))->pluck('name')->label();
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('email', __('Email'));
        $show->field('photo', __('Photo'));
        $show->field('password', __('Password'));
        $show->field('password_last_updated_at', __('Password last updated at'));
        $show->field('last_login_at', __('Last login at'));
        $show->field('created_by', __('Created by'));
        $show->field('status', __('Status'));
        $show->field('verified', __('Verified'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('country_id', __('Country id'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('microfinance_id', __('Microfinance id'));
        $show->field('distributor_id', __('Distributor id'));
        $show->field('buyer_id', __('Buyer id'));
        $show->field('two_auth_method', __('Two auth method'));
        $show->field('user_hash', __('User hash'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('username', __('Username'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $userModel = config('admin.database.users_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new $userModel());

        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->display('id', 'ID');
        $form->text('email', trans('Email Address'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        /*         $form->display('email', 'Email Address')->rules('required|email'); */

        $form->text('username', 'Username')->rules('required');
        $form->text('name', 'Full name')->rules('required');
        $form->text('phone', 'Phone number')->rules('required');

        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);

        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));


        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
            $form->username = strtolower($form->email);
        });

        return $form;
    }
}
