<?php

namespace App\Admin\Controllers;

use App\Models\OrganisationJoiningRequest;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class OrganisationJoiningRequestController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Organisation Joining Requests';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrganisationJoiningRequest());
        $grid->disableBatchActions();
        $grid->disableCreateButton();
        $grid->model()
            ->where([
                'organisation_id' => Auth::user()->organisation_id,
            ])
            ->orderBy('id', 'desc');

        $grid->column('created_at', __('Date'))->display(function ($x) {
            return Utils::my_date($x);
        })->sortable();
        $grid->column('user_id', __('User'))->display(function ($x) {
            if ($this->user == null) {
                return $x;
            }
            return $this->user->name;
        })
            ->sortable();
        $grid->column('status', __('Status'))->label([
            'Accepted' => 'success',
            'Rejected' => 'danger',
            'Pending' => 'warning',
        ])->sortable();

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
        $show = new Show(OrganisationJoiningRequest::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('user_id', __('User id'));
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
        $form = new Form(new OrganisationJoiningRequest());

        $form->hidden('organisation_id', __('Organisation id'))
            ->default(Auth::user()->organisation_id);
        $form->select('user_id', __('User'))
            ->options(function ($id) {
                $user = \App\Models\User::find($id);
                if ($user) {
                    return [$user->id => $user->name];
                }
            })->rules('required');
        $form->text('status', __('Descision'))
            ->options([
                'Accepted' => 'Accepted',
                'Rejected' => 'Rejected',
                'Pending' => 'Pending',
            ])
            ->rules('required');

        return $form;
    }
}
