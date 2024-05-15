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
        Schema::table('day_books', function (Blueprint $table) {

            if (!Schema::hasColumn('day_books', 'product_id')) {

                $table->unsignedBigInteger('product_id')->after('voucher_description_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            }

            if (!Schema::hasColumn('day_books', 'variant_id')) {

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
        Schema::table('day_books', function (Blueprint $table) {

            $table->dropForeign(['product_id']);
            $table->dropForeign(['variant_id']);
            $table->dropColumn('product_id');
            $table->dropColumn('variant_id');
        });
    }
};
