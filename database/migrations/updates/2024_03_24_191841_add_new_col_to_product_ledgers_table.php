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
        Schema::table('product_ledgers', function (Blueprint $table) {

            if (!Schema::hasColumn('product_ledgers', 'stock_issue_product_id')) {

                $table->unsignedBigInteger('stock_issue_product_id')->after('transfer_stock_product_id')->nullable();
                $table->foreign('stock_issue_product_id')->references('id')->on('stock_issue_products')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_ledgers', function (Blueprint $table) {
            $table->dropForeign(['stock_issue_product_id']);
            $table->dropColumn('stock_issue_product_id');
        });
    }
};
