<?php

namespace App\Admin\Controllers;

use App\Models\Weather\WeatherOutbox;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WeatherOutboxController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'WeatherOutbox';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeatherOutbox());

        $grid->column('id', __('Id'));
        $grid->column('subscription_id', __('Subscription id'));
        $grid->column('farmer_id', __('Farmer id'));
        $grid->column('recipient', __('Recipient'));
        $grid->column('message', __('Message'));
        $grid->column('status', __('Status'));
        $grid->column('statuses', __('Statuses'));
        $grid->column('failure_reason', __('Failure reason'));
        $grid->column('processsed_at', __('Processsed at'));
        $grid->column('sent_at', __('Sent at'));
        $grid->column('failed_at', __('Failed at'));
        $grid->column('sent_via', __('Sent via'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(WeatherOutbox::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('subscription_id', __('Subscription id'));
        $show->field('farmer_id', __('Farmer id'));
        $show->field('recipient', __('Recipient'));
        $show->field('message', __('Message'));
        $show->field('status', __('Status'));
        $show->field('statuses', __('Statuses'));
        $show->field('failure_reason', __('Failure reason'));
        $show->field('processsed_at', __('Processsed at'));
        $show->field('sent_at', __('Sent at'));
        $show->field('failed_at', __('Failed at'));
        $show->field('sent_via', __('Sent via'));
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
        $form = new Form(new WeatherOutbox());

        $form->text('subscription_id', __('Subscription id'));
        $form->text('farmer_id', __('Farmer id'));
        $form->text('recipient', __('Recipient'));
        $form->textarea('message', __('Message'));
        $form->text('status', __('Status'));
        $form->text('statuses', __('Statuses'));
        $form->text('failure_reason', __('Failure reason'));
        $form->datetime('processsed_at', __('Processsed at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('sent_at', __('Sent at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('failed_at', __('Failed at'))->default(date('Y-m-d H:i:s'));
        $form->text('sent_via', __('Sent via'))->default('sms');

        return $form;
    }
}
