<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseMenu;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseMenuController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Instructional Files';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseMenu());
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });
        $grid->disableCreateButton();
        $grid->disableFilter();

        $grid->model()->orderBy('name', 'asc');
        $grid->column('name', __('Name'))->sortable();
        $grid->column('english_audio', __('English audio'))
            ->display(function ($audio_url) {
                if ($audio_url) {
                    //check if not null and not empty
                    if ($audio_url == null || $audio_url == '') {
                        return 'N/A';
                    }
                    $url = asset('storage/' . $audio_url);
                    return '<audio controls>
                <source src="' . $url . '" type="audio/mpeg">
                Your browser does not support the audio element.
                </audio>';
                }
            })->sortable();

        $grid->column('items', __('Languages'))->display(function ($items) {
            $languages = [];
            foreach ($this->onlineCourseMenuItems as $item) {
                $languages[] = $item['language']['name'];
            }
            return implode(', ', $languages);
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
        $show = new Show(OnlineCourseMenu::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('name', __('Name'));
        $show->field('english_audio', __('English audio'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourseMenu());

        if ($form->isCreating()) {
            $form->text('name', __('Name'))->rules('required');
        } else {
            $form->display('name', __('Name'));
        }

        $form->file('english_audio', __('English audio'))
            ->rules('mimes:mp3,wav')
            ->help('Audio file in mp3 or wav format')
            ->rules('required');
        $form->disableCreatingCheck();
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableDelete();
        });

        //has many OnlineCourseMenuItem
        $form->hasMany('onlineCourseMenuItems', 'Items', function (Form\NestedForm $form) {
            $form->select('language_id', __('Language'))->options(\App\Models\Settings\Language::all()->pluck('name', 'id'));
            $form->file('audio', __('Audio'))
                ->rules('mimes:mp3,wav')
                ->help('Audio file in mp3 or wav format');
        });

        return $form;
    }
}
