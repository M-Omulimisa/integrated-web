<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddErrorsToOnlineCourseAfricaTalkingCalls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course_africa_talking_calls', function (Blueprint $table) {
            $table->string('has_error')->nullable()->default('No');
            $table->text('error_message')->nullable();
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
