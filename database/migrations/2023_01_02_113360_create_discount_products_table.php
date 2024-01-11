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
        Schema::create('discount_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('discount_id')->nullable()->index('discount_products_discount_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('discount_products_product_id_foreign');
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign(['discount_id'])->references(['id'])->on('discounts')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_products');
    }
};
