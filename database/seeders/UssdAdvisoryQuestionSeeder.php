<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ussd\UssdAdvisoryQuestion;
use App\Models\Ussd\UssdQuestionOption;
use App\Models\Ussd\UssdAdvisoryMessage;

class UssdAdvisoryQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ///////////////////////////////////////////ENGLISH //////////////////////////////////////////////////////////////////////////////////////////////////////
        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>"Nibuzibuki obw'okubugana omukusharura?", 'position' => 1, 'ussd_advisory_topic_id' => '420fe433-c077-4659-b6f2-ad2beb3e131b']
        );
        //////////////////////Options //////////////////////////

                $option = UssdQuestionOption::create(
                    
                    ['option'=>'Ninyenda kumanya oku orikusharura embirabira', 'position' => 1, 'ussd_advisory_question_id' => $question->id],
    
                );

                        UssdAdvisoryMessage::create(
                    
                            ['message'=>"Waba nosharura,torana enjumaz'omwaani ezihisizegye zoonka. omwaani oggu shoromirwegye nigurinda omuteindo murungi kandi nigugira ebeyi nuungi omukatare",  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                    
                            ['message'=>"Ebyorakozese waba nosharura bibebiri ebiyoonjo okweenda kwerinda oruhumbwe oruri kushisha  omutiindo gw'omwaani .Yara entundubare ahansi y'ekiti ky'omwani waba n'oshoroma.Okusharurira omu biintu by'ebiyonjo, nikikuyamba okutaniisa omwaani ogurageire ahansi hamwe n'oguwashoroma.Omubweire bw'enjura nikiyaamba omuhingi okurundaana omwaani omubwiira. ",  'ussd_question_option_id' => $option->id],
            
                        );



                $option = UssdQuestionOption::create(
            
                    ['option'=>'Ninyenda kumanya obu hamwe nokundasharire.', 'position' => 2, 'ussd_advisory_question_id' => $question->id]

                );

                        UssdAdvisoryMessage::create(
                            
                            ['message'=>"Okusharirira kushemereire kukorwa bwanyima y'okusharura  omwaani ogw'omwaka.Kozesa akashumeni ninga  makanki okushara ebitagi ebitarikweendwa.Okusharira kwaba kutakozirwe bwanyima y'okusharura nikireeta obukooko n'endwaara ekirikureta amasharura makye",  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                            
                            ['message'=>"Okwongera amasharura g'omwani gwawe sharirira ebiti by'omwaani ebihango okwenda ngu hatahemu ekyererezi.",  'ussd_question_option_id' => $question->id],
            
                        );


        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>"Nibuzibuki obw'orikubugana omukutwaarwa kweitaka?", 'position' => 2, 'ussd_advisory_topic_id' => '3538e0f2-f8dc-401e-8f73-6729d96e41ca']
        );



        /////////////////Options /////////////////////////////////////

                $option = UssdQuestionOption::create(
                
                    ['option'=>"Tindikubona ebyokwariza. kandi nibigura esente nyiingi", 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                            
                            ['message'=>"Okwarira nikikuru omukwerind okutwarwa kwitaka.kirimu okushangira eitaka ahamutwe ahagati y'ebihaingwa  hamwe nokwetorora ebiti byo omwani orikukoresa  ebisigarira by'ebihaingwa obyomire nka ebinyasi,ebikonk ebishwagarira.",  'ussd_question_option_id' => $option->id],
            
                        );

                        


                $option =UssdQuestionOption::create(
                
                    ['option'=>"Ninyenda kumanya  okunakubasa kukyendeza omwata omumusiri gw'omumwaani gwangye.", 'position' => 2, 'ussd_advisory_question_id' => $question->id]
                );


                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Okurinda omwaata,okutwarwa kweitaka hamwe nokwongera okubiika maizi omwitaka  kozesa ebikonko by'ebikyoori omukwarira.Byeine ebirisa bingi ebi ebimera birikwetenga okukura gye.",  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                            
                            ['message'=>"Okukyendeza okukura kw'omwata kozesa ebyarizo ebyomire nka ebishwagarira, ebikonko obitatane ahamutwe gweitaka",  'ussd_question_option_id' => $option->id],
            
                        );


                
                $option =UssdQuestionOption::create(
        
                    ['option'=>"Omusirigw'omwani gwangye gukabyarwa kare kandi tigurabyeirwe mu nyiriri nahabweekyo tikyanguhi okuteeramu ebitaba", 'position' => 3, 'ussd_advisory_question_id' => $question->id]
                );


                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Omumusiri gw'omwani ogwakuzire byaranmu ebihingwa ebirikushweeka eitaka nka ebinyobwa nka omuringo gw'okerinda okutwaarwa kweitaka hamwe n'okukuma amaizi omwitaka.",  'ussd_question_option_id' => $option->id],
            
                        );




        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>"Nibuzibu ki obworikubugana  omu bukooko hamwe n'endwara?", 'position' => 3, 'ussd_advisory_topic_id' => '9147a131-9a26-4f7b-8c04-37bf144cc780']
        );

        /////////////////Options /////////////////////////////////////

                $option =  UssdQuestionOption::create(
                        
                    ['option'=>"Ninyanda kumanya eki obukooko n'endwara biri kushisha aha muti gwa'omwani.", 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Obukooko bwa'omwani oburikununyuta otwiizi omumizi ya'omwani gwaaba gukiri muto.Okubwerinda  otabyara akati akabweine.",  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Obukooko oburikutaha omunjuma z'omwaani nibuteramu amahuri ekirikureeta omwaani gwaremeera kubi kandi n'omutindo gwaafa. Okukyendeza okuzanzara  kwaabwe torana enjuma ezirweire omumwani ogu washoroma.",  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Okwerinda obukooko n'endwara omumusiri gwawe gw'omwani okukozesa emibazi tikirikwetengwa waba oyokize ebyo ebirweire,kozesa ebirisa ebyobuhangwa,kandi orebe ngu amaizi tigarikutrema omumusiri, Ebihingwa obihe emyanya kandi obisharirire",  'ussd_question_option_id' => $option->id],
            
                        );

                


        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>"Nibuzibuki obwo kushanga omukubyaara?", 'position' => 4, 'ussd_advisory_topic_id' => 'd4301045-571c-473b-900d-a710b8b4179c']
        );

        /////////////////Options /////////////////////////////////////

                $option = UssdQuestionOption::create(
                                
                    ['option'=>"Ninyenda kutunga okushomesibwa ahakubyara kw'omwani.", 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Wazakubyaara omwani tiimba ekiina kirikwingana futi 3 omurubaju hamwe nafuti 3 ahansi",  'ussd_question_option_id' => $option->id],
            
                        );
                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Omwaani gwaheza kurabya ahabw'omushana mwingi nikiragirwa okwarira okwenda kukuma maizi omwita ekirikureta amasharuramarungi.",  'ussd_question_option_id' => $option->id],
            
                        );
                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Abahingi abarikuhinga orutokye omumwaani nibashabwa okutamu ebirikurta ekirisa  omumisiri yabo omurundi gumwe omumwaka okwerinda okucukuka kweita.",  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>"Emiiti y'omwani ashemereire kubyaarwa n'ebihingwa ebiine emiizi migufu ahabwokuba omwani gweine emizi miringwa.",  'ussd_question_option_id' => $option->id],
            
                        );

                $option = UssdQuestionOption::create(
                        
                    ['option'=>"Ninyenda kumanya ahu nakubasa kwiha embibo nungi abeyi nkye", 'position' => 2, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                                            
                            ['message'=>"ACPCU eyine embubo aha bwa abahingi bayo kwenda kukuma omutindo gwembubo aha sente nkye munonga",  'ussd_question_option_id' => $option->id],
            
                        );

        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>"Nimuraba mubizibu ki omu mpinda hinduka yobwiire?", 'position' => 5, 'ussd_advisory_topic_id' => 'f05e99f6-4560-476a-ad35-c01c7e1f4c17']
        );

        /////////////////Options /////////////////////////////////////

               $option =  UssdQuestionOption::create(
                                        
                    ['option'=>"nkarinda nta omusiri gwangye obu tateganisibwa enjura nyinji", 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>"Ahabwa obwire bwe njura nyinji obukwija ,emyanya emwe neyija kuba netunga enjura nyiji buri eizooba.Okwerinda akabi k'enjura nyiji aha musiri gwomwani gyezaho otere ebitaba kwenda kwerinda okutwarwa kweitaka.",  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>"Enjura nyingi neteganisa okweema kwa ebihingwa hamwe nokukura kwenjuma zomwaniekikureta omutindo mubi na masharura makye.Okwerinda eyo bwara emiti yebi bibunda erikumara ekirayambe okutekyeka obufuki hamwe nokwosya kandi nokukyendeza okuhwa kwamizi omwitaka.",  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>"O mubwire byenjura nyingi omwani niguba guri omukabi kahango kwokwatwa endwara endwara ezebisente ekirikukyendeza omutindo .",  'ussd_question_option_id' => $option->id],
            
                        );


        ////////////////////////////////////LUGISU ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
}
