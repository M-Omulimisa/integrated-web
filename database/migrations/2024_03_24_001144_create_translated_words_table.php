<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslatedWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translated_words', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('word')->nullable();
            $table->text('luganda')->nullable();
            $table->text('runyankole')->nullable();
            $table->text('acholi')->nullable();
            $table->text('lumasaba')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translated_words');
    }
}
