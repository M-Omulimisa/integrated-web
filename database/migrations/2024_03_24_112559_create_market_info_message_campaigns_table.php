<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketInfoMessageCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_info_message_campaigns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('packages')->nullable();
            $table->string('send_now')->nullable()->default('No');
            $table->string('confirm_send')->nullable()->default('No');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_info_message_campaigns');
    }
}
