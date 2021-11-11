<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsFormProductWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_warehouses', function (Blueprint $table) {
            $table->decimal('total_purchased', 22, 2)->after('product_quantity')->default(0);
            $table->decimal('total_adjusted', 22, 2)->after('total_purchased')->default(0);
            $table->decimal('total_transferred', 22, 2)->after('total_adjusted')->default(0);
            $table->decimal('total_received', 22, 2)->after('total_transferred')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_warehouses', function (Blueprint $table) {
            //
        });
    }
}
