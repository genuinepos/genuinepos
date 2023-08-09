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
        Schema::create('purchase_sale_product_chains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_product_id')->nullable()->index('purchase_sale_product_chains_purchase_product_id_foreign');
            $table->unsignedBigInteger('sale_product_id')->nullable()->index('purchase_sale_product_chains_sale_product_id_foreign');
            $table->decimal('sold_qty', 22)->default(0);
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
        Schema::dropIfExists('purchase_sale_product_chains');
    }
};
