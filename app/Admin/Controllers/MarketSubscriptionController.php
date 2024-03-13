<?php

namespace App\Admin\Controllers;

use App\Models\Market\MarketSubscription;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MarketSubscriptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'MarketSubscription';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MarketSubscription());

        $grid->column('id', __('Id'));
        $grid->column('farmer_id', __('Farmer id'));
        $grid->column('language_id', __('Language id'));
        $grid->column('location_id', __('Location id'));
        $grid->column('district_id', __('District id'));
        $grid->column('subcounty_id', __('Subcounty id'));
        $grid->column('parish_id', __('Parish id'));
        $grid->column('first_name', __('First name'));
        $grid->column('last_name', __('Last name'));
        $grid->column('email', __('Email'));
        $grid->column('frequency', __('Frequency'));
        $grid->column('period_paid', __('Period paid'));
        $grid->column('start_date', __('Start date'));
        $grid->column('end_date', __('End date'));
        $grid->column('status', __('Status'));
        $grid->column('user_id', __('User id'));
        $grid->column('outbox_generation_status', __('Outbox generation status'));
        $grid->column('outbox_reset_status', __('Outbox reset status'));
        $grid->column('outbox_last_date', __('Outbox last date'));
        $grid->column('seen_by_admin', __('Seen by admin'));
        $grid->column('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'));
        $grid->column('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'));
        $grid->column('renewal_id', __('Renewal id'));
        $grid->column('organisation_id', __('Organisation id'));
        $grid->column('package_id', __('Package id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('phone', __('Phone'));
        $grid->column('region_id', __('Region id'));
        $grid->column('payment_id', __('Payment id'));
        $grid->column('outbox_count', __('Outbox count'));

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
        $show = new Show(MarketSubscription::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('farmer_id', __('Farmer id'));
        $show->field('language_id', __('Language id'));
        $show->field('location_id', __('Location id'));
        $show->field('district_id', __('District id'));
        $show->field('subcounty_id', __('Subcounty id'));
        $show->field('parish_id', __('Parish id'));
        $show->field('first_name', __('First name'));
        $show->field('last_name', __('Last name'));
        $show->field('email', __('Email'));
        $show->field('frequency', __('Frequency'));
        $show->field('period_paid', __('Period paid'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('status', __('Status'));
        $show->field('user_id', __('User id'));
        $show->field('outbox_generation_status', __('Outbox generation status'));
        $show->field('outbox_reset_status', __('Outbox reset status'));
        $show->field('outbox_last_date', __('Outbox last date'));
        $show->field('seen_by_admin', __('Seen by admin'));
        $show->field('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'));
        $show->field('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'));
        $show->field('renewal_id', __('Renewal id'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('package_id', __('Package id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('phone', __('Phone'));
        $show->field('region_id', __('Region id'));
        $show->field('payment_id', __('Payment id'));
        $show->field('outbox_count', __('Outbox count'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MarketSubscription());

        $form->text('farmer_id', __('Farmer id'));
        $form->text('language_id', __('Language id'));
        $form->text('location_id', __('Location id'));
        $form->text('district_id', __('District id'));
        $form->text('subcounty_id', __('Subcounty id'));
        $form->text('parish_id', __('Parish id'));
        $form->text('first_name', __('First name'));
        $form->text('last_name', __('Last name'));
        $form->email('email', __('Email'));
        $form->text('frequency', __('Frequency'));
        $form->number('period_paid', __('Period paid'));
        $form->text('start_date', __('Start date'));
        $form->text('end_date', __('End date'));
        $form->switch('status', __('Status'));
        $form->text('user_id', __('User id'));
        $form->switch('outbox_generation_status', __('Outbox generation status'));
        $form->switch('outbox_reset_status', __('Outbox reset status'));
        $form->date('outbox_last_date', __('Outbox last date'))->default(date('Y-m-d'));
        $form->switch('seen_by_admin', __('Seen by admin'));
        $form->datetime('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'))->default(date('Y-m-d H:i:s'));
        $form->textarea('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'));
        $form->text('renewal_id', __('Renewal id'));
        $form->text('organisation_id', __('Organisation id'));
        $form->text('package_id', __('Package id'));
        $form->mobile('phone', __('Phone'));
        $form->text('region_id', __('Region id'));
        $form->text('payment_id', __('Payment id'));
        $form->text('outbox_count', __('Outbox count'));

        return $form;
    }
}
