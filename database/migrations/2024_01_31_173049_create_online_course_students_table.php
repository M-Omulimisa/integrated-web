<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineCourseStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_course_students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('online_course_id');
            $table->integer('user_id');
            $table->integer('online_course_category_id')->nullable()->default(1);
            $table->string('status')->nullable()->default('pending');
            $table->string('completion_status')->nullable()->default('incomplete');
            $table->integer('position')->nullable()->default(1);
            $table->float('progress')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_course_students');
    }
}
