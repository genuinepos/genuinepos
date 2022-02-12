<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToSupplierLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            Schema::table('supplier_ledgers', function (Blueprint $table) {
                $table->unsignedBigInteger('purchase_return_id')->after('purchase_id')->nullable();
                $table->string('voucher_type', 20)->nullable();
                $table->decimal('debit', 22, 2)->default(0);
                $table->decimal('credit', 22, 2)->default(0);
                $table->decimal('running_balance', 22, 2)->default(0);
                $table->string('amount_type', 20)->nullable()->comment('debit/credit');
                $table->foreign('purchase_return_id')->references('id')->on('purchase_returns')->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
