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
        Schema::create('sale_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->decimal('quantity', 22, 2)->default(0);
            $table->decimal('ordered_quantity', 22, 2)->default(0);
            $table->decimal('delivered_quantity', 22, 2)->default(0);
            $table->decimal('left_quantity', 22, 2)->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('product_unit_id')->nullable();
            $table->tinyInteger('unit_discount_type')->default(1);
            $table->decimal('unit_discount', 22, 2)->default(0);
            $table->decimal('unit_discount_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('tax_ac_id')->nullable();
            $table->tinyInteger('tax_type')->default(1);
            $table->decimal('unit_tax_percent', 22, 2)->default(0);
            $table->decimal('unit_tax_amount', 22, 2)->default(0);
            $table->decimal('unit_cost_inc_tax', 22, 2)->default(0)->comment('this_col_for_invoice_profit_report');
            $table->decimal('unit_price_exc_tax', 22, 2)->default(0);
            $table->decimal('unit_price_inc_tax', 22, 2)->default(0);
            $table->decimal('subtotal', 22, 2)->default(0);
            $table->mediumText('description')->nullable();
            $table->decimal('ex_quantity', 22, 2)->default(0);
            $table->tinyInteger('ex_status')->default(0)->comment('0=no_exchanged,1=prepare_to_exchange,2=exchanged');
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();

            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('SET NULL');
            $table->foreign(['product_unit_id'])->references(['id'])->on('product_units')->onDelete('SET NULL');
            $table->foreign(['tax_ac_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_products');
    }
};
