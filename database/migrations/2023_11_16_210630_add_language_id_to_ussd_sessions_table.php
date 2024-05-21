<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageIdToUssdSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_sessions', function (Blueprint $table) {
            
            $table->text('data')->nullable()->after('last_menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ussd_sessions', function (Blueprint $table) {
            //
        });
    }
}
