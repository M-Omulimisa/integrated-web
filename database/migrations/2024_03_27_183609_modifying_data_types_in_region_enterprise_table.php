<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyingDataTypesInRegionEnterpriseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
        Schema::table('enterprise_region', function (Blueprint $table) {
            $table->uuid('enterprise_id')->change();
            $table->uuid('region_id')->change();
        });
    }
}
