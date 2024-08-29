<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFarmerMarketLocationColumnsToUssdSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->string('farmer_market_parish')->nullable();
            $table->string('farmer_market_subcounty')->nullable();
            $table->string('farmer_market_district')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->dropColumn('farmer_market_parish');
            $table->dropColumn('farmer_market_subcounty');
            $table->dropColumn('farmer_market_district');
        });
    }
}
