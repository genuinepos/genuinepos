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
            $table->double('price_per_year', 22, 2)->after('price')->default(0);
            $table->double('lifetime_price', 22, 2)->after('price_per_year')->default(0);
            $table->renameColumn('price', 'price_per_month');
            $table->renameColumn('applicable_life_time_years', 'applicable_lifetime_years');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->renameColumn('price_per_month', 'price');
            $table->renameColumn('applicable_lifetime_years', 'applicable_life_time_years');
            $table->dropColumn('price_per_year');
            $table->dropColumn('lifetime_price');
        });
    }
};
