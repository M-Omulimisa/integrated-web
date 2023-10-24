<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGardenCoordicatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garden_coordicates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('garden_id');
            $table->string('latitude');
            $table->string('longitude'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('garden_coordicates');
    }
}
