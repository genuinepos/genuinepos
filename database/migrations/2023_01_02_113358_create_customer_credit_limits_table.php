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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->nullable()->index('customer_credit_limits_customer_id_foreign');
            $table->unsignedBigInteger('branch_id')->nullable()->index('customer_credit_limits_branch_id_foreign');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('customer_credit_limits_created_by_id_foreign');
            $table->tinyInteger('customer_type')->nullable();
            $table->decimal('credit_limit', 22)->nullable();
            $table->tinyInteger('pay_term')->nullable()->comment('1=months,2=days');
            $table->bigInteger('pay_term_number')->nullable();
            $table->timestamps();
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
