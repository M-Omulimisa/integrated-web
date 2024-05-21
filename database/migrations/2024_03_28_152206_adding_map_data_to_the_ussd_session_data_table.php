<?php

use App\Models\Utils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingMapDataToTheUssdSessionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            //$table->text('option_mappings')->nullable();
            Utils::create_column(
                'ussd_session_data',
                [
                    [
                        'name' => 'option_mappings',
                        'type' => 'Text',
                    ],
                ]
            );
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
            $table->dropColumn('option_mappings');
        });
    }
}
