<?php

use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingTrainingSessionHasMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_training_session_has_members', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(TrainingSession::class);
            $table->foreignIdFor(User::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_training_session_has_members');
    }
}
