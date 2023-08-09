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
        Schema::create('product_opening_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('product_opening_stocks_branch_id_foreign');
            $table->unsignedBigInteger('warehouse_id')->nullable()->index('product_opening_stocks_warehouse_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('product_opening_stocks_product_id_foreign');
            $table->unsignedBigInteger('product_variant_id')->nullable()->index('product_opening_stocks_product_variant_id_foreign');
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('quantity', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0);
            $table->string('lot_no')->nullable();
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
        Schema::dropIfExists('product_opening_stocks');
    }
};
