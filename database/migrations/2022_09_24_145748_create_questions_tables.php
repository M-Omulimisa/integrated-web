<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('keyword_id');
            $table->uuid('farmer_id');
            $table->string('phone')->nullable();
            $table->longText('body');
            $table->enum('sent_via', ['web', 'sms', 'app', 'ussd'])->default('web');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();

            $table->foreign('keyword_id')->on('keywords')->references('id')->onDelete('CASCADE');
            $table->foreign('farmer_id')->on('farmers')->references('id')->onDelete('CASCADE');
        });

         Schema::create('question_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id');
            $table->longText('image');
            $table->string('type')->nullable();
            $table->integer('size')->default(0);
            $table->timestamps();

            $table->foreign('question_id')->on('questions')->references('id')->onDelete('CASCADE');
        });

         Schema::create('question_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id');
            $table->uuid('user_id')->nullable();
            $table->uuid('extension_officer_id')->nullable();
            $table->longText('response');
            $table->string('status')->default('pending');
            $table->longText('error_message')->nullable();
            $table->timestamps();

            $table->foreign('question_id')->on('questions')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_responses');
        Schema::dropIfExists('question_images');
        Schema::dropIfExists('questions');
    }
}
