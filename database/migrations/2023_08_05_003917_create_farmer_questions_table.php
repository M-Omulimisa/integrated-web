<?php

use App\Models\DistrictModel;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_questions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(User::class)->default(1);
            $table->foreignIdFor(DistrictModel::class)->default(1);
            $table->text('body')->nullable();
            $table->string('category')->nullable();
            $table->string('phone')->nullable();
            $table->string('sent_via')->nullable();
            $table->string('answered')->nullable()->default('no');
            $table->text('audio')->nullable();
            $table->text('photo')->nullable();
            $table->text('video')->nullable();
            $table->text('document')->nullable();
            $table->integer('views')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmer_questions');
    }
}
