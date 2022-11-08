<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Settings\Country;
use App\Models\Settings\Language;
use App\Models\Settings\Enterprise;
use App\Models\Settings\EnterpriseType;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Settings\AgroProduct;
use App\Models\Settings\Keyword;
use App\Models\Settings\MeasureUnit;
use App\Models\Settings\AgentCommissionRanking;

use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class SettingsASeeder extends Seeder
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

        $country = Country::whereName(Country::CTRY_UG)->first();

        Language::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Luganda',
                'country_id'    => $country->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Acholi',
                'country_id'    => $country->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Runyankole',
                'country_id'    => $country->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);


        AgentCommissionRanking::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Beginner',
                'order'         => 1,
                'country_id'    => $country->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Premium',
                'order'         => 2,
                'country_id'    => $country->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Gold',
                'order'         => 3,
                'country_id'    => $country->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        MeasureUnit::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Kilogram',
                'slug'          => 'kg',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Litre',
                'slug'          => 'ltr',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Gram',
                'slug'          => 'g',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Acre',
                'slug'          => 'A',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Each',
                'slug'          => '@',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Packet',
                'slug'          => 'pkt',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Piece',
                'slug'          => 'pc',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $unit = MeasureUnit::whereName('Kilogram')->first();
        Enterprise::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Beans',
                'unit_id'       => $unit->id,
                'category'      => 'Crop',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Maize',
                'unit_id'       => $unit->id,
                'category'      => 'Crop',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'SoyaBean',
                'unit_id'       => $unit->id,
                'category'      => 'Crop',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Sunflower',
                'unit_id'       => $unit->id,
                'category'      => 'Crop',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Simsim',
                'unit_id'       => $unit->id,
                'category'      => 'Crop',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $enterprise = Enterprise::whereName('Maize')->first();
        $enterprise1 = Enterprise::whereName('SoyaBean')->first();

        EnterpriseVariety::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Longe 10H',
                'enterprise_id' => $enterprise->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'DK 9089',
                'enterprise_id' => $enterprise->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Bazooka',
                'enterprise_id' => $enterprise->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Maksoy 1N',
                'enterprise_id' => $enterprise1->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Maksoy 2N',
                'enterprise_id' => $enterprise1->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Maksoy 3N',
                'enterprise_id' => $enterprise1->id,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $variety = EnterpriseVariety::whereName('Maksoy 1N')->first();
        EnterpriseType::create([
                'name'          => 'Maksoy 1N Foundation Seed',
                'enterprise_variety_id' => $variety->id,
            ]);
        $variety = EnterpriseVariety::whereName('Maksoy 2N')->first();
        EnterpriseType::create([
                'name'          => 'Maksoy 2N Foundation Seed',
                'enterprise_variety_id' => $variety->id,
            ]);
        $variety = EnterpriseVariety::whereName('Maksoy 3N')->first();
        EnterpriseType::create([
                'name'          => 'Maksoy 3N Foundation Seed',
                'enterprise_variety_id' => $variety->id,
            ]);

        $unit = MeasureUnit::whereName('Litre')->first();
        $unit2 = MeasureUnit::whereName('Each')->first();
        
        AgroProduct::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Forcrop 4-16-28',
                'unit_id'       => $unit->id,
                'category'      => 'Fertilizer',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Forcrop B-Mo',
                'unit_id'       => $unit->id,
                'category'      => 'Fertilizer',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'PICS bags',
                'unit_id'       => $unit2->id,
                'category'      => 'Post Harvest Material',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Sprayer Pump',
                'unit_id'       => $unit2->id,
                'category'      => 'Post Harvest Material',
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $language = Language::whereName('Luganda')->first();

        Keyword::insert([
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Manya',
                'language_id'   => $language->id,
                'category'      => 'Questions',
                'shortcode'     => 8228,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Mulimisa',
                'language_id'   => $language->id,
                'category'      => 'Registration',
                'shortcode'     => 8228,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
            [
                'id'            => $this->generateUuid(),
                'name'          => 'Miwendo',
                'language_id'   => $language->id,
                'category'      => 'Market Pricing',
                'shortcode'     => 8228,
                'created_at'    => $now, 
                'updated_at'    => $now
            ],
        ]);

        $this->enableForeignKeys();
    }
}
