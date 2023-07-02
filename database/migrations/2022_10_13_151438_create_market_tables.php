<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('market_output_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('farmgain_id')->nullable();
            $table->uuid('enterprise_id');
            $table->uuid('unit_id');
            $table->timestamps();

            $table->foreign('enterprise_id')->references('id')->on('enterprises')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('unit_id')->references('id')->on('measure_units')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('markets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('location_id');
            $table->string('latitude')->nullable()->default(0);
            $table->string('longitude')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('market_commodity_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('market_id');
            $table->enum('type', ['Wholesale', 'Retail']);
            $table->uuid('output_product_id');
            $table->double('price',13,2)->default(0.00);
            $table->uuid('currency_id');
            $table->date('price_date')->nullable();
            $table->timestamps();

            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('output_product_id')->references('id')->on('market_output_products')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('market_packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('market_package_enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('package_id');
            $table->uuid('enterprise_id');
            $table->timestamps();

            $table->foreign('package_id')->references('id')->on('market_packages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('enterprise_id')->references('id')->on('enterprises')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('market_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_id')->nullable();
            $table->uuid('language_id')->nullable();
            $table->uuid('location_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('frequency');
            $table->integer('period_paid');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->boolean('status')->default(false);
            $table->uuid('user_id')->nullable();
            $table->boolean('outbox_generation_status')->default(false);
            $table->boolean('outbox_reset_status')->default(false);
            $table->date('outbox_last_date')->nullable();
            $table->boolean('seen_by_admin')->default(false);
            $table->timestamp('trial_expiry_sms_sent_at')->nullable();
            $table->text('trial_expiry_sms_failure_reason')->nullable();
            $table->uuid('renewal_id')->nullable();
            $table->uuid('organisation_id')->nullable();
            $table->uuid('package_id');
            $table->timestamps();

            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('package_id')->references('id')->on('market_packages')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('market_outbox', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subscription_id');
            $table->uuid('farmer_id')->nullable();
            $table->string('recipient');
            $table->text('message');
            $table->string('status');
            $table->string('failure_reason')->nullable();
            $table->timestamp('processsed_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->string('statuses')->nullable();
            $table->enum('sent_via', ['sms', 'app'])->default('sms');
            $table->timestamps();

            $table->foreign('subscription_id')->references('id')->on('market_subscriptions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('market_keyword_initiated_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('keyword_id')->unique();
            $table->string('sms',160);
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('market_keyword_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('keyword_id')->unique();
            $table->longText('sms',160);
            $table->string('status')->nullable();
            $table->integer('subscribers')->default(0);
            $table->integer('generated_sms')->default(0);
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
        Schema::dropIfExists('market_keyword_prices');
        Schema::dropIfExists('market_keyword_initiated_prices');
        Schema::dropIfExists('market_commodity_prices');
        Schema::dropIfExists('market_output_products');
        Schema::dropIfExists('market_retail_prices');
        Schema::dropIfExists('market_wholesale_prices');
        Schema::dropIfExists('market_package_enterprises');
        Schema::dropIfExists('market_outbox');
        Schema::dropIfExists('market_subscriptions');
        Schema::dropIfExists('market_packages');
        Schema::dropIfExists('markets');
    }
}


