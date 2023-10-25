<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_specifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('farmer_specification');
            $table->uuid('country_id');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->mediumText('description')->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->string('field_type');
            $table->mediumText('html_representation');
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
        Schema::dropIfExists('farmer_specifications');
    }
}
