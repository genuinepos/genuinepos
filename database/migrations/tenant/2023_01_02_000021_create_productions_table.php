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
        Schema::create('productions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index('productions_branch_id_foreign');
            $table->unsignedBigInteger('store_warehouse_id')->nullable()->index('productions_store_warehouse_id_foreign');
            $table->unsignedBigInteger('stock_warehouse_id')->nullable()->index('productions_stock_warehouse_id_foreign');

            $table->unsignedBigInteger('product_id')->nullable()->index('productions_product_id_foreign');
            $table->unsignedBigInteger('variant_id')->nullable()->index('productions_variant_id_foreign');
            $table->decimal('total_ingredient_cost', 22, 2)->nullable();
            $table->decimal('total_output_quantity', 22, 2)->nullable();
            $table->unsignedBigInteger('unit_id')->nullable()->index('productions_unit_id_foreign');
            $table->decimal('total_parameter_quantity', 22, 2)->default(0);
            $table->decimal('total_wasted_quantity', 22, 2)->nullable();
            $table->decimal('total_final_output_quantity', 22, 2)->default(0);
            $table->decimal('additional_production_cost', 22, 2)->nullable();
            $table->decimal('net_cost', 22, 2)->nullable();
            $table->decimal('per_unit_cost_exc_tax', 22, 2)->default(0);
            $table->unsignedBigInteger('tax_ac_id')->nullable()->index('productions_tax_ac_id_foreign');
            $table->tinyInteger('tax_type')->nullable();
            $table->decimal('unit_tax_percent', 22, 2)->default(0);
            $table->decimal('unit_tax_amount', 22, 2)->default(0);
            $table->decimal('per_unit_cost_inc_tax', 22, 2)->default(0);
            $table->decimal('profit_margin', 22, 2)->default(0);
            $table->decimal('per_unit_price_exc_tax', 22, 2)->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('is_last_entry')->default(0);
            $table->boolean('is_default_price')->default(0);
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['store_warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['stock_warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onDelete('CASCADE');
            $table->foreign(['tax_ac_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productions');
    }
};
