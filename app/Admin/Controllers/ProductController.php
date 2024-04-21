<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class ProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Products';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {


        $grid = new Grid(new Product());
        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        $grid->quickSearch('name')->placeholder('Search by name');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', 'Name');
            $cats = \App\Models\ProductCategory::all();
            $filter->equal('category', 'Category')->select(
                $cats->pluck('category', 'id')
            );
            $filter->between('price_1', 'Select Price');
            $filter->between('created_at', 'Created at')->datetime();
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable()
            ->editable();
        $grid->column('description', __('Description'))
            ->hide();

        $grid->column('price_2', __('Original Price'))
            ->sortable()
            ->editable();
        $grid->column('price_1', __('Selling Price'))
            ->sortable()
            ->editable();
        $grid->picture('feature_photo', __('Photo'))
            ->image('', 100, 100)
            ->hide();

        $grid->column('user', __('Vendor'))
            ->display(function ($user) {
                $u =  \App\Models\User::find($user);
                if ($u == null) {
                    return 'Deleted';
                }
                return $u->name;
            })
            ->sortable()->hide();

        $grid->column('category', __('Category'))
            ->display(function ($category) {
                $c =  \App\Models\ProductCategory::find($category);
                if ($c == null) {
                    return 'Deleted';
                }
                return $c->category;
            })
            ->sortable()
            ->hide();

        $grid->column('created_at', __('Created'))
            ->display(function ($created_at) {
                return Utils::my_date($created_at);
            })->sortable()->hide();

        $grid->column('in_house', __('IS IN HOUSE'))
            ->editable('select', ['No' => 'No', 'Yes' => 'Yes'])
            ->sortable();
        $grid->column('phone', __('Phone'))
            ->editable()
            ->sortable();
        /* 
        $form->hidden('in_house')->default('in_house');
        $form->hidden('phone')->default('phone');
*/
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
        $show = new Show(Product::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('metric', __('Metric'));
        $show->field('currency', __('Currency'));
        $show->field('description', __('Description'));
        $show->field('summary', __('Summary'));
        $show->field('price_1', __('Price 1'));
        $show->field('price_2', __('Price 2'));
        $show->field('feature_photo', __('Feature photo'));
        $show->field('rates', __('Rates'));
        $show->field('date_added', __('Date added'));
        $show->field('date_updated', __('Date updated'));
        $show->field('user', __('User'));
        $show->field('category', __('Category'));
        $show->field('sub_category', __('Sub category'));
        $show->field('supplier', __('Supplier'));
        $show->field('url', __('Url'));
        $show->field('status', __('Status'));
        $show->field('in_stock', __('In stock'));
        $show->field('keywords', __('Keywords'));
        $show->field('p_type', __('P type'));
        $show->field('local_id', __('Local id'));
        $show->field('updated_at', __('Updated at'));
        $show->field('created_at', __('Created at'));
        $show->field('data', __('Data'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product());



        if ($form->isCreating()) {
            $form->hidden('user', __('Product provider'))->default(Auth::user()->id)->readOnly()->rules('required');
        }

        $form->text('name', __('Name'))
            ->rules('required');

        //measurement unit radio

        /* 
                'Liters',
                'Pieces',
                'Bags',
                'Boxes',
                'Packets',
                'Bottles',
                'Cans',
                'Units',
                 */
        $form->radio('in_stock', 'Mesurement Units')
            ->options([
                'KG' => 'KG',
                'Meters' => 'Meters',
                'Liters' => 'Liters',
                'Pieces' => 'Pieces',
                'Bags' => 'Bags',
                'Boxes' => 'Boxes',
                'Packets' => 'Packets',
                'Bottles' => 'Bottles',
                'Cans' => 'Cans',
                'Units' => 'Units',
            ])->rules('required');

        $form->decimal('price_2', __('Original Price'))
            ->rules('required');
        $form->decimal('price_1', __('Selling Price'))
            ->rules('required');
        $form->quill('description', __('Description'))
            ->rules('required');

        $form->image('feature_photo', __('Feature photo'))
            ->rules('required')
            ->attribute(['accept' => 'image/*']);

        $cats = \App\Models\ProductCategory::all();
        $form->select('category', __('Category'))
            ->options(
                $cats->pluck('category', 'id')
            )
            ->rules('required');


        $form->morphMany('images', __('Images'), function (Form\NestedForm $form) {
            $form->image('src', __('Image'))
                ->attribute(['accept' => 'image/*']);
            $u = Admin::user();

            /* $p = Product::find(request()->route()->parameter('product'));
            if($p == null){
                throw('Product not found');
            }
             */
            $form->hidden('administrator_id')->default($u->id);
            $form->hidden('note')->default('Web');
            $form->hidden('type')->default('Product');
        });

        $form->hidden('in_house')->default('');
        $form->hidden('phone')->default('');

        /* 	
	
size	
deleted_at	
	
product_id	
parent_endpoint		
	

*/
        /* $form->url('url', __('Url')); 
                $form->decimal('rates', __('Rates'));
        */
        /* $form->keyValue('summary', __('Data')); */



        $form->disableReset();
        $form->disableViewCheck();
        return $form;
    }
}
