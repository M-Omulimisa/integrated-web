<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertDecimalToDoubleInNewInsuranceRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_insurance_requests', function (Blueprint $table) {
            $table->float('insurance_amount', 15, 2)->change();
            $table->float('insurance_acreage', 15, 2)->change();
            $table->float('insurance_sum_insured', 15, 2)->change();
            $table->float('insurance_premium', 15, 2)->change();
            $table->float('markup', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_insurance_requests', function (Blueprint $table) {
            //
        });
    }
}
