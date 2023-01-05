<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnProductsTable extends Migration
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
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('sale_return_products_product_variant_id_foreign');
            $table->decimal('sold_quantity', 22)->default(0);
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('unit_price_exc_tax', 22)->default(0);
            $table->decimal('unit_price_inc_tax', 22)->default(0);
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_discount', 22)->default(0);
            $table->decimal('unit_discount_amount', 22)->default(0);
            $table->tinyInteger('tax_type')->default(1);
            $table->decimal('unit_tax_percent', 22)->default(0);
            $table->decimal('unit_tax_amount', 22)->default(0);
            $table->decimal('return_qty', 22)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('return_subtotal', 22)->default(0);
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();
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
}
