<?php

namespace App\Admin\Controllers;

use App\Models\TestPhoneNumber;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TestPhoneNumberController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Test Phone Numbers';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TestPhoneNumber());
        $grid->model()->orderBy('id', 'desc');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('phone', __('Phone'))->editable()->filter('like')->sortable();
        $grid->column('status', __('Status'))->editable('select', [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ])->sortable()->filter([
            'active' => 'Active',
            'inactive' => 'Inactive',
        ])->dot([
            'active' => 'success',
            'inactive' => 'danger',
        ]);
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
        $show = new Show(TestPhoneNumber::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('phone', __('Phone'));
        $show->field('status', __('Status'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new TestPhoneNumber());

        $form->text('phone', __('Phone'))->rules('required');
        $form->radio('status', __('Status'))->options([
            'active' => 'Active',
            'inactive' => 'Inactive',
        ])->default('active');

        return $form;
    }
}
