<?php

namespace App\Admin\Controllers;

use App\Models\Market\MarketPackage;
use App\Models\MarketInfoMessageCampaign;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MarketInfoMessageCampaignController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Market Info Message Campaigns';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new MarketInfoMessageCampaign());
        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('created_at', __('Date Created'))
            ->display(function ($created_at) {
                return date('d-m-Y', strtotime($created_at));
            })->sortable();
        $grid->column('packages', __('Packages'))
            ->display(function ($packages) {
                $data = "";
                foreach ($packages as $key => $value) {
                    $package = MarketPackage::find($value);
                    if ($package != null) {
                        $data .= "<span class='label label-success'>{$package->name}</span> ";
                    }
                }
                return $data;
            });
        /*         $grid->column('send_now', __('Send now'));
        $grid->column('confirm_send', __('Confirm send')); */

        //messages count
        $grid->column('messages_count', __('Messages'))
            ->display(function () {
                return $this->messages()->count();
            });
        $grid->column('message_sent', __('Message Sent'))
            ->display(function ($message_sent) {
                if ($message_sent == 'Yes') {
                    return 'Sent';
                }
                return 'Not Sent';
                return $message_sent;
            })->sortable();

        //outboxes count
        $grid->column('outboxes_count', __('Outboxes'))
            ->display(function () {
                return $this->outboxes()->count();
            });
        //send now button
        $grid->column('send_now', __('Send Now'))
            ->display(function ($send_now) {
                if ($this->message_sent == 'Yes') {
                    return "<a href='javascript:;' class='btn btn-xs btn-secondary'>Send Now</a>";
                }
                if ($send_now == 'Yes') {
                    $link = url('market-info-message-campaigns-send-now?id=' . $this->id);
                    return "<a target='_blank' href='{$link}' class='btn btn-xs btn-success'>Send Now</a>";
                }
                return 'NOT CONFIRMED';
                return "<a href='/admin/market-info-message-campaigns/{$this->id}/send' class='btn btn-xs btn-danger'>Send Later</a>";
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
        $show = new Show(MarketInfoMessageCampaign::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('packages', __('Packages'));
        $show->field('send_now', __('Send now'));
        $show->field('confirm_send', __('Confirm send'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MarketInfoMessageCampaign());

        //packages
        $packages = [];
        $market_packages = MarketPackage::where('status', 1)->get();
        foreach ($market_packages as $value) {
            $packages[$value->id] = $value->name;
        }
        $form->multipleSelect('packages', __('Select Packages Applicable'))
            ->options($packages)
            ->required();
        $form->radio('send_now', __('Send Message'))
            ->options(['Yes' => 'Send Now', 'No' => 'Send Later'])
            ->default('No')
            ->when('Yes', function (Form $form) {
                $form->radio('confirm_send', __('Confirm Send'))
                    ->options(['Yes' => 'Yes', 'No' => 'No'])
                    ->default('No');
            })
            ->required();

        if ($form->isEditing()) {
            $form->radio('message_sent', __('Re-send Messages'))
                ->options(['No' => 'Re-Send', 'Yes' => 'DO NOT SEND']);
        } else {
            $form->hidden('message_sent', __('Re-send Messages'))->default('No')->value('No');
        }



        return $form;
    }
}
