<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Settings\Location;
use App\Models\Settings\Country;

use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class LocationSeeder extends Seeder
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
        $now        = \Carbon\Carbon::now()->subMonths(6);

        $country1 = Country::whereName(Country::CTRY_UG)->first();

        $location1 = Location::create([
            'country_id' => $country1->id,
            'name' => 'Central Region',
        ]);

        $location1_1 = Location::create([
            'country_id' => $country1->id,
            'name' => 'Kampala District',
            'parent_id' => $location1->id
        ]);

        $location1_1_1 = Location::create([
            'country_id' => $country1->id,
            'name' => 'Kawempe Division',
            'parent_id' => $location1_1->id
        ]);

        $location1_1_1_1 = Location::create([
            'country_id' => $country1->id,
            'name' => 'Kazo Parish',
            'parent_id' => $location1_1_1->id
        ]);

        $location1_2 = Location::create([
            'country_id' => $country1->id,
            'name' => 'Masaka District',
            'parent_id' => $location1->id
        ]);

        $this->enableForeignKeys();
    }
}
