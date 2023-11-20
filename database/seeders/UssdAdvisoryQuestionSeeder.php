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
            
            ['question'=>'Unyola buwanghafu shina nga ubuta imwanyi? Igana khumanya byesi indinikhukhola nga', 'position' => 1, 'ussd_advisory_topic_id' => 'd397b3f9-37d9-44b9-828b-b29c96207b5b']
        );
        //////////////////////Options //////////////////////////

                $option = UssdQuestionOption::create(
                    
                    ['option'=>'Imbuta imwanyi', 'position' => 1, 'ussd_advisory_question_id' => $question->id],
    
                );

                        UssdAdvisoryMessage::create(
                    
                            ['message'=>'Rambisa bibindu bimiliyu nga ubuta imwanyi khubona uri imwanyi sifuna lukukhu ta lwekhuba shonekesa kumutindo ni bukaali bwe imwanyi isi wamisamo. Ba ni litundubaale limiliyu oba lunyakamu asi we kusaala nga ubuta imwanyi.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                    
                            ['message'=>'Khurambisa bibindu bimiliyu nga ubuta shina khuyeta khukhwawula imwanyi isi unyola nga yakwile muliloba khukhwama khu imwanyi isi wama khubuta. Mumbukha iye ifula, nalundi shiyetakho balimi khubuusa imwanyi mangu.',  'ussd_question_option_id' => $option->id],
            
                        );



                $option = UssdQuestionOption::create(
            
                    ['option'=>'Khukhalila tsisakka', 'position' => 2, 'ussd_advisory_question_id' => $question->id]

                );

                        UssdAdvisoryMessage::create(
                            
                            ['message'=>'Khukhalila tsisaaka shili ni khukholebwa nga iseason inghulu iyekhubuta yawele. Rambisa khasumeno oba khamakansi khurusakho tsisaka ni bisina bikhakaniwa ta. Nga khukhalila khwakhukholebwa lwanyuma lwe khubuta, shirera biwukha ni tsindwale atwela ni khukhendesa bubungi bwe imwanyi isi wamisamo.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                            
                            ['message'=>'Khukhwongela khu bungi bwe imwanyi, khalila bisina bibalayu khubona uri buwanga bwingila mu kusaala.',  'ussd_question_option_id' => $question->id],
            
                        );

                $option = UssdQuestionOption::create(
        
                    ['option'=>'Khusha imwanyi', 'position' => 3, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>'Kamatunda kesi umalile khukhwangala ni khusinga kakana khukasha mu bwangu khubona uri si kafunda ta lwekhuba khufunda khunanikha nga ubuta imwanyi. Kamatunda kafundile nabi kamisamo imwanyi ikhali indayi khunywa ta, ela shikhisa kumutindo ni beyi.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                            
                            ['message'=>'Singa shishuma shisha imwanyi bulayi nga ushili khunanikha khusha kamatunda; yishenga urusemo imwanyi inghale oba kamatunda kakaramamo nga usha imwanyi inghale,ino yonekesa (nga nobona imwanyi iluluwa oba iwunya bubi) ela shikhisa kumutindo.',  'ussd_question_option_id' => $option->id],
            
                        );

        


        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>'Unyola buwanghafu shin ani khutiima khwe liloba?', 'position' => 2, 'ussd_advisory_topic_id' => 'ed381306-f0e9-48bb-b511-11ad84f7e214']
        );



        /////////////////Options /////////////////////////////////////

                $option = UssdQuestionOption::create(
                
                    ['option'=>'Ingana khumanya lina ni ingeli iye khulima tsimbibilo', 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                            
                            ['message'=>'Lima tsimbibilo Mukunda nga abe kumukunda kwowo kuli khulupilingikho khukhendesa khutiima khwe liloba ni khulinda bukhafu muliloba nga ukhendesa kametsi khutiima. ',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                            
                            ['message'=>'Khushingilila khutiima khwe liloba nga ifula ingali, liima tsimbibilo ela urusemo liloba mumbibilo tsinghale mu mbuka iye kumumu',  'ussd_question_option_id' => $option->id],
            
                        );


                $option =UssdQuestionOption::create(
                
                    ['option'=>'Ingana khumanya ingeli iye khufuna khumbimba liloba bilayi', 'position' => 2, 'ussd_advisory_question_id' => $question->id]
                );


                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Ingeli indwela indayi ate nga iyebusa iye khufuna bibimbilila liloba nikhwo khurambisa bunyaasi khulwekhuba buli ni bukhafu bukali. Khuburambisa nga shekhubimbilila, nyowa obwanikhile bwome. Nga bwoma, bukhafu bukali bulimo bunakhendela, shino shinabukhola khuba nga unyala waburambisa khubimbilila ali bilime akhali khubyolesakho buwanghafu ta.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                            
                            ['message'=>'Khukhupanisa kumusuru, khutiima khweliloba atwela ni khukhwongela bukhafu mu liloba, rambisa kamasakari khubimbilila liloba. Kalimo biliisa bye phosphorus ni potassium bili bye kumugaso nabi isi bilima ni khubiyeta khutsowa.',  'ussd_question_option_id' => $option->id],
            
                        );



        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>'Unyola buwanghafu shin ani biwukha ni tsindwale?', 'position' => 3, 'ussd_advisory_topic_id' => 'ab61666f-cec2-46c5-98ed-c6c4b249f4af']
        );

        /////////////////Options /////////////////////////////////////

                $option =  UssdQuestionOption::create(
                        
                    ['option'=>'Ingana khumanya biwukha ni tsindwale shina biwamba tsimwanyi', 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Khutalaka khwe kamaru ke mwanyi bulwale bukhulu ela buyokesa ni butonyesa bwa yellow khumaru. Butonyesa buno buyongela khunera ela bwasaala kamabimba ka orange. Buno obwakamisa ni; khulinda bulayi kumukunda, khubyala imwanyi ikhafuna bulwale atwela ni khurambisa kamalesi ketsindwale nga nuwo ifula inanika khugeza nga nordoz 75.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Kamasaa buwukha buwumiyisa kusaala nga bubuula likhobola lye kusaala nga butolosa nga bulya likhobola lino asi isi kusaala kunanikhila ela busha kusaala nga buyongela khukulya mukari. Buno unyala wabwakimisa nga urambisa pamba wamura mu malesi ke biwukha lwanyuma wamwingisa mu malowo kesi bulimo.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Buwukha bulya imwanyi bulima lilowo mu li tunda lye imwanyi, bwaramo kamalowo mukari isi burerera kamaki kawe, shino sherera kamatunda khu khwanguwa ela kaba nga ke kumutindo kwe asi. Rusamo kamatunda kosi ke khubuta khuwele khukhwakamisa khukesa khawukha khano.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Buwutkha bulya imwanyi bulya bunini bubwama mu milandila kye kusaala kwe imwanyi ela buwamba kusaala khukhwama nga kushili imitso. Khushingila bulwale buno, maata tsimitso tsino mushifo she khutsibyala.',  'ussd_question_option_id' => $option->id],
            
                        );

                


        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>'Unyola buwanghafu shina ni khubikha imwanyi?', 'position' => 4, 'ussd_advisory_topic_id' => 'c49ffc60-7f68-478b-9425-73f7c8acd87f']
        );

        /////////////////Options /////////////////////////////////////

                $option = UssdQuestionOption::create(
                                
                    ['option'=>'Inghana khumanya ingeli isi usabikha atwela ni khubiikha imwanyi nga wamalile khubuuta/ kimitendela kyekhubiramo lwanyuma lwe khubuta.', 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Imwanyi ili ni khubikhibwa mushirara oba mu kutiya tsinyonjo. Ukhatsibikha mu sawu tse fertilizer lwekhuba imwanyi kane ikhwese bunyifu ela ifune lukukhu khulwe bunyifu.',  'ussd_question_option_id' => $option->id],
            
                        );
                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Tsisawu tse imwanyi tsili ni khubikhibwa khu bisaala, nga byesutilekho khukhwama asi nga 15cm khuloba khusinya ni khukhwesa bunyifu, ela tsisawu tsilimo imwanyi tsili ni khubikhibwa afazari 30cm khukhwama khukutiyi ni khuli bati.',  'ussd_question_option_id' => $option->id],
            
                        );
                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Isitowa iye imwanyi ili ni khuba aleyikho ni bibindu biwunya nabi nga petroli, tsi fertilizer ni kamalesi khuloba khwonekesa imwanyi inywewa mushikombe atwela ni kumutindo kwe imwanyi ta.',  'ussd_question_option_id' => $option->id],
            
                        );

        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>'Unyola buwanghafu shina ni khushukha shukha khwe bubwile?', 'position' => 5, 'ussd_advisory_topic_id' => '6016b13e-5ca7-4aae-afc0-e36b58822b31']
        );

        /////////////////Options /////////////////////////////////////

               $option =  UssdQuestionOption::create(
                                        
                    ['option'=>'Inyala indyena khulinda kumukunda kwase kwe imwanyi khukhwama isi buwangahfu bubwama khu ifula ikhupilisa/ khutembelela/ ifula inghali?', 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>'Khulwe ifula inghali ilikhukhwitsa, Bisintsa bibindi bisubila khufuna akari we 300ml ni 600ml tse ifula mulunakhu lutwela. Khubona uri ifula yino siyonekesa kumukunda kwowo, bona uri kumukunda kwowo kuli ni tsimbibilo tsindayi khushingilila khutiima khwe liloba.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>'Ifula ikhupilila yingilila ela ikhasisa kamatunda ke imwanyi khutsowa bulayi, shino shikwisaa kumutindo kwe imwanyi ni bukali bwayo. Khukhasisa ifula yino, byala kimisaala kikindi kibimbilila kinyalise khuwa shisisa shimala isi kimisaala kye imwanyi, shiyeta khupiima bukhafu ni khukhendesa khuwamo khwe bukhafu mu liloba.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>'Mumbuka ye ifula, imwanyi ilumbibwa nabi tsindwale nga khutalaka khwe kamaru ela tsikwisa kumutindo kwe imwanyi ni beyi. Khukhwakamisa buwanghafu buno, khalila tsisakka oba utanye kimisaala kimilwale nga bulwale bushili khukenda ta.',  'ussd_question_option_id' => $option->id],
            
                        );


        ////////////////////////////////////LUGISU ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
}
