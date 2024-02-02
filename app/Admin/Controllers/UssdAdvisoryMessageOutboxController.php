<?php

namespace App\Admin\Controllers;

use App\Models\Ussd\UssdAdvisoryMessageOutbox;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UssdAdvisoryMessageOutboxController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UssdAdvisoryMessageOutbox';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UssdAdvisoryMessageOutbox());

    
        $grid->column('session_id', __('Phone Number'))->display(function ($session_data) {
            if ($this->session == 'null') {

                return $session_data;

            }
            return $this->session->phone_number;
        });
        
        $grid->column('message', __('Message'));
        $grid->column('status', __('Status'));

        $grid->column('created_at', __('Created at'))->sortable();
 

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
        $show = new Show(UssdAdvisoryMessageOutbox::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('session_id', __('Session id'));
        $show->field('message', __('Message'));
        $show->field('status', __('Status'));
        $show->field('deleted_at', __('Deleted at'));
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
        $form = new Form(new UssdAdvisoryMessageOutbox());

        $form->text('session_id', __('Session id'));
        $form->textarea('message', __('Message'));
        $form->text('status', __('Status'))->default('pending');

        return $form;
    }
}
