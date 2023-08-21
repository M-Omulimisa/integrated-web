<?php

namespace App\Admin\Controllers;

use App\Models\Elearning\ELearningCourse;
use App\Models\ResourceCategory;
use App\Models\Training\Training;
use App\Models\Training\TrainingResource;
use App\Models\TrainingCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class TrainingResourceCourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Training Resources';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TrainingResource());

        $grid->disableBatchActions();
        $grid->model()->where('organisation_id', Auth::user()->organisation_id);
        $grid->quickSearch('heading')->placeholder('Search by resource name');

        //$grid->column('thumbnail', __('Thumbnail'));
        $grid->column('heading', __('Resource Name'))->sortable();
        $grid->column('type', __('Resource Type'))->sortable();
        $grid->column('view', __('View Resouse'))->display(function ($x) {
            $x = "";
            if ($this->type == "File") {
                $x = url('storage/' . $this->file);
            } elseif ($this->type == "Youtube") {
                $x = $this->youtube;
            }
            if (strlen($x) < 3) {
                return "-";
            }
            return '<b><a target="_blank" href="' . $x . '">View Resource</a></b>';
        })->sortable();
        $grid->column('user_id', __('Uploaded By'))->display(function ($x) {
            if ($this->user == 'null') {
                return $x;
            }
            return $this->user->name;
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
        $show = new Show(TrainingResource::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('heading', __('Heading'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('order', __('Order'));
        $show->field('status', __('Status'));
        $show->field('user_id', __('User id'));
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
        $form = new Form(new TrainingResource());

        $u = Auth::user();
        $form->hidden('organisation_id', __('Organisation id'))
            ->default($u->organisation_id);
        $form->hidden('user_id', __('user_id'))
            ->default($u->id);

        $form->text('heading', __('Resource Title'));
        $form->select('resource_category_id', __('Resource Category'))
            ->options(ResourceCategory::all()->pluck('name', 'id'))
            ->required()
            ->default(1);
        $form->image('thumbnail', __('Resource Thumbnail'));
        $form->radioCard('type', __('Select Resource Type'))
            ->options([
                'File' => 'Document',
                'Photo' => 'Photo',
                'Video' => 'Video',
                'Youtube' => 'Youtube Video',
                'Text' => 'Text',
                'Audio' => 'Audio',
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
            ->when('=', 'Audio', function ($f) {
                return $f->file('audio', 'Select Audio')
                    ->attribute(
                        'accept',
                        '.mp3,.wav'
                    );
            })
            ->when('=', 'Video', function ($f) {
                return $f->file('video', 'Select Video')
                    ->attribute(
                        'accept',
                        '.mp4'
                    );
            })
            ->when('=', 'Youtube', function ($f) {
                return $f->url('youtube', 'Enter YouTube Video Url');
            });


        $form->quill('body', __('Resource Details'));

        $form->hidden('order', __('order'))->default(1);
        $form->hidden('status', __('Status'))->default(1);

        return $form;
    }
}
