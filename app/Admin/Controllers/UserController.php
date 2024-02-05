<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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

        //photo
        /*     $grid->column('photo', __('Photo'))->lightbox(['width' => 50, 'height' => 50]); */
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
    protected function form()
    {
        $form = new Form(new User());
        $roleModel = config('admin.database.roles_model');
        $u = Admin::user();

        $form->hidden('created_by', __('Created by'))
            ->default($u->id);
       
        $form->text('name', __('Name'))->rules('required');
        $form->text('phone', __('Phone'))->rules('required');
        $form->email('email', __('Email'))->rules('required');
        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::all()->pluck('name', 'id'));
        $form->textarea('photo', __('Photo'));
        $form->radio('status', __('Status'))
            ->options(['1' => 'Active', '0' => 'Inactive'])
            ->default('1');

        /*         $form->password('password', __('Password'));
        $form->text('created_by', __('Created by'))
            ->default(Administrator::where('username', 'admin')->first()->id); */

        /*         $form->switch('verified', __('Verified')); 
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->text('country_id', __('Country id'));

        $form->text('microfinance_id', __('Microfinance id'));
        $form->text('distributor_id', __('Distributor id'));
        */
        $form->hidden('organisation_id', __('Organisation'))
            ->default(1);

        /*         $form->textarea('username', __('Username'));
 */
        return $form;
    }
}
