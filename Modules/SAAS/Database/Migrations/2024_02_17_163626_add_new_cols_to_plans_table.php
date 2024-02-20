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
            $table->json('features')->after('status')->nullable();
            $table->decimal('business_price_per_month')->after('lifetime_price')->default(0);
            $table->decimal('business_price_per_year')->after('business_price_per_month')->default(0);
            $table->decimal('business_lifetime_price')->after('business_price_per_year')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('business_price_per_month');
            $table->dropColumn('business_price_per_year');
            $table->dropColumn('business_lifetime_price');
            $table->dropColumn('features');
        });
    }
};
