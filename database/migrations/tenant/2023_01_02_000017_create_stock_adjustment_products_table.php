<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustment_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stock_adjustment_id')->index('stock_adjustment_products_stock_adjustment_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('stock_adjustment_products_branch_id_foreign');
            $table->unsignedBigInteger('warehouse_id')->nullable()->index('stock_adjustment_products_warehouse_id_foreign');
            $table->unsignedBigInteger('product_id')->index('stock_adjustment_products_product_id_foreign');
            $table->unsignedBigInteger('variant_id')->nullable()->index('stock_adjustment_products_variant_id_foreign');
            $table->decimal('quantity', 22)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign(['stock_adjustment_id'])->references(['id'])->on('stock_adjustments')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustment_products');
    }
};
