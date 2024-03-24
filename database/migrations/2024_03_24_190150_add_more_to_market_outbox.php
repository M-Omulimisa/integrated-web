<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreToMarketOutbox extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_outbox', function (Blueprint $table) {
            $table->text('market_info_message_campaign_id')->nullable();
            $table->text('market_package_message_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_outbox', function (Blueprint $table) {
            //
        });
    }
}
