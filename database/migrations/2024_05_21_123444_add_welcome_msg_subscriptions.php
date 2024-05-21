<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWelcomeMsgSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            $table->string('welcome_msg_sent')->default('No')->nullable();
            $table->dateTime('welcome_msg_sent_at')->nullable();
            $table->text('welcome_msg_sent_details')->nullable();
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
