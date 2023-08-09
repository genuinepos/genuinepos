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
            $table->unsignedBigInteger('unit_id')->nullable()->index('productions_unit_id_foreign');
            $table->unsignedBigInteger('tax_id')->nullable()->index('productions_tax_id_foreign');
            $table->tinyInteger('tax_type')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('date')->nullable();
            $table->string('time', 20)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable()->index('productions_warehouse_id_foreign');
            $table->unsignedBigInteger('stock_warehouse_id')->nullable()->index('productions_stock_warehouse_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('productions_branch_id_foreign');
            $table->unsignedBigInteger('stock_branch_id')->nullable()->index('productions_stock_branch_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('productions_product_id_foreign');
            $table->unsignedBigInteger('variant_id')->nullable()->index('productions_variant_id_foreign');
            $table->decimal('total_ingredient_cost', 22)->nullable();
            $table->decimal('quantity', 22)->nullable();
            $table->decimal('parameter_quantity', 22)->default(0);
            $table->decimal('wasted_quantity', 22)->nullable();
            $table->decimal('total_final_quantity', 22)->default(0);
            $table->decimal('unit_cost_exc_tax', 22)->default(0);
            $table->decimal('unit_cost_inc_tax', 22)->default(0);
            $table->decimal('x_margin', 22)->default(0);
            $table->decimal('price_exc_tax', 22)->default(0);
            $table->decimal('production_cost', 22)->nullable();
            $table->decimal('total_cost', 22)->nullable();
            $table->boolean('is_final')->default(false);
            $table->boolean('is_last_entry')->default(false);
            $table->boolean('is_default_price')->default(false);
            $table->unsignedBigInteger('production_account_id')->nullable()->index('productions_production_account_id_foreign');
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
        Schema::dropIfExists('productions');
    }
};
