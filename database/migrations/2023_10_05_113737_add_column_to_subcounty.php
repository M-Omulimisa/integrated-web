<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToSubcounty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->integer('district_id')->nullable();
            $table->integer('subcounty_id')->nullable();
            $table->integer('parish_id')->nullable();
            $table->integer('bank_id')->nullable();
            $table->integer('other_livestock_count')->nullable();
            $table->integer('poultry_count')->nullable();
            $table->integer('sheep_count')->nullable();
            $table->integer('goat_count')->nullable();
            $table->integer('cattle_count')->nullable();
            $table->text('bank_account_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcounty', function (Blueprint $table) {
            //
        });
    }
}
