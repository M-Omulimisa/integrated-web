<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordingOnlineCourseAfricaTalkingCalls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course_africa_talking_calls', function (Blueprint $table) {
            $table->text('recordingUrl')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_course_africa_talking_calls', function (Blueprint $table) {
            //
        });
    }
}
