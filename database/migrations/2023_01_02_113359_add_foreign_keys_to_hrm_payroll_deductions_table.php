<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHrmPayrollDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrm_payroll_deductions', function (Blueprint $table) {
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
        Schema::table('hrm_payroll_deductions', function (Blueprint $table) {
            $table->dropForeign('hrm_payroll_deductions_payroll_id_foreign');
        });
    }
}
