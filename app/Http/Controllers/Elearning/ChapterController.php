<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningChapter;
use App\Models\Elearning\ELearningCourse;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class ChapterController extends Controller
{
    // delete_el_chapters destroy
    // add_el_chapters create
    // view_el_chapters show

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = ELearningCourse::find($courseId);
        return view('e_learning.chapters.index', compact('course'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId, $weekId=null)
    {
        $status = ELearningChapter::status;
        $course = ELearningCourse::find($courseId);

        if (!is_null($weekId)) {
            $chapter    = ELearningChapter::where('course_id', $courseId)->where('parent_id', $weekId)->count();
        }else{
            $chapter    = ELearningChapter::where('course_id', $courseId)->where('summary', 'NOT LIKE', '%week-%')->count();
        }

        $numbering  = ($chapter+1);
        $title      = 'Chapter '.$numbering;

        $weeks      = ELearningChapter::where('id', $weekId)->where('summary','LIKE','%week-%')->where('course_id', $courseId)->whereIn('course_id',function($query){
                            $query->select('id')->where('lecture_type', 'Weekly')->from('e_learning_courses');
                        })->get()->pluck('title','id');

        return view('e_learning.chapters.create',compact('status', 'course', 'title','numbering', 'weeks'));
    }

    /**
     * Store the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $course = ELearningCourse::find($request->course_id);

        request()->validate([
            'title'     => 'required|max:255',
            'summary'   => 'nullable',
            'user_id'   => 'required',
            'numbering' => 'required|numeric',
            'parent_id' => $course->lecture_type == 'Weekly' ? 'required|exists:e_learning_chapters,id' : 'nullable',
            'course_id' => [
                'required', 
                Rule::unique('e_learning_chapters')->where(function ($query) use ($request) {
                    return $query
                        ->where('title', $request->title)
                        ->where('course_id', $request->course_id)
                        ->orWhere('course_id', $request->course_id)
                        ->where('numbering', $request->numbering)
                        ->whereNull('summary')
                        ->whereNotNull('parent_id')
                        ->orWhere('course_id', $request->course_id)
                        ->where('numbering', $request->numbering)
                        ->whereNotNull('summary')
                        ->whereNull('parent_id')
                        ->where('summary', 'NOT LIKE', '%week-%');
                }),
            ],
        ],
        [
            'course_id.unique' => 'Chapter with similar information already exists',
        ]);

        try {
            $data = [
                'title'             => $request->title,
                'summary'           => $request->summary,
                'course_id'         => $request->course_id,
                'parent_id'         => $request->parent_id,
                'user_id'           => $request->user_id,
                'numbering'         => $request->numbering
            ];

            if (ELearningChapter::create($data)) {  
              return redirect('e-learning/courses/chapters/'.$request->course_id)->with('success', 'Chapter successfully created');
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
        $data = ELearningChapter::find($id);
        return view('e_learning.chapters.show', compact('data'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningChapter::findOrFail($id);
        $status = ELearningChapter::status;
        $course = ELearningCourse::where('id', $data->course_id)->first();

        $weeks      = ELearningChapter::where('id',$data->parent_id)->where('summary','LIKE','%week-%')->where('course_id', $course->id)->whereIn('course_id',function($query){
                            $query->select('id')->where('lecture_type', 'Weekly')->from('e_learning_courses');
                        })->get()->pluck('title','id');
        return view('e_learning.chapters.edit', compact('data', 'status', 'course', 'weeks'));
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
        $course = ELearningCourse::find($request->course_id);
        
        request()->validate([
            'title'     => 'required|max:255',
            'summary'   => 'nullable',
            'numbering' => 'required|numeric',
            'parent_id' => $course->lecture_type == 'Weekly' ? 'required|exists:e_learning_chapters,id' : 'nullable',
            'course_id'   => [
                'required', 
                Rule::unique('e_learning_chapters')->where(function ($query) use ($request, $id) {
                    return $query
                        ->where('title', $request->title)
                        ->where('course_id', $request->course_id)
                        ->whereNotIn('id', [$id])
                        ->orWhere('course_id', $request->course_id)
                        ->where('numbering', $request->numbering)
                        ->whereNull('summary')
                        ->whereNotNull('parent_id')
                        ->whereNotIn('id', [$id])
                        ->orWhere('course_id', $request->course_id)
                        ->where('numbering', $request->numbering)
                        ->whereNotNull('summary')
                        ->whereNull('parent_id')
                        ->where('summary', 'NOT LIKE', '%week-%')
                        ->whereNotIn('id', [$id]);
                }),
            ],
        ],
        [
            'course_id.unique' => 'Chapter with similar information already exists',
        ]);

        try {

            if ($data   = ELearningChapter::find($id)) {
              $chapter = [
                    'title'             => $request->title,
                    'summary'           => $request->summary,
                    'status'            => $request->status,
                    'parent_id'         => $request->parent_id,
                    'numbering'         => $request->numbering
                ];

                if ($data->update($chapter)) {  
                  return redirect('e-learning/courses/chapters/'.$data->course_id)->with('success', 'Chapter successfully updated');
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
        if($data = ELearningChapter::findOrFail($id)) {

            // code
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningChapter::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ])->orderBy('numbering', 'ASC');

        $datatables = app('datatables')->of($data);

        $data->where('course_id', $datatables->request->get('course'));

        $lecture_type = $datatables->request->get('lecture_type');
        if ($lecture_type == "Weekly") {
            $data->whereNull('summary');
        }

        return $datatables
        ->addColumn('user', function ($data){
            return $data->user->name;
            })
        ->addColumn('lectures', function ($data){
            return count($data->lectures);
            })
        ->addColumn('assignments', function ($data){
            return count($data->assignments);
            })
        ->addColumn('_status', function ($data){
            return $data->status ? 'Available' : 'Unavailable';
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.chapters";
                $id = $data->id;
                $edit_rights = 'edit_el_chapters';
                // $view_rights = 'view_el_chapters';
                return view('partials.actions', compact('entity', 'id','edit_rights'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'user'])
        ->make(true);
    }

}
