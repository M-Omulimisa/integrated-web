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
            
            ['topic'=>'Coffee Harvest', 'position' => 1, 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce']
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Soil Erosion', 'position' => 2, 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Pests and Diseases', 'position' => 3, 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Storage', 'position' => 4, 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce'],
        );
        UssdAdvisoryTopic::create(
            
            ['topic'=>'Climate Change Adaptation', 'position' => 5, 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce'],
        );


        ////////////////////LUMASAABA/////////////////////////////////////////////
        UssdAdvisoryTopic::create(
            
            ['topic'=>'Khubuta imwanyi', 'position' => 1, 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d']
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Khutiima khwe liloba', 'position' => 2, 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Buwukha ni tsindwale', 'position' => 3, 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Khubiikha', 'position' => 4, 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d'],
        );
        UssdAdvisoryTopic::create(
            
            ['topic'=>'Khurambila mu khushukha shukha khwe bubwiile', 'position' => 5, 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d'],
        );


        ///////////////////////RUNYAKITARA//////////////////////////////////////

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Okusharura omwaani', 'position' => 1, 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Okutwaarwa kweitaka', 'position' => 2, 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>"Obukooko n'enkdwara", 'position' => 3, 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>'Okubyaara', 'position' => 4, 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23'],
        );

        UssdAdvisoryTopic::create(
            
            ['topic'=>"Okumanya empinduka y'obwiire", 'position' => 5, 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23'],
        );

    }
}
