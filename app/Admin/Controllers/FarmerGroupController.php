<?php

namespace App\Admin\Controllers;

use App\Models\Farmers\FarmerGroup;
use App\Models\Settings\Country;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class FarmerGroupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Farmer Groups';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new FarmerGroup());

        $grid->disableBatchActions();
        $grid->column('name', __('Group Name'))->sortable();
        $grid->column('organisation_id', __('Organisation'))->display(function ($x) {
            if ($this->organisation == 'null') {
                return $x;
            }
            return $this->organisation->name;
        })->sortable();
        $grid->column('address', __('Address'));
        $grid->column('group_leader', __('Group leader'));
        $grid->column('group_leader_contact', __('Contact'));
        $grid->column('establishment_year', __('Establishment year'));
        $grid->column('registration_year', __('Registration year'))->hide();
        $grid->column('meeting_venue', __('Meeting venue'))->hide();
        $grid->column('meeting_days', __('Meeting days'))->hide();
        $grid->column('meeting_time', __('Meeting time'))->hide();
        $grid->column('meeting_frequency', __('Meeting frequency'))->hide();
        $grid->column('location_id', __('Location'))->hide();
        $grid->column('last_cycle_savings', __('Last cycle savings'))->hide();
        $grid->column('registration_certificate', __('Registration certificate'));
        $grid->column('latitude', __('Latitude'))->hide();
        $grid->column('longitude', __('Longitude'))->hide();
        $grid->column('status', __('Status'))->hide();
        $grid->column('photo', __('Photo'))->hide();
        $grid->column('id_photo_front', __('Id photo front'))->hide();
        $grid->column('id_photo_back', __('Id photo back'))->hide();

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
        $show = new Show(FarmerGroup::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('country_id', __('Country id'));
        $show->field('organisation_id', __('Organisation id'));
        $show->field('code', __('Code'));
        $show->field('address', __('Address'));
        $show->field('group_leader', __('Group leader'));
        $show->field('group_leader_contact', __('Group leader contact'));
        $show->field('establishment_year', __('Establishment year'));
        $show->field('registration_year', __('Registration year'));
        $show->field('meeting_venue', __('Meeting venue'));
        $show->field('meeting_days', __('Meeting days'));
        $show->field('meeting_time', __('Meeting time'));
        $show->field('meeting_frequency', __('Meeting frequency'));
        $show->field('location_id', __('Location id'));
        $show->field('last_cycle_savings', __('Last cycle savings'));
        $show->field('registration_certificate', __('Registration certificate'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('status', __('Status'));
        $show->field('photo', __('Photo'));
        $show->field('id_photo_front', __('Id photo front'));
        $show->field('id_photo_back', __('Id photo back'));
        $show->field('created_by_user_id', __('Created by user id'));
        $show->field('created_by_agent_id', __('Created by agent id'));
        $show->field('agent_id', __('Agent id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new FarmerGroup());

        $u = Auth::user();
        $form->hidden('organisation_id', __('Organisation id'))
            ->default($u->organisation_id);
        $form->select('country_id', __('Country'))
            ->options(Country::where([])
                ->orderBy('name', 'asc')
                ->get()->pluck('name', 'id'))
            ->rules('required');

        $form->text('name', __('Group Name'))->rules('required');
        $form->text('code', __('Country Code'))->rules('required');
        $form->text('address', __('Address'));
        $form->text('group_leader', __('Group leader'));
        $form->text('group_leader_contact', __('Group leader contact'));
        $form->text('establishment_year', __('Establishment year'));
        $form->text('registration_year', __('Registration year'));
        $form->text('meeting_venue', __('Meeting venue'));
        $form->text('meeting_days', __('Meeting days'));
        $form->text('meeting_time', __('Meeting time'));
        $form->text('location_id', __('Location id'));
        $form->decimal('last_cycle_savings', __('Last cycle savings'))->default(0.00);
        $form->file('registration_certificate', __('Registration certificate'));
        $form->text('latitude', __('GPS Latitude'));
        $form->text('longitude', __('GPS Longitude'));
        $form->select('status', __('Status'))
            ->options([
                'Invited' => 'Invited',
                'Active' => 'Active',
                'Inactive' => 'Inactive',
                'Suspended' => 'Suspended',
                'Banned' => 'Banned'
            ])
            ->default('Active')
            ->rules('required');
        $form->image('photo', __('Photo'));
        $form->textarea('id_photo_front', __('Id photo front'));
        $form->textarea('id_photo_back', __('Id photo back'));
        $form->text('created_by_user_id', __('Created by user'));
        $form->text('created_by_agent_id', __('Created by agent'));
        $form->text('agent_id', __('Agent id'));

        return $form;
    }
}
