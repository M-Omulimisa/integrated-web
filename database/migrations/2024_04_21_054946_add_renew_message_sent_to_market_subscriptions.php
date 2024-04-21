<?php

use App\Models\Market\MarketSubscription;
use App\Models\Utils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRenewMessageSentToMarketSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Utils::create_column(
            (new MarketSubscription())->getTable(),
            [
                [
                    'name' => 'renew_message_sent',
                    'type' => 'String',
                    'default' => 'No',
                ],
                [
                    'name' => 'renew_message_sent_at',
                    'type' => 'DateTime',
                ],
                [
                    'name' => 'renew_message_sent_details',
                    'type' => 'Text',
                ],
            ]
        );
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
