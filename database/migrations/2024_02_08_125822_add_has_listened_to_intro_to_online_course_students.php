<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasListenedToIntroToOnlineCourseStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course_students', function (Blueprint $table) {
            $table->string('has_listened_to_intro')->default('No')->nullable()->after('progress');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_course_students', function (Blueprint $table) {
            //
        });
    }
}
