<?php

namespace App\Admin\Controllers;

use App\Models\Market\MarketPackageMessage;
use App\Models\Market\MarketPackage;
use App\Models\Settings\Language;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MartketPackageMessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'MarketPackageMessage';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MarketPackageMessage());


        $grid->column('package_id', __('Package'))
        ->display(function ($package_id) {

            $f = \App\Models\Market\MarketPackage::find($package_id);

            if ($f == null) {

                return 'Unknown';

            }
            return $f->name;
        });
        $grid->column('language_id', __('Language'))
        ->display(function ($language_id) {

            $f = \App\Models\Settings\Language::find($language_id);

            if ($f == null) {

                return 'Unknown';

            }
            return $f->name;
        });
 
        $grid->column('message', __('Message'));


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
        $show = new Show(MarketPackageMessage::findOrFail($id));

        $show->field('message', __('Message'));
     

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        $market_packages =  MarketPackageMessage::count();
        $next_market_package =  $market_packages + 1;

        $form = new Form(new MarketPackageMessage());

        $form->select('package_id',  __('Select a package'))->options(MarketPackage::all()->pluck('name', 'id'));
        
        $form->select('language_id',  __('Select a language'))->options(Language::all()->pluck('name', 'id'));

        $form->hidden('menu')->default($next_market_package);

        $form->textarea('message', __('Message'));

        return $form;
    }
}
