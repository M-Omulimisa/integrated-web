<?php

namespace App\Admin\Controllers;

use App\Models\Settings\Enterprise;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Schema;

class EnterpriseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Enterprise';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $table = (new Enterprise())->getTable();

        Utils::create_column($table, 'include_in_insurance');
        /* 
        $tables = Schema::getColumnListing($table);
        $cols_to_add = ['include_in_insurance'];
        $cols = [];
        foreach ($tables as $t) {
            $cols[] = $t;
        }

        foreach ($cols_to_add as $col) {
            if (!in_array($col, $cols)) {
                Schema::table($table, function ($table) use ($col) {
                    $table->string('include_in_insurance')->nullable()->default('Yes');
                });
            }
        } */


        $grid = new Grid(new Enterprise());


        $grid->column('name', __('Name'));
        $grid->column('category', __('Category'));
        $grid->column('include_in_insurance', __('Include in insurance'))->default('Yes');
        $grid->column('is_perrenial_crop', __('Is Perrenial Crop'))->default('No');

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
        $show = new Show(Enterprise::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('unit_id', __('Unit id'));
        $show->field('category', __('Category'));
        $show->field('description', __('Description'));
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
        $form = new Form(new Enterprise());

        $form->text('name', __('Name'));
        $form->text('category', __('Category'));
        $form->radio('include_in_insurance', __('Include in insurance'))->options(['Yes' => 'Yes', 'No' => 'No'])->rules('required')->default('Yes');
        $form->radio('is_perrenial_crop', __('Is Perrenial Crop'))->options(['Yes' => 'Yes', 'No' => 'No'])->rules('required')->default('No');
        $form->textarea('description', __('Description'));

        return $form;
    }
}
