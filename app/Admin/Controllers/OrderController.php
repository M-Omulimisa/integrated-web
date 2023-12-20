<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('user', __('User'));
        $grid->column('order_state', __('Order state'));
        $grid->column('amount', __('Amount'));
        $grid->column('date_created', __('Date created'));
        $grid->column('payment_confirmation', __('Payment confirmation'));
        $grid->column('date_updated', __('Date updated'));
        $grid->column('mail', __('Mail'));
        $grid->column('delivery_district', __('Delivery district'));
        $grid->column('temporary_id', __('Temporary id'));
        $grid->column('description', __('Description'));
        $grid->column('customer_name', __('Customer name'));
        $grid->column('customer_phone_number_1', __('Customer phone number 1'));
        $grid->column('customer_phone_number_2', __('Customer phone number 2'));
        $grid->column('customer_address', __('Customer address'));
        $grid->column('order_total', __('Order total'));
        $grid->column('order_details', __('Order details'));
        $grid->column('stripe_id', __('Stripe id'));
        $grid->column('stripe_url', __('Stripe url'));
        $grid->column('stripe_paid', __('Stripe paid'));
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
        $show = new Show(Order::findOrFail($id));

        $show->field('user', __('User'));
        $show->field('order_state', __('Order state'));
        $show->field('amount', __('Amount'));
        $show->field('date_created', __('Date created'));
        $show->field('payment_confirmation', __('Payment confirmation'));
        $show->field('date_updated', __('Date updated'));
        $show->field('mail', __('Mail'));
        $show->field('delivery_district', __('Delivery district'));
        $show->field('temporary_id', __('Temporary id'));
        $show->field('description', __('Description'));
        $show->field('customer_name', __('Customer name'));
        $show->field('customer_phone_number_1', __('Customer phone number 1'));
        $show->field('customer_phone_number_2', __('Customer phone number 2'));
        $show->field('customer_address', __('Customer address'));
        $show->field('order_total', __('Order total'));
        $show->field('order_details', __('Order details'));
        $show->field('stripe_id', __('Stripe id'));
        $show->field('stripe_url', __('Stripe url'));
        $show->field('stripe_paid', __('Stripe paid'));
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
        $form = new Form(new Order());

        $form->textarea('user', __('User'));
        $form->textarea('order_state', __('Order state'));
        $form->textarea('amount', __('Amount'));
        $form->textarea('date_created', __('Date created'));
        $form->textarea('payment_confirmation', __('Payment confirmation'));
        $form->textarea('date_updated', __('Date updated'));
        $form->textarea('mail', __('Mail'));
        $form->textarea('delivery_district', __('Delivery district'));
        $form->textarea('temporary_id', __('Temporary id'));
        $form->textarea('description', __('Description'));
        $form->textarea('customer_name', __('Customer name'));
        $form->textarea('customer_phone_number_1', __('Customer phone number 1'));
        $form->textarea('customer_phone_number_2', __('Customer phone number 2'));
        $form->textarea('customer_address', __('Customer address'));
        $form->textarea('order_total', __('Order total'));
        $form->textarea('order_details', __('Order details'));
        $form->textarea('stripe_id', __('Stripe id'));
        $form->textarea('stripe_url', __('Stripe url'));
        $form->textarea('stripe_paid', __('Stripe paid'));

        return $form;
    }
}
