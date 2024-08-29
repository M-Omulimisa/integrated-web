<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAFewMoreFarmerMarketFieldsToUssdSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->string('farmer_market_category')->nullable();
            $table->string('farmer_market_product')->nullable();
            $table->string('farmer_market_quantity')->nullable();
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
            if (Schema::hasColumn('ussd_session_data', 'farmer_market_category')) {
                $table->dropColumn('farmer_market_category');
            }

            if (Schema::hasColumn('ussd_session_data', 'farmer_market_product')) {
                $table->dropColumn('farmer_market_product');
            }

            if (Schema::hasColumn('ussd_session_data', 'farmer_market_quantity')) {
                $table->dropColumn('farmer_market_quantity');
            }
        });
    }
}
