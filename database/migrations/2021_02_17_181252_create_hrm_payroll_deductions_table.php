<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmPayrollDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_payroll_deductions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id')->nullable();
            $table->string('deduction_name')->nullable();
            $table->tinyInteger('amount_type')->default(1);
            $table->decimal('deduction_percent')->default(0);
            $table->decimal('deduction_amount', 22,2)->default(0.00);
            $table->timestamp('report_date_ts')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->boolean('is_delete_in_update')->default(0.00);
            $table->timestamps();
            $table->foreign('payroll_id')->references('id')->on('hrm_payrolls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_payroll_deductions');
    }
}
