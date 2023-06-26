<?php

namespace App\Admin\Controllers;

use App\Models\Elearning\ELearningCourse;
use App\Models\Elearning\ELearningResource;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class ELearningResourceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'E-Learning Resources';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ELearningResource());
        $grid->disableBatchActions();
        $grid->model()->where('organisation_id', Auth::user()->organisation_id);
        $grid->column('title', __('Title'))->sortable();
        $grid->column('course_id', __('Course'));
        $grid->column('user_id', __('User id'));
        $grid->column('status', __('Status'));
        $grid->column('start_date', __('Start date'));
        $grid->column('end_date', __('End date'));
        $grid->column('display_days', __('Display days'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(ELearningResource::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('course_id', __('Course id'));
        $show->field('title', __('Title'));
        $show->field('body', __('Body'));
        $show->field('user_id', __('User id'));
        $show->field('status', __('Status'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('display_days', __('Display days'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ELearningResource());

        $u = Auth::user();
        $form->hidden('organisation_id', __('Organisation id'))
            ->default($u->organisation_id);
        $form->hidden('user_id', __('user_id'))
            ->default($u->id);

        $form->select('course_id', __('Select Course'))
            ->options(ELearningCourse::where(
                'organisation_id',
                Auth::user()->organisation_id
            )
                ->orderBy('title', 'asc')
                ->get()->pluck('title', 'id'));

        $form->text('title', __('Resource Title'));
        $form->radioCard('type', __('Select Resource Type'))
            ->options([
                'File' => 'Document',
                'Photo' => 'Photo',
                'Video' => 'Video',
                'Youtube' => 'Youtube Video',
                'Text' => 'Text',
            ])
            ->when('=', 'File', function ($f) {
                return $f->file('file', 'Select file')
                    ->attribute(
                        'accept',
                        '.doc,.docx,.pdf'
                    );
            })
            ->when('=', 'Photo', function ($f) {
                return $f->image('photo', 'Select Photo');
            })
            ->when('=', 'Video', function ($f) {
                return $f->file('video', 'Select Video')
                    ->attribute(
                        'accept',
                        '.mp4'
                    );
            })
            ->when('=', 'Youtube', function ($f) {
                return $f->url('', 'Enter YouTube Video Url');
            });

        $form->image('thumbnail', __('Resource Thumbnail'));
        $form->quill('body', __('Resource Details'));

        $form->hidden('status', __('Status'))->default(1);
        $form->hidden('start_date', __('Start date'))->default(date('Y-m-d'));
        $form->hidden('end_date', __('End date'))->default(date('Y-m-d'));
        $form->hidden('display_days', __('Display days'))->default(1);

        return $form;
    }
}
