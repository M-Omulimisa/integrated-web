<?php

namespace App\Admin\Controllers;

use App\Models\FarmerQuestion;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FarmerQuestionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Farmer Questions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {


        /*         $questionsAnswers = array(
            array(
                'question' => 'What are the best crops to grow during the dry season in Uganda?',
                'answer' => 'Some of the best crops to grow during the dry season in Uganda include drought-resistant crops like sorghum, cowpeas, and groundnuts.'
            ),
            array(
                'question' => 'How can I conserve water on my farm during the dry season?',
                'answer' => 'Implement water-saving techniques such as drip irrigation, mulching, and rainwater harvesting to conserve water during dry periods.'
            ),
            array(
                'question' => 'What are the most effective ways to control weeds in my fields?',
                'answer' => 'Manual weeding, use of herbicides (with proper guidance), and practicing crop rotation are effective methods for weed control.'
            ),
            array(
                'question' => 'How can I prevent post-harvest losses on my farm?',
                'answer' => 'Proper drying, storage in pest-resistant containers, and timely harvesting can help prevent post-harvest losses.'
            ),
            array(
                'question' => 'What types of organic fertilizers can I use to improve soil fertility?',
                'answer' => 'Organic fertilizers like compost, animal manure, and green manure crops can help improve soil fertility naturally.'
            ),
            array(
                'question' => 'How can I increase the productivity of my banana plantation?',
                'answer' => 'Proper spacing, regular pruning, and applying balanced fertilizers can help increase banana yield.'
            ),
            array(
                'question' => 'What are the key considerations for selecting suitable livestock breeds for my farm?',
                'answer' => 'Consider factors such as breed adaptability to local conditions, disease resistance, and productivity when selecting livestock breeds.'
            ),
            array(
                'question' => 'How can I control pests and diseases in my tomato farm without using harmful chemicals?',
                'answer' => 'Introducing beneficial insects, using neem-based products, and maintaining proper crop hygiene can help control pests and diseases organically.'
            ),
            array(
                'question' => 'What are the best practices for soil erosion control on hilly farmlands?',
                'answer' => 'Terracing, contour plowing, and planting trees on slopes can effectively control soil erosion on hilly farmlands.'
            ),
            array(
                'question' => 'How can I improve the quality of my coffee beans for better prices in the market?',
                'answer' => 'Proper harvesting, washing, and drying of coffee beans can significantly improve their quality and market value.'
            ),
            array(
                'question' => 'What are the major challenges facing poultry farming in Uganda?',
                'answer' => 'Major challenges in poultry farming include disease outbreaks, feed costs, and marketing difficulties.'
            ),
            array(
                'question' => 'How can I get access to agricultural credit to expand my farm?',
                'answer' => 'You can approach agricultural banks, microfinance institutions, or agricultural cooperatives for agricultural credit opportunities.'
            ),
            array(
                'question' => 'What are the best crops to plant as cover crops to improve soil health?',
                'answer' => 'Legumes like cowpeas and mucuna are excellent cover crops for enriching soil with nitrogen.'
            ),
            array(
                'question' => 'How can I protect my maize farm from fall armyworm infestation?',
                'answer' => 'Early detection, natural enemies (like parasitoids), and using insect-resistant maize varieties can help manage fall armyworm.'
            ),
            array(
                'question' => 'What are the best practices for integrated pest management (IPM) in vegetable farming?',
                'answer' => 'Crop rotation, biological pest control, and regular scouting are key components of IPM for vegetable farming.'
            ),
            array(
                'question' => 'How can I diversify my farm\'s income through agribusiness ventures?',
                'answer' => 'Consider value addition through processing, beekeeping, or starting a small-scale agribusiness enterprise like making value-added products from your crops.'
            ),
            array(
                'question' => 'How can I improve the shelf life of my fresh fruits and vegetables?',
                'answer' => 'Proper post-harvest handling, cooling, and storage in controlled environments can extend the shelf life of fresh produce.'
            ),
            array(
                'question' => 'What are the benefits of using improved seed varieties in my farm?',
                'answer' => 'Improved seed varieties offer higher yields, better disease resistance, and improved market acceptance.'
            ),
            array(
                'question' => 'How can I manage soil acidity in my farm for better crop growth?',
                'answer' => 'Applying lime or agricultural lime to acidic soils can help neutralize soil acidity and improve nutrient availability.'
            ),
            array(
                'question' => 'What are the steps to take if I suspect a notifiable disease outbreak in my livestock?',
                'answer' => 'Immediately notify the nearest veterinary authority and follow their guidance to prevent further spread of the disease.'
            ),
            array(
                'question' => 'How can I integrate agroforestry into my farm for sustainable land use?',
                'answer' => 'Planting trees within and around the farm can provide shade, reduce soil erosion, and offer additional income through timber and fruit production.'
            ),
            array(
                'question' => 'What are the best practices for fish farming in ponds?',
                'answer' => 'Maintaining water quality, providing adequate nutrition, and regular monitoring of fish health are crucial for successful fish farming in ponds.'
            ),
            array(
                'question' => 'How can I access agricultural training and extension services to improve my farming skills?',
                'answer' => 'Seek assistance from local agricultural offices, NGOs, or extension agents who provide training and support to farmers.'
            ),
            array(
                'question' => 'What are the suitable methods for organic pest control in vegetable farming?',
                'answer' => 'Using botanical extracts, biopesticides, and encouraging natural predators like ladybugs can help with organic pest control.'
            ),
        );

        foreach ($questionsAnswers as $key => $val) {
            $q = new FarmerQuestion();
            $q->user_id = 1;
            $q->district_model_id = rand(1, 140);
            $q->body = $val['question'];
            $cats = ['crops', 'livestock', 'poultry', 'fish', 'agribusiness', 'soil', 'water', 'pests', 'diseases', 'post-harvest', 'climate', 'finance', 'training', 'other'];
            $q->category = $cats[rand(0, 13)];
            $q->answered = 'no';
            $q->audio = '';
            $q->photo = '';
            $q->video = '';
            $q->document = '';
            $q->views = rand(0, 1000);
            $q->phone = '256' . rand(700000000, 799999999);
            $q->sent_via = ['ussd', 'web', 'moile app', 'sms', 'whatsapp', 'facebook', 'twitter', 'telegram', 'email'][rand(0, 5)];

            try {
                $q->save();
                $ans = new \App\Models\FarmerQuestionAnswer();
                $ans->user_id = 1;
                $ans->farmer_question_id = $q->id;
                $ans->verified = ['yes', 'no'][rand(0, 1)];
                $ans->body = $val['answer'];
                $ans->audio = '';
                $ans->photo = '';
                $ans->video = '';
                $ans->document = '';
                $ans->save();
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

 */
        $grid = new Grid(new FarmerQuestion());

        //segments
        $segs = request()->segments();
        $grid->model()->where('answered', 'no');
        if (isset($segs[0])) {
            $text = $segs[0];
            //if contains unanswered
            if (strpos($text, 'unanswered') !== false) {
                $grid->model()->where('answered', 'no');
            }
        }

        $grid->model()->orderBy('id', 'desc');
        $grid->disableBatchActions();
        $grid->quickSearch('body', 'phone', 'category')
            ->placeholder('Search question');


        $grid->column('id', __('#ID'))->sortable();
        $grid->column('body', __('Question'))
            ->sortable();
        $grid->column('district_model_id', __('District'))
            ->display(function ($district_model_id) {
                $d = \App\Models\DistrictModel::find($district_model_id);
                if ($d == null) {
                    return 'Unknown';
                }
                return $d->name;
            })
            ->sortable();

        /*  $grid->column('category', __('Category'))
            ->label(
                [
                    'crops' => 'primary',
                    'livestock' => 'success',
                    'poultry' => 'info',
                    'fish' => 'warning',
                    'agribusiness' => 'danger',
                    'soil' => 'primary',
                    'water' => 'success',
                    'pests' => 'info',
                    'diseases' => 'warning',
                    'post-harvest' => 'danger',
                    'climate' => 'primary',
                    'finance' => 'success',
                    'training' => 'info',
                    'other' => 'warning',
                ]
            )
            ->filter(
                [
                    'crops' => 'crops',
                    'livestock' => 'livestock',
                    'poultry' => 'poultry',
                    'fish' => 'fish',
                    'agribusiness' => 'agribusiness',
                    'soil' => 'soil',
                    'water' => 'water',
                    'pests' => 'pests',
                    'diseases' => 'diseases',
                    'post-harvest' => 'post-harvest',
                    'climate' => 'climate',
                    'finance' => 'finance',
                    'training' => 'training',
                    'other' => 'other',
                ]
            ); */
        $grid->column('phone', __('Phone'));
        $grid->column('sent_via', __('Sent Via'))
            ->display(function ($sent_via) {
                if (strtolower($sent_via) == 'sms') {
                    return 'SMS';
                }
                if ($this->sent_via != 'Mobile App') {
                    $this->sent_via = 'Mobile App';
                    $this->save();
                }
                return $this->sent_via;
            })->sortable()
            ->filter(['SMS' => 'SMS', 'Mobile App' => 'Mobile App']);
        $grid->column('answered', __('Answered'))
            ->filter(['yes' => 'yes', 'no' => 'no'])
            ->dot(['yes' => 'success', 'no' => 'danger'])
            ->sortable();
        $grid->column('Answers', __('Answers'))
            ->display(function () {
                return $this->farmer_question_answers()->count();
            });
        $grid->column('audio', __('Audio'))
            ->display(function ($rec) {
                if ($rec == null || strlen($rec) < 2) {
                    return "No Audio";
                }
                $link = url('storage/' . $rec);
                //retun audio player
                return '<audio controls>
                <source src="' . $link . '" type="audio/mpeg">
                Your browser does not support the audio element.
                </audio>';
            })->sortable();
        $grid->column('photo', __('Photo'))
            ->display(function ($rec) {
                if ($rec == null || strlen($rec) < 2) {
                    return "No Picture";
                }
                $link = url('storage/' . $rec);
                //retun photo with link
                return '<a href="' . $link . '" target="_blank"><img src="' . $link . '" style="max-width:100px;max-height:100px" /></a>';
            })->sortable();
        $grid->column('video', __('Video'))->hide();
        $grid->column('document', __('Document'))->hide();
        $grid->column('user_id', __('Farmer'))
            ->display(function ($user_id) {
                $f = \App\Models\User::find($user_id);
                if ($f == null) {
                    return 'Unknown';
                }
                return $f->name;
            })
            ->sortable();
        $grid->column('created_at', __('DATE'))
            ->display(function ($created_at) {
                return date('d M Y', strtotime($created_at));
            })
            ->sortable();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(FarmerQuestion::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('user_id', __('User id'));
        $show->field('district_model_id', __('District model id'));
        $show->field('body', __('Body'));
        $show->field('category', __('Category'));
        $show->field('phone', __('Phone'));
        $show->field('sent_via', __('Sent via'));
        $show->field('answered', __('Answered'));
        $show->field('audio', __('Audio'));
        $show->field('photo', __('Photo'));
        $show->field('video', __('Video'));
        $show->field('document', __('Document'));
        $show->field('views', __('Views'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new FarmerQuestion());

        $u = Auth()->user();
        if ($form->isCreating()) {
            $form->hidden('user_id', __('User id'))->default($u->id);
            $form->textarea('body', __('Body'));
            $form->select('category', __('Category'))
                ->options(
                    [
                        'crops' => 'crops',
                        'livestock' => 'livestock',
                        'poultry' => 'poultry',
                        'fish' => 'fish',
                        'agribusiness' => 'agribusiness',
                        'soil' => 'soil',
                        'water' => 'water',
                        'pests' => 'pests',
                        'diseases' => 'diseases',
                        'post-harvest' => 'post-harvest',
                        'climate' => 'climate',
                        'finance' => 'finance',
                        'training' => 'training',
                        'other' => 'other',
                    ]
                );
        } else {
            $form->textarea('body', __('Body'))->readonly();
            $form->text('category', __('Category'))->readonly();
            $form->text('phone', __('Phone'));
            $form->text('sent_via', __('Sent via'))->readonly();
        }
        $form->textarea('answer_body', __('Answer'));
        $form->radio('answered', __('Send SMS'))
            ->options(['yes' => 'No', 'no' => 'Yes'])
            ->default('no');

        /*         $form->file('audio', __('Audio'));
        $form->image('photo', __('Photo')); */

        if (!$form->isCreating()) {
            $form->hasMany('farmer_question_answers', __('Answers'), function (Form\NestedForm $form) {
                $u = Auth()->user();
                $form->hidden('user_id', __('User id'))->default($u->id);
                $form->textarea('body', __('Body'));
                $form->file('audio', __('Audio'));
                $form->image('photo', __('Photo'));
                /*                 $form->textarea('video', __('Video'));
                $form->textarea('document', __('Document')); */
            });
        }

        /*
        $form->textarea('video', __('Video'));
        $form->textarea('document', __('Document'));
        $form->number('views', __('Views'));
 */
        return $form;
    }
}
