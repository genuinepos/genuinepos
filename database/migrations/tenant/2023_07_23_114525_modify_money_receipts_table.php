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
        Schema::table('money_receipts', function (Blueprint $table) {

            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            $table->dropColumn('month');
            $table->timestamp('date')->change()->nullable();
            $table->renameColumn('date', 'date_ts');
            $table->renameColumn('invoice_id', 'voucher_no');
            $table->unsignedBigInteger('contact_id')->after('id');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('money_receipts', function (Blueprint $table) {

            $table->unsignedBigInteger('customer_id')->after('id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('date_ts')->change()->nullable();
            $table->renameColumn('date_ts', 'date');
            $table->renameColumn('voucher_no', 'invoice_id');
            $table->string('month')->after('date');
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
        });
    }
};
