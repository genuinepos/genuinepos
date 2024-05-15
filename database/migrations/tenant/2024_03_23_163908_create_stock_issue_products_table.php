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
        Schema::create('stock_issue_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_issue_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->decimal('quantity', 22, 2)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
            $table->decimal('subtotal', 22, 2)->default(0);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign(['stock_issue_id'])->references(['id'])->on('stock_issues')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_issue_products');
    }
};
