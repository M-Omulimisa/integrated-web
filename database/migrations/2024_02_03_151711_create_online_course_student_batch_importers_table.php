<?php

use App\Models\OnlineCourse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineCourseStudentBatchImportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_course_student_batch_importers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('file_path')->nullable();
            $table->foreignIdFor(OnlineCourse::class, 'online_course_id')->nullable();
            $table->string('status')->nullable()->default('Pending');
            $table->text('error_message')->nullable();
            $table->integer('total')->nullable()->default(0);
            $table->integer('success')->nullable()->default(0);
            $table->integer('failed')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_course_student_batch_importers');
    }
}
