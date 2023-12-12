<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUssdEvaluationSelectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ussd_evaluation_selections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ussd_evaluation_question_id');
            $table->integer('user_selection');
            $table->uuid('session_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ussd_evaluation_selections');
    }
}
