<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->nullable()->index('loan_companies_branch_id_foreign');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->mediumText('address')->nullable();
            $table->decimal('pay_loan_amount', 22)->default(0);
            $table->decimal('pay_loan_due', 22)->default(0);
            $table->decimal('get_loan_amount', 22)->default(0);
            $table->decimal('get_loan_due', 22)->default(0);
            $table->decimal('total_pay', 22)->default(0);
            $table->decimal('total_receive', 22)->default(0);
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
        Schema::dropIfExists('loan_companies');
    }
}
