<?php

namespace App\Admin\Controllers;

use App\Models\Weather\WeatherSubscription;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WeatherSubscriptionV2Controller extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'WeatherSubscription';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeatherSubscription());

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
        $grid->column('awhere_field_id', __('Awhere field id'));
        $grid->column('seen_by_admin', __('Seen by admin'));
        $grid->column('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'));
        $grid->column('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'));
        $grid->column('renewal_id', __('Renewal id'));
        $grid->column('organisation_id', __('Organisation id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('phone', __('Phone'));
        $grid->column('payment_id', __('Payment id'));
        $grid->column('MNOTransactionReferenceId', __('MNOTransactionReferenceId'));
        $grid->column('payment_reference_id', __('Payment reference id'));
        $grid->column('TransactionStatus', __('TransactionStatus'));
        $grid->column('TransactionAmount', __('TransactionAmount'));
        $grid->column('TransactionCurrencyCode', __('TransactionCurrencyCode'));
        $grid->column('TransactionReference', __('TransactionReference'));
        $grid->column('TransactionInitiationDate', __('TransactionInitiationDate'));
        $grid->column('TransactionCompletionDate', __('TransactionCompletionDate'));
        $grid->column('is_paid', __('Is paid'));
        $grid->column('total_price', __('Total price'));
        $grid->column('renew_message_sent', __('Renew message sent'));
        $grid->column('renew_message_sent_at', __('Renew message sent at'));
        $grid->column('renew_message_sent_details', __('Renew message sent details'));
        $grid->column('is_processed', __('Is processed'));
        $grid->column('is_test', __('Is test'));
        $grid->column('pre_renew_message_sent', __('Pre renew message sent'));
        $grid->column('pre_renew_message_sent_at', __('Pre renew message sent at'));
        $grid->column('pre_renew_message_sent_details', __('Pre renew message sent details'));
        $grid->column('welcome_msg_sent', __('Welcome msg sent'));
        $grid->column('welcome_msg_sent_at', __('Welcome msg sent at'));
        $grid->column('welcome_msg_sent_details', __('Welcome msg sent details'));

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
        $show = new Show(WeatherSubscription::findOrFail($id));

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
        $show->field('awhere_field_id', __('Awhere field id'));
        $show->field('seen_by_admin', __('Seen by admin'));
        $show->field('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'));
        $show->field('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'));
        $show->field('renewal_id', __('Renewal id'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('phone', __('Phone'));
        $show->field('payment_id', __('Payment id'));
        $show->field('MNOTransactionReferenceId', __('MNOTransactionReferenceId'));
        $show->field('payment_reference_id', __('Payment reference id'));
        $show->field('TransactionStatus', __('TransactionStatus'));
        $show->field('TransactionAmount', __('TransactionAmount'));
        $show->field('TransactionCurrencyCode', __('TransactionCurrencyCode'));
        $show->field('TransactionReference', __('TransactionReference'));
        $show->field('TransactionInitiationDate', __('TransactionInitiationDate'));
        $show->field('TransactionCompletionDate', __('TransactionCompletionDate'));
        $show->field('is_paid', __('Is paid'));
        $show->field('total_price', __('Total price'));
        $show->field('renew_message_sent', __('Renew message sent'));
        $show->field('renew_message_sent_at', __('Renew message sent at'));
        $show->field('renew_message_sent_details', __('Renew message sent details'));
        $show->field('is_processed', __('Is processed'));
        $show->field('is_test', __('Is test'));
        $show->field('pre_renew_message_sent', __('Pre renew message sent'));
        $show->field('pre_renew_message_sent_at', __('Pre renew message sent at'));
        $show->field('pre_renew_message_sent_details', __('Pre renew message sent details'));
        $show->field('welcome_msg_sent', __('Welcome msg sent'));
        $show->field('welcome_msg_sent_at', __('Welcome msg sent at'));
        $show->field('welcome_msg_sent_details', __('Welcome msg sent details'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeatherSubscription());

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
        $form->text('awhere_field_id', __('Awhere field id'));
        $form->switch('seen_by_admin', __('Seen by admin'));
        $form->datetime('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'))->default(date('Y-m-d H:i:s'));
        $form->textarea('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'));
        $form->text('renewal_id', __('Renewal id'));
        $form->text('organisation_id', __('Organisation id'));
        $form->mobile('phone', __('Phone'));
        $form->text('payment_id', __('Payment id'));
        $form->textarea('MNOTransactionReferenceId', __('MNOTransactionReferenceId'));
        $form->textarea('payment_reference_id', __('Payment reference id'));
        $form->textarea('TransactionStatus', __('TransactionStatus'));
        $form->textarea('TransactionAmount', __('TransactionAmount'));
        $form->textarea('TransactionCurrencyCode', __('TransactionCurrencyCode'));
        $form->textarea('TransactionReference', __('TransactionReference'));
        $form->textarea('TransactionInitiationDate', __('TransactionInitiationDate'));
        $form->textarea('TransactionCompletionDate', __('TransactionCompletionDate'));
        $form->text('is_paid', __('Is paid'))->default('No');
        $form->number('total_price', __('Total price'));
        $form->text('renew_message_sent', __('Renew message sent'))->default('No');
        $form->datetime('renew_message_sent_at', __('Renew message sent at'))->default(date('Y-m-d H:i:s'));
        $form->textarea('renew_message_sent_details', __('Renew message sent details'));
        $form->text('is_processed', __('Is processed'))->default('No');
        $form->text('is_test', __('Is test'))->default('No');
        $form->text('pre_renew_message_sent', __('Pre renew message sent'))->default('No');
        $form->datetime('pre_renew_message_sent_at', __('Pre renew message sent at'))->default(date('Y-m-d H:i:s'));
        $form->textarea('pre_renew_message_sent_details', __('Pre renew message sent details'));
        $form->text('welcome_msg_sent', __('Welcome msg sent'))->default('No');
        $form->datetime('welcome_msg_sent_at', __('Welcome msg sent at'))->default(date('Y-m-d H:i:s'));
        $form->textarea('welcome_msg_sent_details', __('Welcome msg sent details'));

        return $form;
    }
}
