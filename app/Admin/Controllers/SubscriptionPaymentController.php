<?php

namespace App\Admin\Controllers;

use App\Models\Payments\SubscriptionPayment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SubscriptionPaymentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SubscriptionPayment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SubscriptionPayment());
        $grid->model()->orderBy('updated_at', 'desc');
        $grid->quickSearch(
            'account',
            'reference_id',
            'reference',
            'narrative',
            'details',
            'error_message',
            'weather_subscription_id',
            'market_subscription_id',
            'insurance_subscription_id',
            'weather_session_id',
            'market_session_id',
            'insurance_session_id'

        )
            ->placeholder('Search by account, reference id, reference, narrative, details, error message');
        $grid->column('id', __('Id'))->sortable()->hide();
        $grid->column('weather_subscription_id', __('Weather subscription id'));
        $grid->column('market_subscription_id', __('Market subscription id'));
        $grid->column('insurance_subscription_id', __('Insurance subscription id'));
        $grid->column('method', __('Method'));
        $grid->column('provider', __('Provider'));
        $grid->column('account', __('Account'));
        $grid->column('reference_id', __('Reference id'));
        $grid->column('reference', __('Reference'));
        $grid->column('narrative', __('Narrative'));
        $grid->column('payment_api', __('Payment api'))->hide();
        $grid->column('sms_api', __('Sms api'))->hide();
        $grid->column('amount', __('Amount'));
        $grid->column('status', __('Status'))
            ->filter([
                'SUCCESSFUL' => 'SUCCESSFUL',
                'INDETERMINATE' => 'INDETERMINATE',
                'FAILED' => 'FAILED',
            ])->sortable();
        $grid->column('details', __('Details'));
        $grid->column('error_message', __('Error message'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('tool', __('Tool'));
        $grid->column('weather_session_id', __('Weather session id'));
        $grid->column('market_session_id', __('Market session id'));
        $grid->column('insurance_session_id', __('Insurance session id'));

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
        $show = new Show(SubscriptionPayment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('weather_subscription_id', __('Weather subscription id'));
        $show->field('market_subscription_id', __('Market subscription id'));
        $show->field('insurance_subscription_id', __('Insurance subscription id'));
        $show->field('method', __('Method'));
        $show->field('provider', __('Provider'));
        $show->field('account', __('Account'));
        $show->field('reference_id', __('Reference id'));
        $show->field('reference', __('Reference'));
        $show->field('narrative', __('Narrative'));
        $show->field('payment_api', __('Payment api'));
        $show->field('sms_api', __('Sms api'));
        $show->field('amount', __('Amount'));
        $show->field('status', __('Status'));
        $show->field('details', __('Details'));
        $show->field('error_message', __('Error message'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('tool', __('Tool'));
        $show->field('weather_session_id', __('Weather session id'));
        $show->field('market_session_id', __('Market session id'));
        $show->field('insurance_session_id', __('Insurance session id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SubscriptionPayment());

        $form->text('weather_subscription_id', __('Weather subscription id'));
        $form->text('market_subscription_id', __('Market subscription id'));
        $form->text('insurance_subscription_id', __('Insurance subscription id'));
        $form->text('method', __('Method'));
        $form->text('provider', __('Provider'));
        $form->text('account', __('Account'));
        $form->text('reference_id', __('Reference id'));
        $form->text('reference', __('Reference'));
        $form->text('narrative', __('Narrative'));
        $form->text('payment_api', __('Payment api'));
        $form->text('sms_api', __('Sms api'));
        $form->decimal('amount', __('Amount'));
        $form->text('status', __('Status'))->default('PENDING');
        $form->textarea('details', __('Details'));
        $form->textarea('error_message', __('Error message'));
        $form->text('tool', __('Tool'));
        $form->text('weather_session_id', __('Weather session id'));
        $form->text('market_session_id', __('Market session id'));
        $form->text('insurance_session_id', __('Insurance session id'));

        return $form;
    }
}
