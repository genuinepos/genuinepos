<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCreditLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_credit_limits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->tinyInteger('customer_type')->nullable();
            $table->decimal('credit_limit', 22, 2)->nullable();
            $table->tinyInteger('pay_term')->comment('1=months,2=days')->nullable();
            $table->bigInteger('pay_term_number')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('CASCADE');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('CASCADE');
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
        Schema::dropIfExists('customer_credit_limits');
    }
}
