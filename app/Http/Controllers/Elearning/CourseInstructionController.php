<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningInstruction;
use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningCourseInstruction;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Helpers\MP3File;
use Illuminate\Validation\Rule;

class CourseInstructionController extends Controller
{
    // delete_el_course_instructions destroy
    // view_el_course_instructions show
    // add_el_course_instructions create

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = ELearningCourse::find($courseId);
        $instructions = ELearningInstruction::orderBy('numbering', 'ASC')->get();
        return view('e_learning.instructions.instructions', compact('course', 'instructions'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId, $instructionId)
    {
        $course     = ELearningCourse::find($courseId);
        $instruction   = ELearningInstruction::where('id', $instructionId)->first();

        return view('e_learning.instructions.create',compact('course', 'instruction'));
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
            'audio'     => 'required|file|mimes:mp3,mpga|max:5120',
            'course_id' => 'required',
            'instruction_id' => [
                'required', 
                Rule::unique('e_learning_course_instructions')->where(function ($query) use ($request) {
                    return $query
                        ->where('course_id', $request->course_id)
                        ->where('instruction_id', $request->instruction_id);
                }),
            ],
        ],
        [
            'instruction_id.unique' => 'Instruction already exists',
        ]);

        try {
            $audio = null;
            if ($request->hasFile('audio')){
                $file = $request->audio;

                $audio = $file->store('instructions', 'courses');     
            }

            $data = [
                'instruction_id'    => $request->instruction_id,
                'audio_url'         => $audio,
                'course_id'         => $request->course_id
            ];

            if (ELearningCourseInstruction::create($data)) {  
              return redirect('e-learning/courses/course-instructions/'.$request->course_id)->with('success', 'Instruction successfully created');
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
        $data = ELearningCourseInstruction::find($id);
        $course = ELearningCourse::find($data->chapter->course->id);
        return view('e_learning.instructions.show', compact('data', 'course'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $courseId, $instructionId)
    {
        $data = ELearningCourseInstruction::findOrFail($id);
        $instruction = ELearningInstruction::where('id', $instructionId)->first();
        $course = ELearningCourse::where('id', $courseId)->first();
        return view('e_learning.instructions.edit', compact('data', 'course', 'instruction'));
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
            'audio'     => 'nullable|file|mimes:mp3,mpga|max:5120',
            'course_id' => 'required',
            'instruction_id'   => [
                'required', 
                Rule::unique('e_learning_course_instructions')->where(function ($query) use ($request, $id) {
                    return $query
                        ->where('course_id', $request->course_id)
                        ->where('instruction_id', $request->instruction_id)
                        ->whereNotIn('id', [$id]);
                }),
            ],
        ],
        [
            'instruction_id.unique' => 'Chapter with similar information already exists',
        ]);

        try {
            if ($data   = ELearningCourseInstruction::find($id)) {

                $audio = $data->audio_url;
                    
                if ($request->hasFile('audio')){
                    $file = $request->audio;
                
                    if(!is_null($audio)){
                        if (file_exists('uploads/courses/'.$audio)) {
                            File::delete('uploads/courses/'.$audio);
                        }
                    }
                    $audio = $file->store('instructions', 'courses');      
                }

              $instruction = [
                    'audio_url'         => $audio,
                ];

                if ($data->update($instruction)) {  
                  return redirect('e-learning/courses/course-instructions/'.$data->course_id)->with('success', 'Instruction successfully updated');
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

    public function destroy($id, $courseId, $instructionId)
    {
        if($data = ELearningCourseInstruction::findOrFail($id)) {
            $data->delete();
            return redirect()->back()->with('success', 'Operation was successful');
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningCourseInstruction::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

        $datatables = app('datatables')->of($data);

        $course_id = $datatables->request->get('course');

        return $datatables
        ->addColumn('instruction', function ($data){
            return $data->instruction->instruction;
            })
        ->addColumn('audio', function ($data){
            return '<audio src="'.asset('uploads/courses/'.$data->audio_url).'" controls></audio>';
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.lectures";
                $id = $data->id;
                $edit_rights = 'edit_el_course_instructions';
                // $view_rights = 'view_el_course_instructions';
                return view('partials.actions', compact('entity', 'id','edit_rights'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'user','audio', 'instruction'])
        ->make(true);
    }
}
