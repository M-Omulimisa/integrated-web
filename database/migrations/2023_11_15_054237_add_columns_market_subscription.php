<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsMarketSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->string('market_region')->nullable()->after('market_subscriber');
            $table->string('market_region_id')->nullable()->after('market_region');
            $table->string('market_language')->nullable()->after('market_region_id');
        });

        Schema::table('market_subscriptions', function (Blueprint $table) {
            $table->string('region_id')->before('language_id');
        });

        Schema::create('market_package_regions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('package_id');
            $table->string('region_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
