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
        Schema::create('hrm_payroll_deductions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payroll_id')->nullable()->index('hrm_payroll_deductions_payroll_id_foreign');
            $table->string('deduction_name')->nullable();
            $table->tinyInteger('amount_type')->default(1);
            $table->decimal('deduction_percent')->default(0);
            $table->decimal('deduction_amount', 22)->default(0);
            $table->timestamp('report_date_ts')->nullable();
            $table->boolean('is_delete_in_update')->default(false);
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->timestamps();

            $table->foreign(['payroll_id'])->references(['id'])->on('hrm_payrolls')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
};
