<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToWeatherSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weather_subscriptions', function (Blueprint $table) {
            $table->string('is_processed')->default('No')->nullable();
            $table->string('is_test')->default('No')->nullable();
            $table->string('pre_renew_message_sent')->default('No')->nullable();
            $table->dateTime('pre_renew_message_sent_at')->nullable();
            //pre_renew_message_sent_details
            $table->text('pre_renew_message_sent_details')->nullable();
            // welcome_msg_sent
            $table->string('welcome_msg_sent')->default('No')->nullable();
            //welcome_msg_sent_at
            $table->dateTime('welcome_msg_sent_at')->nullable();
            //welcome_msg_sent_details
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
        Schema::table('weather_subscriptions', function (Blueprint $table) {
            //
        });
    }
}
