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
        Schema::create('hrm_allowance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->string('type');
            $table->unsignedBigInteger('employee_id')->nullable()->index('hrm_allowance_employee_id_foreign');
            $table->tinyInteger('amount_type')->default(1)->comment('1=fixed;2=percentage');
            $table->decimal('amount', 22)->default(0);
            $table->string('applicable_date')->nullable();
            $table->timestamps();

            $table->foreign(['employee_id'])->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_allowance');
    }
};
