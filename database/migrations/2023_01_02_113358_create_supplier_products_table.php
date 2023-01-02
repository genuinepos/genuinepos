<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id')->index('supplier_products_supplier_id_foreign');
            $table->unsignedBigInteger('product_id')->index('supplier_products_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('supplier_products_product_variant_id_foreign');
            $table->bigInteger('label_qty')->default(0);
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
        Schema::dropIfExists('supplier_products');
    }
}
