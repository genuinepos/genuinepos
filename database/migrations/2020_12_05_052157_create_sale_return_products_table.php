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
            $table->id();
            $table->unsignedBigInteger('sale_return_id');
            $table->unsignedBigInteger('sale_product_id');
            $table->decimal('return_qty', 22, 2)->default(0.00);
            $table->string('unit')->nullable();
            $table->decimal('return_subtotal', 22, 2)->default(0.00);
            $table->timestamps();
            $table->foreign('sale_return_id')->references('id')->on('sale_returns')->onDelete('cascade');
            $table->foreign('sale_product_id')->references('id')->on('sale_products')->onDelete('cascade');
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
