<?php

namespace App\Models\Weather;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\ParishModel;
use App\Models\Settings\Language;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\WeatherOutboxRelationship;
use App\Services\Weather\TomorrowApi;
use DateTime;
use Illuminate\Support\Facades\Schema;

class WeatherOutbox extends BaseModel
{
    use Uuid, WeatherOutboxRelationship;

    protected $table = 'weather_outbox';

    protected $fillable = [
        'subscription_id',
        'farmer_id',
        'recipient',
        'message',
        'status',
        'statuses',
        'failure_reason',
        'processsed_at',
        'sent_at',
        'failed_at',
        'sent_via'
    ];


    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (WeatherOutbox $model) {
            $model->id = $model->generateUuid();
        });
    }

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


    public static function make_sms($subscription)
    {
        $weatherApi = new TomorrowApi;
        $weatherApi->set_URL(config('tomorrow.host'));
        $weatherApi->set_key(config('tomorrow.key'));
        $result = null;
        $resp['status'] = 'failed';
        $resp['message'] = 'Failed to send message';
        try {
            $result = $weatherApi->forecast($subscription->parish->lat, $subscription->parish->lng, 'daily');
        } catch (\Exception $e) {
            $resp['message'] = $e->getMessage();
            return $resp;
        }
        if ($result == null) {
            $resp['message'] = 'Failed to get weather data';
            return $resp;
        }

        $sms = $codeDescription = null;
        if ($result != null && isset($result->forecast)) {

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
                    $code = self::translations($max_code, $languageId);
                    $codeDescription = $code->description ?? null;
                } else {
                    $minCode = self::translations($min_code, $languageId);
                    $maxCode = self::translations($max_code, $languageId);
                    $codeDescription = isset($minCode->description) ? $minCode->description . '/' . $maxCode->description : null;
                }
            } else {
                $resp['message'] = 'Failed to fetch weather data.';
                return $resp;
            }
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

                    if ($subscription->update(['outbox_generation_status' => true])) {
                    } else {
                    }
                } else {
                }
            }
            if (!isset($sms)) {
                $resp['message'] = 'Failed to get weather data';
                return $resp;
            }
            if ($sms == null || strlen(trim($sms)) < 4) {
                $resp['message'] = 'Failed to get weather data';
                return $resp;
            }
            $resp['status'] = 'success';
            $resp['message'] = $sms;
            return $resp;
        } else {
            $resp['message'] = 'Failed to get weather data';
            return $resp;
        }



        $sms = 'Simple weather update';
        $outbox_sms = [
            'subscription_id' => $subscription->id,
            // 'farmer_id'       => $subscription->farmer_id,
            'recipient'       => $subscription->phone,
            'message'         => $sms,
            'status'          => 'PENDING'
        ];
        if (WeatherOutbox::create($outbox_sms)) {
            die("success");
        } else {
            die("failed");
        }
    }

    public static function translations($code, $languageId)
    {
        return WeatherCondition::where('digit', $code)->where('language_id', $languageId)->first();
    }
}
