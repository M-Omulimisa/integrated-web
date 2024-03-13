<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentThingsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text("TransactionStatus")->nullable();
            $table->text("TransactionAmount")->nullable();
            $table->text("TransactionCurrencyCode")->nullable();
            $table->text("TransactionReference")->nullable();
            $table->text("TransactionInitiationDate")->nullable();
            $table->text("TransactionCompletionDate")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
