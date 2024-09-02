<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFarmerMarketFieldsToUssdSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->string('farmer_market_units')->nullable();
            $table->decimal('farmer_market_price', 10, 2)->nullable()->after('farmer_market_units');
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
            $table->dropColumn('farmer_market_units');
            $table->dropColumn('farmer_market_price');
        });
    }
}
