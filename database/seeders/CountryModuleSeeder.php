<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Settings\SystemModule;
use App\Models\Settings\Country;
use App\Models\Settings\CountryModule;

use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class CountryModuleSeeder extends Seeder
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
        SystemModule::query()->truncate();

        $modules = [
            $this->rows('Farmers'),
            $this->rows('Extension Services'),
            $this->rows('Village Agents'), 
            $this->rows('Organisations'),
            $this->rows('Insurance'),
            $this->rows('Questions'), 
            $this->rows('Outbreaks'),
            $this->rows('Input Loans'), 
            $this->rows('Training'),
            $this->rows('E-Learning'),   
            $this->rows('Market Info'), 
            $this->rows('Weather Info'), 
            $this->rows('Configurations'),          
        ];

        SystemModule::query()->insert($modules);

        $country = Country::whereName(Country::CTRY_UG)->first();
        $_modules = SystemModule::get();

        foreach ($_modules as $module) {
            CountryModule::create([
                'country_id' => $country->id,
                'module_id' => $module->id
            ]);            
        }

        $this->enableForeignKeys();
    }

    /**
     * Create the database seeds.
     *
     * @return void
     */
    protected function rows($name, $description=null)
    {
        return [
            'id'            => $this->generateUuid(),
            'name'          => $name, 
            'created_at'    => \Carbon\Carbon::now()->subMonths(6), 
            'updated_at'    => \Carbon\Carbon::now()->subMonths(6)
        ];
    }
}
