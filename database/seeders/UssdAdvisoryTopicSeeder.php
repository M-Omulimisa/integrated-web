<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ussd\UssdAdvisoryTopic;

class UssdAdvisoryTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /////////////////ENGLISH //////////////////////////////////////////
        UssdAdvisoryTopic::create(
            
            ['topic'=>'Coffee Harvest', 'position' => 1, 'ussd_language_id' => '1d9d2157-1cef-482e-8424-933358e7efdb']
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Soil Erosion', 'position' => 2, 'ussd_language_id' => '1d9d2157-1cef-482e-8424-933358e7efdb'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Pests and Diseases', 'position' => 3, 'ussd_language_id' => '1d9d2157-1cef-482e-8424-933358e7efdb'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Storage', 'position' => 4, 'ussd_language_id' => '1d9d2157-1cef-482e-8424-933358e7efdb'],
        );
        UssdAdvisoryTopic::create(
            
            ['topic'=>'Climate Change Adaptation', 'position' => 5, 'ussd_language_id' => '1d9d2157-1cef-482e-8424-933358e7efdb'],
        );


        ////////////////////LUMASAABA/////////////////////////////////////////////
        UssdAdvisoryTopic::create(
            
            ['topic'=>'Khubuta imwanyi', 'position' => 1, 'ussd_language_id' => 'd6e788f7-f954-4d5b-a6d4-345b8d1b911a']
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Khutiima khwe liloba', 'position' => 2, 'ussd_language_id' => 'd6e788f7-f954-4d5b-a6d4-345b8d1b911a'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Buwukha ni tsindwale', 'position' => 3, 'ussd_language_id' => 'd6e788f7-f954-4d5b-a6d4-345b8d1b911a'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Khubiikha', 'position' => 4, 'ussd_language_id' => 'd6e788f7-f954-4d5b-a6d4-345b8d1b911a'],
        );
        UssdAdvisoryTopic::create(
            
            ['topic'=>'Khurambila mu khushukha shukha khwe bubwiile', 'position' => 5, 'ussd_language_id' => 'd6e788f7-f954-4d5b-a6d4-345b8d1b911a'],
        );


        ///////////////////////RUNYAKITARA//////////////////////////////////////

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Okusharura omwaani', 'position' => 1, 'ussd_language_id' => '06732262-3032-46d6-8c09-13f348cbf00a'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Okutwaarwa kweitaka', 'position' => 2, 'ussd_language_id' => '06732262-3032-46d6-8c09-13f348cbf00a'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>"Obukooko n'enkdwara", 'position' => 3, 'ussd_language_id' => '06732262-3032-46d6-8c09-13f348cbf00a'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Okubyaara', 'position' => 4, 'ussd_language_id' => '06732262-3032-46d6-8c09-13f348cbf00a'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>"Okumanya empinduka y'obwiire", 'position' => 5, 'ussd_language_id' => '06732262-3032-46d6-8c09-13f348cbf00a'],
        );

    }
}
