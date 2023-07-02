<?php

namespace App\Http\Controllers\Elearning;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningStudent;
use App\Models\Elearning\ELearningStudentEnrollment;
use App\Models\Elearning\ELearningLectureAttendance;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use AppHelper;

use Illuminate\Validation\Rule;

class CourseStudentsController extends Controller
{

    // add_el_students destroy
    // view_el_students show
    // add_el_students store
    // add_el_students create

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = ELearningCourse::find($courseId);
        $students = ELearningStudentEnrollment::where('course_id', $courseId)->whereNull('removed_at')->orderBy('created_at', 'DESC')->get();
        return view('e_learning.enrolled_students.listing', compact('course', 'students'));
    } 

       /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($courseId)
    {
        $course     = ELearningCourse::find($courseId);

        return view('e_learning.enrolled_students.create',compact('course'));
    }

    /**
     * Store the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store($courseId, $studentId)
    {
        try {

            if ($this->studentHasActiveCourse($studentId)) {
                return redirect()->back()->withErrors('Student is currently enrolled for an Active course');
            }

            $student = ELearningStudentEnrollment::where('course_id', $courseId)->where('student_id', $studentId)->first();

            if ($student) {
                if (!is_null($student->removed_at)) {
                    $enrollment = $student->update([
                        'removed_at' => null
                    ]);
                }else{
                    return redirect()->back()->withErrors('Student already enrolled to this course');
                }
            }else{
                $enrollment = ELearningStudentEnrollment::create([
                    'course_id' => $courseId,
                    'student_id'=> $studentId,
                    'added_by'  => auth()->user()->id
                ]);
            }

            if (isset($enrollment) && $enrollment) {
                $course = ELearningCourse::findOrFail($courseId);
                $body   = $course->text_message(1); 

                if (!is_null($body)) {
                    // "Hello ".$enrollment->student->full_name.", You have been enrolled for ".$enrollment->course->code." ".$enrollment->course->title.". Call ".config('ait.voicenumber')." to get started";
                    $body = str_replace('[name]', $enrollment->student->full_name, $body);
                    $body = str_replace('[course_code]', $course->code, $body);
                    $body = str_replace('[course_title]', $course->title, $body);

                    AppHelper::instance()->sendTextMessage($enrollment->student->phone_number, $body);

                    return redirect()->back()->with('success', 'Student successfully enrolled');
                }else{
                    return redirect()->back()->withErrors('Student successfully enrolled. No default message found for numbering 2');
                }
            }else{
                return redirect()->back()->withErrors('Operation failed');
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function show($courseId, $studentId)
    {
        $course = ELearningCourse::find($courseId);

        return view('e_learning.enrolled_students.show', compact('course'));
    }

    public function destroy($courseId, $studentId)
    {
        try {
            $enrollment = ELearningStudentEnrollment::where('course_id', $courseId)->where('student_id', $studentId)->first();

            if (! $enrollment) {
                return redirect()->back()->withErrors('Student is not enrolled to this course');
            }else{

                $student = ELearningStudent::where('id', $studentId)->first();
                $course = ELearningCourse::where('id', $courseId)->first();

                $student_has_lecture = ELearningLectureAttendance::where('student_id', $studentId)
                                        ->whereIn('lecture_id',function($query) use ($courseId){
                                            $query->select('id')->whereIn('chapter_id',function($query) use ($courseId){
                                                $query->select('id')->whereIn('course_id',function($query) use ($courseId){
                                                    $query->select('id')->where('id', $courseId)->from('e_learning_courses');
                                                })->from('e_learning_chapters');
                                            })->from('e_learning_lectures');
                                        })->first();

                if ($student_has_lecture) {
                    $enrollment->update([
                        'removed_at' => Carbon::now(),
                    ]);                    
                }else{
                    $enrollment->delete();                    
                }

                $course = ELearningCourse::findOrFail($courseId);
                $body   = $course->text_message(2); 

                if (!is_null($body)) {
                    // "Hello ".$student->full_name.", Your enrollment for ".$course->code." ".$course->title." has been cancelled";
                    $body = str_replace('[name]', $student->full_name, $body);
                    $body = str_replace('[course_code]', $course->code, $body);
                    $body = str_replace('[course_title]', $course->title, $body);

                    AppHelper::instance()->sendTextMessage($student->phone_number, $body);
                    
                    return redirect()->back()->with('success', 'Enrollment successfully cancelled');
                }else{
                    return redirect()->back()->withErrors('Enrollment successfully cancelled. No default message found for numbering 1');
                }
            }

        } catch (\Throwable $r) {
            return redirect()->back()->withErrors($r->getMessage())->withInput();
        } 
    }

    public function massData(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningStudent::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

        $datatables = app('datatables')->of($data);

        $courseId = $datatables->request->get('course');
        $data->whereNotIn('id',function($query) use ($courseId){
                $query->select('student_id')->where('course_id', $courseId)->from('e_learning_student_enrollments');
            })->orWhereIn('id',function($query) use ($courseId){
                $query->select('student_id')->where('course_id', $courseId)->whereNotNull('removed_at')->from('e_learning_student_enrollments');
            });

        return $datatables
        ->addColumn('check', function ($data){
            return '<input type="checkbox" value="'.$data->id.'" class="deleteRow" />';
            })
        ->addColumn('profile', function ($data){
            $picture = is_null($data->picture) ? 'uploads/profile_pics/default.png' : 'uploads/'.$data->picture; 
            return '<div class="media mt-0">
                        <img class="avatar-lg rounded-circle mr-3" src="'.asset($picture).'" alt="Img">
                            <div class="media-body">
                                <div class="d-md-flex align-items-center">
                                    <h4 class="mb-1">
                                        '.$data->full_name.'
                                    </h4>
                                </div>
                                <p class="mb-0">
                                <span class="text-muted">Gender:</span> '.$data->gender.'<br/>
                                <span class="text-muted">Age Group:</span> '.$data->age_group.'<br/>
                                </p>
                            </div>
                        </div>';
            })
        ->addColumn('contact', function ($data){
            return '<span class="text-muted">Phone:</span> '.$data->phone_number.
                    '<br/><span class="text-muted">Email:</span> '.$data->email.
                    '<br/><span class="text-muted">Location:</span> '.$data->district->name.', '.$data->country;
            })
        ->addColumn('other', function ($data){
            return '<span class="text-muted">Affiliation:</span> '.$data->affiliation.
                    '<br/><span class="text-muted">Qualification:</span> '.$data->qualification;
            })
        ->addColumn('actions', function ($data) use ($courseId){
                if (!$this->studentHasActiveCourse($data->id)) {
                    return '<a href="'.url('e-learning/courses/enrolled-students/'.$courseId.'/'.$data->id.'/enroll').'" class="btn btn-xs text-primary">Enroll</a>'; 
                }
            })
        ->rawColumns(['check', 'actions', 'profile', 'contact', 'other'])
        ->make(true);
    }

    public function attendance($courseId, $studentId)
    {
        // code...
    }

    public function studentHasActiveCourse($studentId)
    {
        $result = ELearningStudentEnrollment::where('student_id', $studentId)
                        ->whereNull('removed_at')
                        ->whereIn('course_id',function($query){
                            $query->select('id')->where('status', 'Open')->from('e_learning_courses');
                        })->get();
        return count($result) > 0 ? true : false;
    }

    public function studentEnrollment($studentId)
    {
        $result = ELearningStudent::where('id', $studentId)->whereIn('id',function($query) use ($studentId){
                $query->select('student_id')->where('student_id', $studentId)->from('e_learning_student_enrollments');
            })->orwhere('id', $studentId)->whereIn('id',function($query) use ($studentId){
                $query->select('student_id')->where('student_id', $studentId)->whereNotNull('removed_at')->from('e_learning_student_enrollments');
            })->first();

        return $result ? true : false;
    }


}
