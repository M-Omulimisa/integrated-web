<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentmentThingsToMarketSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            $table
                ->text("MNOTransactionReferenceId")
                ->nullable();
            $table->text('payment_reference_id')->nullable();
            $table->text("TransactionStatus")->nullable();
            $table->text("TransactionAmount")->nullable();
            $table->text("TransactionCurrencyCode")->nullable();
            $table->text("TransactionReference")->nullable();
            $table->text("TransactionInitiationDate")->nullable();
            $table->text("TransactionCompletionDate")->nullable();
            $table->string("is_paid")->default("NOT PAID")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            //
        });
    }
}
