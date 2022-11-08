<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('iso_code');
            $table->string('dialing_code');
            $table->string('nationality');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->integer('length');
            $table->timestamps();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code');
            $table->timestamps();
        });

        Schema::create('country_currencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->uuid('currency_id');

            $table->unique(['country_id', 'currency_id']);
            $table->timestamps();

            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->foreign('currency_id')->on('currencies')->references('id')->onDelete('CASCADE');
        });

        Schema::create('country_admin_units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->string('name');
            $table->enum('order', [1,2,3,4,5,6,7,8,9,10]);

            $table->unique(['country_id', 'name']);
            $table->timestamps();

            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {               
        Schema::dropIfExists('country_admin_units');
        Schema::dropIfExists('country_currencies');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('countries');
    }
}
