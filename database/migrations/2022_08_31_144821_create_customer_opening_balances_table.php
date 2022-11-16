<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOpeningBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_opening_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->decimal('amount', 22, 2)->default(0);
            $table->timestamp('report_date')->nullable();
            $table->unsignedBigInteger('created_by_id');
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('CASCADE');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('CASCADE');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_opening_balances');
    }
}
