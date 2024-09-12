<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYoUgandaLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yo_uganda_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('date_time')->nullable();
            $table->text('amount')->nullable();
            $table->text('narrative')->nullable();
            $table->text('network_ref')->nullable();
            $table->text('external_ref')->nullable();
            $table->text('Msisdn')->nullable();
            $table->text('payer_names')->nullable();
            $table->text('payer_email')->nullable();
            $table->text('Signature')->nullable();
            $table->text('get_data')->nullable();
            $table->text('post_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yo_uganda_logs');
    }
}
