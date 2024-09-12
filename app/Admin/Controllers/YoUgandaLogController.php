<?php

namespace App\Admin\Controllers;

use App\Models\YoUgandaLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class YoUgandaLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'YoUgandaLog';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new YoUgandaLog());
        $grid->model()->orderBy('id', 'desc');
        $grid->quickSearch('network_ref', 'external_ref', 'Signature', 'get_data', 'post_data');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('created_at', __('Created at'));
        $grid->column('date_time', __('Date time'))->sortable();
        $grid->column('amount', __('Amount'))->sortable();
        $grid->column('narrative', __('Narrative'));
        $grid->column('network_ref', __('Network ref'));
        $grid->column('external_ref', __('External ref'));
        $grid->column('Msisdn', __('Msisdn'));
        $grid->column('payer_names', __('Payer names'));
        $grid->column('payer_email', __('Payer email'));
        $grid->column('Signature', __('Signature'));
        $grid->column('get_data', __('Get data'))->hide();
        $grid->column('post_data', __('Post data'))->hide();

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
        $show = new Show(YoUgandaLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('date_time', __('Date time'));
        $show->field('amount', __('Amount'));
        $show->field('narrative', __('Narrative'));
        $show->field('network_ref', __('Network ref'));
        $show->field('external_ref', __('External ref'));
        $show->field('Msisdn', __('Msisdn'));
        $show->field('payer_names', __('Payer names'));
        $show->field('payer_email', __('Payer email'));
        $show->field('Signature', __('Signature'));
        $show->field('get_data', __('Get data'));
        $show->field('post_data', __('Post data'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new YoUgandaLog());

        $form->textarea('date_time', __('Date time'));
        $form->textarea('amount', __('Amount'));
        $form->textarea('narrative', __('Narrative'));
        $form->textarea('network_ref', __('Network ref'));
        $form->textarea('external_ref', __('External ref'));
        $form->textarea('Msisdn', __('Msisdn'));
        $form->textarea('payer_names', __('Payer names'));
        $form->textarea('payer_email', __('Payer email'));
        $form->textarea('Signature', __('Signature'));
        $form->textarea('get_data', __('Get data'));
        $form->textarea('post_data', __('Post data'));

        return $form;
    }
}
