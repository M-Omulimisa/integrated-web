<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Log;
use DateTime;
use Carbon\Carbon;
use App\Models\ParishModel;
use App\Models\Market\MarketOutbox;
use App\Models\Market\MarketSubscription;
use App\Models\Market\MarketPackageMessage;
use App\Models\Market\MarketPackageRegion;
use Illuminate\Support\Facades\Schema;
use App\Models\Settings\Language;

class GenerateMarketSmsOutbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:generate-market-sms-outbox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a market information sms';

    /**
     * Enables debug logging
     *
     * @var boolean
     */
    private $debug = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return;
        if ($this->debug) Log::info(['Command' => 'Generating market info']);

        $week = $this->getWeekStartAndEndDates(date('Y-m-d'));

        MarketSubscription::where('end_date', '>', Carbon::now())
            ->where(function ($query) {
                $query->whereOutboxGenerationStatus(false)
                    ->whereOutboxResetStatus(false)
                    ->whereNull('outbox_last_date')
                    ->orWhere(function ($query) {
                        $query->whereOutboxGenerationStatus(false)
                            ->whereOutboxResetStatus(true)
                            ->whereDate('outbox_last_date', '!=', Carbon::today());
                    });
            })
            ->whereIn('language_id', function ($query) {
                $query->select('language_id')
                    ->from(with(new MarketPackageMessage)->getTable());
            })

            ->whereNotIn('id', function ($query) use ($week) {
                $query->select('subscription_id')
                    ->where('sent_at', '>=', $week['start_date'])
                    ->where('sent_at', '<=', $week['end_date'])
                    ->from(with(new MarketOutbox)->getTable());
            })
            ->chunk(500, function ($subscriptions) {

                if ($this->debug) logger(count($subscriptions));
                if ($this->debug) echo count($subscriptions);
                if ($this->debug) logger([$subscriptions->pluck('id')->toArray()]);

                MarketSubscription::whereIn('id', $subscriptions->pluck('id')->toArray())->update(['outbox_generation_status' => 2]);

                Log::info($subscriptions);
                foreach ($subscriptions as $subscription) {

                    Log::info($subscription);

                    $subscription->update(['outbox_generation_status' => 3]);

                    $pkgMessage = MarketPackageMessage::where('package_id', $subscription->package_id)->where('language_id', $subscription->language_id)->first();

                    Log::info($pkgMessage);

                    $sms = null;

                    if ($pkgMessage) {

                        if ($this->debug) echo $pkgMessage->message;

                        // if last sent is greater than last update date of the msg
                        // dont send, msg not yet updates
                        if (!is_null($subscription->outbox_last_date) && $subscription->outbox_last_date > $pkgMessage->updated_at) {
                            Log::error(['GenerateMarketSmsOutbox' => 'Language: ' . $subscription->language->name . ' has no new message to generate']);
                            $subscription->update(['outbox_generation_status' => false]);
                        } else {

                            // date('Y-m-d').' Market: '.
                            // .' M-Omulimisa'
                            $sms = $pkgMessage->message;
                            Log::info($sms);

                            if ($this->debug) logger($sms);
                            if ($this->debug) logger(strlen($sms));

                            if ($sms !== '' && strlen($sms) > 10) {
                                $outbox_sms = [
                                    'subscription_id' => $subscription->id,
                                    // 'farmer_id'       => $subscription->farmer_id,
                                    'recipient'       => $subscription->phone,
                                    'message'         => $sms,
                                    'status'          => 'PENDING'
                                ];
                                if (MarketOutbox::create($outbox_sms)) {
                                    if ($this->debug) logger('Outbox sms created');

                                    if ($subscription->update(['outbox_generation_status' => true])) {
                                        if ($this->debug) logger('Outbox sms updated');
                                    } else {
                                        if ($this->debug) logger('Outbox sms for ID' . $subscription->id . ' not updated');
                                    }
                                } else {
                                    if ($this->debug) logger('Outbox sms for ID' . $subscription->id . ' not created');
                                }
                            } // endif sms

                        }
                    } else {
                        Log::error(['GenerateMarketSmsOutbox' => 'Language: ' . $subscription->language->name . ' has no package']);
                    }
                }
            });



        try {
        } catch (\Throwable $r) {
            Log::error(['GenerateMarketSmsOutbox' => $r->getMessage()]);
        }
    }

    public function getWeekStartAndEndDates($inputDate)
    {
        // Create a DateTime object from the input date
        $dateTime = new DateTime($inputDate);

        // Get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
        $dayOfWeek = $dateTime->format('w');

        // Calculate the number of days to subtract to get to Monday (start of the week)
        $daysToSubtract = ($dayOfWeek == 0) ? 6 : ($dayOfWeek - 1);

        // Subtract the days to get to Monday
        $startOfWeek = clone $dateTime;
        $startOfWeek->modify("-{$daysToSubtract} days");

        // Add the remaining days to get to Sunday (end of the week)
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify("+6 days");

        // Return the start and end dates as an array
        return [
            'start_date' => $startOfWeek->format('Y-m-d'),
            'end_date' => $endOfWeek->format('Y-m-d'),
        ];
    }
}
