<?php

namespace App\Admin\Controllers;

use App\Models\DistrictModel;
use App\Models\ParishModel;
use App\Models\SubcountyModel;
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
        foreach (WeatherSubscription::all() as $key => $val) {
            $val->trial_expiry_sms_failure_reason .= '..';
            $val->save();
        }
        $grid = new Grid(new WeatherSubscription());
        // $grid->disableCreateButton();
        $grid->model()->orderBy('created_at', 'desc');
        $grid->quickSearch('first_name')->placeholder('Search first name...');

        $grid->column('first_name', __('Name'))
            ->display(function ($first_name) {
                return $first_name . ' ' . $this->last_name;
            })->sortable();
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
        $grid->column('location_id', __('Location id'))->hide();
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
        $grid->column('email', __('Email'))->hide();
        $grid->column('frequency', __('Frequency'))->sortable();
        $grid->column('period_paid', __('Period Paid'))->sortable();
        $grid->column('start_date', __('Start Date'))->sortable();
        $grid->column('end_date', __('End date'))->sortable();
        $grid->column('status', __('Status'))
            ->using([
                0 => 'Expired',
                1 => 'Active',
            ])->sortable()
            ->filter([
                0 => 'Expired',
                1 => 'Active',
            ])->label([
                0 => 'danger',
                1 => 'success',
            ]);
        $grid->column('outbox_generation_status', __('Outbox generation status'))->hide();
        $grid->column('outbox_reset_status', __('Outbox reset status'))->hide();
        $grid->column('outbox_last_date', __('Outbox last date'))->hide();
        $grid->column('awhere_field_id', __('Awhere field id'))->hide();
        $grid->column('seen_by_admin', __('Seen by admin'))->hide();
        $grid->column('trial_expiry_sms_sent_at', __('Trial expiry sms sent at'))->hide();
        $grid->column('trial_expiry_sms_failure_reason', __('Trial expiry sms failure reason'))->hide();
        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return date('Y-m-d', strtotime($created_at));
            })->sortable();
        $grid->column('phone', __('Phone'))->sortable();

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

        return $form;
    }
}
