<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySubscriptionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('insurance_farmer_compensations');

        Schema::create('insurance_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('agent_id')->nullable();
            $table->uuid('farmer_id')->nullable();
            $table->uuid('location_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email')->nullable();            
            
            $table->uuid('calculator_values_id')->nullable();
            $table->uuid('season_id')->nullable();
            $table->uuid('enterprise_id')->nullable();
            $table->double('acreage',15,2)->nullable();
            $table->double('sum_insured', 15,2)->nullable();
            $table->double('premium', 15,2)->nullable();

            $table->boolean('status')->default(false);
            $table->uuid('user_id')->nullable();
            $table->uuid('organisation_id')->nullable();
            $table->boolean('seen_by_admin')->default(false);

            $table->timestamps();

            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('market_subscriptions', function (Blueprint $table) {
            $table->string('phone')->before('email')->nullable();
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->dropColumn('paying_account');
            $table->dropColumn('payment_amount');
            $table->dropColumn('payment_confirmation');
            $table->dropColumn('reference_id');
            $table->dropColumn('payment_reference');
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_provider');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_details');
            $table->dropColumn('payment_failure_reason');
        });

        Schema::table('weather_subscriptions', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->dropColumn('paying_account');
            $table->dropColumn('payment_amount');
            $table->dropColumn('payment_confirmation');
            $table->dropColumn('reference_id');
            $table->dropColumn('payment_reference');
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_provider');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_details');
            $table->dropColumn('payment_failure_reason');
        });

        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->dropColumn('market_currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_subscriptions');
    }
}