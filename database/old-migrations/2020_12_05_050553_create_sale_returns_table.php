<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->tinyInteger('return_discount_type')->default(1);
            $table->decimal('return_discount', 22, 2)->default(0.00);
            $table->decimal('return_discount_amount', 22, 2)->default(0.00);
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->decimal('total_return_amount', 22, 2)->default(0.00);
            $table->decimal('total_return_due', 22, 2)->default(0.00);
            $table->decimal('total_return_due_pay', 22, 2)->default(0.00);
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_returns');
    }
}
