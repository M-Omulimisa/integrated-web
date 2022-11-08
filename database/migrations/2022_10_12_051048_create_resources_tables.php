<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {              
         Schema::create('training_resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('heading', 100)->nullable();
            $table->longText('thumbnail');
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

         Schema::create('training_resource_languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id');
            $table->uuid('language_id');
            $table->timestamps();

            $table->foreign('resource_id')->on('training_resources')->references('id')->onDelete('CASCADE');
            $table->foreign('language_id')->on('languages')->references('id')->onDelete('CASCADE');
        });

         Schema::create('training_resource_enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id');
            $table->uuid('enterprise_id');
            $table->timestamps();

            $table->foreign('resource_id')->on('training_resources')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE');
        });

         Schema::create('training_resource_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id');
            $table->string('subheading', 100)->nullable();
            $table->longText('details')->nullable();
            $table->longText('image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('resource_id')->on('training_resources')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_resource_sections');
        Schema::dropIfExists('training_resource_enterprises');
        Schema::dropIfExists('training_resource_languages');
        Schema::dropIfExists('training_resources');
    }
}
