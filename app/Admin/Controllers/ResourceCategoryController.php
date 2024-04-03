<?php

namespace App\Admin\Controllers;

use App\Models\ResourceCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ResourceCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Resource Categories';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ResourceCategory());

        $grid->disableBatchActions();
        $grid->disableExport();
        $grid->quickSearch('name')->placeholder('Search by name...');
        //thumbnail
        $grid->column('thumbnail', __('Thumbnail'))
            ->image('', 50, 50);
        $grid->model()->orderBy('name', 'asc');
        $grid->column('name', __('Name'))->sortable();
        $grid->column('type', __('Type'))->sortable();
        $grid->column('details', __('Details'))->hide();

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
        $show = new Show(ResourceCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));
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
        $form = new Form(new ResourceCategory());

        $form->text('name', __('Name'))->rules('required');
        $form->radio('type', __('Category Type'))
            ->options([
                'Crops' => 'Crops',
                'Livestock' => 'Livestock',
            ])->rules('required');
        $form->image('thumbnail', __('Thumbnail'))
            ->uniqueName();
        $form->textarea('details', __('Details'));

        return $form;
    }
}
