<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUssdAdvisoryTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ussd_advisory_topics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('topic');
            $table->mediumText('description')->nullable();
            $table->integer('position');
            $table->uuid('ussd_language_id');
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
        Schema::dropIfExists('ussd_advisory_topics');
    }
}
