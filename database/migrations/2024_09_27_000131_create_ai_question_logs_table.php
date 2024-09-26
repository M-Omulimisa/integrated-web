<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiQuestionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_question_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->text('audio')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('conversation_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_question_logs');
    }
}
