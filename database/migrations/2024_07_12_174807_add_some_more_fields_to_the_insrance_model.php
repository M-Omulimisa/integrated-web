<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeMoreFieldsToTheInsranceModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_insurance_requests', function (Blueprint $table) {
            $table->boolean('approved')->default(false);
            $table->boolean('agent_sale')->default(false)->change();
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
            $table->dropColumn(["approved"]);
        });
    }
}
