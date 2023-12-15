<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorInfoToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text("first_name")->nullable();
            $table->text("last_name")->nullable();
            $table->text("reg_date")->nullable();
            $table->text("last_seen")->nullable();
            $table->text("approved")->nullable();
            $table->text("profile_photo")->nullable();
            $table->text("user_type")->nullable();
            $table->text("sex")->nullable();
            $table->text("reg_number")->nullable();
            $table->text("country")->nullable();
            $table->text("occupation")->nullable();
            $table->text("profile_photo_large")->nullable();
            $table->text("phone_number")->nullable();
            $table->text("location_lat")->nullable();
            $table->text("location_long")->nullable();
            $table->text("facebook")->nullable();
            $table->text("twitter")->nullable();
            $table->text("whatsapp")->nullable();
            $table->text("linkedin")->nullable();
            $table->text("website")->nullable();
            $table->text("other_link")->nullable();
            $table->text("cv")->nullable();
            $table->text("language")->nullable();
            $table->text("about")->nullable();
            $table->text("address")->nullable();
            $table->text("campus_id")->nullable();
            $table->text("complete_profile")->nullable();
            $table->text("title")->nullable();
            $table->text("dob")->nullable();
            $table->text("intro")->nullable();
            $table->text("business_name")->nullable();
            $table->text("business_license_number")->nullable();
            $table->text("business_license_issue_authority")->nullable();
            $table->text("business_license_issue_date")->nullable();
            $table->text("business_license_validity")->nullable();
            $table->text("business_address")->nullable();
            $table->text("business_phone_number")->nullable();
            $table->text("business_whatsapp")->nullable();
            $table->text("business_email")->nullable();
            $table->text("business_logo")->nullable();
            $table->text("business_cover_photo")->nullable();
            $table->text("business_cover_details")->nullable();
            $table->text("nin")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
