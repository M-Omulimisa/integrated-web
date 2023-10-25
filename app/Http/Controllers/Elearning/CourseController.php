<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningChapter;
use App\Models\Elearning\ELearningCourseRegistration;
use App\Models\Elearning\ELearningGeneralAssignment;
use App\Models\Elearning\ELearningLectureTopic;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;

class CourseController extends Controller
{

        // deregister_el_courses deregister
        // add_el_courses create
        // view_el_courses show
        // register_el_courses register
        // delete_el_courses destroy
        // delete_el_courses massDestroy

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ELearningCourse::where('status', 'Open')->orderBy('created_at', 'DESC')->get();
        $closed = ELearningCourse::where('status', 'Closed')->orderBy('created_at', 'DESC')->get();
        return view('e_learning.courses.index', compact('data', 'closed'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status             = ELearningCourse::status;
        $enrollment_status  = ELearningCourse::enrollment_status;
        $lecture_types      = ELearningCourse::lecture_types;

        return view('e_learning.courses.create',compact('status', 'enrollment_status', 'lecture_types'));
    }

    /**
     * Store the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'             => 'required|unique:e_learning_courses,title',
            'status'            => 'required',
            'enrollment_status' => 'required',
            'code'              => 'required|unique:e_learning_courses,code',
            // 'image_banner'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'logo'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'brochure'          => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:2048',
            'lecture_type'      => 'required'
        ]);

        try {

            $image_banner = null;
            $logo = null;
            $brochure = null;

            if ($request->hasFile('image_banner')){
                $file = $request->image_banner;
                
                $data = getimagesize($file); // $width = $data[0]; $height = $data[1];
                if ($data[0] != 1000 && $data[1] != 260) {
                    return redirect()->back()->withErrors('Image banner must be 1000x260px')->withInput();
                }

                $image_banner = $file->store('image_banners', 'courses');      
            }

            if ($request->hasFile('logo')){
                $file = $request->logo;

                $data = getimagesize($file); // $width = $data[0]; $height = $data[1];
                if ($data[0] != 200 && $data[1] != 200) {
                    if(!is_null($image_banner)){
                        File::delete(base_path() . '/public/uploads/courses/'.$image_banner);
                    }
                    return redirect()->back()->withErrors('Logo must be 200x200px')->withInput();
                }

                $logo = $file->store('logos', 'courses');      
            }

            if ($request->hasFile('brochure')){
                $file = $request->brochure;
                $brochure = $file->store('brochures', 'courses');      
            }

            $data = [
                'title'             => $request->title,
                'summary'           => $request->summary,
                'description'       => $request->description,
                'content'           => $request->content,
                'audience'          => $request->audience,
                'outcomes'          => $request->outcomes,
                'user_id'           => $request->user_id,
                'image_banner'      => $image_banner,
                // 'video_url'         => $request->video_url,
                'about_certificates'=> $request->about_certificates,
                'start_date'        => $request->start_date,
                'start_time'        => $request->start_time,
                'end_date'          => $request->end_date,
                'end_time'          => $request->end_time,
                'duration_in_days'  => $request->duration_in_days,
                'duration_in_weeks' => $request->duration_in_weeks,
                'team'              => $request->team,
                // 'operations'        => $request->operations,
                // 'logo'              => $logo,
                // 'brochure'          => $brochure,
                'status'            => $request->status,
                'read_only_mode'    => $request->read_only_mode == 'on' ? true : false,
                'enrollment_status' => $request->enrollment_status,
                'code'              => $request->code,
                'lecture_type'      => $request->lecture_type,
                'certificate_url'   => $request->certificate_url,
                'status_archived_at'=> $request->status == 'archived' ? Carbon::now() : null,
                'enrollment_closed_at' => $request->enrollment_status == 'closed' ? Carbon::now() : null,
            ];

            if (ELearningCourse::create($data)) {  
              return redirect()->route('e-learning.courses.index')->with('success', 'Course successfully created');
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
        $data = ELearningCourse::find($id);
        return view('e_learning.courses.show', compact('data'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ELearningCourse::findOrFail($id);

        $status             = ELearningCourse::status;
        $enrollment_status  = ELearningCourse::enrollment_status;
        $lecture_types      = ELearningCourse::lecture_types;

        return view('e_learning.courses.edit', compact('data', 'status', 'enrollment_status', 'lecture_types'));
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
        try {

            $this->validate($request, [
                'title'             => 'required|unique:e_learning_courses,title,' . $id,
                'status'            => 'required',
                'enrollment_status' => 'required',
                'code'              => 'required|unique:e_learning_courses,code,' . $id,
                'lecture_type'      => 'required'
            ]);

            if ($data   = ELearningCourse::find($id)) {
              $member = [
                    'title'             => $request->title,
                    'summary'           => $request->summary,
                    'description'       => $request->description,
                    'content'           => $request->content,
                    'audience'          => $request->audience,
                    'outcomes'          => $request->outcomes,
                    // 'video_url'         => $request->video_url,
                    'about_certificates'=> $request->about_certificates,
                    'start_date'        => $request->start_date,
                    'start_time'        => $request->start_time,
                    'end_date'          => $request->end_date,
                    'end_time'          => $request->end_time,
                    'duration_in_days'  => $request->duration_in_days,
                    'duration_in_weeks' => $request->duration_in_weeks,
                    'team'              => $request->team,
                    // 'operations'        => $request->operations,
                    'status'            => $request->status,
                    'read_only_mode'    => $request->read_only_mode == 'on' ? true : false,
                    'enrollment_status' => $request->enrollment_status,
                    'code'              => $request->code,
                    'lecture_type'      => $request->lecture_type,
                    'certificate_url'   => $request->certificate_url,
                    'status_archived_at'=> $request->status == 'archived' ? Carbon::now() : null,
                    'enrollment_closed_at' => $request->enrollment_status == 'closed' ? Carbon::now() : null,
                    // 'user_id'           => $request->user_id,
                    // 'image_banner'      => $image_banner,
                    // 'logo'              => $logo,
                    // 'brochure'          => $brochure,
                ];

                if ($data->update($member)) {  
                  return redirect()->route('e-learning.courses.show', $id)->with('success', 'Course successfully updated');
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
        if($data = ELearningCourse::findOrFail($id)) {

            // code
        } 
        else {
            return redirect()->back()->withErrors('Operation was NOT successful');
        }
    }

    /**
     * Delete all selected resources at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('data_ids')) {
            $data_id_array = explode(",", $request->input('data_ids')); 
            if(!empty($data_id_array)) {
            foreach($data_id_array as $id) {
                if($data = ELearningCourse::find($id)) {

                    // code
                }
            }
            }
        }
    }

    /**
     * Change photo form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editFile($id, $file)
    {
        $data = ELearningCourse::findOrFail($id);
        return view('e_learning.courses.edit_files', compact('data', 'file'));
    }

    public function updateFile(Request $request, $id, $file_name)
    {
        if ($course = ELearningCourse::findOrFail($id)) {
            if ($request->hasFile($file_name)){

                if ($file_name == "logo") {
                    $accepted_file_type = 'jpeg,png,jpg';
                    $width = 200;
                    $height = 200;
                    $type = 'image|';
                }
                elseif ($file_name == "image_banner") {
                    $accepted_file_type = 'jpeg,png,jpg';
                    $width = 1000;
                    $height = 260;
                    $type = 'image|';
                }
                elseif ($file_name == "brochure") {
                    $accepted_file_type = 'pdf,doc,docx,ppt,pptx';
                    $type = '';
                }


                $this->validate($request, [
                    $file_name => 'required|'.$type.'mimes:'.$accepted_file_type.'|max:2048',
                ]);

                $file = $request->$file_name;

                if (isset($width) && isset($height)) {
                    $data = getimagesize($file); // $width = $data[0]; $height = $data[1];
                    if ($data[0] != $width && $data[1] != $height) {
                        return redirect()->back()->withErrors('File must be '.$width.'x'.$height.'px')->withInput();
                    }
                }  
                
                if(!is_null($course->$file_name)){
                    if (file_exists(public_path() . '/uploads/courses/'.$course->$file_name)) {
                        File::delete(base_path() . '/public/uploads/courses/'.$course->$file_name);
                    }
                }
                $filepath = $file->store($file_name.'s', 'courses');      
            }
            else{
                    $filepath = null;
            }
        }
        else{
            return redirect()->back()->withErrors('Resource NOT Found')->withInput();
        }
        
        ELearningCourse::where('id',$id)->update([$file_name => $filepath]); 
        return redirect()->route('e-learning.courses.show',$id)->with('success','File updated successfully');
    }

    public function deleteFile($id, $file_name)
    {
        if ($course = ELearningCourse::findOrFail($id)) {
            if(!is_null($course->$file_name)){
                if (file_exists(public_path() . '/uploads/courses/'.$course->$file_name)) {
                    File::delete(base_path() . '/public/uploads/courses/'.$course->$file_name);
                }
            }
        }
        else{
            return redirect()->back()->withErrors('Resource NOT Found')->withInput();
        }
        
        ELearningCourse::where('id',$id)->update([$file_name => null]); 
        return redirect()->route('e-learning.courses.show',$id)->with('success','File deleted successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contents($courseId)
    {
        $course = ELearningCourse::findorFail($courseId);

        if (!$course->userRegisteredForThisCourse(auth()->user()->id) && auth()->user()->id != $course->user_id) {
            return redirect()->route('e-learning.courses.index')->withErrors('Your account does not exists on this course register. Contact Admin');
        }

        if ($course->lecture_type == "Weekly") {
            $chapters = ELearningChapter::where('course_id', $courseId)->where('summary', 'LIKE', '%week-%')->orderBy('numbering', 'ASC')->get();
        }else{
            $chapters = ELearningChapter::where('course_id', $courseId)->orderBy('numbering', 'ASC')->get();
        }

        $general_questions = ELearningGeneralAssignment::where('course_id', $courseId)->get();

        $unanswered_questions = count($course->unansweredQuestions());

        return view('e_learning.courses.contents', compact('course', 'chapters', 'general_questions', 'unanswered_questions'));
    }

    public function register($courseId)
     {
        if (ELearningCourse::find($courseId)) {

            if (ELearningCourseRegistration::where('user_id', auth()->user()->id)->where('course_id', $courseId)->where('status', true)->first()) {
                return redirect()->back()->withErrors('Your account already exists on this course register. Contact Admin');
            }

            $register = ELearningCourseRegistration::create([
                'user_id'   => auth()->user()->id,
                'course_id' => $courseId,
                'role_id'   => auth()->user()->roles->pluck('id')->first() ?? null
            ]); 
            if ($register) {
                return redirect()->route('e-learning.courses.show',$courseId)->with('success','You have registered for this course');
            }else{
                return redirect()->back()->withErrors('Failed to register. Contact Admin');
            }
        }
        else{
            return redirect()->back()->withErrors('Resource NOT Found');
        }
     } 

     public function deregister($courseId)
     {
        if (ELearningCourse::find($courseId)) {

            if ($register = ELearningCourseRegistration::where('user_id', auth()->user()->id)->where('course_id', $courseId)->orderBy('id', 'DESC')->limit(1)->first()) {
                if ($register->status) {
                    $deregister = $register->update([
                        'user_id'   => auth()->user()->id,
                        'course_id' => $courseId,
                        'role_id'   => auth()->user()->roles->pluck('id')->first() ?? null,
                        'status'    => false
                    ]); 

                    if ($deregister) {
                        return redirect()->route('e-learning.courses.show',$courseId)->with('success','You have deregistered for this course');
                    }else{
                        return redirect()->back()->withErrors('Failed to register. Contact Admin');
                    }
                }else{
                    return redirect()->back()->withErrors('Your account already deregistered for this course. Contact Admin');
                }
            }else{
                return redirect()->back()->withErrors('Your account does not exists on this course register. Contact Admin');
            }
        }
        else{
            return redirect()->back()->withErrors('Resource NOT Found');
        }
     } 

}
