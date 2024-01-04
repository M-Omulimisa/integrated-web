<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessageCountToMarketSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('market_subscriptions', 'outbox_count')) $table->string('outbox_count')->default(0)->before('outbox_generation_status');
            if (!Schema::hasColumn('market_subscriptions', 'region_id')) $table->string('region_id')->default(0)->before('outbox_generation_status');
        });

        Schema::table('market_packages', function (Blueprint $table) {
            if (Schema::hasColumn('market_packages', 'region_id')) $table->dropColumn('region_id');
        });

        Schema::table('market_package_regions', function (Blueprint $table) {
            if (!Schema::hasColumn('market_package_regions', 'created_at') && !Schema::hasColumn('market_package_regions', 'updated_at')) $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('market_subscriptions', function (Blueprint $table) {
            // if (Schema::hasColumn('market_subscriptions', 'outbox_count')) $table->dropColumn('outbox_count');
            // if (Schema::hasColumn('market_subscriptions', 'region_id')) $table->dropColumn('region_id');
        });
    }
}
