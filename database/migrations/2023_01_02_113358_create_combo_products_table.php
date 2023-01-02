<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combo_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->nullable()->index('combo_products_product_id_foreign');
            $table->unsignedBigInteger('combo_product_id')->nullable()->index('combo_products_combo_product_id_foreign');
            $table->decimal('quantity', 22)->nullable()->default(0);
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('combo_products_product_variant_id_foreign');
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
        Schema::dropIfExists('combo_products');
    }
}
