<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineCourseAfricaTalkingCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_course_africa_talking_calls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('sessionId')->nullable();
            $table->string('type')->nullable();
            $table->text('phoneNumber')->nullable();
            $table->text('status')->nullable();
            $table->longText('postData')->nullable();
            $table->integer('cost')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_course_africa_talking_calls');
    }
}
