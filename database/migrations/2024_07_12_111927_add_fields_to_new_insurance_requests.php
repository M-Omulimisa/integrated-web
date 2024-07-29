<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToNewInsuranceRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_insurance_requests', function (Blueprint $table) {
            $table->string('session_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('insurance_subscrption_for')->nullable();
            $table->string('insurance_enterprise_id')->nullable();
            $table->decimal('insurance_amount', 10, 2)->nullable();
            $table->string('insurance_subscriber')->nullable();
            $table->decimal('insurance_acreage', 10, 2)->nullable();
            $table->decimal('insurance_sum_insured', 10, 2)->nullable();
            $table->decimal('insurance_premium', 10, 2)->nullable();
            $table->decimal('markup', 10, 2)->nullable();
            $table->string('insurance_coverage')->nullable();
            $table->boolean('confirmation_message')->nullable();
            $table->string('insurance_region_id')->nullable();
            $table->string('agent_id', 36)->nullable();
            $table->string('insurer_name', 36)->nullable();
            $table->string('insurance_type', 36)->nullable()->default('crop');
            $table->string('surname', 36)->nullable();
            $table->string('telephone', 36)->nullable();
            $table->string('other_name', 36)->nullable();
            $table->string('payment_phone', 36)->nullable();
            $table->boolean('paid')->nullable()->default(false);
            $table->boolean('completed')->nullable()->default(false);
            $table->boolean('pending')->nullable()->default(true);
            $table->boolean('cancelled')->nullable()->default(false);
            $table->string('national_id')->nullable();
            $table->string('village_id')->nullable();
            $table->string('driving_license')->nullable();
            $table->string('passport')->nullable();
            $table->string('email')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('category')->nullable();
            $table->boolean('agent_sale')->nullable();
            $table->string('environments')->nullable();
            $table->string('animal_production_business_duration')->nullable();
            $table->string('profession')->nullable();
            $table->string('animals_in_posession_duration')->nullable();
            $table->string('animals_keeping_purpose')->nullable();
            $table->string('loan')->nullable();
            $table->text('selected_animals')->nullable();
            $table->text('animals_lost')->nullable();
            $table->text('selected_products')->nullable();
            $table->text('causes_of_death')->nullable();
            $table->text('animal_health')->nullable();
            $table->text('animal_illness')->nullable();
            $table->text('animal_treatment')->nullable();
            $table->text('animal_contagious')->nullable();
            $table->text('risks')->nullable();
            $table->text('conviction')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('management')->nullable();
            $table->string('supervisory')->nullable();
            $table->string('security')->nullable();
            $table->string('laborer')->nullable();
            $table->string('sub_county')->nullable();
            $table->string('parish')->nullable();
            $table->string('village')->nullable();
            $table->string('district')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_insurance_requests', function (Blueprint $table) {
            $table->dropColumn([
                'session_id', 'phone_number', 'insurance_subscrption_for', 'insurance_enterprise_id',
                'insurance_amount', 'insurance_subscriber', 'insurance_acreage',
                'insurance_sum_insured', 'insurance_premium', 'markup', 'insurance_coverage',
                'confirmation_message', 'insurance_region_id', 'agent_id', 'insurer_name',
                'insurance_type', 'surname', 'telephone', 'other_name', 'payment_phone',
                'paid', 'completed', 'pending', 'cancelled', 'national_id', 'village_id',
                'driving_license', 'passport', 'email', 'lat', 'long', 'category',
                'agent_sale', 'environments', 'animal_production_business_duration',
                'profession', 'animals_in_posession_duration', 'animals_keeping_purpose',
                'loan', 'selected_animals', 'animals_lost', 'selected_products',
                'causes_of_death', 'animal_health', 'animal_illness', 'animal_treatment',
                'animal_contagious', 'risks', 'conviction', 'additional_info',
                'management', 'supervisory', 'security', 'laborer',
                'sub_county', 'parish', 'village', 'district'
            ]);
        });
    }
}
