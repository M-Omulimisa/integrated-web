<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\Country;
use App\Models\Settings\Language;
use App\Models\Weather\WeatherCondition;

use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class WeatherConditionSeeder extends Seeder
{
    use Uuid, DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->disableForeignKeys();
        $now = \Carbon\Carbon::now();

        $conditions = [
            ['1000', 'English', 'Clear, Sunny'],
            ['1100', 'English', 'Mostly Clear'],
            ['1101', 'English', 'Partly Cloudy'],
            ['1102', 'English', 'Mostly Cloudy'],
            ['1001', 'English', 'Cloudy'],
            ['2000', 'English', 'Fog'],
            ['2100', 'English', 'Light Fog'],
            ['4000', 'English', 'Drizzle'],
            ['4001', 'English', 'Rain'],
            ['4200', 'English', 'Light Rain'],
            ['4201', 'English', 'Heavy Rain'],
            ['8000', 'English', 'Thunderstorm'],
            ['1103', 'English', 'Partly Cloudy and Mostly Clear'],
            ['4204', 'English', 'Partly Cloudy and Drizzle'],
            ['4203', 'English', 'Mostly Clear and Drizzle'],
            ['4205', 'English', 'Mostly Cloudy and Drizzle'],
            ['4213', 'English', 'Mostly Clear and Light Rain'],
            ['4214', 'English', 'Partly Cloudy and Light Rain'],
            ['4215', 'English', 'Mostly Cloudy and Light Rain'],
            ['4209', 'English', 'Mostly Clear and Rain'],
            ['4208', 'English', 'Partly Cloudy and Rain'],
            ['4210', 'English', 'Mostly Cloudy and Rain'],
            ['4001', 'English', 'Rain'],
            ['4211', 'English', 'Mostly Clear and Heavy Rain'],
            ['4202', 'English', 'Partly Cloudy and Heavy Rain'],
            ['4212', 'English', 'Mostly Cloudy and Heavy Rain'],
            ['10000', 'English', 'Clear, Sunny'],
            ['11000', 'English', 'Mostly Clear'],
            ['11010', 'English', 'Partly Cloudy'],
            ['11020', 'English', 'Mostly Cloudy'],
            ['10010', 'English', 'Cloudy'],
            ['11030', 'English', 'Partly Cloudy and Mostly Clear'],
            ['42040', 'English', 'Partly Cloudy and Drizzle'],
            ['42030', 'English', 'Mostly Clear and Drizzle'],
            ['42050', 'English', 'Mostly Cloudy and Drizzle'],
            ['40000', 'English', 'Drizzle'],
            ['42000', 'English', 'Light Rain'],
            ['42130', 'English', 'Mostly Clear and Light Rain'],
            ['42140', 'English', 'Partly Cloudy and Light Rain'],
            ['42150', 'English', 'Mostly Cloudy and Light Rain'],
            ['42090', 'English', 'Mostly Clear and Rain'],
            ['42080', 'English', 'Partly Cloudy and Rain'],
            ['42100', 'English', 'Mostly Cloudy and Rain'],
            ['40010', 'English', 'Rain'],
            ['42110', 'English', 'Mostly Clear and Heavy Rain'],
            ['42020', 'English', 'Partly Cloudy and Heavy Rain'],
            ['42120', 'English', 'Mostly Cloudy and Heavy Rain'],
            ['42010', 'English', 'Heavy Rain'],
            ['10001', 'English', 'Clear'],
            ['11001', 'English', 'Mostly Clear'],
            ['11011', 'English', 'Partly Cloudy'],
            ['11021', 'English', 'Mostly Cloudy'],
            ['10011', 'English', 'Cloudy'],
            ['11031', 'English', 'Partly Cloudy and Mostly Clear'],
            ['21001', 'English', 'Light Fog'],
            ['42041', 'English', 'Partly Cloudy and Drizzle'],
            ['42031', 'English', 'Mostly Clear and Drizzle'],
            ['42051', 'English', 'Mostly Cloudy and Drizzle'],
            ['40001', 'English', 'Drizzle'],
            ['42001', 'English', 'Light Rain'],
            ['42131', 'English', 'Mostly Clear and Light Rain'],
            ['42141', 'English', 'Partly Cloudy and Light Rain'],
            ['42151', 'English', 'Mostly Cloudy and Light Rain'],
            ['42091', 'English', 'Mostly Clear and Rain'],
            ['42081', 'English', 'Partly Cloudy and Rain'],
            ['42101', 'English', 'Mostly Cloudy and Rain'],
            ['40011', 'English', 'Rain'],
            ['42111', 'English', 'Mostly Clear and Heavy Rain'],
            ['42021', 'English', 'Partly Cloudy and Heavy Rain'],
            ['42121', 'English', 'Mostly Cloudy and Heavy Rain'],
            ['42011', 'English', 'Heavy Rain'],
        ];

        foreach ($conditions as $condition) {

            $language = Language::whereCountryId(Country::whereName(Country::CTRY_UG)->first()->id)->whereName($condition[1])->first();

            if (!$language) {
                $language = Language::create([
                    'country_id' => Country::whereName(Country::CTRY_UG)->first()->id,
                    'name'       => $condition[1],
                ]);
            }

            if ($language && ! WeatherCondition::whereDigit($condition[0])->whereLanguageId($language->id)->first()) {
                WeatherCondition::create([
                    'digit' => $condition[0],
                    'language_id' => $language->id,
                    'description' => $condition[2],
                ]);
            }

        }

        $this->enableForeignKeys();
    }
}
