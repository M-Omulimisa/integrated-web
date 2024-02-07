<?php

namespace App\Admin\Controllers;

use App\Models\Ussd\UssdLanguage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UssdLanguageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UssdLanguage';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UssdLanguage());

        $grid->column('language', __('Language'))->sortable();

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
        $show = new Show(UssdLanguage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('language', __('Language'));
        $show->field('position', __('Position'));
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
        $form = new Form(new UssdLanguage());

        $form->text('language', __('Language'));
        $form->decimal('position', __('Position'));
        $form->hidden('menu_id', __('Menu id'))->default(4);

        return $form;
    }
}
