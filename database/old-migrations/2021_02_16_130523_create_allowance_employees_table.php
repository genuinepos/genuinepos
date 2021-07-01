<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowanceEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowance_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allowance_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_delete_in_update')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('admin_and_users')->onDelete('cascade');
            $table->foreign('allowance_id')->references('id')->on('hrm_allowance')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allowance_employees');
    }
}
