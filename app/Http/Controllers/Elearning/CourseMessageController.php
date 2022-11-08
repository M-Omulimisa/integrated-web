<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningMessage;
use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningCourseMessage;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Helpers\MP3File;
use Illuminate\Validation\Rule;

class CourseMessageController extends Controller
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
        $messages = ELearningMessage::orderBy('numbering', 'ASC')->get();
        return view('e_learning.messages.messages', compact('course', 'messages'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId, $messageId)
    {
        $course     = ELearningCourse::find($courseId);
        $message   = ELearningMessage::where('id', $messageId)->first();

        return view('e_learning.messages.create',compact('course', 'message'));
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
            'course_id' => 'required',
            'text_message' => 'required',
            'message_id' => [
                'required', 
                Rule::unique('e_learning_course_messages')->where(function ($query) use ($request) {
                    return $query
                        ->where('course_id', $request->course_id)
                        ->where('message_id', $request->message_id);
                }),
            ],
        ],
        [
            'message_id.unique' => 'Message already exists',
        ]);

        try {

            $data = [
                'message_id'    => $request->message_id,
                'course_id'         => $request->course_id,
                'text_message' => $request->text_message,
            ];

            if (ELearningCourseMessage::create($data)) {  
              return redirect('e-learning/courses/course-messages/'.$request->course_id)->with('success', 'Message successfully created');
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
        $data = ELearningCourseMessage::find($id);
        $course = ELearningCourse::find($data->chapter->course->id);
        return view('e_learning.messages.show', compact('data', 'course'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $courseId, $messageId)
    {
        $data = ELearningCourseMessage::findOrFail($id);
        $message = ELearningMessage::where('id', $messageId)->first();
        $course = ELearningCourse::where('id', $courseId)->first();
        return view('e_learning.messages.edit', compact('data', 'course', 'message'));
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
            'course_id' => 'required',
            'message_id'   => [
                'required', 
                Rule::unique('e_learning_course_messages')->where(function ($query) use ($request, $id) {
                    return $query
                        ->where('course_id', $request->course_id)
                        ->where('message_id', $request->message_id)
                        ->whereNotIn('id', [$id]);
                }),
            ],
        ],
        [
            'message_id.unique' => 'Similar information already exists',
        ]);

        try {
            if ($data   = ELearningCourseMessage::find($id)) {

              $message = [
                    'text_message' => $request->text_message,
                ];

                if ($data->update($message)) {  
                  return redirect('e-learning/courses/course-messages/'.$data->course_id)->with('success', 'Message successfully updated');
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

    public function destroy($id, $courseId, $messageId)
    {
        if($data = ELearningCourseMessage::findOrFail($id)) {
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
        $data = ELearningCourseMessage::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

        $datatables = app('datatables')->of($data);

        $course_id = $datatables->request->get('course');

        return $datatables
        ->addColumn('message', function ($data){
            return $data->message->text_message;
            })
        ->addColumn('_message', function ($data){
            return $data->text_message;
            })
        ->addColumn('actions', function ($data){
                $entity = "e-learning.lectures";
                $id = $data->id;
                $edit_rights = 'edit_el_course_instructions';
                // $view_rights = 'view_el_course_instructions';
                return view('partials.actions', compact('entity', 'id','edit_rights'))->render();
           
            })
        ->rawColumns(['check', 'actions', 'user','_message', 'message'])
        ->make(true);
    }
}
