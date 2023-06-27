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

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('country_id', __('Country id'));
        $grid->column('organisation_id', __('Organisation id'));
        $grid->column('code', __('Code'));
        $grid->column('address', __('Address'));
        $grid->column('group_leader', __('Group leader'));
        $grid->column('group_leader_contact', __('Group leader contact'));
        $grid->column('establishment_year', __('Establishment year'));
        $grid->column('registration_year', __('Registration year'));
        $grid->column('meeting_venue', __('Meeting venue'));
        $grid->column('meeting_days', __('Meeting days'));
        $grid->column('meeting_time', __('Meeting time'));
        $grid->column('meeting_frequency', __('Meeting frequency'));
        $grid->column('location_id', __('Location id'));
        $grid->column('last_cycle_savings', __('Last cycle savings'));
        $grid->column('registration_certificate', __('Registration certificate'));
        $grid->column('latitude', __('Latitude'));
        $grid->column('longitude', __('Longitude'));
        $grid->column('status', __('Status'));
        $grid->column('photo', __('Photo'));
        $grid->column('id_photo_front', __('Id photo front'));
        $grid->column('id_photo_back', __('Id photo back'));
        $grid->column('created_by_user_id', __('Created by user id'));
        $grid->column('created_by_agent_id', __('Created by agent id'));
        $grid->column('agent_id', __('Agent id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $form->textarea('registration_certificate', __('Registration certificate'));
        $form->text('latitude', __('Latitude'));
        $form->text('longitude', __('Longitude'));
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
        $form->textarea('photo', __('Photo'));
        $form->textarea('id_photo_front', __('Id photo front'));
        $form->textarea('id_photo_back', __('Id photo back'));
        $form->text('created_by_user_id', __('Created by user id'));
        $form->text('created_by_agent_id', __('Created by agent id'));
        $form->text('agent_id', __('Agent id'));

        return $form;
    }
}
