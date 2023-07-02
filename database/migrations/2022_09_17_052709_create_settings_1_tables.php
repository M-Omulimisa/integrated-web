<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettings1Tables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {       
        Schema::create('languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('country_id');
            $table->timestamps();

            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
        });

        Schema::create('measure_units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('unit_id')->nullable();
            $table->enum('category', ['Crop', 'Livestock']);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')->on('measure_units')->references('id')->onDelete('CASCADE');
        });

        Schema::create('enterprise_varieties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('enterprise_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('enterprise_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('enterprise_variety_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('keywords', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('category');
            $table->uuid('language_id');
            $table->uuid('organisation_id')->nullable();
            $table->string('shortcode')->nullable();
            $table->timestamps();

            $table->foreign('language_id')->on('languages')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
        });

        Schema::create('keyword_success_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('keyword_id');
            $table->longText('response');
            $table->timestamps();

            $table->foreign('keyword_id')->on('keywords')->references('id')->onDelete('CASCADE');
        });

        Schema::create('keyword_failure_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('keyword_id');
            $table->string('reason');
            $table->longText('response');
            $table->unique(['keyword_id', 'reason']);
            $table->timestamps();

            $table->foreign('keyword_id')->on('keywords')->references('id')->onDelete('CASCADE');
        });

        Schema::create('agro_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('category');
            $table->uuid('unit_id');
            $table->double('price',15,2)->default(0);
            $table->timestamps();

            $table->foreign('unit_id')->on('measure_units')->references('id')->onDelete('CASCADE');
        });

        Schema::create('agent_commission_rankings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->string('name');
            $table->integer('order')->default(1);
            $table->unique(['country_id', 'order']);
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
        Schema::dropIfExists('agent_commission_rankings');
        Schema::dropIfExists('agro_products');
        Schema::dropIfExists('enterprise_types');
        Schema::dropIfExists('enterprise_varieties');
        Schema::dropIfExists('keyword_success_responses');
        Schema::dropIfExists('keyword_failure_responses');
        Schema::dropIfExists('keywords');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('enterprises');
        Schema::dropIfExists('measure_units');        
    }
}
