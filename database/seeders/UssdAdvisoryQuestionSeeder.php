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
            
            ['question'=>'What challenges are you facing with harvesting?', 'position' => 1, 'ussd_advisory_topic_id' => '64160291-f832-4787-bd3e-1cb4225633f2']
        );
        //////////////////////Options //////////////////////////

                $option = UssdQuestionOption::create(
                    
                    ['option'=>'Coffee picking', 'position' => 1, 'ussd_advisory_question_id' => $question->id],
    
                );

                        UssdAdvisoryMessage::create(
                    
                            ['message'=>'Use clean containers while harvesting in order to avoid the development of mould which reduces quality and yield. Have a clean tarpaulin or hessian square under the tree while harvesting.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                    
                            ['message'=>'Using clean containers when harvesting will help you to separate coffee found fallen on the ground from the freshly harvested. During the rainy seasons it also helps farmers to gather the harvested coffee quickly.',  'ussd_question_option_id' => $option->id],
            
                        );



                $option = UssdQuestionOption::create(
            
                    ['option'=>'Pruning', 'position' => 2, 'ussd_advisory_question_id' => $question->id]

                );

                        UssdAdvisoryMessage::create(
                            
                            ['message'=>'Pruning should be done after the main coffee harvest. Use a pruning bow saw, or sharp secateurs to remove the unwanted branches and shoots. If pruning is not done after harvesting it leads to pests and diseases infestation and low yield production',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                            
                            ['message'=>'To increase your coffee yields always prune the large coffee trees to ensure proper penetration of light',  'ussd_question_option_id' => $question->id],
            
                        );

                $option = UssdQuestionOption::create(
        
                    ['option'=>'Pulping', 'position' => 3, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>'Rinsed and sorted cherries should be processed as soon as possible to avoid over-fermentation of cherries, which begins after harvesting. Over-fermented coffee will produce coffee that is unpleasant to drink, resulting in lower quality and profitability.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                            
                            ['message'=>'Clean the pulping machine well before beginning to pulp cherries; check to remove any left-over parchment or cherries from previous pulping, these become defects (like sour beans or stinkers) and lower the quality.',  'ussd_question_option_id' => $option->id],
            
                        );

        


        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>'What challenges are you facing with soil erosion?', 'position' => 2, 'ussd_advisory_topic_id' => '2929a1c8-460c-42d2-bf58-7900b4f4c25c']
        );



        /////////////////Options /////////////////////////////////////

                $option = UssdQuestionOption::create(
                
                    ['option'=>'I would like to know when and how to dig terraces', 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                            
                            ['message'=>'Dig terraces along contours in cases of steep slopes on your coffee farm to reduce soil erosion and conserve soil misture by minimising rainwater runoff.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                            
                            ['message'=>'To prevent soil erosion during heavy rains construct terraces and open the existing ones during the dry season',  'ussd_question_option_id' => $option->id],
            
                        );


                $option =UssdQuestionOption::create(
                
                    ['option'=>' I would like to know how to get good affordable mulch', 'position' => 2, 'ussd_advisory_question_id' => $question->id]
                );


                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'A good source of cheap or free mulch is fresh grass clippings as it contains a lot of moisture. To prepare them for use as a mulch, spread them out first to dry. As they dry, their initially hot, steamy quality will dissipate, making them safe to apply around plants.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                            
                            ['message'=>'To control weeds, soil erosion and increase soil moisture retention, use maize stalks for mulching. They are rich in phosphorus and pottasium which are essential nutrients for plant growth.',  'ussd_question_option_id' => $option->id],
            
                        );



        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>'What challenges are you facing with pests and diseases?', 'position' => 3, 'ussd_advisory_topic_id' => '67c5917f-3d73-40de-ac74-380e00f37658']
        );

        /////////////////Options /////////////////////////////////////

                $option =  UssdQuestionOption::create(
                        
                    ['option'=>'I would like to know what pests and diseases affect the coffee plant.', 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Leaf rust is a major coffee disease and its symptoms are pale yellow spots on the lower leaf surfaces. The spots enlarge and produce spores which are orange in color. Its controlled by; good field management practices, use of resistant varieties and application of chemical fungicides at the beginning the rain season e.g. Copper Nordox 75',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'White stem borers are pests that cause great damage to the coffee stem through causing ring barking as they feed on the bark at the base of the tree trunk and extract wood shavings as they burrow into the stem. It can be controlled by dipping cotton wool in any pestcide and stuffing it in the tunnel holes to kill the pests.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'The Coffee borer are insects that burrow into the top of the coffee berry, creating galleries inside the seed where they lay their eggs, leading in lighter coffee beans and a quality decline. Remove all the berries from your previous harvest to control the spread',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'The coffee mealybugs are insects that feed on the sap from the coffee roots and infests the tree from the seedling stage. To prevent the infestation, discard the infected seedling instead of planting it.',  'ussd_question_option_id' => $option->id],
            
                        );

                


        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>'What challenges are you facing with storage?', 'position' => 4, 'ussd_advisory_topic_id' => '86c9ba75-01e8-42d1-a97b-ae4d978b530c']
        );

        /////////////////Options /////////////////////////////////////

                $option = UssdQuestionOption::create(
                                
                    ['option'=>'I would like to know how to package and store coffee after harvesting/post-harvest handling.', 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Coffee should be stored in Silos or clean sisal bags. Do not store them in polybags as the coffee will absorb moisture and grow mould due to condensation.',  'ussd_question_option_id' => $option->id],
            
                        );
                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'Coffee bags should be placed on pallets, that are raised to atleast 15cm to avoid contamination and wetting by ground moisture, while stacked bags should be placed atleast 30cm away from the walls and ceiling.',  'ussd_question_option_id' => $option->id],
            
                        );
                        UssdAdvisoryMessage::create(
                                                    
                            ['message'=>'A coffee warehouse should be isolated from strong smelling liquids such as petrol, fertilizers and chemicals to avoid contamination of the final cup and poor coffee quality',  'ussd_question_option_id' => $option->id],
            
                        );

        $question = UssdAdvisoryQuestion::create(
            
            ['question'=>'What challenges are you facing with climate change adaptation?', 'position' => 5, 'ussd_advisory_topic_id' => 'd68d9c52-2ac4-482e-95ee-754ffd6a56f5']
        );

        /////////////////Options /////////////////////////////////////

               $option =  UssdQuestionOption::create(
                                        
                    ['option'=>'How can I protect my coffee farm from the effects of El nino/flooding/heavy rains', 'position' => 1, 'ussd_advisory_question_id' => $question->id]
                );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>'Due to the upcoming El nino, some regions can expect to receive 300-600 ml of rain in a single day. To mitigate this impact on your coffee farms, ensure your farm has excellent drainage, to prevent soil erosion.',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>'El nino interferes with pollination and prevents proper development of the coffee beans, resulting in lower quality and yields. To combat the El nino rains plant more shade trees which provide adequate shade to coffee plants, helps regulate temperature and reduce moisture loss from the soil',  'ussd_question_option_id' => $option->id],
            
                        );

                        UssdAdvisoryMessage::create(
                                    
                            ['message'=>'During El nino, coffee is prone to diseases such as rust which lowers the coffee quality and impact pricing. To mitigate the damage, prune or stump the infected coffee plant before rust has chance to spread.',  'ussd_question_option_id' => $option->id],
            
                        );


        ////////////////////////////////////LUGISU ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    }
}
