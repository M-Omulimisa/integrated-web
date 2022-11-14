<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id'); 
            $table->string('name'); 
            $table->text('description'); 
            $table->date('start_date'); 
            $table->date('end_date'); 
            $table->unique(['organisation_id', 'name']);
            $table->timestamps();

            $table->foreign('organisation_id')->on('organisations')->references('id')->onDelete('CASCADE');
        });

        Schema::create('loan_input_commissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id'); 
            $table->uuid('enterprise_id');
            $table->uuid('enterprise_variety_id')->nullable();
            $table->uuid('enterprise_type_id')->nullable();  
            $table->unique(['country_id', 'enterprise_id', 'enterprise_variety_id', 'enterprise_type_id'],'ctry_commission_unique');
            $table->timestamps();

            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE'); 
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE'); 
            $table->foreign('enterprise_variety_id')->on('enterprise_varieties')->references('id')->onDelete('CASCADE'); 
            $table->foreign('enterprise_type_id')->on('enterprise_types')->references('id')->onDelete('CASCADE');            
        });

        Schema::create('loan_input_commission_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('loan_input_commission_id'); 
            $table->uuid('commission_ranking_id'); 
            $table->double('rate',15,2)->default(0); 
            $table->enum('type', ['total', 'percentage']); 
            $table->timestamps();

            $table->foreign('loan_input_commission_id')->on('loan_input_commissions')->references('id')->onDelete('CASCADE'); 
            $table->foreign('commission_ranking_id')->on('agent_commission_rankings')->references('id')->onDelete('CASCADE');           
        });

        Schema::create('loan_limits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id')->unique()->nullable(); 
            $table->double('min_group_members',15,2)->nullable();
            $table->double('max_group_members',15,2)->nullable();
            $table->double('min_group_loan_amount',15,2)->nullable();
            $table->double('max_group_loan_amount',15,2)->nullable();
            $table->double('min_amount_per_farmer',15,2)->nullable();
            $table->double('max_amount_per_farmer',15,2)->nullable();
            $table->timestamps();

            $table->foreign('project_id')->on('loan_projects')->references('id')->onDelete('CASCADE');
        });  

        Schema::create('yield_estimations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('enterprise_variety_id');
            $table->double('farm_size',15,2)->default(0);
            $table->uuid('farm_size_unit_id');
            $table->double('input_estimate',15,2)->default(0);
            $table->uuid('input_unit_id');
            $table->double('output_min_estimate',15,2)->default(0);
            $table->double('output_max_estimate',15,2)->default(0);
            $table->uuid('output_unit_id');
            $table->timestamps();

            $table->foreign('enterprise_variety_id')->on('enterprise_varieties')->references('id')->onDelete('CASCADE');
            $table->foreign('farm_size_unit_id')->on('measure_units')->references('id')->onDelete('CASCADE');
            $table->foreign('input_unit_id')->on('measure_units')->references('id')->onDelete('CASCADE');
            $table->foreign('output_unit_id')->on('measure_units')->references('id')->onDelete('CASCADE');
        });

        Schema::create('microfinances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('logo')->nullable();
            $table->text('address');
            $table->text('services');
            $table->timestamps();
        });

        Schema::create('microfinance_loan_charges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('microfinance_id');
            $table->string('name');
            $table->double('charge',15,2)->default(0);
            $table->string('application');
            $table->enum('type', ['total', 'percentage']); 
            $table->timestamps();

            $table->foreign('microfinance_id')->on('microfinances')->references('id')->onDelete('CASCADE');
        });

        Schema::create('buyers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('logo')->nullable();
            $table->string('buyer_name');
            $table->string('contact_person_name');
            $table->string('contact_person_phone')->nullable();
            $table->uuid('location_id');
            $table->text('address')->nullable();
            $table->unique(['buyer_name', 'location_id'], 'buyer_location');
            $table->timestamps();

            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
        });

        Schema::create('buyer_enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('buyer_id');
            $table->uuid('enterprise_id');
            $table->timestamps();

            $table->foreign('buyer_id')->on('buyers')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE');
        });

        Schema::create('distributors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('logo')->nullable();
            $table->string('distributor_name');
            $table->string('contact_person_name');
            $table->string('contact_person_phone')->nullable();
            $table->uuid('location_id');
            $table->text('address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->timestamps();

            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
        });

        Schema::create('distributor_enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('distributor_id');
            $table->uuid('enterprise_id');
            $table->timestamps();

            $table->foreign('distributor_id')->on('distributors')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE');
        });

        Schema::create('distributor_enterprise_varieties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('distributor_id');
            $table->uuid('enterprise_variety_id');
            $table->timestamps();

            $table->foreign('distributor_id')->on('distributors')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_variety_id')->on('enterprise_varieties')->references('id')->onDelete('CASCADE');
        });

        Schema::create('distributor_enterprise_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('distributor_id');
            $table->uuid('enterprise_type_id');
            $table->timestamps();

            $table->foreign('distributor_id')->on('distributors')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_type_id')->on('enterprise_types')->references('id')->onDelete('CASCADE');
        });

        Schema::create('distributor_agro_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('distributor_id');
            $table->uuid('agro_product_id');
            $table->timestamps();

            $table->foreign('distributor_id')->on('distributors')->references('id')->onDelete('CASCADE');
            $table->foreign('agro_product_id')->on('agro_products')->references('id')->onDelete('CASCADE');
        });

        Schema::create('distributor_input_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id')->nullable();
            $table->uuid('season_id')->nullable();
            $table->uuid('distributor_id');
            $table->uuid('enterprise_id');
            $table->uuid('enterprise_variety_id')->nullable();
            $table->uuid('enterprise_type_id')->nullable(); 
            $table->uuid('currency_id');
            $table->double('price',15,2)->default(0);
            $table->uuid('unit_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->on('loan_projects')->references('id')->onDelete('CASCADE');
            $table->foreign('unit_id')->on('measure_units')->references('id')->onDelete('CASCADE');
            $table->foreign('season_id')->on('seasons')->references('id')->onDelete('CASCADE');
            $table->foreign('distributor_id')->on('distributors')->references('id')->onDelete('CASCADE');
            $table->foreign('currency_id')->on('currencies')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE'); 
            $table->foreign('enterprise_variety_id')->on('enterprise_varieties')->references('id')->onDelete('CASCADE'); 
            $table->foreign('enterprise_type_id')->on('enterprise_types')->references('id')->onDelete('CASCADE'); 
        });

        Schema::create('buyer_output_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id')->nullable();
            $table->uuid('season_id')->nullable();
            $table->uuid('buyer_id');
            $table->uuid('enterprise_id');
            $table->uuid('enterprise_variety_id')->nullable();
            $table->uuid('enterprise_type_id')->nullable(); 
            $table->uuid('currency_id');
            $table->double('price',15,2)->default(0);
            $table->uuid('unit_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('project_id')->on('loan_projects')->references('id')->onDelete('CASCADE');
            $table->foreign('season_id')->on('seasons')->references('id')->onDelete('CASCADE');
            $table->foreign('unit_id')->on('measure_units')->references('id')->onDelete('CASCADE');
            $table->foreign('buyer_id')->on('buyers')->references('id')->onDelete('CASCADE');
            $table->foreign('currency_id')->on('currencies')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE'); 
            $table->foreign('enterprise_variety_id')->on('enterprise_varieties')->references('id')->onDelete('CASCADE'); 
            $table->foreign('enterprise_type_id')->on('enterprise_types')->references('id')->onDelete('CASCADE'); 
        });

        Schema::create('loan_project_farmer_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->uuid('farmer_group_id');
            $table->timestamps();

            $table->foreign('project_id')->on('loan_projects')->references('id')->onDelete('CASCADE');
            $table->foreign('farmer_group_id')->on('farmer_groups')->references('id')->onDelete('CASCADE');
        });

        Schema::create('loan_project_microfinances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->uuid('microfinance_id');
            $table->timestamps();

            $table->foreign('project_id')->on('loan_projects')->references('id')->onDelete('CASCADE');
            $table->foreign('microfinance_id')->on('microfinances')->references('id')->onDelete('CASCADE');
        });

        Schema::create('loan_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->uuid('farmer_group_id');
            $table->uuid('microfinance_id');
            $table->enum('type', ['Loan', 'Cash']);
            $table->uuid('user_id')->nullable();
            $table->uuid('village_agent_id')->nullable();
            $table->uuid('farmer_id')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('village_agent_id')->on('agents')->references('id')->onDelete('CASCADE');
            $table->foreign('farmer_id')->on('farmers')->references('id')->onDelete('CASCADE');
            $table->foreign('project_id')->on('loan_projects')->references('id')->onDelete('CASCADE');
            $table->foreign('microfinance_id')->on('microfinances')->references('id')->onDelete('CASCADE');
            $table->foreign('farmer_group_id')->on('farmer_groups')->references('id')->onDelete('CASCADE');
        });

        Schema::create('loan_request_farmers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('loan_request_id');
            $table->uuid('farmer_id');
            $table->double('size_of_land',15,2)->default(0);
            $table->uuid('enterprise_variety_id');
            $table->double('input_estimation',15,2)->default(0);
            $table->double('output_estimation',15,2)->default(0);
            $table->double('price_per_unit',15,2)->default(0);
            $table->double('input_quantity',15,2)->default(0);
            $table->double('estimated_output_quantity',15,2)->default(0);
            $table->double('total_input_amount',15,2)->default(0);
            $table->double('insurance_amount',15,2)->default(0);            
            $table->uuid('user_id')->nullable();
            $table->uuid('village_agent_id')->nullable();
            $table->uuid('by_farmer_id')->nullable();
            $table->timestamps();

            $table->foreign('loan_request_id')->on('loan_requests')->references('id')->onDelete('CASCADE');
            $table->foreign('farmer_id')->on('farmers')->references('id')->onDelete('CASCADE');
            $table->foreign('enterprise_variety_id')->on('enterprise_varieties')->references('id')->onDelete('CASCADE');

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('village_agent_id')->on('agents')->references('id')->onDelete('CASCADE');
            $table->foreign('by_farmer_id')->on('farmers')->references('id')->onDelete('CASCADE');
        });

        Schema::create('lpo_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id')->unique();
            $table->string('name');
            $table->text('title');
            $table->text('notes');
            $table->text('signature');
            $table->timestamps();

            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lpo_settings');
        Schema::dropIfExists('loan_request_farmers');
        Schema::dropIfExists('loan_requests');
        Schema::dropIfExists('loan_limits');
        Schema::dropIfExists('yield_estimations');
        Schema::dropIfExists('microfinance_loan_charges');
        Schema::dropIfExists('loan_project_microfinances');
        Schema::dropIfExists('microfinances');
        Schema::dropIfExists('buyer_enterprises');
        Schema::dropIfExists('buyer_output_prices');
        Schema::dropIfExists('buyers'); 
        Schema::dropIfExists('distributor_agro_products');        
        Schema::dropIfExists('distributor_enterprise_types');
        Schema::dropIfExists('distributor_enterprise_varieties');
        Schema::dropIfExists('distributor_enterprises');
        Schema::dropIfExists('distributor_input_prices');
        Schema::dropIfExists('distributors');
        Schema::dropIfExists('loan_project_farmer_groups');
        Schema::dropIfExists('loan_input_commission_rates');
        Schema::dropIfExists('loan_input_commissions');
        Schema::dropIfExists('loan_projects');
    }
}
