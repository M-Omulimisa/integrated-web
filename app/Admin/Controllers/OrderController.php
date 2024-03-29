<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Models\User;
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
    protected $title = 'Orders';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());
        $grid->disableBatchActions();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->model()->orderBy('id', 'desc');
        $grid->column('user', __('Customer'))->display(function ($user) {
            $u = User::find($user);
            if ($u != null) {
                return $u->name;
            }
            return 'Deleted';
        })->sortable();
        $grid->column('order_state', __('Order Status'))->label([
            'Pending' => 'warning',
            'Processing' => 'info',
            'Completed' => 'success',
            'Cancelled' => 'danger',
        ])->sortable();
        $grid->column('amount', __('Total Amount (UGX)'))
            ->display(function ($amount) {
                if ($amount == null || $amount == 0 || $amount == '') {
                    return '-';
                }
                $amount = (int) $amount;
                return number_format($amount);
            })
            ->sortable();
        $grid->column('created_at', __('Date Created'))
            ->display(function ($date_created) {
                if ($date_created == null || $date_created == 0 || $date_created == '') {
                    return '-';
                }
                return date('d M Y', strtotime($date_created));
            })->sortable();
        $grid->column('payment_confirmation', __('Payment'))
            ->label([
                'Pending' => 'warning',
                'Confirmed' => 'success',
                'Failed' => 'danger',
            ])->sortable();
        $grid->column('description', __('Description'))->hide();
        $grid->column('customer_name', __('Customer name'));
        $grid->column('customer_phone_number_1', __('Customer Contact'))->hide();
        $grid->column('customer_address', __('Customer Address'));
        $grid->column('get_items_1', __('Order items'))
            ->display(function () {
                $items = [];
                foreach ($this->get_items() as $item) {
                    $items[] = $item->product_name . ' (' . $item->product_quantity . ' x ' . number_format($item->product_price_1) . ')';
                }
                return implode(', ', $items);
            });
        $grid->column('order_total', __('Order total'))->sortable();
        $grid->column('order_details', __('Order details'))->hide();
        $grid->column('get_items', __('Order Items'))->display(function () {
            $items = [];
            foreach ($this->get_items() as $item) {
                $items[] = $item->product_name . ' (' . $item->product_quantity . ' x ' . number_format($item->product_price_1) . ')';
            }
            return implode(', ', $items);
        });
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


        $form->display('user', __('Customer'))
            ->with(function ($user) {
                $u = User::find($user);
                if ($u != null) {
                    return $u->name;
                }
                return 'Deleted';
            })->readonly();


        $form->display('amount', __('Total Amount'));
        $form->date('date_created', __('Date Created'))->default(date('Y-m-d'))
            ->readonly();

        $form->select('payment_confirmation', __('Payment Confirmation'))
            ->options([
                'PAID' => 'PAID',
                'NOT PAID' => 'NOT PAID',
            ])->readonly();
        $form->text('mail', __('Customer Email Address'))
            ->readonly();

        $form->text('customer_phone_number_1', __('Customer phone number 1'))->readonly();
        $form->text('customer_phone_number_2', __('Customer phone number 2'))->readonly();
        $form->text('customer_address', __('Customer address'));

        $form->divider();
        $form->radioCard('order_state', __('Order Status'))
            ->options([
                'Pending' => 'Pending',
                'Processing' => 'Processing',
                'Shipping' => 'Shipping',
                'Completed' => 'Completed',
                'Cancelled' => 'Cancelled',
            ])->default('Pending');

        $form->disableCreatingCheck();
        $form->disableEditingCheck();
        $form->disableReset();
        $form->disableViewCheck();
        /* 
        $form->text('description', __('Order Notes'));
        $form->text('customer_name', __('Customer Name'));

 */
        return $form;
    }
}
