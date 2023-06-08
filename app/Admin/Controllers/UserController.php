<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
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
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('phone', __('Phone'));
        $grid->column('email', __('Email'));
        $grid->column('photo', __('Photo'));
        $grid->column('password', __('Password'));
        $grid->column('password_last_updated_at', __('Password last updated at'));
        $grid->column('last_login_at', __('Last login at'));
        $grid->column('created_by', __('Created by'));
        $grid->column('status', __('Status'));
        $grid->column('verified', __('Verified'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('country_id', __('Country id'));
        $grid->column('organisation_id', __('Organisation id'));
        $grid->column('microfinance_id', __('Microfinance id'));
        $grid->column('distributor_id', __('Distributor id'));
        $grid->column('buyer_id', __('Buyer id'));
        $grid->column('two_auth_method', __('Two auth method'));
        $grid->column('user_hash', __('User hash'));
        $grid->column('remember_token', __('Remember token'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('username', __('Username'));

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

        $form->text('name', __('Name'));
        $form->mobile('phone', __('Phone'));
        $form->email('email', __('Email'));
        $form->textarea('photo', __('Photo'));
        $form->password('password', __('Password'));
        $form->datetime('password_last_updated_at', __('Password last updated at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('last_login_at', __('Last login at'))->default(date('Y-m-d H:i:s'));
        $form->text('created_by', __('Created by'));
        $form->text('status', __('Status'))->default('Active');
        $form->switch('verified', __('Verified'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->text('country_id', __('Country id'));
        $form->text('organisation_id', __('Organisation id'));
        $form->text('microfinance_id', __('Microfinance id'));
        $form->text('distributor_id', __('Distributor id'));
        $form->text('buyer_id', __('Buyer id'));
        $form->text('two_auth_method', __('Two auth method'))->default('EMAIL');
        $form->text('user_hash', __('User hash'));
        $form->text('remember_token', __('Remember token'));
        $form->textarea('username', __('Username'));

        return $form;
    }
}
