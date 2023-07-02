<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('insurance_windows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('season_id'); 
            $table->date('opening_date');
            $table->date('closing_date');
            $table->timestamps();

            $table->foreign('season_id')->on('seasons')->references('id')->onDelete('CASCADE');
        });  

        Schema::create('insurance_premium_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id'); 
            $table->uuid('season_id'); 
            $table->uuid('enterprise_id')->nullable(); 
            $table->double('sum_insured_per_acre',15,2)->default(0.00);
            $table->double('premium_per_acre',15,2)->default(0.00);
            $table->string('menu');
            $table->boolean('status')->default(true);
            // $table->unique(['country_id', 'season_id', 'enterprise_id']);
            $table->timestamps();

            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
        });

        Schema::create('insurance_calculator_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id'); 
            $table->uuid('season_id'); 
            $table->double('sum_insured',15,2)->default(0);
            $table->double('sum_insured_special',15,2)->default(0);

            $table->double('govt_subsidy_none',15,2)->default(0);
            $table->double('govt_subsidy_small_scale',15,2)->default(0);
            $table->double('govt_subsidy_large_scale',15,2)->default(0);
            $table->uuid('location_level_id');
            $table->double('govt_subsidy_locations',15,2)->default(0);

            $table->double('scale_limit',15,2)->default(0);
            $table->double('ira_levy',15,2)->default(0);
            $table->double('vat',15,2)->default(0);

            $table->timestamps();

            $table->unique(['country_id', 'season_id']);

            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');
            $table->foreign('location_level_id')->on('country_admin_units')->references('id')->onDelete('CASCADE');
        });

        Schema::create('insurance_commissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('calculator_id');
            $table->uuid('commission_ranking_id')->nullable(); 
            $table->double('commission',15,2)->default(0); 
            $table->enum('type', ['total', 'percentage']); 
            $table->unique(['calculator_id', 'commission_ranking_id'],'calcator_commission_unique');
            $table->timestamps();

            $table->foreign('commission_ranking_id')->on('agent_commission_rankings')->references('id')->onDelete('CASCADE');
            $table->foreign('calculator_id')->on('insurance_calculator_values')->references('id')->onDelete('CASCADE');            
        });

        Schema::create('insured_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('calculator_id');
            $table->uuid('location_id')->nullable(); 
            $table->unique(['calculator_id', 'location_id']);
            $table->timestamps();

            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');
            $table->foreign('calculator_id')->on('insurance_calculator_values')->references('id')->onDelete('CASCADE');            
        });

        Schema::create('insured_enterprises', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('calculator_id');
            $table->uuid('enterprise_id')->nullable(); 
            $table->unique(['calculator_id', 'enterprise_id']);
            $table->timestamps();

            $table->foreign('enterprise_id')->on('enterprises')->references('id')->onDelete('CASCADE');
            $table->foreign('calculator_id')->on('insurance_calculator_values')->references('id')->onDelete('CASCADE');            
        });

        Schema::create('insurance_loss_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->uuid('season_id');
            $table->uuid('location_id')->nullable(); 
            $table->double('payable_percentage',15,2)->default(0);
            $table->double('less_excess',15,2)->default(0);
            $table->unique(['country_id', 'season_id']);
            $table->timestamps();

            $table->foreign('season_id')->on('seasons')->references('id')->onDelete('CASCADE');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');            
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');            
        });

        Schema::create('insurance_full_cover_rates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('country_id');
            $table->uuid('season_id');
            $table->uuid('location_id')->nullable(); // subcounty
            $table->double('rate',15,2)->default(0);
            $table->unique(['country_id', 'season_id']);
            $table->timestamps();

            $table->foreign('season_id')->on('seasons')->references('id')->onDelete('CASCADE');
            $table->foreign('country_id')->on('countries')->references('id')->onDelete('CASCADE');            
            $table->foreign('location_id')->on('locations')->references('id')->onDelete('CASCADE');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {       
        Schema::dropIfExists('insurance_commissions');
        Schema::dropIfExists('insurance_full_cover_rates');
        Schema::dropIfExists('insurance_loss_values');
        Schema::dropIfExists('insured_enterprises');
        Schema::dropIfExists('insured_locations');
        Schema::dropIfExists('insurance_calculator_values');
        Schema::dropIfExists('insurance_premium_options');
        Schema::dropIfExists('insurance_windows');
    }
}