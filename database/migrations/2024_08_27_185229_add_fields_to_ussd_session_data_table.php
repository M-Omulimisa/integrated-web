<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUssdSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->string('farmer_market_user_name')->nullable();
            $table->string('farmer_market_user_type')->nullable();
            $table->string('farmer_market_user_gender')->nullable();
            $table->string('farmer_market_user_age')->nullable();
            $table->string('farmer_market_user_district')->nullable();
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
            $table->dropColumn('farmer_market_user_name');
            $table->dropColumn('farmer_market_user_type');
            $table->dropColumn('farmer_market_user_gender');
            $table->dropColumn('farmer_market_user_age');
            $table->dropColumn('farmer_market_user_district');
        });
    }
}
