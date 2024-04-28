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
        if (!Schema::hasTable('stock_chains')) {

            Schema::create('stock_chains', function (Blueprint $table) {

                $table->bigIncrements('id');
                $table->unsignedBigInteger('branch_id')->nullable()->index('branches_branch_id_foreign');
                $table->unsignedBigInteger('purchase_product_id')->nullable()->index('stock_chains_purchase_product_id_foreign');
                $table->unsignedBigInteger('sale_product_id')->nullable()->index('stock_chains_sale_product_id_foreign');
                $table->unsignedBigInteger('stock_issue_product_id')->nullable()->index('stock_chains_stock_issue_product_id_foreign');
                $table->unsignedBigInteger('stock_adjustment_product_id')->nullable()->index('stock_chains_stock_adjustment_product_id_foreign');
                $table->decimal('out_qty', 22)->default(0);
                $table->timestamps();

                $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('cascade');
                $table->foreign(['purchase_product_id'])->references(['id'])->on('purchase_products')->onDelete('CASCADE');
                $table->foreign(['sale_product_id'])->references(['id'])->on('sale_products')->onDelete('CASCADE');
                $table->foreign(['stock_issue_product_id'])->references(['id'])->on('stock_issue_products')->onDelete('CASCADE');
                $table->foreign(['stock_adjustment_product_id'])->references(['id'])->on('stock_adjustment_products')->onDelete('CASCADE');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_chains');
    }
};
