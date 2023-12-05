<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentIdToSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('market_subscriptions', 'phone')) $table->string('phone')->nullable()->after('last_name');
            if (!Schema::hasColumn('market_subscriptions', 'payment_id')) $table->uuid('payment_id')->nullable()->before('updated_at');
        });

        Schema::table('weather_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('weather_subscriptions', 'phone')) $table->string('phone')->nullable()->after('last_name');
            if (!Schema::hasColumn('weather_subscriptions', 'payment_id')) $table->uuid('payment_id')->nullable()->before('updated_at');
        });

        Schema::table('insurance_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('insurance_subscriptions', 'payment_id')) $table->uuid('payment_id')->nullable()->before('updated_at');
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_payments', 'payment_id')) $table->dropColumn('market_subscription_id');
            if (Schema::hasColumn('subscription_payments', 'payment_id')) $table->dropColumn('weather_subscription_id');
            if (Schema::hasColumn('subscription_payments', 'payment_id')) $table->dropColumn('insurance_subscription_id');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}
