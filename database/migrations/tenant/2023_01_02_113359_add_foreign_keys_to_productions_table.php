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
        Schema::table('productions', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['production_account_id'])->references(['id'])->on('accounts')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['stock_branch_id'])->references(['id'])->on('branches')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['stock_warehouse_id'])->references(['id'])->on('warehouses')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['tax_id'])->references(['id'])->on('taxes')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['unit_id'])->references(['id'])->on('units')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['variant_id'])->references(['id'])->on('product_variants')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropForeign('productions_branch_id_foreign');
            $table->dropForeign('productions_production_account_id_foreign');
            $table->dropForeign('productions_product_id_foreign');
            $table->dropForeign('productions_stock_branch_id_foreign');
            $table->dropForeign('productions_stock_warehouse_id_foreign');
            $table->dropForeign('productions_tax_id_foreign');
            $table->dropForeign('productions_unit_id_foreign');
            $table->dropForeign('productions_variant_id_foreign');
            $table->dropForeign('productions_warehouse_id_foreign');
        });
    }
};
