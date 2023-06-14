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
        Schema::create('product_warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('warehouse_id')->nullable()->index('product_warehouses_warehouse_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('product_warehouses_product_id_foreign');
            $table->decimal('product_quantity', 22)->nullable()->default(0);
            $table->decimal('total_purchased', 22)->default(0);
            $table->decimal('total_adjusted', 22)->default(0);
            $table->decimal('total_transferred', 22)->default(0);
            $table->decimal('total_received', 22)->default(0);
            $table->decimal('total_sale_return', 22)->default(0);
            $table->decimal('total_purchase_return', 22)->default(0);
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
        Schema::dropIfExists('product_warehouses');
    }
};
