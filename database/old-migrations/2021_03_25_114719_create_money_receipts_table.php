<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_receipts', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 22, 2)->default(0);
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->mediumText('note')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('is_amount')->default(0);
            $table->boolean('is_date')->default(0);
            $table->boolean('is_note')->default(0);
            $table->boolean('is_invoice_id')->default(0);
            $table->boolean('is_header_less')->default(0);
			$table->bigInteger('gap_from_top')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamp('date_ts')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
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
}
