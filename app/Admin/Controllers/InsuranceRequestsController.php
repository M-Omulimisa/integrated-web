<?php

namespace App\Admin\Controllers;

use App\Models\NewInsuranceRequest;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

/* TODO: Here's this code from postman for when you need it
{
    "enterprise": "79ca0d29-508f-4e10-a74b-3965541f84a7",
    "amount": 60000,
    "markup": 5000,
    "sumInsured": 1000000,
    "premium": 60000,
    "acreage": 6,
    "coverage": "full",
    "phoneNumber": 256787799198,
    "sessionID": "898l",
    "regionID": "34720b4f-0593-4830-937c-b004fe298f7e",
    "agent_id": "59",
    "surname": "Kabagabe",
    "insurance_type": "crop",
    "insurer_name": "Simeon",
    "telephone": 256787799198,
    "other_name": "Marvice",
    "payment_phone": 256704098918,
    "paid": false,
    "completed": false,
    "pending": true,
    "cancelled": false
}
*/

class InsuranceRequestsController extends AdminController
{
    protected $title = 'Insurance Requests';

    protected function grid()
    {
        $grid = new Grid(new NewInsuranceRequest());
        $grid->column('id', __('Id'))->sortable()->hide();
        $grid->column('session_id', __('Session ID'))->hide();
        $grid->column('phone_number', __('Phone Number'));
        $grid->column('insurance_subscrption_for', __('Insurance Subscription For'))->hide();

        // Displaying Enterprise Name
        $grid->column('insurance_enterprise_id', __('Enterprise'))->display(function ($enterpriseId) {
            $enterprise = \App\Models\Settings\Enterprise::find($enterpriseId);

            if ($enterprise) {
                return $enterprise->name;
            } else {
                return 'N/A';
            }
        });

        $grid->column('insurance_amount', __('Insurance Amount'));
        $grid->column('module', __('Module'));
        $grid->column('insurance_subscriber', __('Insurance Subscriber'));
        $grid->column('insurance_acreage', __('Insurance Acreage'));
        $grid->column('insurance_sum_insured', __('Insurance Sum Insured'));
        $grid->column('insurance_premium', __('Insurance Premium'));
        $grid->column('markup', __('Markup'));
        $grid->column('insurance_coverage', __('Insurance Coverage'));
        $grid->column('confirmation_message', __('Confirmation Message'))->bool();
        $grid->column('insurance_region_id', __('Insurance Region ID'));
        $grid->column('agent_id', __('Agent ID'));
        $grid->column('insurer_name', __('Insurer Name'));
        $grid->column('insurance_type', __('Insurance Type'));
        $grid->column('surname', __('Surname'));
        $grid->column('telephone', __('Telephone'));
        $grid->column('other_name', __('Other Name'));
        $grid->column('payment_phone', __('Payment Phone'));
        $grid->column('paid', __('Paid'))->bool();
        $grid->column('completed', __('Completed'))->bool();
        $grid->column('pending', __('Pending'))->bool();
        $grid->column('cancelled', __('Cancelled'))->bool();
        $grid->column('national_id', __('National ID'));
        $grid->column('village_id', __('Village ID'));
        $grid->column('driving_license', __('Driving License'));
        $grid->column('passport', __('Passport'));
        $grid->column('email', __('Email'));
        $grid->column('lat', __('Latitude'));
        $grid->column('long', __('Longitude'));
        $grid->column('category', __('Category'));
        $grid->column('agent_sale', __('Agent Sale'))->bool();
        $grid->column('environments', __('Environments'));
        $grid->column('animal_production_business_duration', __('Animal Production Business Duration'));
        $grid->column('profession', __('Profession'));
        $grid->column('animals_in_posession_duration', __('Animals in Possession Duration'));
        $grid->column('animals_keeping_purpose', __('Animals Keeping Purpose'));
        $grid->column('loan', __('Loan'));
        $grid->column('selected_animals', __('Selected Animals'));
        $grid->column('animals_lost', __('Animals Lost'));
        $grid->column('selected_products', __('Selected Products'));
        $grid->column('causes_of_death', __('Causes of Death'));
        $grid->column('animal_health', __('Animal Health'));
        $grid->column('animal_illness', __('Animal Illness'));
        $grid->column('animal_treatment', __('Animal Treatment'));
        $grid->column('animal_contagious', __('Animal Contagious'));
        $grid->column('risks', __('Risks'));
        $grid->column('conviction', __('Conviction'));
        $grid->column('additional_info', __('Additional Info'));
        $grid->column('management', __('Management'));
        $grid->column('supervisory', __('Supervisory'));
        $grid->column('security', __('Security'));
        $grid->column('laborer', __('Laborer'));
        $grid->column('sub_county', __('Sub County'));
        $grid->column('parish', __('Parish'));
        $grid->column('village', __('Village'));
        $grid->column('district', __('District'));
        $grid->column('created_at', __('Created At'))->sortable()->hide();
        $grid->column('updated_at', __('Updated At'))->sortable()->hide();

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(NewInsuranceRequest::findOrFail($id));
        $show->field('id', __('Id'))->hide();
        $show->field('session_id', __('Session ID'))->hide();
        $show->field('phone_number', __('Phone Number'));
        $show->field('insurance_subscrption_for', __('Insurance Subscription For'));
        $show->field('insurance_enterprise_id', __('Insurance Enterprise ID'));
        $show->field('insurance_amount', __('Insurance Amount'));
        $show->field('module', __('Module'));
        $show->field('insurance_subscriber', __('Insurance Subscriber'));
        $show->field('insurance_acreage', __('Insurance Acreage'));
        $show->field('insurance_sum_insured', __('Insurance Sum Insured'));
        $show->field('insurance_premium', __('Insurance Premium'));
        $show->field('markup', __('Markup'));
        $show->field('insurance_coverage', __('Insurance Coverage'));
        $show->field('confirmation_message', __('Confirmation Message'));
        $show->field('insurance_region_id', __('Insurance Region ID'));
        $show->field('agent_id', __('Agent ID'));
        $show->field('insurer_name', __('Insurer Name'));
        $show->field('insurance_type', __('Insurance Type'));
        $show->field('surname', __('Surname'));
        $show->field('telephone', __('Telephone'));
        $show->field('other_name', __('Other Name'));
        $show->field('payment_phone', __('Payment Phone'));
        $show->field('paid', __('Paid'));
        $show->field('completed', __('Completed'));
        $show->field('pending', __('Pending'));
        $show->field('cancelled', __('Cancelled'));
        $show->field('national_id', __('National ID'));
        $show->field('village_id', __('Village ID'));
        $show->field('driving_license', __('Driving License'));
        $show->field('passport', __('Passport'));
        $show->field('email', __('Email'));
        $show->field('lat', __('Latitude'));
        $show->field('long', __('Longitude'));
        $show->field('category', __('Category'));
        $show->field('agent_sale', __('Agent Sale'));
        $show->field('environments', __('Environments'));
        $show->field('animal_production_business_duration', __('Animal Production Business Duration'));
        $show->field('profession', __('Profession'));
        $show->field('animals_in_posession_duration', __('Animals in Possession Duration'));
        $show->field('animals_keeping_purpose', __('Animals Keeping Purpose'));
        $show->field('loan', __('Loan'));
        $show->field('selected_animals', __('Selected Animals'));
        $show->field('animals_lost', __('Animals Lost'));
        $show->field('selected_products', __('Selected Products'));
        $show->field('causes_of_death', __('Causes of Death'));
        $show->field('animal_health', __('Animal Health'));
        $show->field('animal_illness', __('Animal Illness'));
        $show->field('animal_treatment', __('Animal Treatment'));
        $show->field('animal_contagious', __('Animal Contagious'));
        $show->field('risks', __('Risks'));
        $show->field('conviction', __('Conviction'));
        $show->field('additional_info', __('Additional Info'));
        $show->field('management', __('Management'));
        $show->field('supervisory', __('Supervisory'));
        $show->field('security', __('Security'));
        $show->field('laborer', __('Laborer'));
        $show->field('sub_county', __('Sub County'));
        $show->field('parish', __('Parish'));
        $show->field('village', __('Village'));
        $show->field('district', __('District'));
        $show->field('created_at', __('Created At'))->hide();
        $show->field('updated_at', __('Updated At'))->hide();

        return $show;
    }

    protected function form()
    {
        $form = new Form(new NewInsuranceRequest());
        $form->text('session_id', __('Session ID'))->hide();
        $form->text('phone_number', __('Phone Number'));
        $form->text('insurance_subscrption_for', __('Insurance Subscription For'));
        $form->text('insurance_enterprise_id', __('Insurance Enterprise ID'));
        $form->decimal('insurance_amount', __('Insurance Amount'))->default(0.00);
        $form->text('module', __('Module'));
        $form->text('insurance_subscriber', __('Insurance Subscriber'));
        $form->decimal('insurance_acreage', __('Insurance Acreage'))->default(0.00);
        $form->decimal('insurance_sum_insured', __('Insurance Sum Insured'))->default(0.00);
        $form->decimal('insurance_premium', __('Insurance Premium'))->default(0.00);
        $form->decimal('markup', __('Markup'))->default(0.00);
        $form->text('insurance_coverage', __('Insurance Coverage'));
        $form->switch('confirmation_message', __('Confirmation Message'));
        $form->text('insurance_region_id', __('Insurance Region ID'));
        $form->text('agent_id', __('Agent ID'));
        $form->text('insurer_name', __('Insurer Name'));
        $form->select('insurance_type', __('Insurance Type'))->options(['crop' => 'Crop', 'other' => 'Other'])->default('crop');
        $form->text('surname', __('Surname'));
        $form->text('telephone', __('Telephone'));
        $form->text('other_name', __('Other Name'));
        $form->text('payment_phone', __('Payment Phone'));
        $form->switch('paid', __('Paid'))->default(false);
        $form->switch('completed', __('Completed'))->default(false);
        $form->switch('pending', __('Pending'))->default(true);
        $form->switch('cancelled', __('Cancelled'))->default(false);
        $form->text('national_id', __('National ID'));
        $form->text('village_id', __('Village ID'));
        $form->text('driving_license', __('Driving License'));
        $form->text('passport', __('Passport'));
        $form->email('email', __('Email'));
        $form->text('lat', __('Latitude'));
        $form->text('long', __('Longitude'));
        $form->text('category', __('Category'));
        $form->switch('agent_sale', __('Agent Sale'));
        $form->text('environments', __('Environments'));
        $form->text('animal_production_business_duration', __('Animal Production Business Duration'));
        $form->text('profession', __('Profession'));
        $form->text('animals_in_posession_duration', __('Animals in Possession Duration'));
        $form->text('animals_keeping_purpose', __('Animals Keeping Purpose'));
        $form->text('loan', __('Loan'));
        $form->textarea('selected_animals', __('Selected Animals'));
        $form->textarea('animals_lost', __('Animals Lost'));
        $form->textarea('selected_products', __('Selected Products'));
        $form->textarea('causes_of_death', __('Causes of Death'));
        $form->textarea('animal_health', __('Animal Health'));
        $form->textarea('animal_illness', __('Animal Illness'));
        $form->textarea('animal_treatment', __('Animal Treatment'));
        $form->textarea('animal_contagious', __('Animal Contagious'));
        $form->textarea('risks', __('Risks'));
        $form->textarea('conviction', __('Conviction'));
        $form->textarea('additional_info', __('Additional Info'));
        $form->text('management', __('Management'));
        $form->text('supervisory', __('Supervisory'));
        $form->text('security', __('Security'));
        $form->text('laborer', __('Laborer'));
        $form->text('sub_county', __('Sub County'));
        $form->text('parish', __('Parish'));
        $form->text('village', __('Village'));
        $form->text('district', __('District'));

        return $form;
    }
}
