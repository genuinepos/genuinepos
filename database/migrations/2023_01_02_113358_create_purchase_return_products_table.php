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
        Schema::create('purchase_return_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_return_id')->index('purchase_return_products_purchase_return_id_foreign');
            $table->unsignedBigInteger('purchase_product_id')->nullable()->index('purchase_return_products_purchase_product_id_foreign')->comment('this_field_only_for_purchase_invoice_return.');
            $table->unsignedBigInteger('product_id')->nullable()->index('purchase_return_products_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('purchase_return_products_product_variant_id_foreign');
            $table->decimal('unit_cost')->default(0);
            $table->decimal('return_qty', 22)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('return_subtotal', 22)->default(0);
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
        Schema::dropIfExists('purchase_return_products');
    }
};
