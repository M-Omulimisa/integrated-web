<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineCourseTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_course_topics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('title')->nullable();
            $table->text('summary')->nullable();
            $table->text('details')->nullable();
            $table->text('image')->nullable();
            $table->text('video_url')->nullable();
            $table->text('audio_url')->nullable();
            $table->integer('online_course_id');
            $table->integer('online_course_category_id')->nullable()->default(1);
            $table->integer('online_course_chapter_id')->nullable()->default(1);
            $table->integer('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_course_topics');
    }
}
