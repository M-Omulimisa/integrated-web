<?php

namespace App\Admin\Controllers;

use App\Models\AdminRole;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use \App\Models\NotificationCampaign;
use App\Models\NotificationMessage;
use App\Models\User;

class NotificationCampaignController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Notification Campaigns';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new NotificationCampaign());

        //order desc
        $grid->model()->orderBy('id', 'desc');
        $grid->column('id', __('SEND NOW'))
            ->display(function ($id) {
                $send_url = url('send-notification-campaigns?id=' . $id);
                return "<a  class='btn btn-success btn-sm' href='$send_url' target='_blank'>SEND NOW</a>";
            });

        $grid->column('created_at', __('Date'))
            ->sortable()
            ->display(function ($created_at) {
                return date('d M Y', strtotime($created_at));
            });
        $grid->column('title', __('Title'))
            ->sortable()
            ->filter('like')
            ->limit(25);
        $grid->column('short_description', __('Short Description'))
            ->limit(25)
            ->filter('like')
            ->sortable();
        $grid->column('body', __('Body'))->hide();
        $grid->column('image', __('Image'))
            ->image('', 100, 100)
            ->sortable();

        $grid->column('type', __('Type'))
            ->sortable()
            ->filter([
                'Text' => 'Text',
                'Url' => 'Url',
            ])
            ->label([
                'Text' => 'info',
                'Url' => 'success',
            ])
            ->hide();
        $grid->column('url', __('Url'))->hide();
        $grid->column('priority', __('Priority'))
            ->sortable()
            ->filter([
                'Normal' => 'Normal',
                'High' => 'High',
                'Urgent' => 'Urgent',
            ])
            ->label([
                'Normal' => 'info',
                'High' => 'warning',
                'Urgent' => 'danger',
            ])->hide();
        $grid->column('status', __('Status'))
            ->sortable()
            ->filter([
                'Draft' => 'Draft',
                'Published' => 'Published',
                'Scheduled' => 'Scheduled',
            ])
            ->dot([
                'Draft' => 'info',
                'Published' => 'success',
                'Scheduled' => 'warning',
            ]);
        $grid->column('ready_to_send', __('Ready to send'))->sortable()->hide();
        $grid->column('target_type', __('Target Type'))
            ->sortable()
            ->filter([
                'All' => 'All',
                'Role' => 'Role',
                'Users' => 'Users',
            ])
            ->label([
                'All' => 'info',
                'Role' => 'success',
                'Users' => 'warning',
            ]);
        $grid->column('target_user_role_id', __('Target user role'))->hide();
        $grid->column('target_users', __('Target users'))->display(function ($target_users) {
            if (is_array($target_users)) {
                return implode(', ', $target_users);
            }
            return $target_users;
        })->hide();
        $grid->column('send_notification', __('Notification'))
            ->sortable()
            ->filter([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label([
                'Yes' => 'success',
                'No' => 'danger',
            ]);
        $grid->column('send_email', __('email'))
            ->sortable()
            ->filter([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label([
                'Yes' => 'success',
                'No' => 'danger',
            ]);
        $grid->column('send_sms', __('SMS'))
            ->sortable()
            ->filter([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label([
                'Yes' => 'success',
                'No' => 'danger',
            ]);
        $grid->column('sheduled_at', __('Sheduled AT'))->hide();
        $grid->column('send_time', __('Send Time'))->hide();
        $grid->column('audience', __('Audience'))->display(function () {
            $total = NotificationMessage::where('notification_campaign_id', $this->id)->count();
            $seen = NotificationMessage::where('notification_campaign_id', $this->id)->where('notification_seen', 'Yes')->count();
            return "<span class='label label-info'>Total: $total</span> <span class='label label-success'>Seen: $seen</span>";
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
        $show = new Show(NotificationCampaign::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('title', __('Title'));
        $show->field('short_description', __('Short description'));
        $show->field('body', __('Body'));
        $show->field('image', __('Image'));
        $show->field('url', __('Url'));
        $show->field('type', __('Type'));
        $show->field('priority', __('Priority'));
        $show->field('status', __('Status'));
        $show->field('ready_to_send', __('Ready to send'));
        $show->field('target_type', __('Target type'));
        $show->field('target_user_role_id', __('Target user role id'));
        $show->field('target_users', __('Target users'))
            ->as(function ($target_users) {
                if (is_array($target_users)) {
                    return implode(', ', $target_users);
                }
                return $target_users;
            });
        $show->field('send_notification', __('Send notification'));
        $show->field('send_email', __('Send email'));
        $show->field('send_sms', __('Send sms'));
        $show->field('sheduled_at', __('Sheduled at'));
        $show->field('send_time', __('Send time'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new NotificationCampaign());
        $form->text('title', __('Title'))->rules('required');
        $form->text('short_description', __('Short Description'))->rules('required');

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
        $form->image('image', __('Image'));

        $form->radio('priority', __('Priority'))
            ->options([
                'Normal' => 'Normal',
                'High' => 'High',
                'Urgent' => 'Urgent',
            ])
            ->rules('required');

        $form->radio('target_type', __('Target type'))
            ->options([
                'All' => 'All',
                'Role' => 'Specific Role',
                'Users' => 'Specific Users',
            ])
            ->rules('required')
            ->when('Role', function (Form $form) {
                $form->select('target_user_role_id', __('Target user role id'))
                    ->options(\App\Models\AdminRole::all()->pluck('name', 'id'))
                    ->rules('required');
            })
            ->when('Users', function (Form $form) {
                $form->multipleSelect('target_users', __('Target users'))
                    ->options(User::getDropDownList([]));
            });

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
        /* $form->text('send_time', __('Send time'))->default('Now'); */


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
                    ])
                    ->rules('required')
                    ->help('This will send the notification to all target users. Please be sure before you click "Yes", this action cannot be undone.');
            });
        return $form;
    }
}
