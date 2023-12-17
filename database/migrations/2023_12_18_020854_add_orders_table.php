<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->text("user")->nullable();
            $table->text("order_state")->nullable();
            $table->text("amount")->nullable();
            $table->text("date_created")->nullable();
            $table->text("payment_confirmation")->nullable();
            $table->text("date_updated")->nullable();
            $table->text("mail")->nullable();
            $table->text("delivery_district")->nullable();
            $table->text("temporary_id")->nullable();
            $table->text("description")->nullable();
            $table->text("customer_name")->nullable();
            $table->text("customer_phone_number_1")->nullable();
            $table->text("customer_phone_number_2")->nullable();
            $table->text("customer_address")->nullable();
            $table->text("order_total")->nullable();
            $table->text("order_details")->nullable();
            $table->text("stripe_id")->nullable();
            $table->text("stripe_url")->nullable();
            $table->text("stripe_paid")->nullable();
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
