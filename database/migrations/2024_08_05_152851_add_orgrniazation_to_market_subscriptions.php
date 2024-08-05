<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrgrniazationToMarketSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            $table->string('organization_id')->nullable();
            $table->string('belong_to_ogranization')->nullable()->default('No');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            //
        });
    }
}
