<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDoneWithUssdFarmingOnboardingToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('done_with_ussd_farming_onboarding')->default("No")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Specify the original data type here for reversibility
            // For example, if it was originally a boolean:
            $table->boolean('done_with_ussd_farming_onboarding')->change();
            // If it was an integer:
            // $table->integer('done_with_ussd_farming_onboarding')->change();
        });
    }
}
