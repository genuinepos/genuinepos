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
        Schema::create('allowance_employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('allowance_id')->nullable()->index('allowance_employees_allowance_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('allowance_employees_user_id_foreign');
            $table->boolean('is_delete_in_update')->nullable()->default(false);
            $table->timestamps();

            $table->foreign(['allowance_id'])->references(['id'])->on('hrm_allowance')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('CASCADE');
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
};
