<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_warehouse_id')->after('warehouse_id')->nullable();
            $table->unsignedBigInteger('stock_branch_id')->after('branch_id')->nullable();
            $table->decimal('total_final_quantity', 22, 2)->after('wasted_quantity')->default(0);
            $table->decimal('unit_cost_exc_tax', 22, 2)->after('total_final_quantity')->default(0);
            $table->decimal('unit_cost_inc_tax', 22, 2)->after('unit_cost_exc_tax')->default(0);
            $table->decimal('x_margin', 22, 2)->after('unit_cost_inc_tax')->default(0);
            $table->decimal('price_exc_tax', 22, 2)->after('x_margin')->default(0);
            $table->foreign('stock_warehouse_id')->references('id')->on('warehouses')->onDelete('CASCADE');
            $table->foreign('stock_branch_id')->references('id')->on('branches')->onDelete('CASCADE');
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
            //
        });
    }
}
