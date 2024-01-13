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
        Schema::create('hrm_payroll_allowances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id')->nullable()->index('hrm_payroll_allowances_payroll_id_foreign');
            $table->unsignedBigInteger('allowance_id')->nullable()->index('hrm_payroll_allowances_allowance_id_foreign');
            $table->string('allowance_name', 255)->nullable();
            $table->string('amount_type')->default('1');
            $table->decimal('allowance_percent', 22, 2)->default(0);
            $table->decimal('allowance_amount', 22, 2)->default(0);
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();

            $table->foreign(['payroll_id'])->references(['id'])->on('hrm_payrolls')->onDelete('CASCADE');
            $table->foreign(['allowance_id'])->references(['id'])->on('hrm_allowances')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_payroll_allowances');
    }
};
