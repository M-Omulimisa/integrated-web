<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToFarmers2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_resources', function (Blueprint $table) {
            $table->text('body')->nullable()->change();
            $table->text('file')->nullable()->change();
            $table->text('type')->nullable()->change();
            $table->text('heading')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_resources', function (Blueprint $table) {
            //
        });
    }
}
