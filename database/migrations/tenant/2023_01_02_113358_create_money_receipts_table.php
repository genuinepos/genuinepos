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
        Schema::create('money_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_id');
            $table->string('voucher_no', 191)->nullable();
            $table->decimal('amount', 22)->nullable();
            $table->boolean('is_customer_name')->default(false);
            $table->unsignedBigInteger('branch_id')->nullable()->index('money_receipts_branch_id_foreign');
            $table->mediumText('note')->nullable();
            $table->string('receiver')->nullable();
            $table->string('ac_details')->nullable();
            $table->boolean('is_date')->default(false);
            $table->boolean('is_header_less')->default(false);
            $table->bigInteger('gap_from_top')->nullable();
            $table->string('date_ts')->nullable();
            $table->timestamps()->nullable();

            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('money_receipts');
    }
};
