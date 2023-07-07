<?php

use App\Models\Farmers\FarmerGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsFarmerGroupIdToTrainingSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->foreignIdFor(FarmerGroup::class)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            //
        });
    }
}
