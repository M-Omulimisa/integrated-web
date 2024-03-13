<?php

namespace App\Admin\Controllers;

use App\Models\Weather\WeatherSmsTranslation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WeatherSmsTranslationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Weather SMS Translations';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeatherSmsTranslation());
        $grid->quickSearch('translation')->placeholder('Search translation...');
        $grid->column('translation', __('Translation'))->sortable();
        $grid->column('language_id', __('Language'))
            ->display(function ($language_id) {
                return \App\Models\Settings\Language::find($language_id)->name;
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
        $show = new Show(WeatherSmsTranslation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('language_id', __('Language id'));
        $show->field('translation', __('Translation'));
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
        $form = new Form(new WeatherSmsTranslation());

        $langs = \App\Models\Settings\Language::all()->pluck('name', 'id');
        $form->select('language_id', __('Language'))
            ->options($langs)
            ->rules('required');
        $form->textarea('translation', __('Translation'));

        return $form;
    }
}
