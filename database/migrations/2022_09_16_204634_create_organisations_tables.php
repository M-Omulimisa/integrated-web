<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('organisations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('logo')->nullable();
            $table->text('address');
            $table->text('services');
            $table->timestamps();
        });

        Schema::create('organisation_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('name');
            $table->timestamps();
        });

        Schema::create('organisation_positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
        });

        Schema::create('organisation_position_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('position_id');
            $table->uuid('permission_id');
            $table->timestamps();

            $table->foreign('position_id')->on('organisation_positions')->references('id')->onDelete('CASCADE');
            $table->foreign('permission_id')->on('organisation_permissions')->references('id')->onDelete('CASCADE');
        });

        Schema::create('organisation_user_positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('position_id');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('position_id')->on('organisation_positions')->references('id')->onDelete('CASCADE');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organisation_user_positions');
        Schema::dropIfExists('organisation_position_permissions');
        Schema::dropIfExists('organisation_positions');
        Schema::dropIfExists('organisation_permissions');
        Schema::dropIfExists('organisations');
    }
}
