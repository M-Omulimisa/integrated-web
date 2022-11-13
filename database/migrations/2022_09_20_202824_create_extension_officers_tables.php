<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionOfficersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('extension_officer_positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable(); 
            $table->string('name');
            $table->string('user_id')->nullable();
            $table->uuid('admin_level')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
            $table->foreign('admin_level')->on('country_admin_units')->references('id')->onDelete('CASCADE');
        });

        Schema::create('extension_officer_position_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('position_id');
            $table->uuid('location_id');
            $table->timestamps();

            $table->foreign('position_id')->on('extension_officer_positions')->references('id')->onDelete('CASCADE');
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
        });

        Schema::create('extension_officers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable(); 
            $table->uuid('extension_officer_id')->nullable();
            $table->uuid('position_id')->nullable(); 
            $table->string('name');           
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->enum('category', ['Extension Officer', 'Expert']);
            $table->string('gender')->nullable();
            $table->string('education_level')->nullable();
            $table->uuid('country_id');
            $table->uuid('location_id')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('created_by')->nullable();
            $table->string('password');
            $table->enum('status', ['Invited', 'Active', 'Inactive', 'Suspended', 'Banned'])->default('Active');
            $table->timestamps();
            
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
            $table->foreign('created_by')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('position_id')->on('extension_officer_positions')->references('id')->onDelete('CASCADE');
            // $table->foreign('extension_officer_id')->on('extension_officers')->references('id')->onDelete('CASCADE');
        });

        Schema::create('extension_officer_languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('extension_officer_id');
            $table->uuid('language_id');
            $table->timestamps();

            $table->foreign('extension_officer_id')->on('extension_officers')->references('id')->onDelete('CASCADE');
            $table->foreign('language_id')->on('languages')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        Schema::dropIfExists('extension_officer_position_locations');
        Schema::dropIfExists('extension_officer_languages');
        Schema::dropIfExists('extension_officers');
        Schema::dropIfExists('extension_officer_positions');
    }
}
