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
        Schema::create('purchase_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->decimal('ordered_quantity', 22, 2)->default(0);
            $table->decimal('received_quantity', 22, 2)->default(0);
            $table->decimal('pending_quantity', 22, 2)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->decimal('unit_cost_exc_tax', 22, 2)->default(0);
            $table->decimal('unit_discount', 22, 2)->default(0);
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_discount_amount', 22, 2)->default(0);
            $table->decimal('unit_cost_with_discount', 22, 2)->default(0);
            $table->decimal('subtotal', 22, 2)->default(0)->comment('Without_tax');
            $table->unsignedBigInteger('tax_ac_id')->nullable();
            $table->tinyInteger('unit_tax_type')->default(1);
            $table->decimal('unit_tax_percent', 22, 2)->default(0);
            $table->decimal('unit_tax_amount', 22, 2)->default(0);
            $table->decimal('net_unit_cost', 22, 2)->default(0)->comment('inc_tax');
            $table->decimal('line_total', 22, 2)->default(0);
            $table->decimal('profit_margin', 22, 2)->default(0);
            $table->decimal('selling_price', 22, 2)->default(0);
            $table->mediumText('description')->nullable();
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();

            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
            $table->foreign(['tax_ac_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_products');
    }
};
