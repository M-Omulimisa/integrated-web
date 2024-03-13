<?php

use App\Models\OnlineCourse;
use App\Models\OnlineCourseTopic;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineCourseLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_course_lessons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(OnlineCourseTopic::class);
            $table->foreignIdFor(OnlineCourse::class);
            $table->foreignIdFor(User::class, 'student_id');
            $table->foreignIdFor(User::class, 'instructor_id');
            $table->dateTime('sheduled_at')->nullable();
            $table->dateTime('attended_at')->nullable();
            $table->string('status')->nullable()->default('Pending');
            $table->string('has_error')->nullable()->default('No');
            $table->text('error_message')->nullable();
            $table->text('details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_course_lessons');
    }
}
