<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Settings\Country;
use App\Models\Settings\Currency;
use App\Models\Settings\CountryCurrency;
use App\Models\Settings\CountryAdminUnit;

use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class CountrySeeder extends Seeder
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

        Country::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => Country::CTRY_UG,
                'iso_code'      => 'UG', 
                'nationality'   => 'Ugandan',
                'dialing_code'  => '256',
                'length'        => 12,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Country::CTRY_GH,
                'iso_code'      => 'UG', 
                'nationality'   => 'Ghanian', 
                'dialing_code'  => '233',
                'length'        => 12,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => Country::CTRY_ZM,
                'iso_code'      => 'ZM', 
                'nationality'   => 'Zambian',
                'dialing_code'  => '260',
                'length'        => 12, 
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $currency = Currency::create([
            'name'          => 'Ugandan Shillings',
            'code'          => 'UGX',
            'created_at'    => $now, 
            'updated_at'    => $now
        ]);

        $country = Country::whereName(Country::CTRY_UG)->first();

        CountryCurrency::create([
            'country_id'    => $country->id,
            'currency_id'   => $currency->id,
            'created_at'    => $now, 
            'updated_at'    => $now
        ]);

        CountryAdminUnit::insert([
            [
                'id'            => $this->generateUuid(),
                'country_id'    => $country->id,
                'name'          => 'Region',
                'order'         => 1,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'country_id'    => $country->id,
                'name'          => 'District',
                'order'         => 2,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'country_id'    => $country->id,
                'name'          => 'Subcounty',
                'order'         => 3,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'country_id'    => $country->id,
                'name'          => 'Parish',
                'order'         => 4,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $this->enableForeignKeys();
    }
}
