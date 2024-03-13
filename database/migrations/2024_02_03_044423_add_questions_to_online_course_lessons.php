<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionsToOnlineCourseLessons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course_lessons', function (Blueprint $table) {
            $table->text('student_audio_question')->nullable();
            $table->text('instructor_audio_question')->nullable();
            $table->string('student_quiz_answer')->nullable()->default('Not Answered');
            $table->string('quiz_answer_status')->nullable()->default('Not Answered');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_course_lessons', function (Blueprint $table) {
            //
        });
    }
}
