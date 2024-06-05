<?php

namespace App\Admin\Controllers;

use App\Models\DistrictModel;
use App\Models\ParishModel;
use App\Models\SubcountyModel;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\Weather\WeatherSubscription;
use Carbon\Carbon;

class WeatherSubscriptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Weather Subscriptions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        /* foreach (WeatherSubscription::all() as $key => $val) {
            $val->trial_expiry_sms_failure_reason .= '..';
            $val->save();
        } */

        Utils::create_column(
            (new WeatherSubscription())->getTable(),
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

        $grid = new Grid(new WeatherSubscription());
        $grid->export(function ($export) {
            $export->filename('weather_subscriptions_' . Carbon::now()->format('Y-m-d'));
            //orginal [is_paid]
            //when exporting is_paid
            $export->column('is_paid', function ($value) {
                if ($value == 'PAID') {
                    return 'PAID';
                }
                return 'NOT PAID';
            });
        });
        $url_process = url('boot-system');
        $html = '<a target="_blank" href="' . $url_process . '" class="btn btn-sm btn-success">Process Weather Subscriptions</a>';
        $grid->header(function ($query) use ($html) {
            return $html;
        });
        // $grid->disableCreateButton();
        $grid->model()->orderBy('created_at', 'desc');
        $grid->quickSearch('first_name')->placeholder('Search first name...');

        $grid->column('first_name', __('Name'))
            ->display(function ($first_name) {
                $name = $first_name . ' ' . $this->last_name;
                $name = trim($name);
                if (strlen($name) == 0) {
                    $name = $this->phone;
                }
                $name = trim($name);
                if (strlen($name) == 0) {
                    $name = '-';
                }
                return $name;
            })->sortable();

        $grid->column('district_id', __('District'))
            ->display(function ($district_id) {
                $d = DistrictModel::find($district_id);
                if ($d == null) {
                    return '-';
                }
                return $d->name;
            })->sortable();

        $grid->column('subcounty_id', __('Subcounty'))
            ->display(function ($subcounty_id) {
                $s = SubcountyModel::find($subcounty_id);
                if ($s == null) {
                    return '-';
                }
                return $s->name;
            })->sortable();

        $grid->column('parish_id', __('Parish'))
            ->display(function ($parish_id) {
                $p = ParishModel::find($parish_id);
                if ($p == null) {
                    return '-';
                }
                return $p->name;
            })->sortable();
        $grid->column('frequency', __('Frequency'))->sortable();



        $grid->column('farmer_id', __('Farmer'))
            ->display(function ($farmer_id) {
                $u = \App\Models\User::find($farmer_id);
                if ($u == null) {
                    return '-';
                }
                return $u->name;
            })->sortable()->hide();
        $grid->column('language_id', __('Language'))
            ->display(function ($language_id) {
                $lang = \App\Models\Settings\Language::find($language_id);
                if ($lang == null) {
                    return '-';
                }
                return $lang->name;
            })->sortable();

        $grid->column('is_paid', __('Payment Status'))
            ->sortable()
            ->using([
                'PAID' => 'PAID',
                'No' => 'NOT PAID',
            ], 'NOT PAID')
            ->label([
                'PAID' => 'success',
                'NOT PAID' => 'danger',
                'No' => 'danger',
                'NO' => 'danger',
            ], 'danger')
            ->filter(['PAID' => 'PAID', 'NO' => 'NOT PAID']);
/*         $grid->column('location_id', __('Location id'))->hide(); */

        //start_date




        /*         $grid->column('email', __('Email'))->hide();
 */

        /*         $grid->column('outbox_generation_status', __('Outbox generation status'))->hide();
        $grid->column('outbox_reset_status', __('Outbox reset status'))->hide();
        $grid->column('outbox_last_date', __('Outbox last date'))->hide();
        $grid->column('awhere_field_id', __('Awhere field id'))->hide();
        $grid->column('seen_by_admin', __('Seen by admin'))->hide();
        $grid->column('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'))->hide();
        $grid->column('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'))->hide(); */
        $grid->column('phone', __('Phone'))->sortable();
        $grid->column('start_date', __('Start Date'))->sortable();
        $grid->column('end_date', __('End Date'))->sortable();



        $grid->column('renew_message_sent', __('Renew alert sent'))
            ->sortable()
            ->dot([
                'Yes' => 'success',
                'Skipped' => 'warning',
                'No' => 'danger',
                'Failed' => 'danger'
            ])
            ->filter(['Yes' => 'Yes', 'Skipped' => 'Skipped', 'Failed' => 'Failed', 'No' => 'No']);
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
            })->limit(20)
            ->hide();
        $grid->column('created_at', __('Created'))->sortable()
            ->display(function ($created_at) {
                return date('d-m-Y', strtotime($created_at));
            })->hide();


        /* 
        $grid->column('MNOTransactionReferenceId', __('MNO Transaction Reference ID'))->hide();
        $grid->column('payment_reference_id', __('Payment Reference ID'))->hide();
        $grid->column('TransactionStatus', __('Transaction Status'))->hide();
        $grid->column('TransactionAmount', __('Transaction Amount'))->hide();
        $grid->column('total_price', __('Total Amount'))->hide();
        $grid->column('TransactionCurrencyCode', __('Transaction Currency Code'))->hide();
        $grid->column('TransactionReference', __('Transaction Reference'))->hide();
        $grid->column('TransactionInitiationDate', __('Transaction Initiation Date'))->hide();
        $grid->column('TransactionCompletionDate', __('Transaction Completion Date'))->hide(); */

        $grid->column('show_details', __('Details'))
            ->display(function () {
                return "Quick View";
            })
            ->expand(function ($data) {
                $my_data = [];
                $my_data['Is Welcome Message Sent?'] = $data->welcome_msg_sent;
                $my_data['Welcome Message Sent At'] = $data->welcome_msg_sent_at;
                $my_data['Welcome Message Sent Details'] = $data->welcome_msg_sent_details;
                $my_data['Is Pre-Renew Message Sent?'] = $data->pre_renew_message_sent;
                $my_data['Pre-Renew Message Sent At'] = $data->pre_renew_message_sent_at;
                $my_data['Pre-Renew Message Sent Details'] = $data->pre_renew_message_sent_details;
                $my_data['Is Expiry Message Sent?'] = $data->renew_message_sent;
                $my_data['Expiry Message Sent At'] = $data->renew_message_sent_at;
                $my_data['Expiry Message Sent Details'] = $data->renew_message_sent_details;
                return new \Encore\Admin\Widgets\Table([], $my_data);
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

        /* $form->text('farmer_id', __('Farmer id'));
                $form->text('location_id', __('Location id'));
                        $form->text('subcounty_id', __('Subcounty id'));
        $form->text('parish_id', __('Parish id'))
                $form->text('frequency', __('Frequency'));
        $form->number('period_paid', __('Period paid')); */


        $langs = \App\Models\Settings\Language::all();
        $form->select('language_id', __('Language'))
            ->options($langs->pluck('name', 'id'))
            ->rules('required');
        $form->text('first_name', __('First name'));
        $form->text('last_name', __('Last name'));
        $form->email('email', __('Email'));

        if (!$form->isCreating()) {
            $form->display('start_date', __('Start Date'));
            $form->display('end_date', __('End date'));
        }
        /*     $form->radio('status', __('Status'))
            ->options([
                0 => 'Expired',
                1 => 'Active',
            ])->rules('required'); */
        /*         $form->switch('outbox_generation_status', __('Outbox generation status'));
        $form->switch('outbox_reset_status', __('Outbox reset status'));
        $form->date('outbox_last_date', __('Outbox last date'))->default(date('Y-m-d'));
        $form->text('awhere_field_id', __('Awhere field id'));
        $form->switch('seen_by_admin', __('Seen by admin'));
        $form->datetime('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'))->default(date('Y-m-d H:i:s'));
        $form->textarea('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'));
        $form->text('renewal_id', __('Renewal id'));
        $form->text('organisation_id', __('Organisation id')); */
        $form->text('phone', __('Phone number'))->rules('required');
        $form->disableCreatingCheck();
        /*         $form->text('payment_id', __('Payment id')); */

        $form->select('parish_id', __('Parish'))
            ->options(ParishModel::selectData())
            ->rules('required');
        $form->select('frequency', __('Frequency'))
            ->options([
                'daily' => 'Daily',
                'weekly' => 'Weekly',
                'monthly' => 'Monthly',
                'yearly' => 'Yearly',
            ])->rules('required');

        $form->decimal('period_paid', __('Period Paid'))->rules('required');

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
        $form->radio('status', __('Status'))
            ->options([
                1 => 'Active',
                0 => 'Not Active',
            ]);

        $form->radio('is_test', __('IS TEST RECORD'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->when('Yes', function ($form) {
                $form->divider('Test Record');

                $form->radio('is_processed', __('Record is processed'))
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ]);

                $form->date('start_date', __('Start date'));
                $form->date('end_date', __('End date'));
                $form->divider();
                $form->radio('pre_renew_message_sent', __('Pre-renew message sent'))
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ]);
                $form->date('pre_renew_message_sent_at', __('Pre renew message sent at'));
                $form->text('pre_renew_message_sent_details', __('Pre renew message_sent details'));

                $form->divider('Renewal Message');
                $form->radio('renew_message_sent', __('Renew message sent'))
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ]);
                $form->date('renew_message_sent_at', __('renew message sent at'));
                $form->text('renew_message_sent_details', __('Renew message_sent details'));
                $form->divider('Welcome Message');
                $form->radio('welcome_msg_sent', __('Welcome message sent'))
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ]);
                $form->date('welcome_msg_sent_at', __('Welcome message sent at'));
                $form->text('welcome_msg_sent_details', __('Welcome message_sent details'));
            })->rules();


        $form->disableCreatingCheck();

        return $form;
    }
}
