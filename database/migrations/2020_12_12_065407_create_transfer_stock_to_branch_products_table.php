<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockToBranchProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_to_branch_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_stock_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->decimal('unit_price', 22, 2);
            $table->decimal('quantity', 22, 2);
            $table->decimal('received_qty', 22, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->decimal('subtotal', 22, 2);
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign('transfer_stock_id')->references('id')->on('transfer_stock_to_branches')->onDelete('cascade');
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
        Schema::dropIfExists('transfer_stock_to_branch_products');
    }
}
