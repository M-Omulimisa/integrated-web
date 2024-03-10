<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_campaigns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('title')->nullable();
            $table->text('short_description')->nullable();
            $table->text('body')->nullable();
            $table->text('image')->nullable();
            $table->text('url')->nullable();
            $table->string('type')->nullable();
            $table->string('priority')->nullable()->default('Normal');
            $table->string('status')->nullable()->default('Draft');
            $table->string('ready_to_send')->nullable()->default('No');
            $table->string('target_type')->nullable()->default('All');
            $table->string('target_user_role_id')->nullable();
            $table->text('target_users')->nullable();
            $table->string('send_notification')->nullable()->default('Yes');
            $table->string('send_email')->nullable()->default('No');
            $table->string('send_sms')->nullable()->default('No');
            $table->dateTime('sheduled_at')->nullable();
            $table->string('send_time')->nullable()->default('Now'); //Now, Later
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_campaigns');
    }
}
