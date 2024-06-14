<?php

namespace App\Admin\Controllers;

use App\Models\TranslatedWord;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TranslatedWordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Translated Words';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        Utils::create_column(
            (new TranslatedWord())->getTable(),
            [
                [
                    'name' => 'lango',
                    'type' => 'Text',
                ],
                [
                    'name' => 'ateso',
                    'type' => 'Text',
                ],
            ]
        );


        $grid = new Grid(new TranslatedWord());
        $grid->model()->orderBy('word', 'asc');
        $grid->column('word', __('Word'))
            ->sortable()
            ->editable()
            ->filter('like');
        $grid->column('luganda', __('Luganda'))
            ->sortable()
            ->editable()
            ->filter('like');
        $grid->column('runyankole', __('Runyankole'))
            ->sortable()
            ->editable()
            ->filter('like');
        $grid->column('acholi', __('Acholi'))
            ->sortable()
            ->editable()
            ->filter('like');
        $grid->column('lumasaba', __('Lumasaba'))
            ->sortable()
            ->editable()
            ->filter('like');

        //lango
        $grid->column('lango', __('Lango'))->sortable()->editable()->filter('like');
        //ateso
        $grid->column('ateso', __('Ateso'))->sortable()->editable()->filter('like');


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
        $show = new Show(TranslatedWord::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('word', __('Word'));
        $show->field('luganda', __('Luganda'));
        $show->field('runyankole', __('Runyankole'));
        $show->field('acholi', __('Acholi'));
        $show->field('lumasaba', __('Lumasaba'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new TranslatedWord());

        $form->text('word', __('English Word'))
            ->required()
            ->rules('required|min:2');
        $form->text('luganda', __('Luganda'));
        $form->text('runyankole', __('Runyankole'));
        $form->text('acholi', __('Acholi'));

        $form->text('lumasaba', __('Lumasaba'));

        $form->text('lango', __('Lango'));
        $form->text('ateso', __('Ateso'));


        return $form;
    }
}
