<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToFarmers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->string('poverty_level')->nullable();
            $table->string('food_security_level')->nullable();
            $table->string('marital_status')->nullable();
            $table->integer('family_size')->nullable();
            $table->string('farm_decision_role')->nullable();
            $table->boolean('is_pwd')->nullable();
            $table->boolean('is_refugee')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('age_group')->nullable();
            $table->string('language_preference')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('phone_type')->nullable();
            $table->string('preferred_info_type')->nullable();
            $table->double('home_gps_latitude')->nullable();
            $table->double('home_gps_longitude')->nullable();
            $table->string('village')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('land_registration_numbers')->nullable();
            $table->integer('labor_force')->nullable();
            $table->string('equipment_owned')->nullable();
            $table->string('livestock')->nullable();
            $table->string('crops_grown')->nullable();
            $table->boolean('has_bank_account')->nullable();
            $table->boolean('has_mobile_money_account')->nullable();
            $table->string('payments_or_transfers')->nullable();
            $table->string('financial_service_provider')->nullable();
            $table->boolean('has_credit')->nullable();
            $table->integer('loan_size')->nullable();
            $table->string('loan_usage')->nullable();
            $table->text('farm_business_plan')->nullable();
            $table->string('covered_risks')->nullable();
            $table->string('insurance_company_name')->nullable();
            $table->integer('insurance_cost')->nullable();
            $table->integer('repaid_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmers', function (Blueprint $table) {
            //
        });
    }
}
