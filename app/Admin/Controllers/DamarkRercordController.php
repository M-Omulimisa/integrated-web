<?php

namespace App\Admin\Controllers;

use App\Models\DamarkRercord;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Schema;

class DamarkRercordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'DMARK Records';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        /* $table = 'ussd_session_data';
        $tables = Schema::getColumnListing($table);
        $col = 'option_mappings';
        $cols = [];
        foreach ($tables as $t) {
            $cols[] = $t;
        }

        if (!in_array($col, $cols)) {
            Schema::table($table, function ($table) {
                $table->text('option_mappings')->nullable();
            });
            die("column added");
        } */
        $grid = new Grid(new DamarkRercord());
        /* $rec = DamarkRercord::find(1);
        $rec->message_body = "Mulimisa Kasese Bwera Muhindo Mubarak ".rand(1, 100);
        $rec->is_processed = "No";
        $rec->sender = "+256783204665";
        $rec->save(); */


        $grid->model()->orderBy('created_at', 'desc');
        $grid->quickSearch('message_body', 'sender', 'external_ref', 'farmer_id', 'question_id')->placeholder('Search...');

        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return date('d-m-Y H:i:s', strtotime($created_at));
            })->sortable();
        $grid->column('sender', __('Sender Phone'))
            ->display(function ($sender) {
                return $sender;
            })->sortable();
        $grid->column('message_body', __('Message Body'))
            ->display(function ($message_body) {
                return Utils::short($message_body, 50);
            })->sortable();
        $grid->column('external_ref', __('External ref'))->hide();
        $grid->column('post_data', __('Post Data'))->hide();
        $grid->column('get_data', __('Get Data'))->hide();
        $grid->column('is_processed', __('Is Processed'))
            ->label([
                'Yes' => 'success',
                'No' => 'danger',
            ])->sortable();
        $grid->column('status', __('Status'))
            ->label([
                'Pending' => 'warning',
                'Failed' => 'danger',
                'Sent' => 'success',
            ])->sortable();
        $grid->column('error_message', __('Error message'))->hide()->sortable();
        $grid->column('type', __('Type'))->label([
            'Registration' => 'primary',
            'Question' => 'info',
            'Other' => 'warning',
        ])->sortable();
        $grid->column('farmer_id', __('Farmer id'))->hide()->sortable();
        $grid->column('question_id', __('Question id'))->hide()->sortable();

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
        $show = new Show(DamarkRercord::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('sender', __('Sender'));
        $show->field('message_body', __('Message body'));
        $show->field('external_ref', __('External ref'));
        $show->field('post_data', __('Post data'));
        $show->field('get_data', __('Get data'));
        $show->field('is_processed', __('Is processed'));
        $show->field('status', __('Status'));
        $show->field('error_message', __('Error message'));
        $show->field('type', __('Type'));
        $show->field('farmer_id', __('Farmer id'));
        $show->field('question_id', __('Question id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DamarkRercord());

        $form->text('sender', __('Sender'))->required();
        $form->textarea('message_body', __('Message Body'))->required();
        $form->radio('is_processed', __('Is Processed'))
            ->options(['Yes' => 'Yes', 'No' => 'No'])
            ->default('No');

        return $form;
    }
}
