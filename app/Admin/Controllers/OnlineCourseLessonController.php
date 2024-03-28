<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourse;
use App\Models\OnlineCourseLesson;
use App\Models\OnlineCourseStudent;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseLessonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Students\' Learning Sessions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseLesson());
       

        //add on top of the grid html data
        /* $grid->header(function ($query) {
            $call_url = url('api/online-make-reminder-calls?force=Yes');
            return "<a target=\"_blank\" href='$call_url' class='btn btn-sm btn-success'>Make Reminder Calls Now</a>";
        }); */

        //$grid->disableActions();
        $grid->disableCreateButton();
        $grid->model()->orderBy('id', 'desc');
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
        });

        $u = Admin::user();
        if ($u->isRole('instructor')) {
            $grid->disableCreateButton();
            $myStudents = OnlineCourse::getMyStudents($u);
            $ids = [];
            foreach ($myStudents as $student) {
                $ids[] = $student['id'];
            }
            $grid->model()->whereIn('student_id', $ids);
        }

        $grid->column('id', __('ID'))->sortable()->hide();
        $grid->column('student_id', __('Student'))
            ->display(function ($student_id) {
                $item = OnlineCourseStudent::find($student_id);
                if ($item != null) {
                    return $item->name;
                }
                $this->delete();
                return 'Deleted';
            })
            ->sortable();

        $grid->column('online_course_id', __('Course'))
            ->display(function ($online_course_id) {
                $item = \App\Models\OnlineCourse::find($online_course_id);
                if ($item != null) {
                    return $item->title;
                }
                $item->delete();
                return 'Deleted';
            })
            ->sortable();
        $grid->column('online_course_topic_id', __('Topic'))
            ->display(function ($online_course_topic_id) {
                $item = \App\Models\OnlineCourseTopic::find($online_course_topic_id);
                if ($item != null) {
                    return $item->title;
                }
                return 'Deleted';
            })
            ->sortable();

        $grid->column('instructor_id', __('Instructor'))
            ->display(function ($instructor_id) {
                $item = \App\Models\User::find($instructor_id);
                if ($item != null) {
                    return $item->name;
                }
                return 'Deleted';
            })
            ->hide()
            ->sortable();
        $grid->column('sheduled_at', __('Sheduled'))
            ->display(function ($sheduled_at) {
                return date('d M Y H:i', strtotime($sheduled_at));
            })
            ->sortable();
        $grid->column('attended_at', __('Attended'))
            ->display(function ($attended_at) {
                if ($attended_at == null || strlen($attended_at) < 2) {
                    return 'Not attended';
                }
                return date('d M Y H:i', strtotime($attended_at));
            })
            ->sortable();
        $grid->column('status', __('Status'))
            ->sortable()
            ->filter([
                'Pending' => 'Pending',
                'Attended' => 'Attended',
            ])
            ->label([
                'Pending' => 'warning',
                'Attended' => 'success'
            ]);

        $grid->column('has_reminder_call', __('Reminder Call'))
            ->sortable()
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes'
            ])
            ->editable('select', [
                'No' => 'No',
                'Yes' => 'Yes'
            ]);
        //reminder_date
        $grid->column('reminder_date', __('Reminder Date'))
            ->display(function ($reminder_date) {
                if ($reminder_date == null || strlen($reminder_date) < 2) {
                    return 'Not set';
                }
                return date('d M Y H:i', strtotime($reminder_date));
            })
            ->sortable();

        $grid->column('has_error', __('Has error'))
            ->label([
                'No' => 'success',
                'Yes' => 'danger'
            ])
            ->sortable()
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes'
            ])->hide();
        $grid->column('error_message', __('Error message'))
            ->display(function ($error_message) {
                if ($error_message == null || strlen($error_message) < 2) {
                    return 'No error';
                }
                return $error_message;
            })
            ->sortable()
            ->hide();
        $grid->column('details', __('Details'))->hide();
        $grid->column('student_quiz_answer', __('Quize Answer'))->sortable();
        $grid->column('student_audio_question', __('Audio Question'))->sortable()
            ->display(function ($student_audio_question) {

                if ($student_audio_question) {
                    //check if not null and not empty
                    if ($student_audio_question == null || $student_audio_question == '') {
                        return 'N/A';
                    }
                    return '<audio controls>
                    <source src="' . $student_audio_question . '" type="audio/mpeg">
                    Your browser does not support the audio element.
                    </audio>' . "<br><a href='$student_audio_question' target='_blank'>Download</a>";
                }
                return 'No Question';
            });
        $grid->column('instructor_audio_question', __('Audio Answer'))->sortable()
            ->display(function ($instructor_audio_question) {
                if ($instructor_audio_question) {
                    //check if not null and not empty
                    if ($instructor_audio_question == null || $instructor_audio_question == '') {
                        return 'N/A';
                    }
                    $url = asset('storage/' . $instructor_audio_question);
                    return '<audio controls>
                    <source src="' . $url . '" type="audio/mpeg">
                    Your browser does not support the audio element.
                    </audio>';
                }
                return 'No Answer';
            });
        $grid->column('student_listened_to_answer', __('Listened to Answer'))->sortable();
        $grid->column('has_answer', __('Has Answer'))->sortable();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(OnlineCourseLesson::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('online_course_topic_id', __('Online course topic id'));
        $show->field('online_course_id', __('Online course id'));
        $show->field('student_id', __('Student id'));
        $show->field('instructor_id', __('Instructor id'));
        $show->field('sheduled_at', __('Sheduled at'));
        $show->field('attended_at', __('Attended at'));
        $show->field('status', __('Status'));
        $show->field('has_error', __('Has error'));
        $show->field('error_message', __('Error message'));
        $show->field('details', __('Details'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourseLesson());
        $form->disableCreatingCheck();

        /*         $form->file('details', __('Answer Auido'))
            ->uniqueName()
            ->removable();
 */
        $form->radio('status', __('Status'))
            ->options([
                'Pending' => 'Pending',
                'Attended' => 'Attended',
            ]);

        $id = request()->route('online_course_lesson');
        if ((int)($id) > 0) {
            $form->html(view('admin.components.recording', ['id' => $id]));
        }
        $form->disableEditingCheck();
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableDelete();
        });

        return $form;
        $form->number('online_course_topic_id', __('Online course topic id'));
        $form->number('online_course_id', __('Online course id'));
        $form->text('student_id', __('Student id'));
        $form->text('instructor_id', __('Instructor id'));
        $form->datetime('sheduled_at', __('Sheduled at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('attended_at', __('Attended at'))->default(date('Y-m-d H:i:s'));
        $form->text('status', __('Status'))->default('Pending');
        $form->text('has_error', __('Has error'))->default('No');
        $form->textarea('error_message', __('Error message'));


        return $form;
    }
}
