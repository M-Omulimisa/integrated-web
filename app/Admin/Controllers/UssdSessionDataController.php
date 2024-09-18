<?php

namespace App\Admin\Controllers;

use App\Models\Ussd\UssdSessionData;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UssdSessionDataController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UssdSessionData';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UssdSessionData());
        $grid->model()->orderBy('created_at', 'desc');
        $grid->quickSearch('phone_number', 'market_subscrption_for', 'weather_subscrption_for', 'insurance_subscrption_for', 'farmer_market_user_name', 'farmer_market_user_type');
        

        $grid->column('id', __('Id'))->sortable();
        $grid->column('session_id', __('Session id'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('module', __('Module'));
        $grid->column('market_subscrption_for', __('Market subscrption for'));
        $grid->column('market_subscriber', __('Market subscriber'));
        $grid->column('market_region', __('Market region'));
        $grid->column('market_region_id', __('Market region id'));
        $grid->column('market_language', __('Market language'));
        $grid->column('market_package_id', __('Market package id'));
        $grid->column('market_language_id', __('Market language id'));
        $grid->column('market_frequency', __('Market frequency'));
        $grid->column('market_frequency_count', __('Market frequency count'));
        $grid->column('market_cost', __('Market cost'));
        $grid->column('market_confirmation', __('Market confirmation'));
        $grid->column('market_payment_status', __('Market payment status'));
        $grid->column('weather_subscrption_for', __('Weather subscrption for'));
        $grid->column('weather_subscriber', __('Weather subscriber'));
        $grid->column('weather_subscriber_name', __('Weather subscriber name'));
        $grid->column('weather_district', __('Weather district'));
        $grid->column('weather_district_id', __('Weather district id'));
        $grid->column('weather_subcounty', __('Weather subcounty'));
        $grid->column('weather_subcounty_id', __('Weather subcounty id'));
        $grid->column('weather_parish', __('Weather parish'));
        $grid->column('weather_parish_id', __('Weather parish id'));
        $grid->column('weather_frequency', __('Weather frequency'));
        $grid->column('weather_frequency_count', __('Weather frequency count'));
        $grid->column('weather_confirmation', __('Weather confirmation'));
        $grid->column('weather_payment_status', __('Weather payment status'));
        $grid->column('insurance_subscrption_for', __('Insurance subscrption for'));
        $grid->column('insurance_subscriber', __('Insurance subscriber'));
        $grid->column('insurance_subscriber_name', __('Insurance subscriber name'));
        $grid->column('insurance_district', __('Insurance district'));
        $grid->column('insurance_district_id', __('Insurance district id'));
        $grid->column('insurance_subcounty', __('Insurance subcounty'));
        $grid->column('insurance_subcounty_id', __('Insurance subcounty id'));
        $grid->column('insurance_parish', __('Insurance parish'));
        $grid->column('insurance_parish_id', __('Insurance parish id'));
        $grid->column('insurance_season_id', __('Insurance season id'));
        $grid->column('insurance_enterprise_id', __('Insurance enterprise id'));
        $grid->column('insurance_acreage', __('Insurance acreage'));
        $grid->column('insurance_sum_insured', __('Insurance sum insured'));
        $grid->column('insurance_premium', __('Insurance premium'));
        $grid->column('insurance_confirmation', __('Insurance confirmation'));
        $grid->column('insurance_payment_status', __('Insurance payment status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('confirmation_message', __('Confirmation message'));
        $grid->column('insurance_amount', __('Insurance amount'));
        $grid->column('referee_phone', __('Referee phone'));
        $grid->column('weather_amount', __('Weather amount'));
        $grid->column('markup', __('Markup'));
        $grid->column('insurance_coverage', __('Insurance coverage'));
        $grid->column('insurance_region_id', __('Insurance region id'));
        $grid->column('option_mappings', __('Option mappings'));
        $grid->column('weather_language_id', __('Weather language id'));
        $grid->column('agent_id', __('Agent id'));
        $grid->column('insurer_name', __('Insurer name'));
        $grid->column('insurance_type', __('Insurance type'));
        $grid->column('surname', __('Surname'));
        $grid->column('telephone', __('Telephone'));
        $grid->column('other_name', __('Other name'));
        $grid->column('payment_phone', __('Payment phone'));
        $grid->column('paid', __('Paid'));
        $grid->column('completed', __('Completed'));
        $grid->column('pending', __('Pending'));
        $grid->column('cancelled', __('Cancelled'));
        $grid->column('farmer_market_user_name', __('Farmer market user name'));
        $grid->column('farmer_market_user_type', __('Farmer market user type'));
        $grid->column('farmer_market_user_gender', __('Farmer market user gender'));
        $grid->column('farmer_market_user_age', __('Farmer market user age'));
        $grid->column('farmer_market_user_district', __('Farmer market user district'));
        $grid->column('farmer_market_category', __('Farmer market category'));
        $grid->column('farmer_market_product', __('Farmer market product'));
        $grid->column('farmer_market_quantity', __('Farmer market quantity'));
        $grid->column('farmer_market_category_options', __('Farmer market category options'));
        $grid->column('farmer_market_parish', __('Farmer market parish'));
        $grid->column('farmer_market_subcounty', __('Farmer market subcounty'));
        $grid->column('farmer_market_district', __('Farmer market district'));
        $grid->column('selected_subcounty_id', __('Selected subcounty id'));
        $grid->column('farmer_market_units', __('Farmer market units'));
        $grid->column('farmer_market_price', __('Farmer market price'));

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
        $show = new Show(UssdSessionData::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('session_id', __('Session id'));
        $show->field('phone_number', __('Phone number'));
        $show->field('module', __('Module'));
        $show->field('market_subscrption_for', __('Market subscrption for'));
        $show->field('market_subscriber', __('Market subscriber'));
        $show->field('market_region', __('Market region'));
        $show->field('market_region_id', __('Market region id'));
        $show->field('market_language', __('Market language'));
        $show->field('market_package_id', __('Market package id'));
        $show->field('market_language_id', __('Market language id'));
        $show->field('market_frequency', __('Market frequency'));
        $show->field('market_frequency_count', __('Market frequency count'));
        $show->field('market_cost', __('Market cost'));
        $show->field('market_confirmation', __('Market confirmation'));
        $show->field('market_payment_status', __('Market payment status'));
        $show->field('weather_subscrption_for', __('Weather subscrption for'));
        $show->field('weather_subscriber', __('Weather subscriber'));
        $show->field('weather_subscriber_name', __('Weather subscriber name'));
        $show->field('weather_district', __('Weather district'));
        $show->field('weather_district_id', __('Weather district id'));
        $show->field('weather_subcounty', __('Weather subcounty'));
        $show->field('weather_subcounty_id', __('Weather subcounty id'));
        $show->field('weather_parish', __('Weather parish'));
        $show->field('weather_parish_id', __('Weather parish id'));
        $show->field('weather_frequency', __('Weather frequency'));
        $show->field('weather_frequency_count', __('Weather frequency count'));
        $show->field('weather_confirmation', __('Weather confirmation'));
        $show->field('weather_payment_status', __('Weather payment status'));
        $show->field('insurance_subscrption_for', __('Insurance subscrption for'));
        $show->field('insurance_subscriber', __('Insurance subscriber'));
        $show->field('insurance_subscriber_name', __('Insurance subscriber name'));
        $show->field('insurance_district', __('Insurance district'));
        $show->field('insurance_district_id', __('Insurance district id'));
        $show->field('insurance_subcounty', __('Insurance subcounty'));
        $show->field('insurance_subcounty_id', __('Insurance subcounty id'));
        $show->field('insurance_parish', __('Insurance parish'));
        $show->field('insurance_parish_id', __('Insurance parish id'));
        $show->field('insurance_season_id', __('Insurance season id'));
        $show->field('insurance_enterprise_id', __('Insurance enterprise id'));
        $show->field('insurance_acreage', __('Insurance acreage'));
        $show->field('insurance_sum_insured', __('Insurance sum insured'));
        $show->field('insurance_premium', __('Insurance premium'));
        $show->field('insurance_confirmation', __('Insurance confirmation'));
        $show->field('insurance_payment_status', __('Insurance payment status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('confirmation_message', __('Confirmation message'));
        $show->field('insurance_amount', __('Insurance amount'));
        $show->field('referee_phone', __('Referee phone'));
        $show->field('weather_amount', __('Weather amount'));
        $show->field('markup', __('Markup'));
        $show->field('insurance_coverage', __('Insurance coverage'));
        $show->field('insurance_region_id', __('Insurance region id'));
        $show->field('option_mappings', __('Option mappings'));
        $show->field('weather_language_id', __('Weather language id'));
        $show->field('agent_id', __('Agent id'));
        $show->field('insurer_name', __('Insurer name'));
        $show->field('insurance_type', __('Insurance type'));
        $show->field('surname', __('Surname'));
        $show->field('telephone', __('Telephone'));
        $show->field('other_name', __('Other name'));
        $show->field('payment_phone', __('Payment phone'));
        $show->field('paid', __('Paid'));
        $show->field('completed', __('Completed'));
        $show->field('pending', __('Pending'));
        $show->field('cancelled', __('Cancelled'));
        $show->field('farmer_market_user_name', __('Farmer market user name'));
        $show->field('farmer_market_user_type', __('Farmer market user type'));
        $show->field('farmer_market_user_gender', __('Farmer market user gender'));
        $show->field('farmer_market_user_age', __('Farmer market user age'));
        $show->field('farmer_market_user_district', __('Farmer market user district'));
        $show->field('farmer_market_category', __('Farmer market category'));
        $show->field('farmer_market_product', __('Farmer market product'));
        $show->field('farmer_market_quantity', __('Farmer market quantity'));
        $show->field('farmer_market_category_options', __('Farmer market category options'));
        $show->field('farmer_market_parish', __('Farmer market parish'));
        $show->field('farmer_market_subcounty', __('Farmer market subcounty'));
        $show->field('farmer_market_district', __('Farmer market district'));
        $show->field('selected_subcounty_id', __('Selected subcounty id'));
        $show->field('farmer_market_units', __('Farmer market units'));
        $show->field('farmer_market_price', __('Farmer market price'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UssdSessionData());

        $form->text('session_id', __('Session id'));
        $form->text('phone_number', __('Phone number'));
        $form->text('module', __('Module'));
        $form->text('market_subscrption_for', __('Market subscrption for'));
        $form->text('market_subscriber', __('Market subscriber'));
        $form->text('market_region', __('Market region'));
        $form->text('market_region_id', __('Market region id'));
        $form->text('market_language', __('Market language'));
        $form->text('market_package_id', __('Market package id'));
        $form->text('market_language_id', __('Market language id'));
        $form->text('market_frequency', __('Market frequency'));
        $form->number('market_frequency_count', __('Market frequency count'));
        $form->decimal('market_cost', __('Market cost'));
        $form->switch('market_confirmation', __('Market confirmation'));
        $form->text('market_payment_status', __('Market payment status'))->default('PENDING');
        $form->text('weather_subscrption_for', __('Weather subscrption for'));
        $form->text('weather_subscriber', __('Weather subscriber'));
        $form->text('weather_subscriber_name', __('Weather subscriber name'));
        $form->text('weather_district', __('Weather district'));
        $form->text('weather_district_id', __('Weather district id'));
        $form->text('weather_subcounty', __('Weather subcounty'));
        $form->text('weather_subcounty_id', __('Weather subcounty id'));
        $form->text('weather_parish', __('Weather parish'));
        $form->text('weather_parish_id', __('Weather parish id'));
        $form->text('weather_frequency', __('Weather frequency'));
        $form->decimal('weather_frequency_count', __('Weather frequency count'));
        $form->switch('weather_confirmation', __('Weather confirmation'));
        $form->text('weather_payment_status', __('Weather payment status'))->default('PENDING');
        $form->text('insurance_subscrption_for', __('Insurance subscrption for'));
        $form->text('insurance_subscriber', __('Insurance subscriber'));
        $form->text('insurance_subscriber_name', __('Insurance subscriber name'));
        $form->text('insurance_district', __('Insurance district'));
        $form->text('insurance_district_id', __('Insurance district id'));
        $form->text('insurance_subcounty', __('Insurance subcounty'));
        $form->text('insurance_subcounty_id', __('Insurance subcounty id'));
        $form->text('insurance_parish', __('Insurance parish'));
        $form->text('insurance_parish_id', __('Insurance parish id'));
        $form->text('insurance_season_id', __('Insurance season id'));
        $form->text('insurance_enterprise_id', __('Insurance enterprise id'));
        $form->decimal('insurance_acreage', __('Insurance acreage'));
        $form->decimal('insurance_sum_insured', __('Insurance sum insured'));
        $form->decimal('insurance_premium', __('Insurance premium'));
        $form->switch('insurance_confirmation', __('Insurance confirmation'));
        $form->text('insurance_payment_status', __('Insurance payment status'))->default('PENDING');
        $form->textarea('confirmation_message', __('Confirmation message'));
        $form->decimal('insurance_amount', __('Insurance amount'));
        $form->text('referee_phone', __('Referee phone'));
        $form->decimal('weather_amount', __('Weather amount'));
        $form->text('markup', __('Markup'));
        $form->text('insurance_coverage', __('Insurance coverage'))->default('half');
        $form->text('insurance_region_id', __('Insurance region id'));
        $form->textarea('option_mappings', __('Option mappings'));
        $form->text('weather_language_id', __('Weather language id'));
        $form->text('agent_id', __('Agent id'));
        $form->text('insurer_name', __('Insurer name'));
        $form->text('insurance_type', __('Insurance type'))->default('crop');
        $form->text('surname', __('Surname'));
        $form->text('telephone', __('Telephone'));
        $form->text('other_name', __('Other name'));
        $form->text('payment_phone', __('Payment phone'));
        $form->switch('paid', __('Paid'));
        $form->switch('completed', __('Completed'));
        $form->switch('pending', __('Pending'))->default(1);
        $form->switch('cancelled', __('Cancelled'));
        $form->text('farmer_market_user_name', __('Farmer market user name'));
        $form->text('farmer_market_user_type', __('Farmer market user type'));
        $form->text('farmer_market_user_gender', __('Farmer market user gender'));
        $form->text('farmer_market_user_age', __('Farmer market user age'));
        $form->text('farmer_market_user_district', __('Farmer market user district'));
        $form->text('farmer_market_category', __('Farmer market category'));
        $form->text('farmer_market_product', __('Farmer market product'));
        $form->text('farmer_market_quantity', __('Farmer market quantity'));
        $form->textarea('farmer_market_category_options', __('Farmer market category options'));
        $form->text('farmer_market_parish', __('Farmer market parish'));
        $form->text('farmer_market_subcounty', __('Farmer market subcounty'));
        $form->text('farmer_market_district', __('Farmer market district'));
        $form->textarea('selected_subcounty_id', __('Selected subcounty id'));
        $form->text('farmer_market_units', __('Farmer market units'));
        $form->decimal('farmer_market_price', __('Farmer market price'));

        return $form;
    }
}
