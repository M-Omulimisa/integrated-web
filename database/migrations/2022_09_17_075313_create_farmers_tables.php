<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    
        Schema::create('farmer_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('country_id');
            $table->uuid('organisation_id')->nullable();
            $table->string('code');
            $table->string('address')->nullable();
            $table->string('group_leader')->nullable();
            $table->string('group_leader_contact')->nullable();
            $table->string('establishment_year')->nullable();
            $table->string('registration_year')->nullable();            
            $table->string('meeting_venue')->nullable();            
            $table->string('meeting_days')->nullable();
            $table->string('meeting_time')->nullable();
            $table->enum('meeting_frequency', ['Daily', 'Weekly', 'Monthly', 'Yearly'])->nullable();
            $table->uuid('location_id')->nullable();
            $table->double('last_cycle_savings', 15, 2)->default(0.00);
            $table->text('registration_certificate')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('status', ['Invited', 'Active', 'Inactive', 'Suspended', 'Banned'])->default('Active');
            $table->text('photo')->nullable();
            $table->text('id_photo_front')->nullable();
            $table->text('id_photo_back')->nullable();
            $table->uuid('created_by_user_id')->nullable();
            $table->uuid('created_by_agent_id')->nullable();
            $table->uuid('agent_id')->nullable();
            $table->timestamps();

            $table->foreign('created_by_user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
            // 'created_by_agent_id' --- in agents migration
            // 'agent_id' --- in agents migration
        });

        Schema::create('farmer_group_enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_group_id');
            $table->uuid('enterprise_id');
            $table->timestamps();

            $table->foreign('farmer_group_id')->on('farmer_groups')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE');
        });

        Schema::create('farmers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id')->nullable();
            $table->uuid('farmer_group_id')->nullable();            
            $table->string('first_name');
            $table->string('last_name');
            $table->uuid('country_id');
            $table->uuid('language_id');
            $table->string('national_id_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('education_level')->nullable();
            $table->string('year_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_your_phone')->nullable();
            $table->boolean('is_mm_registered')->nullable();
            $table->text('other_economic_activity')->nullable();
            $table->uuid('location_id')->nullable();
            $table->string('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('password')->nullable();

            $table->string('farming_scale')->nullable();
            $table->double('land_holding_in_acres', 15, 2)->nullable();
            $table->double('land_under_farming_in_acres', 15, 2)->nullable();            
            $table->boolean('ever_bought_insurance')->nullable();
            $table->string('ever_received_credit')->nullable();

            $table->enum('status', ['Invited', 'Active', 'Inactive', 'Suspended', 'Banned'])->default('Active');
            $table->uuid('created_by_user_id')->nullable();
            $table->uuid('created_by_agent_id')->nullable();
            $table->uuid('agent_id')->nullable();
            $table->timestamps();

            $table->foreign('created_by_user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
            $table->foreign('farmer_group_id')->on('farmer_groups')->references('id')->onDelete('CASCADE');
            // 'created_by_agent_id' --- in agents migration
            // 'agent_id' --- in agents migration
        });

        Schema::create('farmer_enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('farmer_id');
            $table->uuid('enterprise_id');
            $table->timestamps();

            $table->foreign('farmer_id')->on('farmers')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE');
        });

        // Schema::create('farmer_farming_challenges', function (Blueprint $table) {
        //     $table->uuid('id')->primary();
        //     $table->uuid('farmer_id');
        //     $table->uuid('farming_challenge_id');
        //     $table->timestamps();

        //     $table->foreign('farmer_id')->on('farmers')->references('id')->onDelete('CASCADE');
        //     $table->foreign('farming_challenge_id')->on('farming_challenges')->references('id')->onDelete('CASCADE');
        // });

        Schema::table('farmer_groups', function (Blueprint $table) {
            $table->foreign('created_by_agent_id')->on('agents')->references('id')->onDelete('CASCADE');
            $table->foreign('agent_id')->on('agents')->references('id')->onDelete('CASCADE');
        });

        Schema::table('farmers', function (Blueprint $table) {
            $table->foreign('created_by_agent_id')->on('agents')->references('id')->onDelete('CASCADE');
            $table->foreign('agent_id')->on('agents')->references('id')->onDelete('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('farmer_farming_challenges');
        // Schema::dropIfExists('farmer_farming_practices');
        Schema::dropIfExists('farmer_enterprises');
        Schema::dropIfExists('farmers');
        Schema::dropIfExists('farmer_group_enterprises');
        Schema::dropIfExists('farmer_groups');
    }
}
