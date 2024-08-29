<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->default('User')->change();
            $table->string('farmer_market_user_type')->default('buyer');
            $table->string('gender')->default('male');
            $table->date('date_of_birth')->nullable();
            $table->string('user_district')->default('Kampala');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->change();
            $table->dropColumn('farmer_market_user_type');
            $table->dropColumn('gender');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('user_district');
        });
    }
}