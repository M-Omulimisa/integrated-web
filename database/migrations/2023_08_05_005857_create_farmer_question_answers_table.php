<?php

use App\Models\FarmerQuestion;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_question_answers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(User::class)->default(1);
            $table->foreignIdFor(FarmerQuestion::class)->default(1);
            $table->string('verified')->nullable()->default('no');
            $table->text('body')->nullable();
            $table->text('audio')->nullable();
            $table->text('photo')->nullable();
            $table->text('video')->nullable();
            $table->text('document')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmer_question_answers');
    }
}
