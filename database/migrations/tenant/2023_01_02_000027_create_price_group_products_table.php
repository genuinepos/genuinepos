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
        Schema::create('price_group_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('price_group_id')->nullable()->index('price_group_products_price_group_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('price_group_products_product_id_foreign');
            $table->unsignedBigInteger('variant_id')->nullable()->index('price_group_products_variant_id_foreign');
            $table->decimal('price', 22, 2)->nullable();
            $table->timestamps();

            $table->foreign(['price_group_id'])->references(['id'])->on('price_groups')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_group_products');
    }
};
