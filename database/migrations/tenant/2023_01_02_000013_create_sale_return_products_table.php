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
        Schema::create('sale_return_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_return_id')->index('sale_return_products_sale_return_id_foreign');
            $table->unsignedBigInteger('sale_product_id')->nullable()->index('sale_return_products_sale_product_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('sale_return_products_product_id_foreign');
            $table->unsignedBigInteger('variant_id')->nullable()->index('sale_return_products_variant_id_foreign');
            $table->decimal('sold_quantity', 22, 2)->default(0);
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
            $table->decimal('unit_price_exc_tax', 22, 2)->default(0);
            $table->decimal('unit_price_inc_tax', 22, 2)->default(0);
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_discount', 22, 2)->default(0);
            $table->decimal('unit_discount_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('tax_ac_id')->nullable();
            $table->tinyInteger('tax_type')->default(1);
            $table->decimal('unit_tax_percent', 22, 2)->default(0);
            $table->decimal('unit_tax_amount', 22, 2)->default(0);
            $table->decimal('return_qty', 22, 2)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('return_subtotal', 22, 2)->default(0);
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();

            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
            $table->foreign(['tax_ac_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['sale_product_id'])->references(['id'])->on('sale_products')->onDelete('CASCADE');
            $table->foreign(['sale_return_id'])->references(['id'])->on('sale_returns')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_return_products');
    }
};
