<?php

use App\Models\Organisations\Organisation;
use App\Models\Settings\Location;
use App\Models\Training\Training;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Organisation::class)->default('57159775-b9e0-41ce-ad99-4fdd6ed8c1a0');
            $table->foreignIdFor(Training::class);
            $table->foreignIdFor(Location::class);
            $table->foreignIdFor(User::class, 'conducted_by');
            $table->date('session_date')->nullable();
            $table->time('start_date')->nullable();
            $table->time('end_date')->nullable();
            $table->text('details')->nullable();
            $table->text('topics_covered')->nullable();
            $table->text('attendance_list_pictures')->nullable();
            $table->text('members_pictures')->nullable();
            $table->string('gps_latitude')->nullable();
            $table->string('gps_longitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_sessions');
    }
}
