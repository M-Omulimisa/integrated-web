<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSchedulingOnUssdAdvisoryMessageOutboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_advisory_message_outboxes', function (Blueprint $table) {
            
            $table->uuid('batch_number')->nullable()->after('status');
            $table->integer('message_schedule_number')->default(1)->after('batch_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ussd_advisory_message_outboxes', function (Blueprint $table) {
            //
        });
    }
}
