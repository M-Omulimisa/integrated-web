<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
         Schema::create('alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id')->nullable();
            $table->longText('message');
            $table->boolean('is_to_users')->default(false);
            $table->boolean('is_to_farmers')->default(false);
            $table->boolean('is_village_agents')->default(false);
            $table->boolean('is_extension_officers')->default(false);
            $table->boolean('is_scheduled')->default(false);
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('status')->default('Pending');
            $table->uuid('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
        });

         Schema::create('alert_recipients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('alert_id');
            $table->uuid('user_id')->nullable();
            $table->uuid('farmer_id')->nullable();
            $table->uuid('village_agent_id')->nullable();
            $table->uuid('extension_officer_id')->nullable();
            $table->string('phone');
            $table->string('status')->default('Pending');
            $table->timestamps();

            $table->foreign('farmer_id')->on('farmers')->references('id')->onDelete('CASCADE');
            $table->foreign('village_agent_id')->on('agents')->references('id')->onDelete('CASCADE');
            $table->foreign('extension_officer_id')->on('extension_officers')->references('id')->onDelete('CASCADE');
        });

         Schema::create('alert_languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('alert_id');
            $table->uuid('language_id');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('alert_id')->on('farmers')->references('id')->onDelete('CASCADE');
            $table->foreign('language_id')->on('languages')->references('id')->onDelete('CASCADE');
        });

         Schema::create('alert_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('alert_id');
            $table->uuid('location_id');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('alert_id')->on('farmers')->references('id')->onDelete('CASCADE');
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
        });

         Schema::create('alert_enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('alert_id');
            $table->uuid('enterprise_id');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('alert_id')->on('farmers')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE');
        });

         Schema::create('alert_user_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('alert_id');
            $table->string('user_group');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('alert_id')->on('farmers')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_user_groups');
        Schema::dropIfExists('alert_enterprises');
        Schema::dropIfExists('alert_locations');
        Schema::dropIfExists('alert_languages');
        Schema::dropIfExists('alert_recipients');
        Schema::dropIfExists('alerts');
    }
}
