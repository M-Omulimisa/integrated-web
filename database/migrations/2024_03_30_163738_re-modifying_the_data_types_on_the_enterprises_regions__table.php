<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReModifyingTheDataTypesOnTheEnterprisesRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('enterprise_region');
        Schema::create('enterprise_region', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprise_id')->nullable();
            $table->foreignId('region_id')->nullable();
            // Add any additional columns you may need
            $table->timestamps();
        });
        
        Schema::table('enterprise_region', function (Blueprint $table) {
            $table->string('enterprise_id', 36)->change();
            $table->string('region_id', 36)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
