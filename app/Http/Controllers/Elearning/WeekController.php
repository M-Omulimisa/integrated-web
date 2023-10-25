<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningChapter;
use App\Models\Elearning\ELearningCourse;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class WeekController extends Controller
{
    // if (!Gate::allows('add_el_chapters create
    // if (! Gate::allows('view_el_chapters show
    // if (! Gate::allows('delete_el_chapters destroy
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = ELearningCourse::find($courseId);
        return view('e_learning.weeks.index', compact('course'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId)
    {
        $status = ELearningChapter::status;
        $course = ELearningCourse::find($courseId);

        $chapter = ELearningChapter::where('course_id', $courseId)->where('summary', 'LIKE', '%week-%')->count();
        $numbering = ($chapter+1);
        $title = 'Week '.$numbering;
        return view('e_learning.weeks.create',compact('status', 'course', 'title','numbering'));
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
            'user_id'   => 'required',
            'numbering' => 'required|numeric',
            'start_date'=> 'required',
            'end_date'  => 'nullable',
            'summary'   => 'required',
            'course_id' => [
                'required', 
                Rule::unique('e_learning_chapters')->where(function ($query) use ($request) {
                    return $query
                        ->where('title', $request->title)
                        ->where('course_id', $request->course_id)
                        ->where('summary', 'LIKE', '%week-%')
                        ->orWhere('course_id', $request->course_id)
                        ->where('numbering', $request->numbering)
                        ->where('summary', 'LIKE', '%week-%');
                }),
            ],
        ],
        [
            'course_id.unique' => 'Week with similar information already exists',
        ]);

        try {
            $data = [
                'title'             => $request->title,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'summary'           => str_replace(" ", "-", strtolower($request->summary)),
                'course_id'         => $request->course_id,
                'user_id'           => $request->user_id,
                'numbering'         => $request->numbering
            ];

            if (ELearningChapter::create($data)) {  
              return redirect('e-learning/courses/weeks/'.$request->course_id)->with('success', 'Week successfully created');
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
        return view('e_learning.weeks.show', compact('data'));
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

        $chapter = ELearningChapter::where('course_id', $data->course_id)->where('summary', 'LIKE', '%week-%')->count();
        $title = 'Week '.($chapter+1);
        return view('e_learning.weeks.edit', compact('data', 'status', 'course', 'title'));
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
            'numbering' => 'required|numeric',
            'start_date'=> 'required',
            'end_date'  => 'nullable',
            'summary'   => 'required',
            'course_id'   => [
                'required', 
                Rule::unique('e_learning_chapters')->where(function ($query) use ($request, $id) {
                    return $query
                        ->where('title', $request->title)
                        ->where('course_id', $request->course_id)
                        ->where('summary', 'LIKE', '%week-%')
                        ->whereNotIn('id', [$id])
                        ->orWhere('course_id', $request->course_id)
                        ->where('numbering', $request->numbering)
                        ->where('summary', 'LIKE', '%week-%')
                        ->whereNotIn('id', [$id]);
                }),
            ],
        ],
        [
            'course_id.unique' => 'Week with similar information already exists',
        ]);

        try {

            if ($data   = ELearningChapter::find($id)) {
              $chapter = [
                    'title'             => $request->title,
                    'start_date'        => $request->start_date,
                    'end_date'          => $request->end_date,
                    'status'            => $request->status,
                    'summary'           => str_replace(" ", "-", strtolower($request->summary)),
                    'numbering'         => $request->numbering
                ];

                if ($data->update($chapter)) {  
                  return redirect('e-learning/courses/weeks/'.$data->course_id)->with('success', 'Week successfully updated');
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
        $data->where('summary', 'LIKE', '%week-%')->whereIn('course_id',function($query) use ($lecture_type){
            $query->select('id')->where('lecture_type', $lecture_type)->from('e_learning_courses');
        });

        return $datatables
        ->addColumn('user', function ($data){
            return $data->user->name;
            })
        ->addColumn('topics', function ($data){
            return 0;
            })
        ->addColumn('_status', function ($data){
            return $data->status ? 'Available' : 'Unavailable';
            })
        ->addColumn('actions', function ($data){
                $entity         = "e-learning.weeks";
                $id             = $data->id;
                $courseId             = $data->course_id;
                $edit_rights    = 'edit_el_chapters';
                $add_topics     = 'add_el_chapters';
                // $view_rights = 'view_el_chapters';
                return view('e_learning.weeks._actions', compact('entity', 'id','edit_rights','add_topics', 'courseId'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'user'])
        ->make(true);
    }

}
