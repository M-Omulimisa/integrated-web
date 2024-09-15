<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewLatestFieldsToFarmers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->text('safety_measures')->nullable();
            $table->text('pesticides_fertilizers_type')->nullable();
            $table->text('pesticides_fertilizers_quantity')->nullable();
            $table->text('water_conservation_practices')->nullable();
            $table->text('biodiversity_protection_measures')->nullable();
            $table->text('processing_techniques')->nullable();
            $table->text('processing_techniques_other')->nullable();
            $table->text('certifications_obtained')->nullable();
            $table->text('certifications_obtained_other')->nullable();
            $table->text('quality_control_measures')->nullable();
            $table->text('supply_chain_entities_contracts')->nullable();
            $table->text('financial_transactions')->nullable();
            $table->text('access_to_credit')->nullable();
            $table->text('credit_details_source')->nullable();
            $table->text('credit_details_amount')->nullable();
            $table->text('credit_details_purpose')->nullable();
            $table->text('membership_in_savings_credit_groups')->nullable();
            $table->text('savings_credit_groups_name')->nullable();
            $table->text('income_sources')->nullable();
            $table->text('income_sources_non_agricultural')->nullable();
            $table->text('average_annual_income_from_farming')->nullable();
            $table->text('mobile_phone_ownership')->nullable();
            $table->text('use_of_mobile_money_services')->nullable();
            $table->text('access_to_internet')->nullable();
            $table->text('use_of_agricultural_apps')->nullable();
            $table->text('agricultural_apps_types')->nullable();
            $table->text('agricultural_apps_frequency')->nullable();
            $table->text('consent_to_data_collection_use')->nullable();
            $table->text('data_security_measures')->nullable();
            $table->text('access_control')->nullable();
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
