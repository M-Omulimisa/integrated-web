<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePrimaryKeyOnMarketPackageEnterprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_package_enterprises', function (Blueprint $table) {
            
            $table->dropColumn('id');
  

        });

        Schema::table('market_package_enterprises', function (Blueprint $table) {
            
            $table->id();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_package_enterprises', function (Blueprint $table) {
            //
        });
    }
}
