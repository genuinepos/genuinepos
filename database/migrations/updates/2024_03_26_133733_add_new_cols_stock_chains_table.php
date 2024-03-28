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

            if (!Schema::hasColumn('stock_chains', 'branch_id')) {

                $table->unsignedBigInteger('branch_id')->after('id')->nullable();
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            }

            if (!Schema::hasColumn('stock_chains', 'product_id')) {

                $table->unsignedBigInteger('product_id')->after('branch_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            }

            if (!Schema::hasColumn('stock_chains', 'variant_id')) {

                $table->unsignedBigInteger('variant_id')->after('product_id')->nullable();
                $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_chains', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');

            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });
    }
};
