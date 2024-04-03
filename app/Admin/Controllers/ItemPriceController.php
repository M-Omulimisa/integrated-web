<?php

namespace App\Admin\Controllers;

use App\Models\ItemPrice;
use App\Models\Settings\Enterprise;
use App\Models\Settings\MeasureUnit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Schema;

class ItemPriceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Item Prices';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $table = (new ItemPrice())->getTable();
        $tables = Schema::getColumnListing($table);
        $cols_to_add = ['price_type', 'price_1'];
        $cols = [];
        foreach ($tables as $t) {
            $cols[] = $t;
        }

        foreach ($cols_to_add as $col) {
            if (!in_array($col, $cols)) {
                Schema::table($table, function ($table) use ($col) {
                    if ($col == 'price_type') {
                        $table->string('price_type')->nullable()->default('Single');
                    } else {
                        $table->bigInteger('price_1')->nullable();
                    }
                });
            }
        }

        $grid = new Grid(new ItemPrice());
        $ents = [];
        foreach (Enterprise::where([])
            ->orderBy('name', 'asc')
            ->get() as $key => $value) {
            if ($value->unit != null) {
                $value->name .= ' (' . $value->unit->slug . ')';
            }
            $ents[$value->id] = $value->name;
        }
        $grid->model()->orderBy('created_at', 'desc');
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();

        $grid->column('due_to_date', __('Due to date'))
            ->display(function ($due_to_date) {
                return date('d-m-Y', strtotime($due_to_date));
            })->sortable()
            ->width(150)
            ->filter('range', 'date');
        $grid->column('item_id', __('Item'))
            ->display(function ($item_id) {
                if ($this->item == null) {
                    return '-';
                }
                return $this->item->name_text;
            })->sortable()
            ->width(250)
            ->filter($ents);
        $grid->column('price', __('Unit Price'))
            ->sortable()
            ->filter('range')
            ->display(function ($price) {
                if ($this->price_type == 'Range') {
                    return number_format($price) . ' - ' . number_format($this->price_1);
                }
                return number_format($price);
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
        $show = new Show(ItemPrice::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('item_id', __('Item id'));
        $show->field('price', __('Price'));
        $show->field('due_to_date', __('Due to date'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ItemPrice());

        $ents = [];
        foreach (Enterprise::where([])
            ->orderBy('name', 'asc')
            ->get() as $key => $value) {
            if ($value->unit != null) {
                $value->name .= ' (' . $value->unit->slug . ')';
            }
            $ents[$value->id] = $value->name;
        }

        $form->date('due_to_date', __('Due to date'))
            ->default(date('Y-m-d'))
            ->rules('required');
        $form->select(
            'item_id',
            __('Select Enterprise')
        )->options($ents)
            ->rules('required');
        $form->radio('price_type', __('Price Type'))
            ->options(['Single' => 'Single', 'Range' => 'Range'])
            ->default('Single')
            ->when('Range', function (Form $form) {
                $form->decimal('price', __('Price (UGX)'))
                    ->rules('required');
                $form->decimal('price_1', __('Price 1'))
                    ->rules('required');
            })->when('Single', function (Form $form) {
                $form->decimal('price', __('Price (UGX)'))
                    ->rules('required');
            });



        return $form;
    }
}
