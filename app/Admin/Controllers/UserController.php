<?php

namespace App\Admin\Controllers;

use App\Models\DistrictModel;
use App\Models\ParishModel;
use App\Models\SubcountyModel;
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

        $u = Admin::user();
        //is not role administrator
        if (!$u->isRole('administrator')) {
            $grid->model()->where('organisation_id', $u->organisation_id);
        }


        $grid->quickSearch('name', 'email', 'phone', 'first_name', "selected_projects", 'last_name')->placeholder('Search by name, email, phone, first name, last name');
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
        $grid->column('phone', __('Phone'))->sortable()->filter('like');
        $grid->column('email', __('Email'))->sortable();
        // $grid->column('selected_projects', __('Affiliations'))->sortable();
        $grid->column('other', __('Other'))->sortable();
        $grid->column('farmer_market_user_type', __('Farmer Market user type'))->sortable();
        $grid->column('done_with_ussd_farming_onboarding', __('Ever used Market USSD'))
            ->display(function ($value) {
                return $value === 'Yes' ? 'Yes' : 'No';
            })
            ->sortable();
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
        $show->field('selected_projects', __('Affiliations'));
        $show->field('other', __('Other'));
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

        $form->divider('Location Information');


        $form->select('parish_id', __('Select Parish'))
            ->options(ParishModel::selectData())
            ->rules('required');

        //organisation_id
        $u = Admin::user();
        $form->hidden('organisation_id', __('Organisation id'))
            ->default($u->organisation_id); 


        /*   $form->select('district_id', __('District'))->options(function ($id) {
            $district = DistrictModel::find($id);
            if ($district) {
                return [$district->id => $district->name];
            }
        })->ajax(env('APP_URL') . '/api/select-distcists')
            ->load('subcounty_id', env('APP_URL') . '/api/select-subcounties?by_id=1', 'id', 'name');
        $form->select('subcounty_id', __('Subcounty'))->options(function ($id) {
            $item = SubcountyModel::find($id);
            if ($item) {
                return [$item->id => $item->name];
            }
        })
            ->load('parish_id', env('APP_URL') . '/api/select-parishes?by_id=1', 'id', 'name'); */
        /* 
        $form->select('parish_id', __('Parish'))->options(function ($id) {
            $item = ParishModel::find($id);
            if ($item) {
                return [$item->id => $item->name];
            }
        }); */


        $form->text('village', __('Village'));
        $form->text('address', __('Address'));


        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);


        $roles = [];

        $u = Admin::user();


        foreach ($roleModel::all() as $key => $r) {
            //is not role administrator
            if (!$u->isRole('administrator')) {
                //if slug is administrator continue
                if ($r->slug == 'administrator') {
                    continue;
                }
            }
            $roles[$r->id] = $r->name;
        }
 

        $form->multipleSelect('roles', trans('admin.roles'))->options($roles);
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
