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
        if (Schema::hasTable('purchase_sale_product_chains')) {

            Schema::table('purchase_sale_product_chains', function (Blueprint $table) {

                $table->rename('stock_chains');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('stock_chains')) {

            Schema::table('stock_chains', function (Blueprint $table) {

                $table->rename('purchase_sale_product_chains');
            });
        }
    }
};
