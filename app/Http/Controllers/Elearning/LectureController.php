<?php

namespace App\Http\Controllers\Elearning;

use AppHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningChapter;
use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningLecture;
use App\Models\Elearning\ELearningLectureAttendance;
use App\Models\Elearning\ELearningLectureTopic;
use App\Models\Elearning\ELearningLectureTopicResponse;
use App\Models\Elearning\ELearningLectureTopicSubscription;
use App\Models\Elearning\ELearningLectureTopicLike;
use App\Models\Elearning\ELearningLectureTopicResponseLike;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Helpers\MP3File;
use Illuminate\Validation\Rule;

class LectureController extends Controller
{
    // if (!Gate::allows('add_el_lectures create
    // if (! Gate::allows('view_el_lectures show
    // if (! Gate::allows('delete_el_lectures destroy
    // if (!Gate::allows('add_el_lecture_topics newTopic
    // if (!Gate::allows('view_el_lecture_topics showTopic
    // if (!Gate::allows('add_el_lecture_topic_responses newResponse
    // if (! Gate::allows('add_el_lecture_topic_subscriptions subscribeTopic
    // if (! Gate::allows('add_el_lecture_topic_likes likeTopic
    // if (! Gate::allows('add_el_lecture_topic_likes likeTopicResponse

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = ELearningCourse::find($courseId);
        return view('e_learning.lectures.index', compact('course'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId)
    {
        $status     = ELearningLecture::status;
        $course     = ELearningCourse::find($courseId);
        
        if ($course->lecture_type == "Weekly") {
            $chapters   = ELearningChapter::where('course_id',$courseId)->whereNull('summary')->get()->pluck('title','id');
        }else{
            $chapters   = ELearningChapter::where('course_id',$courseId)->get()->pluck('title','id');
        }
        // $numbering = 

        return view('e_learning.lectures.create',compact('status', 'course', 'chapters'));
    }

    /**
     * Store the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        request()->validate([
            'title'     => 'required|max:255',
            'audio'     => 'required|file|mimes:mp3,mpga|max:5120',
            'user_id'   => 'required',
            'numbering' => 'required|numeric',
            'chapter_id' => [
                'required', 
                Rule::unique('e_learning_lectures')->where(function ($query) use ($request) {
                    return $query
                        ->where('title', $request->title)
                        ->where('chapter_id', $request->chapter_id)
                        ->orWhere('chapter_id', $request->chapter_id)
                        ->where('numbering', $request->numbering);
                }),
            ],
        ],
        [
            'chapter_id.unique' => 'Lecture with similar information already exists',
        ]);

        try {
            $audio = null;
            if ($request->hasFile('audio')){
                $file = $request->audio;

                // $mp3file = new MP3File($file);//http://www.npr.org/rss/podcast.php?id=510282
                // $duration1 = $mp3file->getDurationEstimate();//(faster) for CBR only
                // $duration2 = $mp3file->getDuration();//(slower) for VBR (or CBR)

                $audio = $file->store('lectures', 'courses');     
            }

            $data = [
                'title'             => $request->title,
                'chapter_id'           => $request->chapter_id,
                'audio_url'         => $audio,
                'user_id'           => $request->user_id,
                'numbering'         => $request->numbering
            ];

            if (ELearningLecture::create($data)) {  
              return redirect('e-learning/courses/lectures/'.$request->course_id)->with('success', 'Lecture successfully created');
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Created')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function show($id)
    {
        $data = ELearningLecture::find($id);
        $course = ELearningCourse::find($data->chapter->course->id);

        if ($course->userRegisteredForThisCourse(auth()->user()->id) && !$data->hasBeenWatched()) {
            ELearningLectureAttendance::create([
                'lecture_id' => $id,
                'user_id' => auth()->user()->id
            ]);
        }
        return view('e_learning.lectures.show', compact('data', 'course'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningLecture::findOrFail($id);
        $status = ELearningLecture::status;
        $chapters = ELearningChapter::get()->pluck('title','id');
        $course = ELearningCourse::where('id', $data->chapter->course_id)->first();
        return view('e_learning.lectures.edit', compact('data', 'status', 'course', 'chapters'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'title'     => 'required|max:255',
            'audio'     => 'nullable|file|mimes:mp3,mpga|max:5120',
            'status'   => 'required',
            'numbering' => 'required|numeric',
            'chapter_id'   => [
                'required', 
                Rule::unique('e_learning_lectures')->where(function ($query) use ($request, $id) {
                    return $query
                        ->where('title', $request->title)
                        ->where('chapter_id', $request->chapter_id)
                        ->whereNotIn('id', [$id])
                        ->orWhere('chapter_id', $request->chapter_id)
                        ->where('numbering', $request->numbering)
                        ->whereNotIn('id', [$id]);
                }),
            ],
        ],
        [
            'chapter_id.unique' => 'Chapter with similar information already exists',
        ]);

        try {
            if ($data   = ELearningLecture::find($id)) {

                $audio = $data->audio_url;
                    
                if ($request->hasFile('audio')){
                    $file = $request->audio;
                
                    if(!is_null($audio)){
                        if (file_exists('uploads/courses/'.$audio)) {
                            File::delete('uploads/courses/'.$audio);
                        }
                    }
                    $audio = $file->store('lectures', 'courses');      
                }

              $lecture = [
                    'title'             => $request->title,
                    'chapter_id'        => $request->chapter_id,
                    'audio_url'         => $audio,
                    'numbering'         => $request->numbering,
                    'status'            => $request->status
                ];

                if ($data->update($lecture)) {  
                  return redirect('e-learning/courses/lectures/'.$data->chapter->course->id)->with('success', 'Lecture successfully updated');
                }
                else{
                  return redirect()->back()->withErrors('Resource NOT Updated')->withInput();
                }
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Found')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function destroy($id)
    {
        if($data = ELearningLecture::findOrFail($id)) {
            if ($data->visitsperlecture() == 0 || $data->attendancesperlecture() == 0) {
                $data->delete();
                return redirect()->back()->with('success', 'Lecture successfully deleted');
            }
            return redirect()->back()->withErrors('Operation was NOT successful. Lecture is linked');
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningLecture::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

        $datatables = app('datatables')->of($data);

        $course_id = $datatables->request->get('course');

        $data->whereIn('chapter_id',function($query) use ($course_id){
            $query->select('id')->where('course_id', $course_id)->from('e_learning_chapters');
        });

        return $datatables
        ->addColumn('user', function ($data){
            return $data->user->name;
            })
        ->addColumn('lectures', function ($data){
            return 0;
            })
        ->addColumn('_status', function ($data){
            return $data->status ? 'Available' : 'Unavailable';
            })
        ->addColumn('_chapter', function ($data){
            return $data->chapter->title;
            })
        ->addColumn('audio', function ($data){
            return '<audio src="'.asset('uploads/courses/'.$data->audio_url).'" controls></audio>';
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.lectures";
                $id = $data->id;

                $delete_rights = $data->visitsperlecture() > 0 || $data->attendancesperlecture() > 0 ? '' : 'delete_el_lectures';

                $edit_rights = 'edit_el_lectures';
                // $view_rights = 'view_el_lectures';
                return view('partials.actions', compact('entity', 'id','edit_rights', 'delete_rights'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'user','audio'])
        ->make(true);
    }

    public function newTopic($lectureId)
    {
        $data   = ELearningLecture::find($lectureId);
        $course = ELearningCourse::find($data->chapter->course_id);
        return view('e_learning.lectures.topics.create',compact('data', 'course'));
    }

    public function storeTopic(Request $request)
    {
        request()->validate([
            'audio' => 'required_without_all:description|file|mimes:mp3,mpga|max:5120',
            'description' => 'required_without_all:audio',

            'subject'   => 'required|max:255',
            'user_id'   => 'required',
            'lecture_id' => [
                'required', 
                Rule::unique('e_learning_lecture_topics')->where(function ($query) use ($request) {
                    return $query
                        ->where('subject', $request->subject)
                        ->where('lecture_id', $request->lecture_id);
                }),
            ],
        ],
        [
            'lecture_id.unique' => 'Topic with similar information already exists',
        ]);

        try {
            $audio = null;
            if ($request->hasFile('audio')){
                $file = $request->audio;
                $audio = $file->store('lecture_topics', 'courses');     
            }

            $data = [
                'subject'           => $request->subject,
                'lecture_id'        => $request->lecture_id,
                'audio_url'         => $audio,
                'user_id'           => $request->user_id,
                'description'       => $request->description
            ];

            if (ELearningLectureTopic::create($data)) {  
              return redirect()->route('e-learning.lectures.show', $request->lecture_id)->with('success', 'Topic successfully added');
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Created')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function showTopic($topicId)
    {
        $data   = ELearningLectureTopic::find($topicId);
        $course = ELearningCourse::find($data->lecture->chapter->course->id);
        return view('e_learning.lectures.topics.show',compact('data', 'course'));
    }

    public function newResponse($topicId)
    {
        $data   = ELearningLectureTopic::find($topicId);
        $course = ELearningCourse::find($data->lecture->chapter->course->id);
        return view('e_learning.lectures.topics.respond',compact('data', 'course'));
    }

    public function storeResponse(Request $request)
    {
        request()->validate([
            'audio' => 'required|file|mimes:mpga,mp2,mp2a,mp3,m2a,m3a,weba,webm,wav|max:5120',
            // 'comment' => 'required_without_all:audio',
            'user_id'   => 'required',
            'lecture_topic_id' => 'required'
        ]);

        try {
            $audio = null;
            if ($request->hasFile('audio')){
                $file = $request->audio;
                $audio = $file->store('lecture_topic_responses', 'courses');     
            }

            $topic = ELearningLectureTopic::where('id', $request->lecture_topic_id)->first();

            $data = [
                'lecture_topic_id'  => $request->lecture_topic_id,
                'audio_url'         => $audio,
                'user_id'           => $request->user_id,
                'student_id'        => $topic->student_id
            ];

            if (ELearningLectureTopicResponse::create($data)) {

                $course = ELearningCourse::findOrFail($topic->lecture->chapter->course_id);
                $body   = $course->text_message(3); 

                if (!is_null($body)) {
                    // "Hello! You have a new reply from the instructor. Call 0323200710 to listen to the reply";
                    $body = str_replace('[name]', $topic->student->full_name, $body);
                    $body = str_replace('[course_code]', $topic->lecture->chapter->course->code, $body);
                    $body = str_replace('[course_title]', $topic->lecture->chapter->course->title, $body);

                    AppHelper::instance()->sendTextMessage($topic->student->phone_number, $body);
                    
                    return redirect('e-learning/courses/lectures/'.$request->lecture_topic_id.'/topics/show')->with('success', 'Response successfully added');
                }else{
                    return redirect('e-learning/courses/lectures/'.$request->lecture_topic_id.'/topics/show')->withErrors('Response successfully added. No default message found for numbering 3');
                }              
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Created')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function subscribeTopic($topicId)
    {
        $data = ELearningLectureTopic::find($topicId);

        if (! $data->hasSuscribed()) {
            ELearningLectureTopicSubscription::create([
                'lecture_topic_id' => $topicId,
                'user_id' => auth()->user()->id
            ]);
        }
        return redirect()->back()->with('success', 'Subscription successfully');
    }

    public function likeTopic($topicId)
    {
        $data = ELearningLectureTopic::find($topicId);

        if (! $data->hasLiked()) {
            ELearningLectureTopicLike::create([
                'lecture_topic_id' => $topicId,
                'user_id' => auth()->user()->id
            ]);
        }
        return redirect()->back()->with('success', 'Topic liked!');
    }

    public function likeTopicResponse($responseId)
    {
        $data = ELearningLectureTopicResponse::find($responseId);

        if (! $data->hasLiked()) {
            ELearningLectureTopicResponseLike::create([
                'lecture_topic_response_id' => $responseId,
                'user_id' => auth()->user()->id
            ]);
        }
        return redirect()->back()->with('success', 'Response liked!');
    }


}
