<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSubscriptionLocationsToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            $table->string('district_id')->nullable()->change();
            $table->string('subcounty_id')->nullable()->change();
            $table->string('parish_id')->nullable()->change();
        });

        Schema::table('insurance_subscriptions', function (Blueprint $table) {
            $table->string('district_id')->nullable()->change();
            $table->string('subcounty_id')->nullable()->change();
            $table->string('parish_id')->nullable()->change();
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
