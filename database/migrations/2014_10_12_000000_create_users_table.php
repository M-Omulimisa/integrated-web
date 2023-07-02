<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email', 160)->unique();
            $table->text('photo')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('password_last_updated_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->uuid('created_by')->nullable();
            $table->enum('status', ['Invited', 'Active', 'Inactive', 'Suspended', 'Banned'])->default('Active');
            $table->boolean('verified')->default(false);           
            $table->timestamp('email_verified_at')->nullable(); 
            $table->uuid('country_id')->nullable();
            $table->uuid('organisation_id')->nullable();
            $table->uuid('microfinance_id')->nullable();
            $table->uuid('distributor_id')->nullable();
            $table->uuid('buyer_id')->nullable();
            $table->string('two_auth_method')->default('EMAIL');
            $table->string('user_hash')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function($table) {
           // $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
       });

        Schema::connection('mysql')->create('user_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->timestamp('activated_at')->nullable();
            $table->uuid('activated_by')->nullable(); 
            $table->timestamp('suspended_at')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->uuid('suspended_by')->nullable();            
            $table->timestamp('suspended_till')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->text('ban_reason')->nullable();
            $table->uuid('banned_by')->nullable();
            $table->timestamps();
            
            $table->foreign('activated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('suspended_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('banned_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('user_statuses');
        Schema::connection('mysql')->dropIfExists('users');
    }
}
