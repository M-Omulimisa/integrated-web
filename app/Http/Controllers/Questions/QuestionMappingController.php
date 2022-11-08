<?php
    
namespace App\Http\Controllers\Questions;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Models\User;
use App\Models\questions\Question;
    
class QuestionMappingController extends Controller
{
    public $_permission    = "question-mapping";
    public $_route         = "questions.mapping";
    public $_dir           = "questions.mapping";
    public $_menu_group    = "Farmer Questions";
    public $_page_title    = 'Mapping farmer questions & responses';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function questions(Request $request)
    {
        try {            
            return view($this->_dir.'.questions');
        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage());
        }
    }

    public function questionMap()
    {
        $questions = Question::whereNotNull('latitude')->whereNotNull('longitude')->limit(10000)->get();

        $result = array();
        if (count($questions) > 0) {
            foreach ($questions as $question) {

                $desc = '';
                // $desc .= 'Name: <strong>'.$farmer->first_name.' '.$farmer->last_name.'</strong><br>';
                // $desc .= 'Gender: <strong>'.$farmer->gender.'</strong><br>';
                // $desc .= 'YOB: <strong>'.$farmer->year_of_birth.'</strong><br>';
                // $desc .= 'Phone: <strong>'.$farmer->phone.'</strong><br>';
                // $desc .= 'Location: <strong>'.$farmer->location->name.'</strong>';

                $result[] = array(
                        'type' => 'Feature', 
                        'properties' => [
                            'id' => 'ak16994521',
                            'mag' => 2.3,
                            'time' => 1507425650893,
                            'felt' => null,
                            'das' => 0,
                            'description' => $desc,
                        ],
                        'geometry' => [
                            'type' => 'Point',
                            'coordinates' => [ $question->longitude, $question->latitude, 0.0 ]
                        ]
                    );
            }
        }
        return response()->json($result);
    }
}