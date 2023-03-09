<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('weather_subscription_id')->nullable();
            $table->uuid('market_subscription_id')->nullable();
            $table->uuid('insurance_subscription_id')->nullable();
            $table->string('method');
            $table->string('provider');
            $table->string('account');
            $table->string('reference_id')->nullable();
            $table->string('reference')->nullable();
            $table->string('narrative')->nullable();
            $table->string('payment_api')->nullable();
            $table->string('sms_api')->nullable();
            $table->double('amount', 15,2);
            $table->string('status')->default('PENDING');
            $table->text('details')->nullable();
            $table->text('error_message')->nullable();
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
        Schema::dropIfExists('subscription_payments');
    }
}
