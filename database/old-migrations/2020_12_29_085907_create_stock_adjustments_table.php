<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('branch_id');
            $table->string('invoice_id')->nullable();
            $table->bigInteger('total_item')->default(0);
            $table->decimal('total_qty', 22, 2)->default(0.00);
            $table->decimal('net_total_amount', 22, 2)->default(0.00);
            $table->decimal('recovered_amount', 22, 2)->default(0.00);
            $table->tinyInteger('type',)->default(0);
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('report_date_ts')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admin_and_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
    }
}
