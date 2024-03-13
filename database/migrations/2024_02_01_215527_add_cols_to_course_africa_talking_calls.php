<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToCourseAfricaTalkingCalls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('online_course_africa_talking_calls', function (Blueprint $table) {
            $table->text('callSessionState')->nullable();
            $table->text('direction')->nullable();
            $table->text('callerCountryCode')->nullable();
            $table->text('destinationCountryCode')->nullable();
            $table->string('amount')->nullable();
            $table->string('durationInSeconds')->nullable();
            $table->string('callerNumber')->nullable();
            $table->string('destinationNumber')->nullable();
            $table->string('callerCarrierName')->nullable();
            $table->string('callStartTime')->nullable();
            $table->string('isActive')->nullable();
            $table->string('currencyCode')->nullable();
            $table->string('digit')->nullable();
        });
    }
 
    /**
     * Reverse the migrations.
     * 
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
