<?php

use App\Models\NotificationCampaign;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(NotificationCampaign::class)->nullable();
            $table->foreignIdFor(User::class)->nullable();
            $table->timestamps();
            $table->text('title')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('email')->nullable();
            $table->text('sms_body')->nullable();
            $table->text('short_description')->nullable();
            $table->text('body')->nullable();
            $table->text('image')->nullable();
            $table->text('url')->nullable();
            $table->string('type')->nullable();
            $table->string('priority')->nullable()->default('Normal');
            $table->string('status')->nullable()->default('Draft');
            $table->string('ready_to_send')->nullable()->default('No');
            $table->string('send_notification')->nullable()->default('Yes');
            $table->string('send_email')->nullable()->default('No');
            $table->string('send_sms')->nullable()->default('No');
            $table->dateTime('sheduled_at')->nullable();
            $table->string('email_sent')->nullable()->default('No');
            $table->string('sms_sent')->nullable()->default('No');
            $table->string('notification_seen')->nullable()->default('No');
            $table->dateTime('notification_seen_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_messages');
    }
}
