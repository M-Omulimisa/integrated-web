<?php

namespace App\Admin\Controllers;

use App\Models\AdminRoleUser;
use App\Models\OnlineCourse;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourse());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('title', 'Title');
            $filter->equal('instructor_id', 'Instructor')->select(\App\Models\User::all()->pluck('name', 'id'));
            $filter->equal('online_course_category_id', 'Course Category')->select(\App\Models\OnlineCourseCategory::all()->pluck('name', 'id'));
        });
 
        $grid->column('title', __('Title'))->sortable();

        $grid->column('instructor_id', __('Instructor'))
            ->display(function ($instructor_id) {
                $user = \App\Models\User::find($instructor_id);
                if ($user) {
                    return $user->name;
                }
            })
            ->sortable();
        $grid->column('online_course_category_id', __('Course Category'))
            ->display(function ($online_course_category_id) {
                $category = \App\Models\OnlineCourseCategory::find($online_course_category_id);
                if ($category) {
                    return $category->name;
                }
            })
            ->sortable();

        $grid->column('summary', __('Summary'))->hide();

        $grid->column('video_url', __('Video url'))->hide();
        $grid->column('audio_url', __('Audio url'))
            ->display(function ($audio_url) {
                if (!$audio_url) {
                    return "-";
                }
                if (strlen($audio_url) < 3) {
                    return "-";
                }
                $link = url('storage/' . $audio_url);
                return "<audio controls><source src='" . $link . "' type='audio/mpeg'></audio>";
            })->sortable();

        $grid->column('link', __('Send Instructor Notification'))
            ->display(function () {
                $url = url('send-inspector-notification?id=' . $this->id);
                return "<a target=\"_blank\" href='" . $url . "'>Send Instructor Notification</a>";
            })->sortable();

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
        $show = new Show(OnlineCourse::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('title', __('Title'));
        $show->field('summary', __('Summary'));
        $show->field('details', __('Details'));
        $show->field('content', __('Content'));
        $show->field('photo', __('Photo'));
        $show->field('video_url', __('Video url'));
        $show->field('audio_url', __('Audio url'));
        $show->field('instructor_id', __('Instructor id'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('online_course_category_id', __('Online course category id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourse());

        $form->text('title', __('Course Title'))
            ->placeholder('Enter Course Title Here')
            ->rules('required');
        $form->select('photo', __('Language'))
            ->options(\App\Models\Settings\Language::all()->pluck('name', 'id'))
            ->rules('required');

        /*    $link = ('/api/ajax-users');
        $form->select('instructor_id', __('Instructor'))
            ->options(function ($id) {
                $user = \App\Models\User::find($id);
                if ($user) {
                    return [$user->id => $user->name];
                }
            })
            ->ajax($link);
 */


        $link = ('/api/ajax-users');
        $users = \App\Models\User::all();
        $data = [];
        $instructors = [];
        foreach ($users as $user) {
            if (!($user->isRole('instructor'))) {
                continue;
            }
            $data[$user->id] = $user->name . " - #" . $user->id;
            $instructors[$user->id] = $user->name . " - #" . $user->id;
        }
        $form->select('instructor_id', __('Instructor'))
            ->options($data)
            ->rules('required');

        $form->file('audio_url', __('Introductory Audio'))
            ->attribute(['accept' => 'audio/*'])
            ->rules('required')
            ->help('Please upload an audio file');

        $form->divider();
        $form->html(
            <<<HTML
        <small><b>NOTE: Use following keywords</b><br><code>[STUDENT_NAME]</code> to be dynamically substituded by student's name in SMS.</small><br>
        <small><code>[COURSE_NAME]</code> to be dynamically substituded by course's name in SMS.</small><br>
        HTML
        );
        $form->textarea('summary', __('Welcome Message To Student (SMS) - Template'))
            ->placeholder('Enter Welcome Message To Student Here')
            ->rules('required');


        $form->multipleSelect('other_instructors', __('Other Instructors'))
            ->options($instructors);
        /*         

        $form->file('video_url', __('Intro Video'))
            ->attribute(['accept' => 'video/*']);
      

        $form->quill('details', __('Course Details'))
            ->placeholder('Enter Course Details Here');



        $form->hidden('organisation_id', __('Organisation id'))->default(1);
        $form->hidden('online_course_category_id', __('Online course category id'))->default(1); */
        return $form;
    }
}
