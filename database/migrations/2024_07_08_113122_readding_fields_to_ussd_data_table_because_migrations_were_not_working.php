<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReaddingFieldsToUssdDataTableBecauseMigrationsWereNotWorking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ussd_session_data', function (Blueprint $table) {
            $table->string('national_id')->nullable();
            $table->string('village_id')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('passport')->nullable();
            $table->string('email')->nullable();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->string('category')->nullable();
            $table->boolean('agent_sale')->default(false);
            $table->json('environments')->nullable();
            $table->string('animal_production_business_duration')->nullable();
            $table->string('profession')->nullable();
            $table->string('animals_in_posession_duration')->nullable();
            $table->string('animals_keeping_purpose')->nullable();
            $table->string('loan')->nullable();
            $table->json('selected_animals')->nullable();
            $table->string('animals_lost')->nullable();
            $table->json('selected_products')->nullable();
            $table->string('causes_of_death')->nullable();
            $table->string('animal_health')->nullable();
            $table->string('animal_illness')->nullable();
            $table->string('animal_treatment')->nullable();
            $table->string('animal_contagious')->nullable();
            $table->string('risks')->nullable();
            $table->boolean('conviction')->default(false);
            $table->text('additional_info')->nullable();
            $table->json('management')->nullable();
            $table->json('supervisory')->nullable();
            $table->json('security')->nullable();
            $table->json('laborer')->nullable();
            $table->string('sub_county')->nullable();
            $table->string('parish')->nullable();
            $table->string('village')->nullable();
            $table->string('district')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ussd_data_table_because_migrations_were_not_working', function (Blueprint $table) {
            //
        });
    }
}
