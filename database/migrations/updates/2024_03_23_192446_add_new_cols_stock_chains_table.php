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
        Schema::table('stock_chains', function (Blueprint $table) {

            if (!Schema::hasColumn('stock_chains', 'stock_issue_product_id')) {

                $table->unsignedBigInteger('stock_issue_product_id')->after('sale_product_id')->nullable();
                $table->foreign(['stock_issue_product_id'])->references(['id'])->on('stock_issue_products')->onDelete('CASCADE');
            }

            if (!Schema::hasColumn('stock_chains', 'stock_adjustment_product_id')) {

                $table->unsignedBigInteger('stock_adjustment_product_id')->after('stock_issue_product_id')->nullable();
                $table->foreign(['stock_adjustment_product_id'])->references(['id'])->on('stock_adjustment_products')->onDelete('CASCADE');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_chains', function (Blueprint $table) {
            $table->dropForeign(['stock_issue_product_id']);
            $table->dropColumn('stock_issue_product_id');

            $table->dropForeign(['stock_adjustment_product_id']);
            $table->dropColumn('stock_adjustment_product_id');
        });
    }
};
