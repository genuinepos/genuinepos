<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferStockBranchToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stock_branch_to_branches', function (Blueprint $table) {
            $table->id();
            $table->string('ref_id', 191)->nullable();
            $table->unsignedBigInteger('sender_branch_id')->nullable();
            $table->unsignedBigInteger('sender_warehouse_id')->nullable();
            $table->unsignedBigInteger('receiver_branch_id')->nullable();
            $table->unsignedBigInteger('receiver_warehouse_id')->nullable();
            $table->decimal('total_item', 22, 2)->default(0);
            $table->decimal('total_stock_value', 22, 2)->default(0);
            $table->unsignedBigInteger('expense_account_id')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->string('payment_note', 255)->nullable();
            $table->decimal('transfer_cost', 22, 2)->default(0);
            $table->decimal('total_send_qty', 22, 2)->default(0);
            $table->decimal('total_received_qty', 22, 2)->default(0);
            $table->decimal('total_pending_qty', 22, 2)->default(0);
            $table->tinyInteger('receive_status')->default(1)->comment('1=pending,2=partial,3=completed');
            $table->string('date')->nullable();
            $table->mediumText('transfer_note')->nullable();
            $table->mediumText('receiver_note')->nullable();
            $table->timestamp('report_date')->nullable();
            
            $table->timestamps();
            $table->foreign('sender_branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('sender_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('receiver_branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('receiver_warehouse_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('expense_account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->foreign('bank_account_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_stock_branch_to_branches');
    }
}
