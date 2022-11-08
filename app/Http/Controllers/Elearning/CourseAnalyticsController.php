<?php

namespace App\Http\Controllers\Elearning;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings\Language;
use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningChapter;
use App\Models\Elearning\ELearningCourseRegistration;
use App\Models\Elearning\ELearningGeneralAssignment;
use App\Models\Elearning\ELearningLectureTopic;
use App\Models\Elearning\ELearningStudentEnrollment;
use App\Models\Elearning\ELearningLectureVisit;
use App\Models\Elearning\ELearningLecture;
use App\Models\Elearning\ELearningLectureAttendance;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\File;

class CourseAnalyticsController extends Controller
{

    public function overview($courseId)
    {
        $course = ELearningCourse::findOrFail($courseId);

        $statistics                 = new Request;
        $statistics->students       = count($course->enrolledStudents());
        $statistics->instructors    = 0;
        $statistics->chapters       = count($course->chapters);
        $statistics->lessons        = count($course->lectures());
        $statistics->announcements  = count($course->announcements);
        $statistics->resources      = count($course->resources);
        $statistics->forum          = count($course->forums);
        $statistics->quiz           = count($course->lecture_quiz_questions()) + count($course->lecture_general_questions);
        $statistics->calls = 0;

        return view('e_learning.reports.course_overview', compact('course', 'statistics'));
    }

    public function students($courseId)
    {
        $course = ELearningCourse::findOrFail($courseId);

        $gender = new Request;
        $gender->Male = count($this->enrolledStudentsByGender($courseId, 'Male'));
        $gender->Female = count($this->enrolledStudentsByGender($courseId, 'Female'));
        $gender->NotDisclosed = count($this->enrolledStudentsByGender($courseId, 'Not Disclosed'));

        $activeness = new Request;
        $activeness->Active = count($this->studentsWhohaveAttendedLectures($courseId));
        $activeness->Inactive = count($course->enrolledStudents()) - count($this->studentsWhohaveAttendedLectures($courseId));

        $affiliation = new Request;
        $affiliation->None = count($this->enrolledStudentsByAffiliation($courseId, 'None'));
        $affiliation->Academia = count($this->enrolledStudentsByAffiliation($courseId, 'Academia'));
        $affiliation->Individual = count($this->enrolledStudentsByAffiliation($courseId, 'Individual'));
        $affiliation->CommunityOrganisation = count($this->enrolledStudentsByAffiliation($courseId, 'Community Organisation'));
        $affiliation->ForProfitOrganisation = count($this->enrolledStudentsByAffiliation($courseId, 'For-Profit Organisation'));
        $affiliation->NonProfitOrganisation = count($this->enrolledStudentsByAffiliation($courseId, 'Non-Profit Organisation'));
        $affiliation->NotDisclosed = count($this->enrolledStudentsByAffiliation($courseId, 'Not Disclosed'));

        $age = new Request;
        $age->None = count($this->enrolledStudentsByAge($courseId, 'None'));
        $age->LessThan16 = count($this->enrolledStudentsByAge($courseId, 'Less than 16'));
        $age->age1620 = count($this->enrolledStudentsByAge($courseId, '16-20'));
        $age->age2125 = count($this->enrolledStudentsByAge($courseId, '21-25'));
        $age->age2630 = count($this->enrolledStudentsByAge($courseId, '26-30'));
        $age->age3135 = count($this->enrolledStudentsByAge($courseId, '31-35'));
        $age->age3640 = count($this->enrolledStudentsByAge($courseId, '36-40'));
        $age->age4145 = count($this->enrolledStudentsByAge($courseId, '41-45'));
        $age->age4650 = count($this->enrolledStudentsByAge($courseId, '46-50'));
        $age->ageGreaterThan50 = count($this->enrolledStudentsByAge($courseId, 'Greater than 50'));
        $age->ageNotDisclosed = count($this->enrolledStudentsByAge($courseId, 'Not Disclosed'));

        $qualification = new Request;
        $qualification->None = count($this->enrolledStudentsByQualification($courseId, 'None'));
        $qualification->HiSchool = count($this->enrolledStudentsByQualification($courseId, 'Hi School'));
        $qualification->PreUniversity = count($this->enrolledStudentsByQualification($courseId, 'Pre University'));
        $qualification->UnderGraduate = count($this->enrolledStudentsByQualification($courseId, 'Under Graduate'));
        $qualification->PostGraduate = count($this->enrolledStudentsByQualification($courseId, 'Post Graduate'));
        $qualification->Doctorate = count($this->enrolledStudentsByQualification($courseId, 'Doctorate'));
        $qualification->Other = count($this->enrolledStudentsByQualification($courseId, 'Other'));
        $qualification->NotDisclosed = count($this->enrolledStudentsByQualification($courseId, 'Not Disclosed'));

        $daily_enrollment = ELearningStudentEnrollment::select(array(DB::Raw('COUNT(id) studentcount'),DB::Raw('DATE(created_at) day')))->where('course_id', $courseId)->groupBy('day')->get();

        return view('e_learning.reports.students', compact('course', 'gender', 'activeness', 'affiliation', 'age', 'qualification', 'daily_enrollment'));
    }

    public function lectures($courseId)
    {
        $course = ELearningCourse::findOrFail($courseId);

        // $visitsperlecture = ELearningLectureVisit::select(array(DB::Raw('COUNT(id) lectvisits'),'lecture_id'))->groupBy('lecture_id')->get();
        // $studentvisitsperlecture = ELearningLectureVisit::select('lecture_id')->groupBy('lecture_id')->get();

        $courselectures = ELearningLecture::whereIn('chapter_id',function($query) use ($courseId){
                            $query->select('id')->where('course_id', $courseId)->from('e_learning_chapters');                
                        })->get();
        return view('e_learning.reports.lectures', compact('course', 'courselectures'));
    }

    public function quiz($courseId)
    {
        $course = ELearningCourse::findOrFail($courseId);
        return view('e_learning.reports.quiz', compact('course'));
    }

    public function questions($courseId)
    {
        $course = ELearningCourse::findOrFail($courseId);
        $courselectures = ELearningLecture::whereIn('chapter_id',function($query) use ($courseId){
                            $query->select('id')->where('course_id', $courseId)->from('e_learning_chapters');                
                        })->get();
        return view('e_learning.reports.questions', compact('course', 'courselectures'));
    }

    public function enrolledStudentsByGender($courseId, $gender)
    {
        return ELearningStudentEnrollment::where('course_id', $courseId)->whereNull('removed_at')->whereIn('student_id',function($query) use ($gender){
                            $query->select('id')->where('gender', $gender)->from('e_learning_students');                
                        })->get();
    }

    public function enrolledStudentsByAge($courseId, $age_group)
    {
        return ELearningStudentEnrollment::where('course_id', $courseId)->whereNull('removed_at')->whereIn('student_id',function($query) use ($age_group){
                            $query->select('id')->where('age_group', $age_group)->from('e_learning_students');                
                        })->get();
    }

    public function enrolledStudentsByAffiliation($courseId, $affiliation)
    {
        return ELearningStudentEnrollment::where('course_id', $courseId)->whereNull('removed_at')->whereIn('student_id',function($query) use ($affiliation){
                            $query->select('id')->where('affiliation', $affiliation)->from('e_learning_students');                
                        })->get();
    }

    public function enrolledStudentsByQualification($courseId, $qualification)
    {
        return ELearningStudentEnrollment::where('course_id', $courseId)->whereNull('removed_at')->whereIn('student_id',function($query) use ($qualification){
                            $query->select('id')->where('qualification', $qualification)->from('e_learning_students');                
                        })->get();
    }

    public function studentsWhohaveAttendedLectures($courseId)
    {
        return ELearningLectureAttendance::select('student_id')->whereNotNull('student_id')->whereIn('lecture_id',function($query) use ($courseId){
                $query->select('id')->from('e_learning_lectures')
                        ->whereIn('chapter_id',function($query) use ($courseId){
                                $query->select('id')->where('course_id', $courseId)->from('e_learning_chapters');
                            });
            })->groupBy('student_id')->get();
    }

    public function studentAttendance(Request $request)
    {
        \DB::statement(\DB::raw('set @rownum=0'));
        $data = ELearningStudentEnrollment::select(['*', \DB::raw('@rownum  := @rownum  + 1 AS rownum') ]);

        $datatables = app('datatables')->of($data);

        $course_id = $datatables->request->get('course');

        $data->where('course_id', $course_id)->whereNull('removed_at');

        return $datatables
        ->addColumn('student', function ($data){
            return $data->student->full_name;
            })
        ->addColumn('contact', function ($data){
            return $data->student->phone_number;
            })
        ->addColumn('lectures', function ($data) use ($course_id){
                $course = ELearningCourse::findorFail($course_id);
                if ($course->lecture_type == "Weekly") {
                    $chapters = ELearningChapter::where('course_id', $course_id)->where('summary', 'LIKE', '%week-%')->orderBy('numbering', 'ASC')->get();
                }else{
                    $chapters = ELearningChapter::where('course_id', $course_id)->orderBy('numbering', 'ASC')->get();
                }

                $_chapters = '';
                if (count($chapters) > 0) {
                    foreach ($chapters as $chapter) {
                        $lectures = '';
                        if (count($this->lecturesAttendedByStudentPerChapter($course_id, $data->student_id, $chapter->id)) > 0) {
                            foreach ($this->lecturesAttendedByStudentPerChapter($course_id, $data->student_id, $chapter->id) as $lecture) {
                                $lectures .= $lecture->lecture->title.', ';
                            }
                        }else{
                            $lectures .= 'No lectures attended yet';
                        }
                        $_chapters .= $chapter->title.'('.rtrim($lectures, ', ').'), ';
                    }
                    return rtrim($_chapters, ', ');
                }
            })
        ->rawColumns(['check', 'student','contact', 'lectures'])
        ->make(true);
    }

    public function lecturesAttendedByStudent($courseId, $student_id)
    {
        return ELearningLectureAttendance::where('student_id', $student_id)->whereIn('lecture_id',function($query) use ($courseId){
                $query->select('id')->from('e_learning_lectures')
                        ->whereIn('chapter_id',function($query) use ($courseId){
                                $query->select('id')->where('course_id', $courseId)->from('e_learning_chapters');
                            });
            })->get();
    } 

    public function lecturesAttendedByStudentPerChapter($courseId, $student_id, $chapter_id)
    {
        return ELearningLectureAttendance::where('student_id', $student_id)->whereIn('lecture_id',function($query) use ($courseId, $chapter_id){
                $query->select('id')->from('e_learning_lectures')
                        ->whereIn('chapter_id',function($query) use ($courseId, $chapter_id){
                                $query->select('id')->where('id', $chapter_id)->where('course_id', $courseId)->from('e_learning_chapters');
                            });
            })->get();
    } 

    

    // $lectures = '';

    // if (count($this->lecturesAttendedByStudent($course_id, $data->student_id)) > 0) {
    //     foreach ($this->lecturesAttendedByStudent($course_id, $data->student_id) as $lecture) {
    //         $lectures .= $lecture->chapter->title.':'.$lecture->lecture->title.', ';
    //     }
    // if (count($this->chaptersAttendedByStudent($course_id, $data->student_id)) > 0) {
    //     foreach ($this->chaptersAttendedByStudent($course_id, $data->student_id) as $lecture) {
    //         $lectures .= $lecture->id.', ';
    //     }
    // }else{
    //     $lectures .= 'No lectures attended yet';
    // }
    // return $lectures;  

}