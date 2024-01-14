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
            $table->unsignedBigInteger('variant_id')->nullable()->index('product_opening_stocks_variant_id_foreign');
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('quantity', 22)->default(0);
            $table->decimal('subtotal', 22)->default(0);
            $table->string('lot_no')->nullable();
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
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
        Schema::dropIfExists('product_opening_stocks');
    }
};
