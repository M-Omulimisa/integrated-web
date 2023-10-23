<?php

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGardenModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garden_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Administrator::class, 'user_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('subcounty_id')->nullable();
            $table->integer('parish_id')->nullable();
            $table->integer('crop_id')->nullable();
            $table->text('village')->nullable();
            $table->text('crop_planted')->nullable();
            $table->text('name')->nullable();
            $table->text('details')->nullable();
            $table->text('size')->nullable();
            $table->text('status')->nullable();
            $table->text('soil_type')->nullable();
            $table->text('soil_ph')->nullable();
            $table->text('soil_texture')->nullable();
            $table->text('soil_depth')->nullable();
            $table->text('soil_moisture')->nullable();
            $table->text('photos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('garden_models');
    }
}
