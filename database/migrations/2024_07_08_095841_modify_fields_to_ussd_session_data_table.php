<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFieldsToUssdSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->boolean('paid')->nullable()->default(false)->change();
            $table->boolean('completed')->nullable()->default(false)->change();
            $table->boolean('pending')->nullable()->default(true)->change();
            $table->boolean('cancelled')->nullable()->default(false)->change();
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
        });
    }
}
