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
        Schema::create('supplier_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('supplier_ledgers_branch_id_foreign');
            $table->unsignedBigInteger('supplier_id')->nullable()->index('supplier_ledgers_supplier_id_foreign');
            $table->unsignedBigInteger('purchase_id')->nullable()->index('supplier_ledgers_purchase_id_foreign');
            $table->unsignedBigInteger('purchase_return_id')->nullable()->index('supplier_ledgers_purchase_return_id_foreign');
            $table->unsignedBigInteger('purchase_payment_id')->nullable()->index('supplier_ledgers_purchase_payment_id_foreign');
            $table->unsignedBigInteger('supplier_payment_id')->nullable()->index('supplier_ledgers_supplier_payment_id_foreign');
            $table->tinyInteger('row_type')->default(1)->comment('1=purchase;2=purchase_payment;3=opening_balance;4=direct_payment');
            $table->decimal('amount', 22)->nullable()->comment('only_for_opening');
            $table->string('date', 30)->nullable();
            $table->timestamp('report_date')->nullable();
            $table->timestamps();
            $table->string('voucher_type', 20)->nullable();
            $table->decimal('debit', 22)->default(0);
            $table->decimal('credit', 22)->default(0);
            $table->decimal('running_balance', 22)->default(0);
            $table->string('amount_type', 20)->nullable()->comment('debit/credit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_ledgers');
    }
};
