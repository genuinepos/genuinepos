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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('expense_account_id')->nullable()->index('stock_adjustments_expense_account_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('stock_adjustments_branch_id_foreign');
            $table->string('voucher_no')->nullable();
            $table->bigInteger('total_item')->default(0);
            $table->decimal('total_qty', 22)->default(0);
            $table->decimal('net_total_amount', 22)->default(0);
            $table->decimal('recovered_amount', 22)->default(0);
            $table->tinyInteger('type')->default(0);
            $table->string('date')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('stock_adjustments_created_by_id_foreign');
            $table->timestamps();

            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['expense_account_id'])->references(['id'])->on('accounts')->onDelete('CASCADE');
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
};
