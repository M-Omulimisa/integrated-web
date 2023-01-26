<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketPackagingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_packages', function (Blueprint $table) {
            if (! Schema::hasColumn('market_packages', 'menu')) $table->integer('menu')->after('name');
            if (! Schema::hasColumn('market_packages', 'status')) $table->boolean('status')->default(true)->after('menu');

            if (Schema::hasColumn('market_packages', 'frequency')) $table->dropColumn('frequency');
            if (Schema::hasColumn('market_packages', 'messages')) $table->dropColumn('messages');
            if (Schema::hasColumn('market_packages', 'cost')) $table->dropColumn('cost');
        });

        Schema::create('market_package_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('package_id');
            $table->uuid('language_id');
            $table->integer('menu');
            $table->text('message')->nullable();
            $table->timestamps();

            $table->foreign('package_id')->on('market_packages')->references('id')->onDelete('CASCADE');
            $table->foreign('language_id')->on('languages')->references('id')->onDelete('CASCADE');
        });

        Schema::create('market_package_pricings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('package_id');
            $table->string('frequency');
            $table->integer('menu');
            $table->integer('messages')->default(0);
            $table->double('cost', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('package_id')->on('market_packages')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_package_pricings');
        Schema::dropIfExists('market_package_messages');
        
    }
}
