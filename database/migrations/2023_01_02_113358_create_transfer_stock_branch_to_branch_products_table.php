<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockBranchToBranchProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_branch_to_branch_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transfer_id')->nullable()->index('transfer_stock_branch_to_branch_products_transfer_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('transfer_stock_branch_to_branch_products_product_id_foreign');
            $table->unsignedBigInteger('variant_id')->nullable()->index('transfer_stock_branch_to_branch_products_variant_id_foreign');
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('unit_price_inc_tax', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0);
            $table->decimal('send_qty', 22)->default(0);
            $table->decimal('received_qty', 22)->default(0);
            $table->decimal('pending_qty', 22)->default(0);
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
        Schema::dropIfExists('transfer_stock_branch_to_branch_products');
    }
}
