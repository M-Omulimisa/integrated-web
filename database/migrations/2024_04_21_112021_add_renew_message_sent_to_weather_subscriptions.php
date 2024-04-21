<?php

use App\Models\Utils;
use App\Models\Weather\WeatherSubscription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRenewMessageSentToWeatherSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Utils::create_column(
            (new WeatherSubscription())->getTable(),
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
        Schema::table('weather_subscriptions', function (Blueprint $table) {
            //
        });
    }
}
