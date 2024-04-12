<?php

namespace App\Admin\Controllers;

use App\Models\Farmers\Farmer;
use App\Models\Insurance\InsuranceSubscription;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Season;
use App\Models\Settings\Region;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InsuranceSubscriptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Insurance Subscriptions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new InsuranceSubscription());

        $grid->disableBatchActions();
        $grid->column('agent_id', __('Agent'))
            ->display(function ($agent_id) {
                $agent = User::find($agent_id);
                if($agent == null)
                    return "N/A";
                return $agent->name;
            })->sortable();
        $grid->column('agent_phone', __('Agent phone'));
        $grid->column('first_name', __('First name'));
        $grid->column('last_name', __('Last name'));
        $grid->column('phone', __('Phone'));
        $grid->column('insurance_subscriber', __('Subscriber'));
        $grid->column('insurance_subscrption_for', __('Insuring for'));
        $grid->column('insurance_season_id', __('Season'))
            ->display(function ($agent_id) {
                $agent = Season::find($agent_id);
                if($agent == null)
                    return "N/A";
                return $agent->name;
            })->sortable();
        $grid->column('enterprise_id', __('Enterprise'))
            ->display(function ($agent_id) {
                $agent = Enterprise::find($agent_id);
                if($agent == null)
                    return "N/A";
                return $agent->name;
            })->sortable();
        $grid->column('insurance_region_id', __('Region'))
            ->display(function ($agent_id) {
                $agent = Region::find($agent_id);
                if($agent == null)
                    return "N/A";
                return $agent->name;
            })->sortable();
        $grid->column('insurance_acreage', __('Acreage'));
        $grid->column('sum_insured', __('Sum insured'));
        $grid->column('premium', __('Premium'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('payment_id', __('Payment id'));

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
        $show = new Show(InsuranceSubscription::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('agent_id', __('Agent id'));
        $show->field('agent_phone', __('Agent phone'));
        $show->field('farmer_id', __('Farmer id'));
        $show->field('location_id', __('Location id'));
        $show->field('district_id', __('District id'));
        $show->field('subcounty_id', __('Subcounty id'));
        $show->field('parish_id', __('Parish id'));
        $show->field('first_name', __('First name'));
        $show->field('last_name', __('Last name'));
        $show->field('phone', __('Phone'));
        $show->field('email', __('Email'));
        $show->field('calculator_values_id', __('Calculator values id'));
        $show->field('season_id', __('Season id'));
        $show->field('enterprise_id', __('Enterprise id'));
        $show->field('acreage', __('Acreage'));
        $show->field('sum_insured', __('Sum insured'));
        $show->field('premium', __('Premium'));
        $show->field('status', __('Status'));
        $show->field('user_id', __('User id'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('seen_by_admin', __('Seen by admin'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
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
        $form = new Form(new InsuranceSubscription());

        $u = Admin::user();

        $form->hidden('agent_id', __('Agent id'))->default($u->id);
        $form->text('agent_phone', __('Agent Phone Number'))->rules('required');
        $farmsrs = Farmer::where([])->get();
        $form->select('farmer_id', __('Farmer'))
            ->options($farmsrs->pluck('first_name', 'id'))
            ->rules('required');

        $form->text('location_id', __('Location id'));
        $form->text('district_id', __('District id'));
        $form->text('subcounty_id', __('Subcounty id'));
        $form->text('parish_id', __('Parish id'));
        $form->text('first_name', __('First name'));
        $form->text('last_name', __('Last name'));
        $form->mobile('phone', __('Phone'));
        $form->email('email', __('Email'));
        $form->text('calculator_values_id', __('Calculator values id'));
        $form->text('season_id', __('Season id'));
        $form->text('enterprise_id', __('Enterprise id'));
        $form->decimal('acreage', __('Acreage'));
        $form->decimal('sum_insured', __('Sum insured'));
        $form->decimal('premium', __('Premium'));
        $form->switch('status', __('Status'));
        $form->text('user_id', __('User id'));
        $form->text('organisation_id', __('Organisation id'));
        $form->switch('seen_by_admin', __('Seen by admin'));
        $form->text('payment_id', __('Payment id'));

        return $form;
    }
}
