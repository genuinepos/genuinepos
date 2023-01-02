<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('branches')->default(false);
            $table->boolean('hrm')->default(false);
            $table->boolean('todo')->default(false);
            $table->boolean('service')->default(false);
            $table->boolean('manufacturing')->default(false);
            $table->boolean('e_commerce')->default(false);
            $table->bigInteger('branch_limit')->default(0);
            $table->bigInteger('cash_counter_limit')->default(0);
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
        Schema::dropIfExists('addons');
    }
}
