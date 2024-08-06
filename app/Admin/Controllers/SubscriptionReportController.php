<?php

namespace App\Admin\Controllers;

use App\Models\Organisations\Organisation;
use App\Models\SubscriptionReport;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SubscriptionReportController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Subscription Reports';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SubscriptionReport());
        /*         $s = SubscriptionReport::find(1);
        $s = SubscriptionReport::prepare($s);
        $s->save();
        dd($s); */

        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Sn.'))->sortable();
        $grid->column('created_at', __('DATE'));
        $grid->column('title', __('Title'));
        $grid->column('organization_id', __('Organization id'));
        $grid->column('markert_subs_count', __('Markert subs count'));
        $grid->column('markert_sms_count', __('Markert sms count'));
        $grid->column('date_type', __('Date type'));
        $grid->column('start_date', __('Start date'));
        $grid->column('end_date', __('End date'));
        $grid->column('is_generated', __('Is generated'));
        $grid->column('date_generated', __('Date generated'));
        $grid->column('pdf_file', __('PRINT'))
            ->display(function ($pdf_file) {
                $link = url('subscription-reports-print?pdf=' . $this->id); 
                //in new tab
                return "<a href='$link' target='_blank'>Print</a>"; 
            });

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
        $show = new Show(SubscriptionReport::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('title', __('Title'));
        $show->field('organization_id', __('Organization id'));
        $show->field('markert_subs_count', __('Markert subs count'));
        $show->field('markert_sms_count', __('Markert sms count'));
        $show->field('date_type', __('Date type'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('is_generated', __('Is generated'));
        $show->field('date_generated', __('Date generated'));
        $show->field('pdf_file', __('Pdf file'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SubscriptionReport());

        if ($form->isCreating()) {
            $form->hidden('is_generated', __('Is generated'))->default('No');
        }
        if (!$form->isCreating()) {
            $form->text('title', __('Title'))->rules('required');
        }
        $form->select('organization_id', __('Selecting Organization'))
            ->options(Organisation::all()->pluck('name', 'id'))
            ->rules('required');

        $form->radio('date_type', __('Date type'))
            ->options([
                'this_week' => 'This week',
                'previous_week' => 'Previous week',
                'last_week' => 'Last week',
                'this_month' => 'This month',
                'previous_month' => 'Previous month',
                'last_month' => 'Last month',
                'this_year' => 'This year',
                'previous_year' => 'Previous year',
                'last_year' => 'Last year',
                'custom' => 'Custom',
            ])
            ->rules('required')
            ->when('custom', function (Form $form) {
                $form->date('start_date', __('Start date'))->default(date('Y-m-d'))->rules('required');
                $form->date('end_date', __('End date'))->default(date('Y-m-d'))->rules('required|after:start_date');
            });

        if (!$form->isCreating()) {
            $form->radio('is_generated', __('Is generated'))->default('No');
        }


        if (!$form->isCreating()) {
            $form->radio('is_generated', __('Regenerate Report'))
                ->options([
                    'No' => 'Yes',
                    'Yes' => 'No',
                ])
                ->rules('required');
        }

        return $form;
    }
}
