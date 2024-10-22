<?php

namespace App\Admin\Controllers;

use App\Models\SMSOutbox;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SMSOutboxController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SMS Outboxes';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SMSOutbox());
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('created_at', __('Created'))->sortable()->filter('range', 'datetime')
            ->display(function ($created_at) {
                return Utils::my_date_time($created_at);
            });
        $grid->column('updated_at', __('Updated'))->sortable()->filter('range', 'datetime')->hide();
        $grid->column('phone', __('Phone'))->sortable()->filter('like');
        $grid->column('sms', __('Sms'))->sortable()->filter('like');
        $grid->column('status', __('Status'))->sortable()->filter([
            'pending' => 'Pending',
            'sent' => 'Sent',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
        ])->dot([
            'pending' => 'info',
            'sent' => 'success',
            'failed' => 'danger',
            'cancelled' => 'warning',
        ]);
        //reason
        $grid->column('reason', __('Reason'))->sortable()->filter('like');

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
        $show = new Show(SMSOutbox::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('phone', __('Phone'));
        $show->field('sms', __('Sms'));
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
        $form = new Form(new SMSOutbox());

        $form->textarea('phone', __('Phone'));
        $form->textarea('sms', __('Sms'));
        $form->text('status', __('Status'));

        return $form;
    }
}
