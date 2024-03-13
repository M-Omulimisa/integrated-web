<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\Country;
use App\Models\Settings\Language;
use App\Models\Weather\WeatherSmsTranslation;

use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class WeatherSmsTranslationSeed extends Seeder
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

        $translations = [
            ['Luganda', 'Weather:Entebereza yobudde,Temperature:Ebugumu,Rain Chance:Enkuba'],
        ];

        foreach ($translations as $translation) {

            $language = Language::whereCountryId(Country::whereName(Country::CTRY_UG)->first()->id)->whereName($translation[0])->first();

            if ($language) {
                WeatherSmsTranslation::create([
                    'language_id' => $language->id,
                    'translation' => $translation[1],
                ]);
            }

        }

        $this->enableForeignKeys();
    }
}
