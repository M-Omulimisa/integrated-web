<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('digit');
            $table->string('category')->nullable();
            $table->string('position')->nullable();
            $table->uuid('language_id');
            $table->string('description');
            $table->string('constraints')->nullable();
            $table->uuid('user_id');
            $table->unique(['digit', 'language_id']);
            $table->timestamps();

            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
        
        Schema::create('weather_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_id')->nullable();
            $table->uuid('language_id')->nullable();
            $table->uuid('location_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('frequency');
            $table->integer('period_paid');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->boolean('status')->default(false);
            $table->uuid('user_id')->nullable();
            $table->uuid('renewal_id')->nullable();
            $table->uuid('organisation_id')->nullable();
            $table->boolean('seen_by_admin')->default(false);

            $table->boolean('outbox_generation_status')->default(false);
            $table->boolean('outbox_reset_status')->default(false);
            $table->date('outbox_last_date')->nullable();
            $table->string('awhere_field_id')->nullable();
            $table->timestamp('trial_expiry_sms_sent_at')->nullable();
            $table->text('trial_expiry_sms_failure_reason')->nullable();
            $table->timestamps();

            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('renewal_id')->references('id')->on('weather_subscriptions')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('weather_outbox', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subscription_id');
            $table->uuid('farmer_id')->nullable();
            $table->string('recipient');
            $table->text('message');
            $table->string('status');
            $table->string('statuses')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamp('processsed_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->enum('sent_via', ['sms', 'app'])->default('sms');
            $table->timestamps();

            $table->foreign('subscription_id')->references('id')->on('weather_subscriptions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_outbox');
        Schema::dropIfExists('weather_subscriptions');
        Schema::dropIfExists('weather_conditions');
    }
}
