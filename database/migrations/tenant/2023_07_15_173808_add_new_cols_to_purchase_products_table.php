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
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->tinyInteger('unit_discount_type')->after('unit_discount')->default(1);
            $table->decimal('unit_discount_amount', 22, 2)->after('unit_discount_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->dropColumn('unit_discount_type');
            $table->dropColumn('unit_discount_amount');
        });
    }
};
