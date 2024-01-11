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
        Schema::create('hrm_allowances', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->tinyInteger('type')->default(1)->comment('1=Allowance;2=Deduction');
            $table->tinyInteger('amount_type')->default(1)->comment('1=fixed;2=percentage');
            $table->decimal('amount', 22, 2)->default(0);
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
        Schema::dropIfExists('hrm_allowances');
    }
};
