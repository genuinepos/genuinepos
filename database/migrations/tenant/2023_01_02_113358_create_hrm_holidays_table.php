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
        Schema::create('hrm_holidays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('holiday_name');
            $table->string('start_date');
            $table->string('end_date');
            $table->unsignedBigInteger('branch_id')->nullable()->index('hrm_holidays_branch_id_foreign');
            $table->boolean('is_all')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_holidays');
    }
};
