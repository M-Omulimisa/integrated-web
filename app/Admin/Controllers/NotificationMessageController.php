<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\NotificationMessage;

class NotificationMessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Notification Messages';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new NotificationMessage());

        //order by
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('SEND NOW'))
            ->display(function ($id) {
                $send_url = url('send-notification?id=' . $id);
                return "<a class='btn btn-sm btn-success' href='$send_url' target='_blank'>SEND NOW</a>";
            });

        $grid->column('notification_campaign_id', __('Campaign'))
            ->display(function ($id) {
                $campaign = \App\Models\NotificationCampaign::find($id);
                if ($campaign) {
                    return $campaign->title;
                }
                return "N/A";
            })->sortable()
            ->hide();
        $grid->column('user_id', __('Reciever'))
            ->display(function ($id) {
                $user = \App\Models\User::find($id);
                if ($user) {
                    return $user->name;
                }
                return "N/A";
            })->sortable();
        $grid->column('created_at', __('DATE'))
            ->sortable()
            ->display(function ($date) {
                return date('d M Y', strtotime($date));
            })
            ->hide();
        $grid->column('title', __('Title'))
            ->sortable()
            ->limit(30);
        $grid->column('phone_number', __('Phone Number'))
            ->sortable();
        $grid->column('email', __('Email'))->sortable();
        $grid->column('sms_body', __('Sms body'))
            ->limit(30)
            ->sortable();
        $grid->column('short_description', __('Short description'))
            ->limit(30)
            ->sortable();
        $grid->column('image', __('Image'))
            ->image('', 100, 100)
            ->sortable();
        $grid->column('url', __('Url'))->sortable()->hide();
        $grid->column('type', __('Type'))->sortable()->hide();
        $grid->column('priority', __('Priority'))->sortable()->hide();
        $grid->column('status', __('Status'))
            ->sortable()
            ->label([
                'Draft' => 'default',
                'Published' => 'success',
                'Scheduled' => 'info',
            ])
            ->filter([
                'Draft' => 'Draft',
                'Published' => 'Published',
                'Scheduled' => 'Scheduled',
            ]);
        $grid->column('ready_to_send', __('Ready to send'))
            ->sortable()
            ->label([
                'No' => 'danger',
                'Yes' => 'success',
            ])
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes',
            ])->hide();
        $grid->column('send_notification', __('Notification'))->sortable()->hide();
        $grid->column('send_email', __('Email'))->sortable()
            ->label([
                'No' => 'danger',
                'Yes' => 'success',
            ])
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes',
            ]);
        $grid->column('send_sms', __('Send sms'))->sortable()
            ->label([
                'No' => 'danger',
                'Yes' => 'success',
            ])
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes',
            ]);
        $grid->column('sheduled_at', __('Sheduled at'))->hide();
        $grid->column('email_sent', __('Email sent'))
            ->sortable()
            ->label([
                'No' => 'danger',
                'Yes' => 'success',
            ])
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes',
            ]);
        $grid->column('sms_sent', __('Sms sent'))->sortable()
            ->label([
                'No' => 'danger',
                'Yes' => 'success',
            ])
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes',
            ]);
        $grid->column('notification_sent', __('Notification Sent'))->sortable()
            ->label([
                'No' => 'danger',
                'Yes' => 'success',
            ])
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes',
            ]);

        $grid->column('notification_seen', __('Notification seen'))
            ->sortable()
            ->label([
                'No' => 'danger',
                'Yes' => 'success',
            ])
            ->filter([
                'No' => 'No',
                'Yes' => 'Yes',
            ]);

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
        $show = new Show(NotificationMessage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('notification_campaign_id', __('Notification campaign id'));
        $show->field('user_id', __('User id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('title', __('Title'));
        $show->field('phone_number', __('Phone number'));
        $show->field('email', __('Email'));
        $show->field('sms_body', __('Sms body'));
        $show->field('short_description', __('Short description'));
        $show->field('body', __('Body'));
        $show->field('image', __('Image'));
        $show->field('url', __('Url'));
        $show->field('type', __('Type'));
        $show->field('priority', __('Priority'));
        $show->field('status', __('Status'));
        $show->field('ready_to_send', __('Ready to send'));
        $show->field('send_notification', __('Send notification'));
        $show->field('send_email', __('Send email'));
        $show->field('send_sms', __('Send sms'));
        $show->field('sheduled_at', __('Sheduled at'));
        $show->field('email_sent', __('Email sent'));
        $show->field('sms_sent', __('Sms sent'));
        $show->field('notification_seen', __('Notification seen'));
        $show->field('notification_seen_time', __('Notification seen time'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new NotificationMessage());

        if (!$form->isCreating()) {
            $form->display('notification_campaign_id', __('Notification Campaign'))
                ->with(function ($id) {
                    $campaign = \App\Models\NotificationCampaign::find($id);
                    if ($campaign) {
                        return $campaign->title;
                    }
                    return "N/A";
                });
            //reciever
            $form->display('user_id', __('User'))
                ->with(function ($id) {
                    $user = \App\Models\User::find($id);
                    if ($user) {
                        return $user->name;
                    }
                    return "N/A";
                });
        } else {
            $form->select('notification_campaign_id', __('Notification Campaign'))
                ->options(\App\Models\NotificationCampaign::all()->pluck('title', 'id'));
            $form->select('user_id', __('Reciever'))
                ->options(\App\Models\User::getDropDownList([]))
                ->rules('required');
        }
        $form->text('title', __('Title'))->rules('required');
        $form->text('phone_number', __('Phone Number'));
        $form->email('email', __('Email'));

        $form->radio('type', __('Type'))
            ->options([
                'Text' => 'Text',
                'Url' => 'Url',
            ])
            ->rules('required')
            ->when('Text', function (Form $form) {
                $form->quill('body', __('Body'))->rules('required');
            })
            ->when('Url', function (Form $form) {
                $form->url('url', __('Url'))->rules('required');
            });

        $form->text('short_description', __('Short Description'))->rules('required');
        $form->image('image', __('Image'));
        $form->radio('priority', __('Priority'))->default('Normal')
            ->rules('required')
            ->options([
                'Normal' => 'Normal',
                'High' => 'High',
                'Urgent' => 'Urgent',
            ]);

        $form->radio('send_notification', __('Send notification'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->rules('required');
        $form->radio('send_email', __('Send Email'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->rules('required');
        $form->radio('send_sms', __('Send SMS'))
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->rules('required')
            ->When('Yes', function (Form $form) {
                $form->textarea('sms_body', __('sms_body'))->rules('required');
            });



        $form->radio('status', __('Publish Status'))
            ->options([
                'Draft' => 'Save as Draft',
                'Published' => 'Publish Now',
                'Scheduled' => 'Schedule for Later',
            ])
            ->rules('required')
            ->when('Scheduled', function (Form $form) {
                $form->datetime('sheduled_at', __('Sheduled at'))
                    ->rules('required')
                    ->help('This will send the notification to all target users at the specified time.');
            })
            ->when('Published', function (Form $form) {
                $form->radio('ready_to_send', __('Are you ready to send?'))
                    ->options([
                        'No' => 'No',
                        'Yes' => 'Yes',
                        'Sent' => 'Sent'
                    ])
                    ->rules('required')
                    ->help('This will send the notification to all target users. Please be sure before you click "Yes", this action cannot be undone.');

                $form->radio('email_sent', __('Email sent'))->default('No')
                    ->options([
                        'No' => 'No',
                        'Yes' => 'Yes',
                    ])
                    ->rules('required');
                $form->radio('sms_sent', __('Sms sent'))->default('No')
                    ->options([
                        'No' => 'No',
                        'Yes' => 'Yes',
                    ])
                    ->rules('required');
                /* $form->radio('notification_seen', __('Notification seen'))->default('No')
                    ->options([
                        'No' => 'No',
                        'Yes' => 'Yes',
                    ])
                    ->rules('required'); */
                $form->radio('notification_sent', __('Notification Sent'))->default('No')
                    ->options([
                        'No' => 'No',
                        'Yes' => 'Yes',
                    ])
                    ->rules('required');
            });

        return $form;
    }
}
