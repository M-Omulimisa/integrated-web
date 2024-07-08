<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingMissingFieldsToUssdSessionData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->string('agent_id', 36)->nullable();
            $table->string('insurer_name', 36)->nullable();
            $table->string('insurance_type', 36)->nullable()->default("crop");
            $table->string('surname', 36)->nullable();
            $table->string('other_name', 36)->nullable();
            $table->string('payment_phone', 36)->nullable();
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
            $table->dropColumn('agent_id');
            $table->dropColumn('insurer_name');
            $table->dropColumn('insurance_type');
            $table->dropColumn('surname');
            $table->dropColumn('other_name');
            $table->dropColumn('payment_phone');
        });
    }
}
