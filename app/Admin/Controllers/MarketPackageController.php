<?php

namespace App\Admin\Controllers;

use App\Models\Market\MarketPackage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Country;
use Str;

class MarketPackageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Market Packages';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MarketPackage());
        $grid->quickSearch('name')->placeholder('Search by name');
        $grid->model()->orderBy('name', 'asc');
        $grid->column('name', __('Name'))->sortable();
        $grid->column('ents', __('Enterprise'))->display(function ($ents) {
            $ents = array_map(function ($ent) {
                return "<span class='label label-success'>{$ent['name']}</span>";
            }, $ents);
            return join('&nbsp;', $ents);
        });

        //count subscriptions
        $grid->column('subscriptions_count', __('Subscriptions'))->display(function () {
            return $this->subscriptions()->count();
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
        $show = new Show(MarketPackage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('country_id', __('Country id'));
        $show->field('name', __('Name'));
        $show->field('menu', __('Menu'));
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

        $market_packages =  MarketPackage::count();
        $next_market_package =  $market_packages + 1;
        $form = new Form(new MarketPackage());

        $form->select('country_id',  __('Select a country'))->options(Country::all()->pluck('name', 'id'));
        $form->text('name', __('Name of package'));
        $form->hidden('menu')->default($next_market_package);

        $form->multipleSelect('ents', 'Select enterprise')->options(Enterprise::all()->pluck('name', 'id'));

        $form->hasMany('pricing', function (Form\NestedForm $form) {

            $form->select('frequency', 'Select frequency')->options(['trial' => 'trial', 'daily' => 'daily', 'weekly' => 'weekly', 'monthly' => 'monthly', 'yearly' => 'yearly']);
            $form->text('cost', __('Cost of package'));
            $form->hidden('messages')->default(1);
        });

        $form->deleted(function () {

            info("gg");
        });

        return $form;
    }
}
