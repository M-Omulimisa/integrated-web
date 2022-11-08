<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;

use App\Models\Settings\Type;
use App\Models\Users\Permission;
use Database\Seeders\Traits\DisableForeignKeys;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Database\Seeders\Traits\FormatPermission;

class PermissionSeeder extends Seeder
{
    use DisableForeignKeys, Uuid, FormatPermission;
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        Permission::query()->truncate();
        $appId = Type::findByAlias('app')->id;
        $guardName = 'web';
        $now = \Carbon\Carbon::now();

        $permissions = [
            /*******************************ROLES*********************************************/
            $this->webPermissions('manage_roles', $appId, 'users', 'Create, Update'),            
            $this->webPermissions('list_roles', $appId, 'users', 'List, View, Download'),
            $this->webPermissions('delete_roles', $appId, 'users', 'Delete'),

            $this->webPermissions('manage_permissions', $appId, 'users', 'Create, Update'),            
            $this->webPermissions('list_permissions', $appId, 'users', 'List, View, Download'),
            $this->webPermissions('delete_permissions', $appId, 'users', 'Delete'),

            $this->webPermissions('manage_users', $appId, 'users', 'Create, Update'),            
            $this->webPermissions('list_users', $appId, 'users', 'List, View, Download'),
            $this->webPermissions('delete_users', $appId, 'users', 'Delete'),

            $this->webPermissions('manage_settings', $appId, 'settings', 'Create, Update'),            
            $this->webPermissions('list_settings', $appId, 'settings', 'List, View, Download'),
            $this->webPermissions('delete_settings', $appId, 'settings', 'Delete'),

            $this->webPermissions('manage_organisations', $appId, 'organisations', 'Create, Update'),            
            $this->webPermissions('list_organisations', $appId, 'organisations', 'List, View, Download'),
            $this->webPermissions('delete_organisations', $appId, 'organisations', 'Delete'),

            $this->webPermissions('manage_organisation_settings', $appId, 'organisations', 'Create, Update'),            
            $this->webPermissions('list_organisation_settings', $appId, 'organisations', 'List, View, Download'),
            $this->webPermissions('delete_organisation_settings', $appId, 'organisations', 'Delete'),   

            $this->webPermissions('manage_organisation_users', $appId, 'organisations', 'Create, Update'),            
            $this->webPermissions('list_organisation_users', $appId, 'organisations', 'List, View, Download'),
            $this->webPermissions('delete_organisation_users', $appId, 'organisations', 'Delete'), 

            $this->webPermissions('manage_farmers', $appId, 'farmers', 'Create, Update'),            
            $this->webPermissions('list_farmers', $appId, 'farmers', 'List, View, Download'),
            $this->webPermissions('delete_farmers', $appId, 'farmers', 'Delete'), 

            $this->webPermissions('manage_farmer_groups', $appId, 'farmers', 'Create, Update'),            
            $this->webPermissions('list_farmer_groups', $appId, 'farmers', 'List, View, Download'),
            $this->webPermissions('delete_farmer_groups', $appId, 'farmers', 'Delete'),

            $this->webPermissions('manage_village_agents', $appId, 'agents', 'Create, Update'),            
            $this->webPermissions('list_village_agents', $appId, 'agents', 'List, View, Download'),
            $this->webPermissions('delete_village_agents', $appId, 'agents', 'Delete'),  

            $this->webPermissions('manage_extension_officers', $appId, 'extension', 'Create, Update'),            
            $this->webPermissions('list_extension_officers', $appId, 'extension', 'List, View, Download'),
            $this->webPermissions('delete_extension_officers', $appId, 'extension', 'Delete'), 

            $this->webPermissions('manage_extension_officer_positions', $appId, 'extension', 'Create, Update'),            
            $this->webPermissions('list_extension_officer_positions', $appId, 'extension', 'List, View, Download'),
            $this->webPermissions('delete_extension_officer_positions', $appId, 'extension', 'Delete'), 

            $this->webPermissions('manage_questions', $appId, 'questions', 'Create, Update'),            
            $this->webPermissions('list_questions', $appId, 'questions', 'List, View, Download'),
            $this->webPermissions('delete_questions', $appId, 'questions', 'Delete'), 

            $this->webPermissions('manage_question_responses', $appId, 'questions', 'Create, Update'),            
            $this->webPermissions('list_question_responses', $appId, 'questions', 'List, View, Download'),
            $this->webPermissions('delete_question_responses', $appId, 'questions', 'Delete'), 

            // TRAINING

            $this->webPermissions('manage_training_topics', $appId, 'trainings', 'Create, Update'),            
            $this->webPermissions('list_training_topics', $appId, 'trainings', 'List, View, Download'),
            $this->webPermissions('delete_training_topics', $appId, 'trainings', 'Delete'),  

            $this->webPermissions('manage_trainings', $appId, 'trainings', 'Create, Update'),            
            $this->webPermissions('list_trainings', $appId, 'trainings', 'List, View, Download'),
            $this->webPermissions('delete_trainings', $appId, 'trainings', 'Delete'),

            $this->webPermissions('manage_training_resources', $appId, 'trainings', 'Create, Update'),            
            $this->webPermissions('list_training_resources', $appId, 'trainings', 'List, View, Download'),
            $this->webPermissions('delete_training_resources', $appId, 'trainings', 'Delete'), 

            /* el_lecture_attendances */ $this->webPermissions('list_el_lecture_attendances', $appId, 'elearning', 'List'),
            /* el_lecture_attendances */ $this->webPermissions('view_el_lecture_attendances', $appId, 'elearning', 'View'),
            /* course_announcements */ $this->webPermissions('view_course_announcements', $appId, 'elearning', 'List'),
            /* el_announcements */ $this->webPermissions('list_el_announcements', $appId, 'elearning', 'List'),
            /* el_announcements */ $this->webPermissions('view_el_announcements', $appId, 'elearning', 'View'),
            /* el_announcements */ $this->webPermissions('add_el_announcements', $appId, 'elearning', 'Create'),
            /* el_announcements */ $this->webPermissions('edit_el_announcements', $appId, 'elearning', 'Edit'),
            /* el_announcements */ $this->webPermissions('delete_el_announcements', $appId, 'elearning', 'Delete'),
            /* el_announcements */ $this->webPermissions('download_el_announcements', $appId, 'elearning', 'Download'),
            /* el_announcement_views */ $this->webPermissions('list_el_announcement_views', $appId, 'elearning', 'List'),
            /* el_announcement_views */ $this->webPermissions('view_el_announcement_views', $appId, 'elearning', 'View'), 
            /* el_announcement_subscriptions */ $this->webPermissions('list_el_announcement_subscriptions', $appId, 'elearning', 'List'),
            /* el_announcement_subscriptions */ $this->webPermissions('view_el_announcement_subscriptions', $appId, 'elearning', 'View'),
            /* el_announcement_subscriptions */ $this->webPermissions('add_el_announcement_subscriptions', $appId, 'elearning', 'SUbscribe'),
            /* el_announcement_subscriptions */ $this->webPermissions('delete_el_announcement_subscriptions', $appId, 'elearning', 'Unsubscribe'),
            /* course_contents */ $this->webPermissions('view_course_contents', $appId, 'elearning', 'List'),
            /* el_chapters */ $this->webPermissions('list_el_chapters', $appId, 'elearning', 'List'),
            /* el_chapters */ $this->webPermissions('view_el_chapters', $appId, 'elearning', 'View'),
            /* el_chapters */ $this->webPermissions('add_el_chapters', $appId, 'elearning', 'Create'),
            /* el_chapters */ $this->webPermissions('edit_el_chapters', $appId, 'elearning', 'Edit'),
            /* el_chapters */ $this->webPermissions('delete_el_chapters', $appId, 'elearning', 'Delete'),
            /* el_chapters */ $this->webPermissions('download_el_chapters', $appId, 'elearning', 'Download'),
            /* el_lectures */ $this->webPermissions('list_el_lectures', $appId, 'elearning', 'List'),
            /* el_lectures */ $this->webPermissions('view_el_lectures', $appId, 'elearning', 'View'),
            /* el_lectures */ $this->webPermissions('add_el_lectures', $appId, 'elearning', 'Create'),
            /* el_lectures */ $this->webPermissions('edit_el_lectures', $appId, 'elearning', 'Edit'),
            /* el_lectures */ $this->webPermissions('delete_el_lectures', $appId, 'elearning', 'Delete'),
            /* el_lectures */ $this->webPermissions('download_el_lectures', $appId, 'elearning', 'Download'),
            /* el_lecture_topics */ $this->webPermissions('list_el_lecture_topics', $appId, 'elearning', 'List'),
            /* el_lecture_topics */ $this->webPermissions('view_el_lecture_topics', $appId, 'elearning', 'View'),
            /* el_lecture_topics */ $this->webPermissions('add_el_lecture_topics', $appId, 'elearning', 'Add'),
            /* el_lecture_topics */ $this->webPermissions('edit_el_lecture_topics', $appId, 'elearning', 'Edit'),
            /* el_lecture_topics */ $this->webPermissions('delete_el_lecture_topics', $appId, 'elearning', 'Block'),
            /* el_lecture_topics */ $this->webPermissions('download_el_lecture_topics', $appId, 'elearning', 'Download'),
            /* el_lecture_topic_responses */ $this->webPermissions('list_el_lecture_topic_responses', $appId, 'elearning', 'List'),
            /* el_lecture_topic_responses */ $this->webPermissions('view_el_lecture_topic_responses', $appId, 'elearning', 'View'),
            /* el_lecture_topic_responses */ $this->webPermissions('add_el_lecture_topic_responses', $appId, 'elearning', 'Add'),
            /* el_lecture_topic_responses */ $this->webPermissions('edit_el_lecture_topic_responses', $appId, 'elearning', 'Edit'),
            /* el_lecture_topic_responses */ $this->webPermissions('delete_el_lecture_topic_responses', $appId, 'elearning', 'Block'),
            /* el_lecture_topic_responses */ $this->webPermissions('download_el_lecture_topic_responses', $appId, 'elearning', 'Download'),
            /* el_lecture_topic_subscriptions */ $this->webPermissions('list_el_lecture_topic_subscriptions', $appId, 'elearning', 'List'),
            /* el_lecture_topic_subscriptions */ $this->webPermissions('view_el_lecture_topic_subscriptions', $appId, 'elearning', 'View'),
            /* el_lecture_topic_subscriptions */ $this->webPermissions('add_el_lecture_topic_subscriptions', $appId, 'elearning', 'Add'),
            /* el_lecture_topic_subscriptions */ $this->webPermissions('delete_el_lecture_topic_subscriptions', $appId, 'elearning', 'Block'), 
            /* el_lecture_topic_likes */ $this->webPermissions('list_el_lecture_topic_likes', $appId, 'elearning', 'List'),
            /* el_lecture_topic_likes */ $this->webPermissions('view_el_lecture_topic_likes', $appId, 'elearning', 'View'),
            /* el_lecture_topic_likes */ $this->webPermissions('add_el_lecture_topic_likes', $appId, 'elearning', 'Add'),
            /* el_lecture_topic_likes */ $this->webPermissions('delete_el_lecture_topic_likes', $appId, 'elearning', 'Block'), 
            /* course_forum */ $this->webPermissions('view_course_forum', $appId, 'elearning', 'List'),
            /* el_forum_topics */ $this->webPermissions('list_el_forum_topics', $appId, 'elearning', 'List'),
            /* el_forum_topics */ $this->webPermissions('view_el_forum_topics', $appId, 'elearning', 'View'),
            /* el_forum_topics */ $this->webPermissions('add_el_forum_topics', $appId, 'elearning', 'Add'),
            /* el_forum_topics */ $this->webPermissions('edit_el_forum_topics', $appId, 'elearning', 'Edit'),
            /* el_forum_topics */ $this->webPermissions('delete_el_forum_topics', $appId, 'elearning', 'Block'),
            /* el_forum_topics */ $this->webPermissions('download_el_forum_topics', $appId, 'elearning', 'Download'),
            /* el_forum_topic_responses */ $this->webPermissions('list_el_forum_topic_responses', $appId, 'elearning', 'List'),
            /* el_forum_topic_responses */ $this->webPermissions('view_el_forum_topic_responses', $appId, 'elearning', 'View'),
            /* el_forum_topic_responses */ $this->webPermissions('add_el_forum_topic_responses', $appId, 'elearning', 'Add'),
            /* el_forum_topic_responses */ $this->webPermissions('edit_el_forum_topic_responses', $appId, 'elearning', 'Edit'),
            /* el_forum_topic_responses */ $this->webPermissions('delete_el_forum_topic_responses', $appId, 'elearning', 'Block'),
            /* el_forum_topic_responses */ $this->webPermissions('download_el_forum_topic_responses', $appId, 'elearning', 'Download'),
            /* el_forum_topic_subscriptions */ $this->webPermissions('list_el_forum_topic_subscriptions', $appId, 'elearning', 'List'),
            /* el_forum_topic_subscriptions */ $this->webPermissions('view_el_forum_topic_subscriptions', $appId, 'elearning', 'View'),
            /* el_forum_topic_subscriptions */ $this->webPermissions('add_el_forum_topic_subscriptions', $appId, 'elearning', 'SUbscribe'),
            /* el_forum_topic_subscriptions */ $this->webPermissions('delete_el_forum_topic_subscriptions', $appId, 'elearning', 'Unsubscribe'),
            /* el_forum_topic_likes */ $this->webPermissions('list_el_forum_topic_likes', $appId, 'elearning', 'List'),
            /* el_forum_topic_likes */ $this->webPermissions('view_el_forum_topic_likes', $appId, 'elearning', 'View'),
            /* el_forum_topic_likes */ $this->webPermissions('add_el_forum_topic_likes', $appId, 'elearning', 'Like'),
            /* el_forum_topic_likes */ $this->webPermissions('delete_el_forum_topic_likes', $appId, 'elearning', 'Unlike'),
            /* el_instructions */ $this->webPermissions('list_el_instructions', $appId, 'elearning', 'List'),
            /* el_instructions */ $this->webPermissions('view_el_instructions', $appId, 'elearning', 'View'),
            /* el_instructions */ $this->webPermissions('add_el_instructions', $appId, 'elearning', 'Add'),
            /* el_instructions */ $this->webPermissions('edit_el_instructions', $appId, 'elearning', 'Edit'),
            /* el_instructions */ $this->webPermissions('delete_el_instructions', $appId, 'elearning', 'Remove'),
            /* el_instructions */ $this->webPermissions('download_el_instructions', $appId, 'elearning', 'Download'), 
            /* el_course_instructions */ $this->webPermissions('list_el_course_instructions', $appId, 'elearning', 'List'),
            /* el_course_instructions */ $this->webPermissions('view_el_course_instructions', $appId, 'elearning', 'View'),
            /* el_course_instructions */ $this->webPermissions('add_el_course_instructions', $appId, 'elearning', 'Add'),
            /* el_course_instructions */ $this->webPermissions('edit_el_course_instructions', $appId, 'elearning', 'Edit'),
            /* el_course_instructions */ $this->webPermissions('delete_el_course_instructions', $appId, 'elearning', 'Remove'),
            /* el_course_instructions */ $this->webPermissions('download_el_course_instructions', $appId, 'elearning', 'Download'), 
            /* access_e_learning */ $this->webPermissions('access_e_learning', $appId, 'elearning', 'Access E-Learning'),
            /* el_instructor_invitations */ $this->webPermissions('list_el_instructor_invitations', $appId, 'elearning', 'View'),
            /* el_instructor_invitations */ $this->webPermissions('add_el_instructor_invitations', $appId, 'elearning', 'Invite'),
            /* el_instructors */ $this->webPermissions('list_el_instructors', $appId, 'elearning', 'List'),
            /* el_instructors */ $this->webPermissions('view_el_instructors', $appId, 'elearning', 'View'),
            /* el_instructors */ $this->webPermissions('edit_el_instructors', $appId, 'elearning', 'Edit'),
            /* deactivate_el_instructors */ $this->webPermissions('deactivate_el_instructors', $appId, 'elearning', 'Block'),
            /* el_instructors */ $this->webPermissions('download_el_instructors', $appId, 'elearning', 'Download'),
            /* el_courses */ $this->webPermissions('list_el_courses', $appId, 'elearning', 'List'),
            /* el_courses */ $this->webPermissions('view_el_courses', $appId, 'elearning', 'View'),
            /* el_courses */ $this->webPermissions('add_el_courses', $appId, 'elearning', 'Create'),
            /* el_courses */ $this->webPermissions('edit_el_courses', $appId, 'elearning', 'Edit'),
            /* el_courses */ $this->webPermissions('delete_el_courses', $appId, 'elearning', 'Archive'),
            /* el_courses */ $this->webPermissions('download_el_courses', $appId, 'elearning', 'Download'),
            /* register_el_courses */ $this->webPermissions('register_el_courses', $appId, 'elearning', 'Register'), 
            /* deregister_el_courses */ $this->webPermissions('deregister_el_courses', $appId, 'elearning', 'De-register'),
            /* el_students */ $this->webPermissions('list_el_students', $appId, 'elearning', 'List'),
            /* el_students */ $this->webPermissions('view_el_students', $appId, 'elearning', 'View'),
            /* el_students */ $this->webPermissions('add_el_students', $appId, 'elearning', 'Add'),
            /* el_students */ $this->webPermissions('edit_el_students', $appId, 'elearning', 'Edit'),
            /* deactivate_el_students */ $this->webPermissions('deactivate_el_students', $appId, 'elearning', 'Block'),
            /* el_students */ $this->webPermissions('download_el_students', $appId, 'elearning', 'Download'),
            /* course_resources */ $this->webPermissions('view_course_resources', $appId, 'elearning', 'List'),
            /* el_resources */ $this->webPermissions('list_el_resources', $appId, 'elearning', 'List'),
            /* el_resources */ $this->webPermissions('view_el_resources', $appId, 'elearning', 'View'),
            /* el_resources */ $this->webPermissions('add_el_resources', $appId, 'elearning', 'Create'),
            /* el_resources */ $this->webPermissions('edit_el_resources', $appId, 'elearning', 'Edit'),
            /* el_resources */ $this->webPermissions('delete_el_resources', $appId, 'elearning', 'Delete'),
            /* el_resources */ $this->webPermissions('download_el_resources', $appId, 'elearning', 'Download'),
            /* el_resource_views */ $this->webPermissions('list_el_resource_views', $appId, 'elearning', 'List'),
            /* el_resource_views */ $this->webPermissions('view_el_resource_views', $appId, 'elearning', 'View'), 
            /* el_resource_subscriptions */ $this->webPermissions('list_el_resource_subscriptions', $appId, 'elearning', 'List'),
            /* el_resource_subscriptions */ $this->webPermissions('view_el_resource_subscriptions', $appId, 'elearning', 'View'),
            /* el_resource_subscriptions */ $this->webPermissions('add_el_resource_subscriptions', $appId, 'elearning', 'SUbscribe'),
            /* el_resource_subscriptions */ $this->webPermissions('delete_el_resource_subscriptions', $appId, 'elearning', 'Unsubscribe'), 
        ];

        $this->enableForeignKeys();
        Permission::query()->insert($permissions);
    }
}



            // farmers
            // farmer-groups
            // farmer-mapping  

            // village-agents
            // village-agent-mapping

            // extension-officers
            // extension-mapping
            // extension-settings

            // organisations
            // organisation-users
            // organisation-positions

            // questions
            // question-responses
            // question-mapping
            // question-settings

            // alerts
            // alerts-mapping

            // trainings
            // training-resources
            // training-resource-topics

            // elearning-settings
            // courses
            // instructors
            // students

            // keyword-market-prices
            // market-prices
            // market-subscriptions
