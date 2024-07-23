<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewImportFieldsToFarmers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            if (!Schema::hasColumn('farmers', 'external_id')) {
                $table->text('external_id')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'external_group_id')) {
                $table->text('external_group_id')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'other_name')) {
                $table->text('other_name')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'participant_code')) {
                $table->text('participant_code')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'household_size')) {
                $table->text('household_size')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'household_details')) {
                $table->text('household_details')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'disability_type')) {
                $table->text('disability_type')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'number_of_pwd_in_house_hold')) {
                $table->text('number_of_pwd_in_house_hold')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'household_head_relationship')) {
                $table->text('household_head_relationship')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'household_head_year_of_birth')) {
                $table->text('household_head_year_of_birth')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'household_head_occupation')) {
                $table->text('household_head_occupation')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'received_other_ngo_support')) {
                $table->text('received_other_ngo_support')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'min_income_range')) {
                $table->text('min_income_range')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'max_income_range')) {
                $table->text('max_income_range')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'own_a_smart_phone')) {
                $table->text('own_a_smart_phone')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'village_id')) {
                $table->text('village_id')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'is_house_hold_head')) {
                $table->text('is_house_hold_head')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'participant_mobile_money')) {
                $table->text('participant_mobile_money')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'employment_status')) {
                $table->text('employment_status')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'created_by')) {
                $table->text('created_by')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'highest_education_level')) {
                $table->text('highest_education_level')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'main_economic_activity')) {
                $table->text('main_economic_activity')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'number_of_children')) {
                $table->text('number_of_children')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'next_of_kin_first_name')) {
                $table->text('next_of_kin_first_name')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'next_of_kin_last_name')) {
                $table->text('next_of_kin_last_name')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'next_of_kin_contact')) {
                $table->text('next_of_kin_contact')->nullable();
            }
            if (!Schema::hasColumn('farmers', 'is_imported')) {
                $table->string('is_imported')->nullable()->default('No');
            }
            if (!Schema::hasColumn('farmers', 'imported_processed')) {
                $table->string('imported_processed')->nullable()->default('No');
            }
            if (!Schema::hasColumn('farmers', 'imported_page_number')) {
                $table->integer('imported_page_number')->nullable()->default(0);
            }
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
