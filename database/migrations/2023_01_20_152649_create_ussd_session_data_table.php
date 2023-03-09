<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUssdSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ussd_session_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('session_id');
            $table->string('phone_number');
            $table->string('module')->nullable();

            // Market subscription columns
            $table->string('market_subscrption_for')->nullable();
            $table->string('market_subscriber')->nullable();
            $table->uuid('market_package_id')->nullable();
            $table->uuid('market_language_id')->nullable();
            $table->string('market_frequency')->nullable();
            $table->integer('market_frequency_count')->nullable();
            $table->double('market_cost', 15,2)->nullable();
            $table->boolean('market_confirmation')->default(false);
            $table->string('market_payment_status')->default('PENDING');

            // weather
            $table->string('weather_subscrption_for')->nullable();
            $table->string('weather_subscriber')->nullable();
            $table->string('weather_subscriber_name')->nullable();
            $table->string('weather_district')->nullable();
            $table->uuid('weather_district_id')->nullable();
            $table->string('weather_subcounty')->nullable();
            $table->uuid('weather_subcounty_id')->nullable();
            $table->string('weather_parish')->nullable();
            $table->uuid('weather_parish_id')->nullable();
            $table->string('weather_frequency')->nullable();
            $table->double('weather_frequency_count', 15,2)->nullable();
            $table->boolean('weather_confirmation')->default(false);
            $table->string('weather_payment_status')->default('PENDING');

            // insurance
            $table->string('insurance_subscrption_for')->nullable();
            $table->string('insurance_subscriber')->nullable();
            $table->string('insurance_subscriber_name')->nullable();
            $table->string('insurance_district')->nullable();
            $table->uuid('insurance_district_id')->nullable();
            $table->string('insurance_subcounty')->nullable();
            $table->uuid('insurance_subcounty_id')->nullable();
            $table->string('insurance_parish')->nullable();
            $table->uuid('insurance_parish_id')->nullable();
            $table->uuid('insurance_season_id')->nullable();
            $table->uuid('insurance_enterprise_id')->nullable();
            $table->double('insurance_acreage',15,2)->nullable();
            $table->double('insurance_sum_insured', 15,2)->nullable();
            $table->double('insurance_premium', 15,2)->nullable();
            $table->boolean('insurance_confirmation')->default(false);
            $table->string('insurance_payment_status')->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ussd_session_data');
    }
}
