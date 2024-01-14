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
        Schema::create('hrm_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index('hrm_payrolls_branch_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('hrm_payrolls_user_id_foreign');
            $table->unsignedBigInteger('expense_account_id')->nullable()->index('hrm_payrolls_expense_account_id_foreign');
            $table->string('voucher_no')->nullable();
            $table->decimal('duration_time', 22, 2)->default(0);
            $table->string('duration_unit')->nullable();
            $table->decimal('amount_per_unit', 22, 2)->default(0);
            $table->decimal('total_amount', 22, 2)->default(0);
            $table->decimal('total_allowance', 22, 2)->default(0);
            $table->decimal('total_deduction', 22, 2)->default(0);
            $table->decimal('gross_amount', 22, 2)->default(0);
            $table->decimal('paid', 22, 2)->default(0);
            $table->decimal('due', 22, 2)->default(0);
            $table->timestamp('date_ts')->nullable();
            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('hrm_payrolls_created_by_id_foreign');
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('cascade');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('set null');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('cascade');
            $table->foreign(['expense_account_id'])->references(['id'])->on('accounts')->onDelete('cascade');
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
};
