<?php

namespace App\Admin\Controllers;

use App\Models\Weather\WeatherCondition;
use Encore\Admin\Controllers\AdminController;
use App\Models\Settings\Language;
use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WeatherConditionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Weather Conditions';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeatherCondition());
        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('digit', __('Digit'));
        $grid->column('language_id', __('Language'))->display(function ($language_id) {

            $f = Language::find($language_id);

            if ($f == null) {

                return 'Unknown';
            }
            return $f->name;
        });
        $grid->column('description', __('Description'));


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
        $show = new Show(WeatherCondition::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('digit', __('Digit'));
        $show->field('language_id', __('Language id'));
        $show->field('description', __('Description'));
        $show->field('created_at', __('Created at'));


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeatherCondition());

        $form->number('digit', __('Digit'));
        $form->select('language_id', 'Select language')->options(Language::all()->pluck('name', 'id'));
        $form->text('description', __('Description'));

        return $form;
    }
}
