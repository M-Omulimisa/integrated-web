<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Log;
use DateTime;
use Carbon\Carbon;
use App\Models\ParishModel;
use App\Models\Weather\WeatherOutbox;
use App\Models\Weather\WeatherSubscription;
use Illuminate\Support\Facades\Schema;
use App\Models\Weather\WeatherCondition;
use App\Services\Weather\TomorrowApi;
use App\Models\Settings\Language;
use App\Models\Weather\WeatherSmsTranslation;

class GenerateWeatherSmsOutbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:generate-weather-sms-outbox {start_time} {start_meridiem} {end_time} {end_meridiem}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a weather information sms';

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
        $start_time     = $this->argument('start_time');
        $start_meridiem = $this->argument('start_meridiem');
        $end_time       = $this->argument('end_time');
        $end_meridiem   = $this->argument('end_meridiem');

        if ($this->debug) Log::info(['Command' => 'Generating weather info']);

        if (time() > strtotime($start_time . ' ' . $start_meridiem) && time() < strtotime($end_time . ' ' . $end_meridiem)) {

            if ($this->debug) Log::info(['Command' => 'Past 12am']);

            try {

                $subscriptions = WeatherSubscription::where('end_date', '>', Carbon::now())
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
                    ->whereIn('parish_id', function ($query) {
                        $query->select('id')
                            ->whereRaw('LENGTH(lat) > 0')
                            ->whereRaw('LENGTH(lng) > 0')
                            ->from(with(new ParishModel)->getTable());
                    })
                    ->limit(150)->get();

                if (count($subscriptions) > 0) {

                    $recordsPerSecond = 3;
                    $delayInSeconds = 1 / $recordsPerSecond;

                    if ($this->debug) logger(count($subscriptions));
                    if ($this->debug) echo count($subscriptions);
                    if ($this->debug) logger([$subscriptions->pluck('id')->toArray()]);

                    WeatherSubscription::whereIn('id', $subscriptions->pluck('id')->toArray())->update(['outbox_generation_status' => 2]);

                    foreach ($subscriptions as $subscription) {

                        $subscription->update(['outbox_generation_status' => 3]);

                        // logger('Start('.$subscription->id.'): '. Carbon::now());

                        $weatherApi = new TomorrowApi;
                        $weatherApi->set_URL(config('tomorrow.host'));
                        $weatherApi->set_key(config('tomorrow.key'));

                        if ($this->debug) logger('Lat: ' . $subscription->parish->lat);
                        if ($this->debug) logger('Lng: ' . $subscription->parish->lng);

                        $result = $weatherApi->forecast($subscription->parish->lat, $subscription->parish->lng, 'daily');

                        $sms = $codeDescription = null;

                        if (isset($result->forecast)) {

                            for ($i = 0; $i < count($result->forecast->daily); $i++) {
                                $dateTime = new DateTime($result->forecast->daily[$i]->time);
                                $date = $dateTime->format('d-m-Y');

                                if ($date == date('d-m-Y')) {
                                    $weather = $result->forecast->daily[$i]->values;
                                    break;
                                }
                            }

                            if (isset($weather) && Schema::hasTable('weather_conditions')) {

                                $avg_temp = $weather->temperatureAvg;
                                $max_temp = $weather->temperatureMax;
                                $min_temp = $weather->temperatureMin;

                                $avg_rain_chance = $weather->precipitationProbabilityAvg;
                                $max_rain_chance = $weather->precipitationProbabilityMax;
                                $min_rain_chance = $weather->precipitationProbabilityMin;

                                $max_code = $weather->weatherCodeMax;
                                $min_code = $weather->weatherCodeMin;

                                $languageId = $subscription->language_id ?? Language::whereName('English')->first()->id;
                                if ($max_code == $min_code) {
                                    $code = $this->translations($max_code, $languageId);
                                    $codeDescription = $code->description ?? null;
                                } else {
                                    $minCode = $this->translations($min_code, $languageId);
                                    $maxCode = $this->translations($max_code, $languageId);
                                    $codeDescription = isset($minCode->description) ? $minCode->description . '/' . $maxCode->description : null;
                                }
                            } else {
                                if ($this->debug) logger('Missing table weather conditions');
                            }

                            // logger('End('.$subscription->id.'): '. Carbon::now());

                            $codeDescription = isset($codeDescription) ? $codeDescription . '. ' : '';

                            $sms = str_replace('  ', ' ', $date . ' Weather: ' . $codeDescription . 'Temperature (' . $min_temp . 'C <> ' . $max_temp . 'C) Rain Chance (' . $min_rain_chance . '% <> ' . $max_rain_chance . '%). M-Omulimisa');

                            if ($sms_translation = WeatherSmsTranslation::whereLanguageId($languageId)->first()) {
                                if (strpos($sms_translation->translation, ',') !== false) {
                                    $_translations = explode(",", $sms_translation->translation);
                                    for ($i = 0; $i < count($_translations); $i++) {
                                        if (strpos($_translations[$i], ':') !== false) {
                                            $_translation = explode(":", $_translations[$i]);
                                            $sms = str_replace($_translation[0], $_translation[1], $sms);
                                        }
                                    }
                                } else {
                                    if (strpos($sms_translation->translation, ':') !== false) {
                                        $_translation = explode(":", $sms_translation->translation);
                                        $sms = str_replace($_translation[0], $_translation[1], $sms);
                                    }
                                }
                            }

                            if ($this->debug) logger($sms);
                            if ($this->debug) logger(strlen($sms));

                            if (isset($subscription)) {
                                $parish = ParishModel::find($subscription->parish_id);
                                if ($parish != null) {
                                    $sms = $parish->name_text . ': ' . $sms;
                                }
                            }

                            if ($sms !== '' && strlen($sms) > 10) {
                                $outbox_sms = [
                                    'subscription_id' => $subscription->id,
                                    // 'farmer_id'       => $subscription->farmer_id,
                                    'recipient'       => $subscription->phone,
                                    'message'         => $sms,
                                    'status'          => 'PENDING'
                                ];
                                if (WeatherOutbox::create($outbox_sms)) {
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
                        } else {
                            if ($this->debug) logger($result->error_message);
                        }

                        sleep($delayInSeconds);
                    }
                }
            } catch (\Throwable $r) {
                Log::error(['GenerateWeatherSmsOutbox' => $r->getMessage()]);
            }
        }
    }

    public function translations($code, $languageId)
    {
        return WeatherCondition::where('digit', $code)->where('language_id', $languageId)->first();
    }
}
