<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProductBranchVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_branch_variants', function (Blueprint $table) {
            $table->decimal('total_sale_return', 22, 2)->after('total_opening_stock')->default(0);
            $table->decimal('total_purchase_return', 22, 2)->after('total_sale_return')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_branch_variants', function (Blueprint $table) {
            //
        });
    }
}
