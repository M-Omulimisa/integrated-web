<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegionModel;

class RegionMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegionModel::whereName('Central')->update(['menu_name' => 'National', 'menu_status' => TRUE]);
        RegionModel::whereName('Northern')->update(['menu_name' => 'Northern', 'menu_status' => TRUE]);
    }
}
