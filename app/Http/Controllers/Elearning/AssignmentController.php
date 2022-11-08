<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningChapter;
use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningAssignment;
use App\Models\Elearning\ELearningAssignmentResponse;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Helpers\MP3File;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    // view_el_lectures show
    // add_el_lectures create        
    // delete_el_lectures destroy

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = ELearningCourse::find($courseId);
        return view('e_learning.assignments.index', compact('course'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId)
    {
        $status     = ELearningAssignment::status;
        $course     = ELearningCourse::find($courseId);
        
        if ($course->lecture_type == "Weekly") {
            $chapters   = ELearningChapter::where('course_id',$courseId)->whereNull('summary')->get()->pluck('title','id');
        }else{
            $chapters   = ELearningChapter::where('course_id',$courseId)->get()->pluck('title','id');
        }
        $types = ['Multi-choice'=>'Multi choice', 'True-or-False'=>'True or False'];

        return view('e_learning.assignments.create',compact('status', 'course', 'chapters', 'types'));
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
            'type'      => 'required',
            'answer'    => 'required',
            'chapter_id' => [
                'required', 
                Rule::unique('e_learning_assignments')->where(function ($query) use ($request) {
                    return $query
                        ->where('title', $request->title)
                        ->where('chapter_id', $request->chapter_id)
                        ->orWhere('chapter_id', $request->chapter_id)
                        ->where('numbering', $request->numbering);
                }),
            ],
        ],
        [
            'chapter_id.unique' => 'Assignment with similar information already exists',
        ]);

        try {
            $audio = null;
            if ($request->hasFile('audio')){
                $file = $request->audio;

                // $mp3file = new MP3File($file);//http://www.npr.org/rss/podcast.php?id=510282
                // $duration1 = $mp3file->getDurationEstimate();//(faster) for CBR only
                // $duration2 = $mp3file->getDuration();//(slower) for VBR (or CBR)

                $audio = $file->store('assignments', 'courses');     
            }

            $data = [
                'title'             => $request->title,
                'chapter_id'           => $request->chapter_id,
                'audio_url'         => $audio,
                'user_id'           => $request->user_id,
                'numbering'         => $request->numbering,
                'type'              => $request->type,
                'answer'            => $request->answer
            ];

            if (ELearningAssignment::create($data)) {  
              return redirect('e-learning/courses/assignments/'.$request->course_id)->with('success', 'Assignment successfully created');
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
        $data = ELearningAssignment::find($id);
        $course = ELearningCourse::find($data->chapter->course->id);
        return view('e_learning.assignments.show', compact('data', 'course'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningAssignment::findOrFail($id);
        $status = ELearningAssignment::status;
        $chapters = ELearningChapter::get()->pluck('title','id');
        $course = ELearningCourse::where('id', $data->chapter->course_id)->first();
        $types = ['Multi-choice'=>'Multi choice', 'True-or-False'=>'True or False'];

        return view('e_learning.assignments.edit', compact('data', 'status', 'course', 'chapters', 'types'));
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
            'type'      => 'required',
            'answer'    => 'required',
            'chapter_id'   => [
                'required', 
                Rule::unique('e_learning_assignments')->where(function ($query) use ($request, $id) {
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
            if ($data   = ELearningAssignment::find($id)) {

                $audio = $data->audio_url;
                    
                if ($request->hasFile('audio')){
                    $file = $request->audio;
                
                    if(!is_null($audio)){
                        if (file_exists('uploads/courses/'.$audio)) {
                            File::delete('uploads/courses/'.$audio);
                        }
                    }
                    $audio = $file->store('assignments', 'courses');      
                }

              $lecture = [
                    'title'             => $request->title,
                    'chapter_id'        => $request->chapter_id,
                    'audio_url'         => $audio,
                    'numbering'         => $request->numbering,
                    'status'            => $request->status,
                    'type'              => $request->type,
                    'answer'            => $request->answer
                ];

                if ($data->update($lecture)) {  
                  return redirect('e-learning/courses/assignments/'.$data->chapter->course->id)->with('success', 'Assignment successfully updated');
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
        if($data = ELearningAssignment::findOrFail($id)) {

            // code
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningAssignment::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

        $datatables = app('datatables')->of($data);

        $course_id = $datatables->request->get('course');

        $data->whereIn('chapter_id',function($query) use ($course_id){
            $query->select('id')->where('course_id', $course_id)->from('e_learning_chapters');
        });

        return $datatables
        ->addColumn('user', function ($data){
            return $data->user->name;
            })
        ->addColumn('assignments', function ($data){
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
                $entity = "e-learning.assignments";
                $id = $data->id;
                $edit_rights = 'edit_el_lectures';
                // $view_rights = 'view_el_lectures';
                return view('partials.actions', compact('entity', 'id','edit_rights'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'user','audio'])
        ->make(true);
    }
}
