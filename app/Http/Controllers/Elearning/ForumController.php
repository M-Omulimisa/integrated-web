<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningForumTopic;
use App\Models\Elearning\ELearningForumTopicResponse;
use App\Models\Elearning\ELearningForumTopicSubscription;
use App\Models\Elearning\ELearningForumTopicLike;
use App\Models\Elearning\ELearningForumTopicResponseLike;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Helpers\MP3File;
use Illuminate\Validation\Rule;

class ForumController extends Controller
{
    // if (! Gate::allows('add_el_forum_topic_likes likeTopicResponse
    // if (! Gate::allows('add_el_forum_topic_likes likeTopic
    // if (! Gate::allows('add_el_forum_topic_subscriptions subscribeTopic
    // if (!Gate::allows('add_el_forum_topic_responses newResponse
    // if (! Gate::allows('delete_el_forums destroy
    // if (!Gate::allows('view_el_forum_topics show
    // if (!Gate::allows('add_el_forum_topics create

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = ELearningCourse::findorFail($courseId);
        $topics = ELearningForumTopic::where('course_id', $courseId)->orderBy('created_at','DESC')->get();
        return view('e_learning.forum.index', compact('course', 'topics'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId)
    {
        $course = ELearningCourse::find($courseId);
        return view('e_learning.forum.create',compact('course'));
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
            'audio' => 'required_without_all:description|file|mimes:mp3,mpga|max:5120',
            'description' => 'required_without_all:audio',

            'subject'   => 'required|max:255',
            'user_id'   => 'required',
            'course_id' => [
                'required', 
                Rule::unique('e_learning_forum_topics')->where(function ($query) use ($request) {
                    return $query
                        ->where('subject', $request->subject)
                        ->where('course_id', $request->course_id);
                }),
            ],
        ],
        [
            'course_id.unique' => 'Topic with similar information already exists',
        ]);

        try {
            $audio = null;
            if ($request->hasFile('audio')){
                $file = $request->audio;
                $audio = $file->store('forum_topics', 'courses');     
            }

            $data = [
                'subject'           => $request->subject,
                'course_id'        => $request->course_id,
                'audio_url'         => $audio,
                'user_id'           => $request->user_id,
                'description'       => $request->description
            ];

            if (ELearningForumTopic::create($data)) {  
              return redirect('e-learning/courses/forums/'.$request->course_id)->with('success', 'Topic successfully added');
            }
            else{
              return redirect()->back()->withErrors('Resource NOT Created')->withInput();
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function show($topicId)
    {
        $data   = ELearningForumTopic::findOrFail($topicId);
        $course = ELearningCourse::find($data->course_id);
        return view('e_learning.forum.show',compact('data', 'course'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 
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
        // 
    }

    public function destroy($id)
    {
        if($data = ELearningForum::findOrFail($id)) {

            // code
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    public function massData(Request $request)
    {
        // 
    }

    public function newResponse($topicId)
    {
        $data   = ELearningForumTopic::find($topicId);
        $course = ELearningCourse::find($data->course_id);
        return view('e_learning.forum.respond',compact('data', 'course'));
    }

    public function storeResponse(Request $request)
    {
        request()->validate([
            'audio' => 'required_without_all:comment|file|mimes:mp3,mpga|max:5120',
            'comment' => 'required_without_all:audio',
            'user_id'   => 'required',
            'forum_topic_id' => 'required'
        ]);

        try {
            $audio = null;
            if ($request->hasFile('audio')){
                $file = $request->audio;
                $audio = $file->store('forum_topic_responses', 'courses');     
            }

            $data = [
                'forum_topic_id'  => $request->forum_topic_id,
                'audio_url'         => $audio,
                'user_id'           => $request->user_id,
                'comment'           => $request->comment
            ];

            if (ELearningForumTopicResponse::create($data)) {  
              return redirect('e-learning/courses/forums/'.$request->forum_topic_id.'/topics/show')->with('success', 'Response successfully added');
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
        $data = ELearningForumTopic::find($topicId);

        if (! $data->hasSuscribed()) {
            ELearningForumTopicSubscription::create([
                'forum_topic_id' => $topicId,
                'user_id' => auth()->user()->id
            ]);
        }
        return redirect()->back()->with('success', 'Subscription successfully');
    }

    public function likeTopic($topicId)
    {
        $data = ELearningForumTopic::find($topicId);

        if (! $data->hasLiked()) {
            ELearningForumTopicLike::create([
                'forum_topic_id' => $topicId,
                'user_id' => auth()->user()->id
            ]);
        }
        return redirect()->back()->with('success', 'Topic liked!');
    }

    public function likeTopicResponse($responseId)
    {
        $data = ELearningForumTopicResponse::find($responseId);

        if (! $data->hasLiked()) {
            ELearningForumTopicResponseLike::create([
                'forum_topic_response_id' => $responseId,
                'user_id' => auth()->user()->id
            ]);
        }
        return redirect()->back()->with('success', 'Response liked!');
    }


}
