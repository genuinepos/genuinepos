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
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('purchase_return_id');
            $table->unsignedBigInteger('purchase_product_id')->nullable()->comment('this_field_only_for_purchase_invoice_return.');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('unit_cost_exc_tax', 22, 2)->default(0);
            $table->decimal('return_qty', 22, 2)->default(0);
            $table->decimal('purchased_qty', 22, 2)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_discount', 22, 2)->default(0);
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_discount_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('tax_ac_id')->nullable();
            $table->tinyInteger('unit_tax_type')->default(1);
            $table->decimal('unit_tax_percent', 22, 2)->default(0);
            $table->decimal('unit_tax_amount', 22, 2)->default(0);
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0);
            $table->decimal('return_subtotal', 22)->default(0);
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['purchase_return_id'])->references(['id'])->on('purchase_returns')->onDelete('CASCADE');
            $table->foreign(['purchase_product_id'])->references(['id'])->on('purchase_products')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
            $table->foreign(['tax_ac_id'])->references(['id'])->on('product_variants')->onDelete('SET NULL');
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
