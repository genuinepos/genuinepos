<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_payrolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('hrm_payrolls_user_id_foreign');
            $table->string('reference_no')->nullable();
            $table->decimal('duration_time', 22)->default(0);
            $table->string('duration_unit')->nullable();
            $table->decimal('amount_per_unit', 22)->default(0);
            $table->decimal('total_amount', 22)->default(0);
            $table->decimal('total_allowance_amount', 22)->default(0);
            $table->decimal('total_deduction_amount', 22)->default(0);
            $table->decimal('gross_amount', 22)->default(0);
            $table->decimal('paid', 22)->default(0);
            $table->decimal('due', 22)->default(0);
            $table->timestamp('report_date_ts')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable()->index('hrm_payrolls_admin_id_foreign');
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
        Schema::dropIfExists('hrm_payrolls');
    }
}
