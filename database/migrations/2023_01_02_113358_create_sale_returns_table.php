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
            $table->bigIncrements('id');
            $table->bigInteger('total_item')->default(0);
            $table->decimal('total_qty', 22)->default(0);
            $table->string('invoice_id');
            $table->unsignedBigInteger('sale_id')->nullable()->index('sale_returns_sale_id_foreign');
            $table->unsignedBigInteger('customer_id')->nullable()->index('sale_returns_customer_id_foreign');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('warehouse_id')->nullable()->index('sale_returns_warehouse_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('sale_returns_branch_id_foreign');
            $table->unsignedBigInteger('sale_return_account_id')->nullable()->index('sale_returns_sale_return_account_id_foreign');
            $table->tinyInteger('return_discount_type')->default(1);
            $table->decimal('return_discount', 22)->default(0);
            $table->decimal('return_discount_amount', 22)->default(0);
            $table->decimal('return_tax', 22)->default(0);
            $table->decimal('return_tax_amount', 22)->default(0);
            $table->decimal('net_total_amount', 22)->default(0);
            $table->decimal('total_return_amount', 22)->default(0);
            $table->decimal('total_return_due', 22)->default(0);
            $table->decimal('total_return_due_pay', 22)->default(0);
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('report_date')->nullable();
            $table->text('return_note')->nullable();
            $table->timestamps();
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
