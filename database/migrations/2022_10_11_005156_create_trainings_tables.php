<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {                
         Schema::create('training_topics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->uuid('organisation_id')->nullable();
            $table->string('topic');
            $table->longText('details')->nullable();
            $table->boolean('status')->default(true);
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
        });

        Schema::create('training_subtopics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('topic_id');
            $table->string('title');
            $table->enum('type', ['subtopic', 'activity']);
            $table->longText('details')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('topic_id')->on('training_topics')->references('id')->onDelete('CASCADE');
        });

        Schema::create('trainings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('village_agent_id')->nullable();
            $table->uuid('extension_officer_id')->nullable();
            $table->uuid('user_id')->nullable();

            $table->uuid('subtopic_id');
            $table->longText('details')->nullable();

            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('venue')->nullable();
            $table->uuid('location_id')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->enum('status', ['Pending', 'On-going', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('village_agent_id')->on('agents')->references('id')->onDelete('CASCADE');
            $table->foreign('extension_officer_id')->on('extension_officers')->references('id')->onDelete('CASCADE');
            $table->foreign('subtopic_id')->on('training_subtopics')->references('id')->onDelete('CASCADE');
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
        });

        Schema::create('training_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('training_id');
            $table->longText('attachments');
            $table->timestamps();

            $table->foreign('training_id')->on('trainings')->references('id')->onDelete('CASCADE');
        });

        Schema::create('training_farmer_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('training_id');
            $table->uuid('farmer_group_id');
            $table->timestamps();

            $table->foreign('training_id')->on('trainings')->references('id')->onDelete('CASCADE');
            $table->foreign('farmer_group_id')->on('farmer_groups')->references('id')->onDelete('CASCADE');
        });

        Schema::create('training_farmers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('training_id');
            $table->uuid('farmer_id');
            $table->timestamps();

            $table->foreign('training_id')->on('trainings')->references('id')->onDelete('CASCADE');
            $table->foreign('farmer_id')->on('farmers')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_farmers');
        Schema::dropIfExists('training_farmer_groups');
        Schema::dropIfExists('training_attachments');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('training_subtopics');
        Schema::dropIfExists('training_topics');
    }
}
