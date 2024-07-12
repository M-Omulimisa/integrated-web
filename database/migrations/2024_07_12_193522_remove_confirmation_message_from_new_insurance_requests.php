<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveConfirmationMessageFromNewInsuranceRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_insurance_requests', function (Blueprint $table) {
            $table->dropColumn('confirmation_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_insurance_requests', function (Blueprint $table) {
            $table->text('confirmation_message')->nullable();
        });
    }
}
