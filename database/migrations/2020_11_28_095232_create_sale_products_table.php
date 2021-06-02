<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('quantity', 22,2)->default(0.00);
            $table->string('unit')->nullable();
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_discount', 22,2)->default(0.00);
            $table->decimal('unit_discount_amount', 22, 2)->default(0.00);
            $table->decimal('unit_tax_percent', 22, 2)->default(0.00);
            $table->decimal('unit_tax_amount', 22,2)->default(0.00);
            $table->decimal('unit_cost_inc_tax', 22,2)->default(0.00)->comment('this_col_for_invoice_profit_report');
            $table->decimal('unit_price_exc_tax', 22,2)->default(0.00);
            $table->decimal('unit_price_inc_tax', 22,2)->default(0.00);
            $table->decimal('subtotal', 22,2)->default(0.00);
            $table->mediumText('description',)->nullable();
            $table->decimal('ex_quantity', 22,2)->default(0.00);
            $table->tinyInteger('ex_status')->default(0)->comment('0=no_exchanged,1=prepare_to_exchange,2=exchanged');
            $table->boolean('delete_in_update')->default(0);
            $table->timestamps();
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
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
}
