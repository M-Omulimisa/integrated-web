<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneValidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phone_validations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference', 15)->unique();

            $table->uuid('user_id');
            $table->uuid('organisation_id')->nullable();

            $table->string('phonenumber')->nullable();

            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();
            $table->text('message_payload')->nullable();
            $table->double('cost')->nullable();

            $table->string('phone_status')->nullable();
            $table->double('phone_match',15,2)->default(0);
            $table->string('phone_surname')->nullable();
            $table->string('phone_firstname')->nullable();
            $table->string('phone_middlename')->nullable();
            $table->string('mno_authority')->nullable();

            $table->text('report_path')->nullable();
            $table->string('source')->default('web');
            $table->string('token')->nullable();
            $table->enum('status', ['PENDING', 'SUCCESSFUL', 'FAILED'])->default('PENDING');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phone_validations');
    }
}
