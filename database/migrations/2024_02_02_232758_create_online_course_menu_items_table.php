<?php

use App\Models\Settings\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineCourseMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_course_menu_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Language::class);
            $table->foreignIdFor(\App\Models\OnlineCourseMenu::class);
            $table->text('audio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_course_menu_items');
    }
}
