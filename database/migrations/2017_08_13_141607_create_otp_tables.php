<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtpTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('one_time_password_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('otp_code')->index();
            $table->string('refer_number')->index();
            $table->string('status')->index();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::connection('mysql')->create('one_time_passwords', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('status')->index();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::connection('mysql')->create('one_time_password_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('otp_id');
            $table->string('phone')->index();
            $table->string('type')->index();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('send_failed_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('discarded_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::connection('mysql')->dropIfExists('one_time_password_logs');
        Schema::connection('mysql')->dropIfExists('one_time_passwords');
        Schema::connection('mysql')->dropIfExists('one_time_password_activities');
    }
}
