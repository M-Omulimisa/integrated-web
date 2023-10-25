<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('alias', 80)->unique();
            $table->timestamps();
        });

        Schema::connection('mysql')->create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('value')->nullable();
            $table->string('settingable_type', 160)->nullable();
            $table->uuid('settingable_id')->nullable();
            $table->string('context')->nullable();
            $table->boolean('autoload')->default(0);
            $table->boolean('public')->default(1);
            $table->timestamps();

            $table->index(['settingable_type', 'settingable_id'], 'settingable_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('settings');
        Schema::connection('mysql')->dropIfExists('types');
    }
}
