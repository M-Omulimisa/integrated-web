<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ussd\UssdEvaluationQuestion;
use App\Models\Ussd\UssdEvaluationQuestionOption;

class UssdEvaluationQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ////////////////////////ENGLISH//////////////////////////////////////////////////////////
        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Did the content help to address specific coffee challenges you were experiencing?', 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce', 'position' => 1]
        );
                

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Yes', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Partially', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'No', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'was the content helpful to you during this coffee season?', 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce', 'position' => 2]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Yes', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Partially', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'No', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'How useful were the coffee tips to you?', 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce', 'position' => 3]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'They were useful', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'They were partially useful', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'They were not useful', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );


        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Did you prefer or like the local language that was used?', 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce', 'position' => 4]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Yes, i liked it', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'I partially liked it', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'No, i did not like it', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );


        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Have you implemented what you learned from the tips to your coffee farms?', 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce', 'position' => 5]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Yes', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Partially', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'No', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Did you find the USSD delivery channel interactive?', 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce', 'position' => 6]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Yes', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Partially', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'No', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Did you find the USSD channel easy to use?', 'ussd_language_id' => '2df58ff9-e623-4af3-82bc-048ef73eb4ce', 'position' => 7]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Yes', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Partially', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'No', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        ///////////////////////LUGISU//////////////////////////////////////////////////////////

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Bubakha bwesi wafuna bwakhuyeta khukhwilamo buwanghafu bwesi abe ufuna?', 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d', 'position' => 1]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Ye', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Sili bwosi ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Tawe', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );
        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Bubakha bwesi wafuna bwaba bwe kumugaso isi uli mu season iye imwanyi ino?', 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d', 'position' => 2]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Ye', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Sili nabi ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Tawe', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );
        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Bubakha buno bwaba bwe kumugaso burye?', 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d', 'position' => 3]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Bwaba bwe kumugaso nabi', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Si bwaba bwe kumugaso nabi ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'sibwaba bwe kumugaso ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );


        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Wakana lulimi lwesi khwarambisa khuweresa bubakha?', 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d', 'position' => 4]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Ye Nalukana', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Sinalukana nabi ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Tawe sinalukana ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );
        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Warere mungola byesi wayikile khukhwama mu bubakha Mukunda mwowo?', 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d', 'position' => 5]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Ye', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Sili bwosi ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Tawe', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );


        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Bubakha buno khubira mu USSD, abe bukhunyalisa khukhwilamo?', 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d', 'position' => 6]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Ye', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Sili nabi ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Tawe', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );
        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Khufuna bubakha khusimu yowo khubira mu USSD, wakhunyola nga khukhwangu khurambisa?', 'ussd_language_id' => '04c724c2-a554-43c3-966e-a02ad512ef9d', 'position' => 7]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Ye', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Sili nabi ta', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'Tawe', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );


        ////////////////////RUNYANKOLE ///////////////////////////////////////////////////////

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Okushomesibwa kukakuyamba okuhezaho ebibu byenyini ebiwabire nobugana omukuhinga omwani?', 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23', 'position' => 1]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa eego', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa mpora mpora', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bya ngaha', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Ebibakuragirire bikahwera omwisharura ryomwani ogu mwakwa?', 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23', 'position' => 2]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'eego', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'mporampora', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'ngaaha', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Okushomesibwa aha mwani kukakuyamba kuta?', 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23', 'position' => 3]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'ahabwabikayamba munonga', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa bikayamba mporampora', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa tibirayambire', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );


        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Okashemezibwa orurimi Orunyankore orwakoresibwe?', 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23', 'position' => 4]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'rukanshemeza', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa mporampora', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'tiruranshemize.', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

                
        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>"Watire omunkora ebyakushomesibye aha musiri gwawe gw'omwani?", 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23', 'position' => 5]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa eego', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa mporampora', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa ngaaha', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Oshangire omukutu ogwokukozesa eshura gubonire okukozesa?', 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23', 'position' => 6]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa eego', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa mporampora', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa ngaha', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

        $question = UssdEvaluationQuestion::create(   
            ['evaluation_question'=>'Oshangire omukutu ogwokukozesa eshura gwanguhi okukozesa?', 'ussd_language_id' => '21629c7a-96dc-4212-af8d-a2a6b06a1c23', 'position' => 7]
        );

                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa eego', 'ussd_evaluation_question_id' => $question->id, 'position'  => 1]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa mporampora', 'ussd_evaluation_question_id' => $question->id, 'position'  => 2]
                );
                UssdEvaluationQuestionOption::create(
                    ['evaluation_question_option' => 'aha bwa ngaha', 'ussd_evaluation_question_id' => $question->id, 'position'  => 3]
                );

    }
}