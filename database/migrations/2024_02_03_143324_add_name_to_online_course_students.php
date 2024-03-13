<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameToOnlineCourseStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course_students', function (Blueprint $table) {
            $table->string('user_id')->nullable()->change();
            $table->text('name')->nullable();
            $table->text('phone')->nullable();
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
