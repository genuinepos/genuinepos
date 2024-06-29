<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('is_trial_plan')->after('period_unit')->default(0);
            $table->boolean('trial_days')->after('is_trial_plan')->default(0);
            $table->integer('trial_shop_count')->after('trial_days')->nullable();
            $table->renameColumn('period_value', 'applicable_life_time_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('is_trial_plan');
            $table->dropColumn('trial_days');
            $table->dropColumn('trial_shop_count');
            $table->renameColumn('applicable_life_time_years', 'period_value');
        });
    }
};
