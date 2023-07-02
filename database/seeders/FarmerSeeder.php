<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Settings\Country;
use App\Models\Settings\Language;
use App\Models\Settings\Location;
use App\Models\Farmers\Farmer;

class FarmerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::whereName(Country::CTRY_UG)->first();
        $language = Language::whereName('Luganda')->first();
        $location = Location::whereName('Kazo Parish')->first();

          $data = [
                "country_id" => $country->id,
                "organisation_id" => null,
                "language_id" => $language->id,
                "first_name" => "Najja",
                "last_name" => "Najib",
                "gender" => "Male",
                "year_of_birth" => "1990",
                "national_id_number" => "00000",
                "education_level" => "None",
                "phone" => "702794162",
                "is_your_phone" => "1",
                "is_mm_registered" => "1",
                "farming_scale" => "Small",
                // "activities" => array:2 [▼
                //   0 => "a2e7286d-5d73-4819-8b85-fabf4fd508b3"
                //   1 => "e101972b-9bb7-4af6-99b8-f2fc4e05e2b4"
                // ]
                // "practices" => array:2 [▼
                //   0 => "3751d168-be24-43ba-a303-b575a4be8b1e"
                //   1 => "9d7c1334-cd3a-49c2-b96c-586d07508b0f"
                // ]
                // "challenges" => array:2 [▼
                //   0 => "f65bb0d6-98c0-4b7c-883c-8da687e4d4d5"
                //   1 => "c983d729-837e-48bc-b0f1-49d2127dbc15"
                // ]
                "other_economic_activity" => "None",
                "land_holding_in_acres" => "0",
                "land_under_farming_in_acres" => "0",
                "location_id" => $location->id,
                "longitude" => "32",
                "latitude" => "0.5",
                "status" => "Active",
                "email" => null,
                "password" => "5588",
          ];

          $farmer = Farmer::create($data);
    }
}
