<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColsSaleReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_return_products', function (Blueprint $table) {
            
            $table->decimal('sold_quantity', 22, 2)->after('product_variant_id')->default(0);
            $table->decimal('unit_cost_inc_tax', 22, 2)->after('sold_quantity')->default(0);
            $table->decimal('unit_price_exc_tax', 22, 2)->after('unit_cost_inc_tax')->default(0);
            $table->decimal('unit_price_inc_tax', 22, 2)->after('unit_price_exc_tax')->default(0);
            $table->tinyInteger('unit_discount_type')->after('unit_price_inc_tax')->default(0);
            $table->decimal('unit_discount', 22, 2)->after('unit_discount_type')->default(0);
            $table->decimal('unit_discount_amount', 22, 2)->after('unit_discount')->default(0);
            $table->decimal('tax_type', 22, 2)->after('unit_discount_amount')->default(0);
            $table->decimal('unit_tax_percent', 22, 2)->after('tax_type')->default(0);
            $table->decimal('unit_tax_amount', 22, 2)->after('unit_tax_percent')->default(0);
            $table->boolean('is_delete_in_update')->after('return_subtotal')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_return_products', function (Blueprint $table) {
            //
        });
    }
}
