<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('voucher_no');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('supplier_account_id')->nullable();
            $table->unsignedBigInteger('purchase_account_id')->nullable();
            $table->decimal('total_item', 22, 2)->default(0);
            $table->decimal('total_qty', 22, 2)->default(0);
            $table->decimal('net_total_amount', 22, 2)->default(0);
            $table->decimal('return_discount', 22, 2)->default(0);
            $table->decimal('return_discount_type', 22, 2)->default(0);
            $table->decimal('return_discount_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('return_tax_ac_id')->nullable();
            $table->decimal('return_tax_percent', 22, 2)->default(0);
            $table->tinyInteger('return_tax_type')->default(1);
            $table->decimal('return_tax_amount', 22, 2)->default(0);
            $table->decimal('total_return_amount', 22, 2)->default(0);
            $table->decimal('due', 22, 2)->default(0);
            $table->decimal('received_amount', 22, 2)->default(0);
            $table->string('date')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['purchase_id'])->references(['id'])->on('purchases')->onDelete('CASCADE');
            $table->foreign(['supplier_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['purchase_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['return_tax_ac_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_returns');
    }
};
