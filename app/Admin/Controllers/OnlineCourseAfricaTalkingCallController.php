<?php

namespace App\Admin\Controllers;

use App\Models\OnlineCourseAfricaTalkingCall;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OnlineCourseAfricaTalkingCallController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OnlineCourseAfricaTalkingCall';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OnlineCourseAfricaTalkingCall());
        $grid->model()->orderBy('id', 'desc');


        $grid->column('id', __('Id'));
        $grid->column('recordingUrl', __('recordingUrl'))
            ->sortable();
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->column('sessionId', __('SessionId'));
        $grid->column('type', __('Type'))->hide();
        $grid->column('phoneNumber', __('PhoneNumber'))->hide();
        $grid->column('status', __('Status'));
        $grid->column('postData', __('PostData'))->hide();
        $grid->column('cost', __('Cost'));
        $grid->column('callSessionState', __('CallSessionState'));
        $grid->column('direction', __('Direction'));
        $grid->column('callerCountryCode', __('CallerCountryCode'))->hide();
        $grid->column('destinationCountryCode', __('DestinationCountryCode'))->hide();
        $grid->column('amount', __('Amount'));
        $grid->column('durationInSeconds', __('DurationInSeconds'))->hide();
        $grid->column('callerNumber', __('CallerNumber'));
        $grid->column('destinationNumber', __('DestinationNumber'));
        $grid->column('callerCarrierName', __('CallerCarrierName'));
        $grid->column('callStartTime', __('CallStartTime'));
        $grid->column('isActive', __('IsActive'));
        $grid->column('currencyCode', __('CurrencyCode'));
        $grid->column('digit', __('Digit'));
        $grid->column('has_error', __('Has error'));
        $grid->column('error_message', __('Error message'));

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
        $show = new Show(OnlineCourseAfricaTalkingCall::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('sessionId', __('SessionId'));
        $show->field('type', __('Type'));
        $show->field('phoneNumber', __('PhoneNumber'));
        $show->field('status', __('Status'));
        $show->field('postData', __('PostData'));
        $show->field('cost', __('Cost'));
        $show->field('callSessionState', __('CallSessionState'));
        $show->field('direction', __('Direction'));
        $show->field('callerCountryCode', __('CallerCountryCode'));
        $show->field('destinationCountryCode', __('DestinationCountryCode'));
        $show->field('amount', __('Amount'));
        $show->field('durationInSeconds', __('DurationInSeconds'));
        $show->field('callerNumber', __('CallerNumber'));
        $show->field('destinationNumber', __('DestinationNumber'));
        $show->field('callerCarrierName', __('CallerCarrierName'));
        $show->field('callStartTime', __('CallStartTime'));
        $show->field('isActive', __('IsActive'));
        $show->field('currencyCode', __('CurrencyCode'));
        $show->field('digit', __('Digit'));
        $show->field('has_error', __('Has error'));
        $show->field('error_message', __('Error message'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OnlineCourseAfricaTalkingCall());

        $form->text('sessionId', __('SessionId'));
        $form->text('type', __('Type'));
        $form->textarea('phoneNumber', __('PhoneNumber'));
        $form->textarea('status', __('Status'));
        $form->textarea('postData', __('PostData'));
        $form->number('cost', __('Cost'));
        $form->textarea('callSessionState', __('CallSessionState'));
        $form->textarea('direction', __('Direction'));
        $form->textarea('callerCountryCode', __('CallerCountryCode'));
        $form->textarea('destinationCountryCode', __('DestinationCountryCode'));
        $form->text('amount', __('Amount'));
        $form->text('durationInSeconds', __('DurationInSeconds'));
        $form->text('callerNumber', __('CallerNumber'));
        $form->text('destinationNumber', __('DestinationNumber'));
        $form->text('callerCarrierName', __('CallerCarrierName'));
        $form->text('callStartTime', __('CallStartTime'));
        $form->text('isActive', __('IsActive'));
        $form->text('currencyCode', __('CurrencyCode'));
        $form->text('digit', __('Digit'));
        $form->text('has_error', __('Has error'))->default('No');
        $form->textarea('error_message', __('Error message'));

        return $form;
    }
}
