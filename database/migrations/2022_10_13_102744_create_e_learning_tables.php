<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateELearningTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_learning_instructor_invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name')->nullable();
            $table->string('email')->unique();
            $table->string('token')->nullable();
            $table->uuid('role_id')->nullable();           
            $table->uuid('user_id');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('role_id')->on('roles')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_instructors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name')->nullable();
            $table->string('picture')->nullable();
            $table->string('gender');
            $table->string('age_group')->nullable();
            $table->string('affiliation')->nullable();
            $table->string('qualification')->nullable();
            $table->string('country')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('email_notifications')->default(false);
            $table->boolean('sms_notifications')->default(false);
            $table->uuid('organisation_id')->nullable();
            $table->uuid('location_id')->nullable();
            $table->string('address')->nullable();
            $table->string('business')->nullable();            
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name')->nullable();
            $table->string('picture')->nullable();
            $table->string('gender');
            $table->string('age_group')->nullable();
            $table->string('affiliation')->nullable();
            $table->string('qualification')->nullable();
            $table->string('country')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->boolean('email_notifications')->default(false);
            $table->boolean('sms_notifications')->default(false);
            $table->uuid('organisation_id')->nullable();
            $table->uuid('location_id')->nullable();
            $table->string('address')->nullable();
            $table->string('business')->nullable();            
            $table->uuid('user_id');
            $table->uuid('added_by');
            $table->timestamps();

            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('added_by')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_courses', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('title');
                $table->text('summary')->nullable();
                $table->text('description');
                $table->text('content')->nullable();
                $table->text('audience')->nullable();
                $table->text('outcomes')->nullable();
                $table->uuid('user_id');
                $table->string('image_banner')->nullable();
                $table->string('video_url')->nullable();
                $table->text('about_certificates')->nullable();
                $table->date('start_date')->nullable();
                $table->string('start_time')->nullable();
                $table->date('end_date')->nullable();
                $table->string('end_time')->nullable();
                $table->integer('duration_in_days')->nullable();
                $table->integer('duration_in_weeks')->nullable();
                $table->text('team')->nullable();
                $table->text('operations')->nullable();
                $table->string('logo')->nullable();
                $table->string('brochure')->nullable();
                $table->string('status')->default('Open');
                $table->boolean('read_only_mode')->default(false);
                $table->string('enrollment_status')->default('Current');
                $table->string('code')->unique();
                $table->string('certificate_url')->nullable();
                $table->timestamp('status_archived_at')->nullable();
                $table->timestamp('enrollment_closed_at')->nullable();
                $table->string('lecture_type')->nullable();
                $table->timestamps(); 

                $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_chapters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->integer('numbering');

            $table->uuid('parent_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->timestamps(); 

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            // $table->foreign('parent_id')->on('e_learning_chapters')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_lectures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chapter_id');
            $table->string('title');
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('document_url')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->integer('numbering');
            $table->timestamps();

            // $table->foreign('chapter_id')->on('e_learning_chapters')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_lecture_attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('lecture_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
            $table->foreign('lecture_id')->on('e_learning_lectures')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_lecture_topics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_id');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('document_url')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->uuid('student_id')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
            $table->foreign('lecture_id')->on('e_learning_lectures')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_lecture_topic_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_topic_id');
            $table->text('comment')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('document_url')->nullable();
            $table->uuid('student_id')->nullable();
            $table->uuid('user_id');
            $table->boolean('owner_has_listened')->default(false);
            $table->timestamps();

            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
            $table->foreign('lecture_topic_id')->on('e_learning_lecture_topics')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_lecture_topic_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_topic_id');
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->uuid('student_id')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
            $table->foreign('lecture_topic_id')->on('e_learning_lecture_topics')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_lecture_topic_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_topic_id');
            $table->uuid('student_id')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
            $table->foreign('lecture_topic_id')->on('e_learning_lecture_topics')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_lecture_topic_response_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_topic_response_id');
            $table->uuid('student_id')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
            // $table->foreign('lecture_topic_response_id')->on('e_learning_lecture_topic_responses')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });        

        Schema::create('e_learning_course_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('user_id');
            $table->uuid('role_id')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('role_id')->on('roles')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_announcements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->string('title');
            $table->text('body');
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('document_url')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('display_days')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_announcement_views', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('announcement_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('announcement_id')->on('e_learning_announcements')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_announcement_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->string('title');
            $table->text('body');
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('display_days')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_resource_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->string('title')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_resource_views', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resource_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('resource_id')->on('e_learning_resources')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_resource_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_forum_topics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('document_url')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_forum_topic_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('forum_topic_id');
            $table->text('comment')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('document_url')->nullable();
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('forum_topic_id')->on('e_learning_forum_topics')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_forum_topic_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('forum_topic_id');
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('forum_topic_id')->on('e_learning_forum_topics')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_forum_topic_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('forum_topic_id');
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('forum_topic_id')->on('e_learning_forum_topics')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_forum_topic_response_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('forum_topic_response_id');
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            // $table->foreign('forum_topic_response_id')->on('e_learning_forum_topic_responses')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_incoming_calls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('callSessionState')->nullable();
            $table->string('direction')->nullable();
            $table->string('callerCountryCode')->nullable();
            $table->integer('durationInSeconds')->nullable();
            $table->double('amount',15,2)->nullable();
            $table->string('callerNumber')->nullable();
            $table->string('destinationNumber')->nullable();
            $table->string('callerCarrierName')->nullable();
            $table->string('status')->nullable();
            $table->text('sessionId')->nullable();
            $table->timestamp('callStartTime')->nullable();
            $table->boolean('isActive')->default(false);
            $table->string('currencyCode')->nullable();
            $table->uuid('course_id')->nullable();

            $table->uuid('student_id')->nullable();
            $table->string('call_back_student')->default('PENDING');
            $table->timestamp('called_back_at')->nullable();
            $table->text('call_back_failure')->nullable();
            $table->timestamp('call_back_failed_at')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_outgoing_calls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('callSessionState')->nullable();
            $table->string('direction')->nullable();
            $table->string('callerCountryCode')->nullable();
            $table->integer('durationInSeconds')->nullable();
            $table->double('amount',15,2)->nullable();
            $table->string('callerNumber')->nullable();
            $table->string('destinationNumber')->nullable();
            $table->string('callerCarrierName')->nullable();
            $table->string('status')->nullable();
            $table->text('sessionId')->nullable();
            $table->timestamp('callStartTime')->nullable();
            $table->boolean('isActive')->default(false);
            $table->string('currencyCode')->nullable();
            $table->text('recordingUrl')->nullable();
            $table->uuid('course_id')->nullable();

            $table->uuid('student_id')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
        });


        Schema::create('e_learning_voice_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('session_id');
            $table->string('phone_number');
            $table->string('last_menu');
            $table->timestamps();
        });

        Schema::create('e_learning_voice_menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('session_id');
            $table->string('phone_number');
            $table->string('main_action')->nullable();

            $table->string('student_id')->nullable();
            $table->string('course_id')->nullable();
            $table->string('course_type')->nullable();

            $table->string('lesson_action')->nullable();

            $table->string('previous_week_id')->nullable();
            $table->string('current_week_id')->nullable();
            $table->string('next_week_id')->nullable();

            $table->string('previous_chapter_id')->nullable();
            $table->string('current_chapter_id')->nullable();
            $table->string('next_chapter_id')->nullable();

            $table->string('previous_lecture_id')->nullable();
            $table->string('current_lecture_id')->nullable();
            $table->string('next_lecture_id')->nullable();

            $table->string('previous_question_id')->nullable();
            $table->string('current_question_id')->nullable();
            $table->string('next_question_id')->nullable();

            $table->boolean('chapter_has_assignment')->nullable();
            $table->boolean('course_has_assignment')->nullable();

            $table->string('previous_response_id')->nullable();
            $table->string('current_response_id')->nullable();
            $table->string('next_response_id')->nullable();

            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        Schema::create('e_learning_student_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('student_id');
            $table->uuid('added_by');
            $table->boolean('status')->default(false);
            $table->timestamp('removed_at')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
            $table->foreign('added_by')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id')->nullable();
            $table->uuid('chapter_id')->nullable();
            $table->string('title');
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('document_url')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->integer('numbering');

            $table->string('type')->nullable();
            $table->string('answer')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            // $table->foreign('chapter_id')->on('e_learning_chapters')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_assignment_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id')->nullable();
            $table->uuid('chapter_id')->nullable();
            $table->uuid('assignment_id');
            $table->string('answer')->nullable();
            $table->uuid('student_id')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            // $table->foreign('chapter_id')->on('e_learning_chapters')->references('id')->onDelete('CASCADE');
            $table->foreign('assignment_id')->on('e_learning_assignments')->references('id')->onDelete('CASCADE');
            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_instructions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('instruction')->nullable();
            $table->integer('numbering');
            $table->string('default_audio_url')->nullable();
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_course_instructions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('instruction_id');
            $table->string('audio_url');
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('instruction_id')->on('e_learning_instructions')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_general_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id')->nullable();
            $table->string('title');
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('document_url')->nullable();
            $table->uuid('user_id');
            $table->boolean('status')->default(true);
            $table->integer('numbering');

            $table->string('type')->nullable();
            $table->string('answer')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_general_assignment_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id')->nullable();
            $table->uuid('assignment_id');
            $table->uuid('student_id')->nullable();
            $table->string('answer')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('assignment_id')->on('e_learning_assignments')->references('id')->onDelete('CASCADE');
            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_lecture_visits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecture_id')->nullable();
            $table->uuid('student_id')->nullable();
            $table->string('action')->nullable();
            $table->timestamps();

            $table->foreign('lecture_id')->on('e_learning_lectures')->references('id')->onDelete('CASCADE');
            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_inactive_students_outgoing_calls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('callSessionState')->nullable();
            $table->string('callerNumber')->nullable();
            $table->text('sessionId')->nullable();

            $table->uuid('student_id')->nullable();
            $table->uuid('course_id')->nullable();

            $table->string('call_student')->default('PENDING');
            $table->timestamp('called_at')->nullable();
            $table->text('call_failure')->nullable();
            $table->timestamp('call_failed_at')->nullable();
            $table->timestamp('call_back_at')->nullable();

            $table->timestamps();

            $table->foreign('student_id')->on('e_learning_students')->references('id')->onDelete('CASCADE');
            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_inactive_students_call_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('calling_time')->default('12 am');
            $table->integer('retry_after_in_minutes')->default(5);
            $table->integer('number_of_retries')->default(3);
            $table->integer('make_missed_after_in_minutes')->default(3);
            $table->integer('calls_per_cycle')->default(10);            
            $table->timestamps();
        });

        Schema::create('e_learning_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('default_message')->nullable();
            $table->integer('numbering');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
        });

        Schema::create('e_learning_course_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('message_id');
            $table->text('text_message')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->on('e_learning_courses')->references('id')->onDelete('CASCADE');
            $table->foreign('message_id')->on('e_learning_messages')->references('id')->onDelete('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_learning_course_messages');
        Schema::dropIfExists('e_learning_messages');
        Schema::dropIfExists('e_learning_inactive_students_call_settings');
        Schema::dropIfExists('e_learning_inactive_students_outgoing_calls');
        Schema::dropIfExists('e_learning_course_instructions');
        Schema::dropIfExists('e_learning_instructions');

        Schema::dropIfExists('e_learning_general_assignments');
        Schema::dropIfExists('e_learning_general_assignment_responses');
        Schema::dropIfExists('e_learning_assignment_responses');
        Schema::dropIfExists('e_learning_assignments');

        Schema::dropIfExists('e_learning_student_enrollments');

        Schema::dropIfExists('e_learning_voice_sessions');
        Schema::dropIfExists('e_learning_voice_menus');
        Schema::dropIfExists('e_learning_incoming_calls');
        Schema::dropIfExists('e_learning_outgoing_calls');

        Schema::dropIfExists('e_learning_forum_topic_responses');
        Schema::dropIfExists('e_learning_forum_topic_subscriptions');
        Schema::dropIfExists('e_learning_forum_topic_likes');
        Schema::dropIfExists('e_learning_forum_topic_response_likes');
        Schema::dropIfExists('e_learning_forum_topics');

        Schema::dropIfExists('e_learning_resource_attachments');
        Schema::dropIfExists('e_learning_resource_views');
        Schema::dropIfExists('e_learning_resource_subscriptions');
        Schema::dropIfExists('e_learning_resources');

        Schema::dropIfExists('e_learning_announcement_views');
        Schema::dropIfExists('e_learning_announcement_subscriptions');
        Schema::dropIfExists('e_learning_announcements');

        Schema::dropIfExists('e_learning_course_registrations');
        Schema::dropIfExists('e_learning_chapters');

        Schema::dropIfExists('e_learning_lecture_attendances');
        Schema::dropIfExists('e_learning_lecture_visits');

        Schema::dropIfExists('e_learning_lecture_topic_responses');
        Schema::dropIfExists('e_learning_lecture_topic_subscriptions');
        Schema::dropIfExists('e_learning_lecture_topic_likes');
        Schema::dropIfExists('e_learning_lecture_topic_response_likes');
        Schema::dropIfExists('e_learning_lecture_topics');
        
        Schema::dropIfExists('e_learning_lectures');

        Schema::dropIfExists('e_learning_courses');
        Schema::dropIfExists('e_learning_students');

        Schema::dropIfExists('e_learning_instructor_invitations');
        Schema::dropIfExists('e_learning_instructors');
    }
}
