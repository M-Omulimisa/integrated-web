<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuColsToRegions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('region', function (Blueprint $table) {
            if (!Schema::hasColumn('region', 'menu_status')) $table->boolean('menu_status')->default(false);
            if (!Schema::hasColumn('region', 'menu_name')) $table->string('menu_name')->nullable();
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
        Schema::table('region', function (Blueprint $table) {
            if (Schema::hasColumn('region', 'menu_status')) $table->dropColumn('menu_status');
            if (Schema::hasColumn('region', 'menu_name')) $table->dropColumn('menu_name');
        });
    }
}
