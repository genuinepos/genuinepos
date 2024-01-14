<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfer_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('sender_branch_id')->nullable();
            $table->unsignedBigInteger('receiver_branch_id')->nullable();
            $table->unsignedBigInteger('sender_warehouse_id')->nullable();
            $table->unsignedBigInteger('receiver_warehouse_id')->nullable();
            $table->decimal('total_item', 22, 2)->default(0);
            $table->decimal('total_qty', 22, 2)->default(0);
            $table->decimal('total_stock_value', 22, 2)->default(0);
            $table->decimal('total_send_qty', 22, 2)->default(0);
            $table->decimal('total_received_qty', 22, 2)->default(0);
            $table->decimal('total_pending_qty', 22, 2)->default(0);
            $table->decimal('received_stock_value', 22, 2)->default(0);
            $table->tinyInteger('receive_status')->default(0);
            $table->text('transfer_note')->nullable();
            $table->text('receiver_note')->nullable();
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->string('receive_date')->nullable();
            $table->unsignedBigInteger('send_by_id')->nullable();
            $table->unsignedBigInteger('received_by_id')->nullable();
            $table->timestamps();

            $table->foreign(['sender_branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['receiver_branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['sender_warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['receiver_warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
            $table->foreign(['send_by_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['received_by_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_stocks');
    }
};
