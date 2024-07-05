<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingNewFieldsToTheInsuranceSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance_subscriptions', function (Blueprint $table) {
            $table->string('surname', 36)->nullable();
            $table->json('environments')->nullable();
            $table->string('insurer_name', 36)->nullable();
            $table->string('national_id', 36)->nullable();
            $table->string('driving_license', 36)->nullable();
            $table->string('village_id', 36)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->string('passport', 36)->nullable();
            $table->decimal('long', 11, 8)->nullable();
            $table->string('other_name', 36)->nullable();
            $table->integer('animal_production_business_duration')->nullable();
            $table->string('profession', 36)->nullable();
            $table->integer('animals_in_posession_duration')->nullable();
            $table->json('animals_insured')->nullable();
            $table->string('animals_keeping_purpose', 36)->nullable();
            $table->string('loan', 36)->nullable();
            $table->string('animals_lost', 36)->nullable();
            $table->string('causes_of_death', 36)->nullable();
            $table->json('selected_animals')->nullable();
            $table->string('animal_health')->nullable();
            $table->string('animal_illness')->nullable();
            $table->string('animal_treatment')->nullable();
            $table->json('risks')->nullable();
            $table->boolean('animal_contagious')->default(false);
            $table->boolean('animal_vaccinated')->default(false);
            $table->boolean('conviction')->default(false);
            $table->text('additional_info')->nullable();
            $table->json('management')->nullable();
            $table->json('supervisory')->nullable();
            $table->json('security')->nullable();
            $table->json('laborer')->nullable();
            $table->string('insurance_type')->nullable();
            $table->string('name')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('payment_phone')->nullable();
            $table->boolean('same_paying')->default(true);
            $table->timestamp('completed_at')->nullable();
            $table->string('session_id')->nullable();
            $table->string('enterprise')->nullable();
            $table->json('coverage')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('region_id', 36)->nullable();
            $table->json('selected_products')->nullable();
            $table->string('model')->nullable();
            $table->string('category', 36)->nullable();
            $table->boolean('agent_sale')->default(false);
            $table->string('sub_county', 36)->nullable();
            $table->string('parish', 36)->nullable();
            $table->string('village', 36)->nullable();
            $table->string('district', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurance_subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'surname',
                'environments',
                'insurer_name',
                'national_id',
                'driving_license',
                'village_id',
                'lat',
                'passport',
                'long',
                'other_name',
                'animal_production_business_duration',
                'profession',
                'animals_in_posession_duration',
                'animals_insured',
                'animals_keeping_purpose',
                'loan',
                'animals_lost',
                'causes_of_death',
                'selected_animals',
                'animal_health',
                'animal_illness',
                'animal_treatment',
                'risks',
                'animal_contagious',
                'animal_vaccinated',
                'conviction',
                'additional_info',
                'management',
                'supervisory',
                'security',
                'laborer',
                'insurance_type',
                'name',
                'cancelled_at',
                'payment_phone',
                'same_paying',
                'completed_at',
                'session_id',
                'enterprise',
                'coverage',
                'phone_number',
                'region_id',
                'selected_products',
                'model',
                'category',
                'agent_sale',
                'sub_county',
                'parish',
                'village',
                'district'
            ]);
        });
    }
}