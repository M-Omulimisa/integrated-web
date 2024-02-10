<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseLesson;
use App\Models\OnlineCourseStudent;
use Encore\Admin\Controllers\AdminController;
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
        $grid->header(function ($query) {
            $call_url = url('api/online-make-reminder-calls?force=Yes');
            return "<a target=\"_blank\" href='$call_url' class='btn btn-sm btn-success'>Make Reminder Calls Now</a>";
        });
        //$grid->disableActions();
        $grid->disableCreateButton();
        $grid->model()->orderBy('id', 'desc');
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
        });

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

        $form->file('details', __('Answer Auido'))
            ->uniqueName()
            ->removable();

        $form->radio('status', __('Status'))
            ->options([
                'Pending' => 'Pending',
                'Attended' => 'Attended',
            ])->default('Pending');

        $form->html(<<<SCRIPT
        <script>
        $(document).ready(function(){
            // audio recorder
            let recorder, audio_stream;
            const recordButton = document.getElementById("recordButton");
            const details = $(".details");
            recordButton.addEventListener("click", startRecording);
            
            // stop recording
            const stopButton = document.getElementById("stopButton");
            stopButton.addEventListener("click", stopRecording);
            stopButton.disabled = true;
            
            // set preview
            const preview = document.getElementById("audio-playback");
            
            // set download button event
            const downloadAudio = document.getElementById("downloadButton");
            downloadAudio.addEventListener("click", downloadRecording);
            
            function startRecording() {
                // button settings
                recordButton.disabled = true;
                recordButton.innerText = "Recording..."
                $(".details").addClass("hide");
                $("#recordButton").addClass("button-animate");
            
                $("#stopButton").removeClass("inactive");
                stopButton.disabled = false;
            
            
                if (!$("#audio-playback").hasClass("hidden")) {
                    $("#audio-playback").addClass("hidden")
                };
            
                if (!$("#downloadContainer").hasClass("hidden")) {
                    $("#downloadContainer").addClass("hidden")
                };
            
                navigator.mediaDevices.getUserMedia({ audio: true })
                    .then(function (stream) {
                        audio_stream = stream;
                        recorder = new MediaRecorder(stream);
            
                        // when there is data, compile into object for preview src
                        recorder.ondataavailable = function (e) {
                            const url = URL.createObjectURL(e.data);
                            preview.src = url;
            
                            // set link href as blob url, replaced instantly if re-recorded
                            downloadAudio.href = url;
                        };
                        recorder.start();
            
                        timeout_status = setTimeout(function () {
                            console.log("5 min timeout");
                            stopRecording();
                        }, 300000);
                    });
            }
            
            function stopRecording() {
                recorder.stop();
                audio_stream.getAudioTracks()[0].stop();
            
                // buttons reset
                recordButton.disabled = false;
                recordButton.innerText = "Redo Recording"
                $("#recordButton").removeClass("button-animate");
            
                $("#stopButton").addClass("inactive");
                stopButton.disabled = true;
            
                $("#audio-playback").removeClass("hidden");
            
                $("#downloadContainer").removeClass("hidden");
            }
            
            function downloadRecording(){
                var name = new Date();
                var res = name.toISOString().slice(0,10)
                downloadAudio.download = res + '.wav';
            }
        });
        </script>
        SCRIPT);

        $form->html(<<<HTML
        <div class="form-group">
            <label for="audio-playback">Audio Playback</label>
            <audio id="audio-playback" controls class="form-control"></audio>
        </div>
        <div class="form-group">
            <button id="recordButton" class="btn btn-primary">Record Audio</button>
            <button id="stopButton" class="btn btn-danger inactive">Stop Recording</button>
        </div>
        <div class="form-group hidden" id="downloadContainer">
            <a id="downloadButton" class="btn btn-success" download>Download Audio</a>
        </div>
        HTML);



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
