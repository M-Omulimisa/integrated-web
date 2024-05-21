<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreReminderToMarketSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            $table->string('is_test')->default('No')->nullable();
            $table->string('pre_renew_message_sent')->default('No')->nullable();
            $table->dateTime('pre_renew_message_sent_at')->nullable();
            $table->text('pre_renew_message_sent_details')->nullable();
        });
    }
/*  */
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
