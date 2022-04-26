<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColsToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('gross_pay');
            $table->dropColumn('previous_due');
            $table->decimal('all_total_payable', 22, 2)->after('attachment')->default(0);
            $table->decimal('gross_pay', 22, 2)->after('all_total_payable')->default(0);
            $table->decimal('previous_due', 22, 2)->after('gross_pay')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            //
        });
    }
}
