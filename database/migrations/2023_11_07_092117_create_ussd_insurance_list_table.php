<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUssdInsuranceListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->double('insurance_amount', 15,2)->nullable()->before('insurance_confirmation');
            $table->string('referee_phone')->nullable();
        });

        Schema::create('ussd_insurance_lists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ussd_session_data_id');
            $table->uuid('insurance_enterprise_id')->nullable();
            $table->double('insurance_acreage',15,2)->nullable();
            $table->double('insurance_sum_insured', 15,2)->nullable();
            $table->double('insurance_premium', 15,2)->nullable();
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
        Schema::dropIfExists('ussd_insurance_lists');
    }
}
