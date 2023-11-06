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
        Schema::create('sale_returns', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('voucher_no');
            $table->unsignedBigInteger('sale_id')->nullable()->index('sale_returns_sale_id_foreign');
            $table->unsignedBigInteger('customer_account_id')->nullable()->index('sale_returns_customer_account_id_foreign');
            $table->unsignedBigInteger('warehouse_id')->nullable()->index('sale_returns_warehouse_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('sale_returns_branch_id_foreign');
            $table->unsignedBigInteger('sale_account_id')->nullable()->index('sale_returns_sale_account_id_foreign');
            $table->bigInteger('total_item')->default(0);
            $table->decimal('total_qty', 22, 2)->default(0);
            $table->decimal('net_total_amount', 22, 2)->default(0);
            $table->tinyInteger('return_discount_type')->default(1);
            $table->decimal('return_discount', 22, 2)->default(0);
            $table->decimal('return_discount_amount', 22, 2)->default(0);
            $table->unsignedBigInteger('return_tax_ac_id')->nullable();
            $table->decimal('return_tax_percent', 22, 2)->default(0);
            $table->decimal('return_tax_amount', 22, 2)->default(0);
            $table->decimal('total_return_amount', 22, 2)->default(0);
            $table->decimal('paid', 22, 2)->default(0);
            $table->decimal('due', 22, 2)->default(0);
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['customer_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
            $table->foreign(['sale_id'])->references(['id'])->on('sales')->onDelete('CASCADE');
            $table->foreign(['sale_account_id'])->references(['id'])->on('accounts')->onDelete('SET NULL');
            $table->foreign(['warehouse_id'])->references(['id'])->on('warehouses')->onDelete('CASCADE');
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
        Schema::dropIfExists('sale_returns');
    }
};
