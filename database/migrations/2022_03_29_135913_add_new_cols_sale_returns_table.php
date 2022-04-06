<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColsSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            $table->bigInteger('total_item')->after('id')->default(0);
            $table->decimal('total_qty', 22, 2)->after('total_item')->default(0);
            $table->decimal('return_tax', 22, 2)->after('return_discount_amount')->default(0);
            $table->decimal('return_tax_amount', 22, 2)->after('return_tax')->default(0);
            $table->text('return_note')->after('report_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_returns', function (Blueprint $table) {
            //
        });
    }
}
