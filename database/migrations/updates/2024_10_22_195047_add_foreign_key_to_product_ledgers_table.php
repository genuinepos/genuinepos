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
            $table->foreign('sale_return_product_id')->references('id')->on('sale_return_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_ledgers', function (Blueprint $table) {
            $table->dropForeign(['sale_return_product_id']);
        });
    }
};
