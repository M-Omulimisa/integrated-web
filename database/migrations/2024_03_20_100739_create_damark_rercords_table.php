<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamarkRercordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damark_rercords', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('sender')->nullable();
            $table->text('message_body')->nullable();
            $table->text('external_ref')->nullable();
            $table->text('post_data')->nullable();
            $table->text('get_data')->nullable();
            $table->string('is_processed')->nullable()->default('No');
            $table->string('status')->nullable()->default('Pending');
            $table->text('error_message')->nullable();
            $table->string('type')->nullable();
            $table->string('farmer_id')->nullable();
            $table->string('question_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('damark_rercords');
    }
}
