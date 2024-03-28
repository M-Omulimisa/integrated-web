<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnterpriseRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //drop table if exists
        Schema::dropIfExists('enterprise_region');
        Schema::create('enterprise_region', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprise_id')->nullable();
            $table->foreignId('region_id')->nullable();
            // Add any additional columns you may need
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
        Schema::dropIfExists('enterprise_region');
    }
}
