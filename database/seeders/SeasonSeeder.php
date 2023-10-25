<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Settings\Season;
use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Settings\Country;

class SeasonSeeder extends Seeder
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

        $country = Country::where('dialing_code', '256')->first();

        Season::insert([
            [
                'id'            => $this->generateUuid(),
                'country_id'    => $country->id,
                'name'          => 'First 2020',
                'start_date'    => '2020-03-01',
                'end_date'      => '2020-07-31', 
                'created_at'    => $now,
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'country_id'    => $country->id,
                'name'          => 'Second 2020',
                'start_date'    => '2020-09-01',
                'end_date'      => '2020-12-31', 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $this->enableForeignKeys();
    }
}
