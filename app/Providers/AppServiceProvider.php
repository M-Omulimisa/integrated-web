<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;
use Log;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Logger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // not run the Passport migrations that are in our vendor/ directory
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(125);

        /**
     * @param string      $webhookUrl             Slack Webhook URL
     * @param string|null $channel                Slack channel (encoded ID or name)
     * @param string|null $username               Name of a bot
     * @param bool        $useAttachment          Whether the message should be added to Slack as attachment (plain text otherwise)
     * @param string|null $iconEmoji              The emoji name to use (or null)
     * @param bool        $useShortAttachment     Whether the the context/extra messages added to Slack as attachments are in a short style
     * @param bool        $includeContextAndExtra Whether the attachment should include context and extra data
     * @param string|int  $level                  The minimum logging level at which this handler will be triggered
     * @param bool        $bubble                 Whether the messages that are handled can bubble up the stack or not
     * @param array       $excludeFields          Dot separated list of fields to exclude from slack message. E.g. ['context.field1', 'extra.field2']
     */

        if (env('APP_ENV') == 'production') {
            // Send errors to slack channel
            $monolog = Log::getLogger();
            $slackHandler = new SlackWebhookHandler(
                env('LOG_SLACK_WEBHOOK_URL'), 
                '#m-omulimisa-unified-logs',
                'Alerts', 
                false, 
                'warning', 
                true, 
                true, 
                Logger::ERROR,
                true,
                []
            );
            $monolog->pushHandler($slackHandler);
        }
    }
}
