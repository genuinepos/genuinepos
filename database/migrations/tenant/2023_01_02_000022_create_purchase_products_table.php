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
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('purchase_products_branch_id_foreign')->comment('This column for track branch wise FIFO/LIFO method.');
            $table->unsignedBigInteger('purchase_id')->nullable()->index('purchase_products_purchase_id_foreign');
            $table->unsignedBigInteger('product_id')->index('purchase_products_product_id_foreign');
            $table->unsignedBigInteger('variant_id')->nullable()->index('purchase_products_variant_id_foreign');
            $table->decimal('quantity', 22)->nullable()->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_cost_exc_tax', 22)->default(0);
            $table->decimal('unit_discount', 22)->default(0);
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_discount_amount', 22, 2)->default(0);
            $table->decimal('unit_cost_with_discount', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0)->comment('Without_tax');
            $table->decimal('unit_tax_percent', 22)->default(0);
            $table->unsignedBigInteger('tax_ac_id')->nullable();
            $table->tinyInteger('tax_type')->nullable();
            $table->decimal('unit_tax_amount', 22)->default(0);
            $table->decimal('net_unit_cost', 22)->default(0)->comment('With_tax');
            $table->decimal('line_total', 22)->default(0);
            $table->decimal('profit_margin', 22)->default(0);
            $table->decimal('selling_price', 22)->default(0);
            $table->mediumText('description')->nullable();
            $table->boolean('is_received')->default(false);
            $table->string('lot_no', 191)->nullable();
            $table->string('batch_number')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->boolean('delete_in_update')->default(false);
            $table->unsignedBigInteger('purchase_order_product_id')->nullable()->index('purchase_products_purchase_order_product_id_foreign')->comment('when product add from purchase_order_products table');
            $table->decimal('left_qty', 22)->default(0);
            $table->unsignedBigInteger('production_id')->nullable()->index('purchase_products_production_id_foreign');
            $table->unsignedBigInteger('opening_stock_id')->nullable()->index('purchase_products_opening_stock_id_foreign');
            $table->unsignedBigInteger('sale_return_product_id')->nullable()->index('purchase_products_sale_return_product_id_foreign');
            $table->unsignedBigInteger('transfer_stock_product_id')->nullable()->index('purchase_products_transfer_stock_product_id_foreign');
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
            $table->foreign(['tax_ac_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['opening_stock_id'])->references(['id'])->on('product_opening_stocks')->onDelete('CASCADE');
            $table->foreign(['production_id'])->references(['id'])->on('productions')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['purchase_order_product_id'])->references(['id'])->on('purchase_order_products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
            $table->foreign(['sale_return_product_id'])->references(['id'])->on('sale_return_products')->onDelete('CASCADE');
            $table->foreign(['transfer_stock_product_id'])->references(['id'])->on('transfer_stock_products')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_products');
    }
};
