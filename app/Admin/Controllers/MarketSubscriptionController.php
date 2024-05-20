<?php

namespace App\Admin\Controllers;

use App\Models\Market\MarketPackagePricing;
use App\Models\Market\MarketSubscription;
use App\Models\Settings\Location;
use App\Models\Utils;
use Dflydev\DotAccessData\Util;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Schema;

class MarketSubscriptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Market Subscriptions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        //$colls_of_table = Schema::getColumnListing((new MarketSubscription())->getTable());
        //dd($colls_of_table);
        Utils::create_column(
            (new MarketSubscription())->getTable(),
            [
                [
                    'name' => 'renew_message_sent',
                    'type' => 'String',
                    'default' => 'No',
                ],
                [
                    'name' => 'renew_message_sent_at',
                    'type' => 'DateTime',
                ],
                [
                    'name' => 'renew_message_sent_details',
                    'type' => 'Text',
                ],
            ]
        );

        Utils::create_column(
            (new MarketSubscription())->getTable(),
            [
                [
                    'name' => 'is_processed',
                    'type' => 'String',
                    'default' => 'No',
                ],
            ]
        );

        $grid = new Grid(new MarketSubscription());
        $currnt_url = url()->current();
        $segs = explode('/', $currnt_url);
        $where = [];
        if (in_array('market-subscriptions', $segs)) {
            $where['is_paid'] = 'PAID';
            $where['status'] = 1;
        } else if (in_array('market-subscriptions-expired', $segs)) {
            $where['is_paid'] = 'PAID';
            $where['status'] = 0;
        } else if (in_array('market-subscriptions-not-paid', $segs)) {
            $where['is_paid'] = 'NOT PAID';
            $where['status'] = 0;
        }
        $grid->model()
            ->where($where)
            ->orderBy('created_at', 'desc');
        $grid->quickSearch('phone')->placeholder('Search first name...');
        /*         $grid->column('language_id', __('Language id'));
        $grid->column('location_id', __('Location id'));
        $grid->column('district_id', __('District id'));
        $grid->column('subcounty_id', __('Subcounty id'));
        $grid->column('parish_id', __('Parish id')); */

        $packages = [];
        foreach (\App\Models\Market\MarketPackage::all() as $key => $package) {
            $packages[$package->id] = $package->name;
        }

        $grid->column('phone', __('Phone'))
            ->width(150)
            ->filter('like')
            ->sortable();
        $grid->column('first_name', __('name'))
            ->display(function ($first_name) {
                return $first_name . ' ' . $this->last_name;
            })->sortable()
            ->hide();

        $grid->column('package_id', __('Package'))
            ->display(function ($package_id) {
                if ($this->package == null) {
                    return '-';
                }
                return $this->package->name;
            })
            ->filter($packages)->sortable()
            ->width(150);

        $grid->column('frequency', __('Frequency'))
            ->using([
                'trial' => 'Trial (Free)',
                'weekly' => 'Weekly',
                'monthly' => 'Monthly',
                'yearly' => 'Yearly'
            ])
            ->sortable();
        $grid->column('period_paid', __('Period'))->sortable();
        $grid->column('start_date', __('Start date'))
            ->display(function ($start_date) {
                return date('d-m-Y', strtotime($start_date));
            })->sortable();
        $grid->column('end_date', __('End date'))
            ->display(function ($start_date) {
                return date('d-m-Y', strtotime($start_date));
            })->sortable();
        $grid->column('status', __('STATUS'))
            ->using(['1' => 'Active', '0' => 'Expired'])
            ->sortable()
            ->label([
                '1' => 'success',
                '0' => 'danger'
            ])
            ->filter(['1' => 'Active', '0' => 'Expired']);
        $grid->column('renew_message_sent', __('Renew alert sent'))
            ->sortable()
            ->dot([
                'Yes' => 'success',
                'Skipped' => 'warning',
                'No' => 'danger',
                'Failed' => 'danger'
            ])
            ->filter(['Yes' => 'Yes', 'Skipped' => 'Skipped', 'Failed' => 'Failed', 'No' => 'No']);

        /*         $grid->column('outbox_generation_status', __('Outbox generation status'));
        $grid->column('outbox_reset_status', __('Outbox reset status'));
        $grid->column('outbox_last_date', __('Outbox last date'));
        $grid->column('seen_by_admin', __('Seen by admin'));
        $grid->column('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'));
        $grid->column('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'));
        $grid->column('renewal_id', __('Renewal id'));
        $grid->column('organisation_id', __('Organisation id'));
        $grid->column('package_id', __('Package id'));

        $grid->column('updated_at', __('Updated at')); */

        /*         $grid->column('region_id', __('Region id'));
        $grid->column('payment_id', __('Payment id'));
        $grid->column('outbox_count', __('Outbox count')); */


        $grid->column('renew_message_sent_at', __('Renew Alert sent at'))->sortable()
            ->display(function ($created_at) {
                if ($created_at == null) {
                    return '-';
                }
                return Utils::my_date($created_at);
            });
        $grid->column('renew_message_sent_details', __('Renew Alert Sent Details'))->sortable()
            ->display(function ($created_at) {
                if ($created_at == null) {
                    return '-';
                }
                return ($created_at);
            })->limit(20);
        $grid->column('created_at', __('Created'))->sortable()
            ->display(function ($created_at) {
                return date('d-m-Y', strtotime($created_at));
            })->hide();

        $grid->column('is_paid', __('Payment Status'))
            ->sortable()
            ->label([
                'PAID' => 'success',
                'NOT PAID' => 'danger'
            ])
            ->filter(['PAID' => 'PAID', 'NOT PAID' => 'NOT PAID']);
        $grid->column('MNOTransactionReferenceId', __('MNO Transaction Reference ID'))->hide()->sortable();
        $grid->column('payment_reference_id', __('Payment Reference ID'))->hide();
        $grid->column('TransactionStatus', __('Transaction Status'))->hide();
        $grid->column('TransactionAmount', __('Transaction Amount'))->hide();
        $grid->column('total_price', __('Total Amount'))->hide();
        $grid->column('TransactionCurrencyCode', __('Transaction Currency Code'))->hide();
        $grid->column('TransactionReference', __('Transaction Reference'))->hide();
        $grid->column('TransactionInitiationDate', __('Transaction Initiation Date'))->hide();
        $grid->column('TransactionCompletionDate', __('Transaction Completion Date'))->hide();
        $grid->column('is_processed', __('Is Processed'))
            ->hide()
            ->label([
                'Yes' => 'success',
                'No' => 'danger'
            ])
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
        $f =  MarketSubscription::find('e0a7f6d1-e00c-4394-bbe5-7b376bc28eca');
        //$f->total_price = rand(1000, 10000);
        //$f->save();
        //dd($f->total_price);
        $form = new Form(new MarketSubscription());

        $form->text('first_name', __('First Name'));
        $form->text('last_name', __('Last name'));
        $form->text('email', __('Email'));
        $form->text('phone', __('Phone number'))->rules('required');
        $form->divider();
        $packages = [];
        foreach (\App\Models\Market\MarketPackage::all() as $key => $package) {
            $packages[$package->id] = $package->name;
            $pricings = MarketPackagePricing::where([
                'package_id' => $package->id
            ])->get();
        }


        //$url = '/api/market-package-pricings';
        $form->select('package_id', __('Package'))
            ->options($packages)
            ->rules('required');
        $form->select('frequency', __('Frequency'))
            ->options([
                'trial' => 'Trial (Free)',
                'weekly' => 'Weekly',
                'monthly' => 'Monthly',
                'yearly' => 'Yearly'
            ])
            ->rules('required');
        $form->decimal('period_paid', __('Period Paid'))->rules('required');

        if ($form->isEditing()) {
            $form->date('start_date', __('Start date'))
                ->readonly();
            $form->date('end_date', __('End date'))
                ->readonly();
            $form->hidden('status', __('Status'))
                ->default(1);
        }
        $u = Admin::user();
        $form->hidden('farmer_id', __('Farmer_id'))
            ->rules('required')
            ->default($u->id);
        $form->hidden('user_id', __('FARMER'))
            ->rules('required')
            ->default($u->id);
        $langs = [];
        foreach (\App\Models\Settings\Language::where([
            'market' => 'Yes'
        ])
            ->orderBy('position', 'asc')
            ->get() as $key => $lang) {
            $langs[$lang->id] = $lang->name;
        }

        $form->select('language_id', __('Language'))
            ->options($langs)
            ->rules('required');
        $locations = [];
        $default_location = 1;
        foreach (Location::all() as $key => $location) {
            $locations[$location->id] = $location->name;
            $default_location = $location->id;
            break;
        }
        $form->hidden('location_id', __('Region'))
            ->default($default_location);
        if (!$form->isCreating()) {
            $form->radio('renew_message_sent', __('Renew alert sent'))
                ->options([
                    'Yes' => 'Yes',
                    'Skipped' => 'Skipped',
                    'No' => 'No',
                    'Failed' => 'Failed'
                ]);
        }
        $form->radio('is_paid', __('Payment Status'))
            ->options([
                'PAID' => 'PAID',
                'NOT PAID' => 'NOT PAID',
            ])
            ->when('PAID', function (Form $form) {
                $form->text('MNOTransactionReferenceId', __('MNO Transaction Reference ID'));
                $form->text('payment_reference_id', __('Payment Reference ID'));
                $form->text('TransactionStatus', __('Transaction Status'));
                $form->decimal('TransactionAmount', __('Transaction Amount'));
                $form->decimal('total_price', __('Total Amount'));
                $form->text('TransactionCurrencyCode', __('Transaction Currency Code'));
                $form->text('TransactionReference', __('Transaction Reference'));
                $form->datetime('TransactionInitiationDate', __('Transaction Initiation Date'));
                $form->datetime('TransactionCompletionDate', __('Transaction Completion Date'));
            });
        /* $form->radio('status', __('Status'))
            ->options([
                1 => 'Active',
                0 => 'Not Active',
            ]); */
        $form->radio('is_processed', __('Record is processed'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ]);

        $form->disableCreatingCheck();
        return $form;
    }
}
