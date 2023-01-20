<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryOrganisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_organisation', function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->uuid('organisation_id');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
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
        Schema::dropIfExists('country_organisation');
    }
}
