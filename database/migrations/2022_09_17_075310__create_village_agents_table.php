<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillageAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable(); 
            $table->uuid('agent_id')->nullable(); 
            $table->string('name');
            $table->string('national_id_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_mm_phone')->default(0);
            $table->string('mm_phone')->nullable();
            $table->string('email')->nullable();
            $table->uuid('country_id');
            $table->uuid('location_id')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('password')->nullable();
            $table->string('user_id')->nullable();
            $table->enum('status', ['Invited', 'Active', 'Inactive', 'Suspended', 'Banned'])->default('Active');
            $table->enum('category', ['village', 'insurance']);
            $table->text('photo')->nullable();
            $table->text('id_photo_front')->nullable();
            $table->text('id_photo_back')->nullable();
            $table->uuid('commission_ranking_id')->nullable();
            $table->timestamps();

            $table->foreign('commission_ranking_id')->on('agent_commission_rankings')->references('id')->onDelete('CASCADE');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::table('agents', function($table) {
            // $table->foreign('agent_id')->on('agents')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
