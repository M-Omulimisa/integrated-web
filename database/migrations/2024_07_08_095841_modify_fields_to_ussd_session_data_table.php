<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFieldsToUssdSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            // Add 'paid' column if it doesn't exist
            if (!Schema::hasColumn('ussd_session_data', 'paid')) {
                $table->boolean('paid')->nullable()->default(false);
            } else {
                $table->boolean('paid')->nullable()->default(false)->change();
            }

            // Add 'completed' column if it doesn't exist
            if (!Schema::hasColumn('ussd_session_data', 'completed')) {
                $table->boolean('completed')->nullable()->default(false);
            } else {
                $table->boolean('completed')->nullable()->default(false)->change();
            }

            // Add 'pending' column if it doesn't exist
            if (!Schema::hasColumn('ussd_session_data', 'pending')) {
                $table->boolean('pending')->nullable()->default(true);
            } else {
                $table->boolean('pending')->nullable()->default(true)->change();
            }

            // Add 'cancelled' column if it doesn't exist
            if (!Schema::hasColumn('ussd_session_data', 'cancelled')) {
                $table->boolean('cancelled')->nullable()->default(false);
            } else {
                $table->boolean('cancelled')->nullable()->default(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            // You can drop columns here if needed, but usually, in a down migration,
            // you would leave it empty unless you have specific rollback steps.
        });
    }
}
