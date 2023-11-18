<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ussd\UssdLanguage;

class UssdLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UssdLanguage::create(
            
            ['language'=>'English', 'position' => 1, 'menu_id' => 4]
        );

        UssdLanguage::create(
            
            ['language'=>'Lumasaaba', 'position' => 2, 'menu_id' => 4],
        );

        UssdLanguage::create(
            
            ['language'=>'Runyakitara ', 'position' => 3, 'menu_id' => 4],
        );

        
            
    }
}
