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
        Schema::create('purchase_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_id')->index('purchase_order_products_purchase_id_foreign');
            $table->unsignedBigInteger('product_id')->index('purchase_order_products_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('purchase_order_products_product_variant_id_foreign');
            $table->decimal('order_quantity', 22)->default(0);
            $table->decimal('received_quantity', 22)->default(0);
            $table->decimal('pending_quantity', 22)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('unit_cost', 22)->default(0);
            $table->decimal('unit_discount', 22)->default(0);
            $table->decimal('unit_cost_with_discount', 10)->default(0);
            $table->decimal('subtotal', 22)->default(0)->comment('Without_tax');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->decimal('unit_tax_percent', 22)->default(0);
            $table->decimal('unit_tax', 22)->default(0);
            $table->decimal('net_unit_cost', 22)->default(0)->comment('inc_tax');
            $table->decimal('ordered_unit_cost', 22)->default(0)->comment('inc_tax');
            $table->decimal('line_total', 22)->default(0);
            $table->decimal('profit_margin', 22)->default(0);
            $table->decimal('selling_price', 22)->default(0);
            $table->mediumText('description')->nullable();
            $table->string('lot_no')->nullable();
            $table->boolean('delete_in_update')->default(false);
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
        Schema::dropIfExists('purchase_order_products');
    }
};
