<?php

namespace App\Admin\Controllers;

use App\Models\Market\MarketPackageMessage;
use App\Models\Market\MarketPackage;
use App\Models\MarketInfoMessageCampaign;
use App\Models\Settings\Language;
use App\Models\Utils;
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
    protected $title = 'Market Package Messages';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        $grid = new Grid(new MarketPackageMessage());
        $campaigns = [];
        foreach (MarketInfoMessageCampaign::where([])
            ->orderBy('created_at', 'desc')->get()
            as $campaign) {
            $campaigns[$campaign->id] = Utils::my_date($campaign->created_at);
        }
        $packs = [];
        foreach (MarketPackage::where([])
            ->orderBy('name', 'asc')->get()
            as $pack) {
            $packs[$pack->id] = $pack->name;
        }
        $langs = [];
        foreach (Language::where([])
            ->orderBy('name', 'asc')->get()
            as $pack) {
            $langs[$pack->id] = $pack->name;
        }

        $grid->model()
            ->orderBy('created_at', 'desc');
        $grid->column('created_at', 'Date')
            ->display(function ($x) {
                return Utils::my_date($x);
            })->width('90')
            ->filter('range', 'date')
            ->sortable();
        $grid->column('package_id', __('Package'))
            ->display(function ($package_id) {
                $f = \App\Models\Market\MarketPackage::find($package_id);

                if ($f == null) {

                    return 'Unknown';
                }
                return $f->name;
            })->filter($packs)->sortable();
        $grid->column('language_id', __('Language'))
            ->display(function ($language_id) {

                $f = \App\Models\Settings\Language::find($language_id);

                if ($f == null) {

                    return 'Unknown';
                }
                return $f->name;
            })->filter($langs)->sortable();

        $grid->column('message', __('Message'))
            ->filter('like')->sortable()
            ->limit(80);
        $grid->column('market_info_message_campaign_id', __('Campaign'))
            ->display(function () {
                if ($this->campaign == null) {
                    return '-';
                }
                return Utils::my_date($this->campaign->created_at);
            })
            ->filter($campaigns)
            ->sortable();
        //outboxes Count
        $grid->column('outbox_count', __('Outboxes'))
            ->display(function () {
                return $this->outboxes()->count();
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
