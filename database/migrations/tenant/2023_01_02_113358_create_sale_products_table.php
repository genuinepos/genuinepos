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
        Schema::create('sale_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_id')->index('sale_products_sale_id_foreign');
            $table->unsignedBigInteger('product_id')->index('sale_products_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('sale_products_product_variant_id_foreign');
            $table->decimal('quantity', 22)->default(0);
            $table->string('unit')->nullable();
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_discount', 22)->default(0);
            $table->decimal('unit_discount_amount', 22)->default(0);
            $table->decimal('unit_tax_percent', 22)->default(0);
            $table->decimal('unit_tax_amount', 22)->default(0);
            $table->decimal('unit_cost_inc_tax', 22)->default(0)->comment('this_col_for_invoice_profit_report');
            $table->decimal('unit_price_exc_tax', 22)->default(0);
            $table->decimal('unit_price_inc_tax', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0);
            $table->mediumText('description')->nullable();
            $table->decimal('ex_quantity', 22)->default(0);
            $table->tinyInteger('ex_status')->default(0)->comment('0=no_exchanged,1=prepare_to_exchange,2=exchanged');
            $table->boolean('delete_in_update')->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('stock_branch_id')->nullable()->index('sale_products_stock_branch_id_foreign');
            $table->unsignedBigInteger('stock_warehouse_id')->nullable()->index('sale_products_stock_warehouse_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_products');
    }
};
