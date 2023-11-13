<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToWeatherSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->string('tool')->nullable();
            $table->string('weather_session_id')->nullable();
            $table->string('market_session_id')->nullable();
            $table->string('insurance_session_id')->nullable();
        });

        Schema::table('weather_subscriptions', function (Blueprint $table) {
            $table->string('district_id')->after('location_id');
            $table->string('subcounty_id')->after('district_id');
            $table->string('parish_id')->after('subcounty_id');
        });

        Schema::table('market_subscriptions', function (Blueprint $table) {
            $table->string('district_id')->after('location_id');
            $table->string('subcounty_id')->after('district_id');
            $table->string('parish_id')->after('subcounty_id');
        });

        Schema::table('insurance_subscriptions', function (Blueprint $table) {
            $table->string('district_id')->after('location_id');
            $table->string('subcounty_id')->after('district_id');
            $table->string('parish_id')->after('subcounty_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
