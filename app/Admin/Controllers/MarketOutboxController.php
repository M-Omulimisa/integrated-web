<?php

namespace App\Admin\Controllers;

use App\Models\Market\MarketOutbox;
use App\Models\MarketInfoMessageCampaign;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MarketOutboxController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Market Outboxes';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MarketOutbox());

        $campaigns = [];
        foreach (MarketInfoMessageCampaign::where([])
            ->orderBy('created_at', 'desc')->get()
            as $campaign) {
            $campaigns[$campaign->id] = Utils::my_date($campaign->created_at);
        }

        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('subscription_id', __('Subscription'))->hide();
        $grid->column('farmer_id', __('Farmer id'))->hide();
        $grid->column('recipient', __('Recipient'))
            ->filter('like')
            ->sortable()
            ->width(120);
        $grid->column('message', __('Message'))
            ->filter('like')
            ->sortable()
            ->limit(60);
        $grid->column('status', __('Status'))
            ->width(80)
            ->filter([
                'Pending' => 'Pending',
                'Sent' => 'Sent',
                'Failed' => 'Failed',
            ])
            ->label([
                'Pending' => 'info',
                'Sent' => 'success',
                'Failed' => 'danger',
            ]);
        $grid->column('failure_reason', __('Failure reason'))->hide();
        $grid->column('processsed_at', __('Processsed at'))->hide();
        $grid->column('sent_at', __('Sent at'))->hide();
        $grid->column('failed_at', __('Failed at'))->hide();
        $grid->column('statuses', __('Statuses'))->hide();
        $grid->column('sent_via', __('Sent via'))->hide();
        $grid->column('created_at', __('DATE'))
            ->sortable()
            ->width(100)
            ->filter('range', 'date')
            ->display(function ($created_at) {
                return date('d-m-Y', strtotime($created_at));
            });
        $grid->column('market_info_message_campaign_id', __('Campaign'))
            ->display(function () {
                if ($this->campaign == null) {
                    return '-';
                }
                return Utils::my_date($this->campaign->created_at);
            })
            ->filter($campaigns)
            ->sortable();

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
        $show = new Show(MarketOutbox::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('subscription_id', __('Subscription id'));
        $show->field('farmer_id', __('Farmer id'));
        $show->field('recipient', __('Recipient'));
        $show->field('message', __('Message'));
        $show->field('status', __('Status'));
        $show->field('failure_reason', __('Failure reason'));
        $show->field('processsed_at', __('Processsed at'));
        $show->field('sent_at', __('Sent at'));
        $show->field('failed_at', __('Failed at'));
        $show->field('statuses', __('Statuses'));
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
        $form = new Form(new MarketOutbox());

        $form->text('subscription_id', __('Subscription id'));
        $form->text('farmer_id', __('Farmer id'));
        $form->text('recipient', __('Recipient'));
        $form->textarea('message', __('Message'));
        $form->text('status', __('Status'));
        $form->text('failure_reason', __('Failure reason'));
        $form->datetime('processsed_at', __('Processsed at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('sent_at', __('Sent at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('failed_at', __('Failed at'))->default(date('Y-m-d H:i:s'));
        $form->text('statuses', __('Statuses'));
        $form->text('sent_via', __('Sent via'))->default('sms');

        return $form;
    }
}
