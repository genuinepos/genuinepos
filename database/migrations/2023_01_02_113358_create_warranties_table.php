<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarrantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('duration');
            $table->string('duration_type', 191)->nullable();
            $table->mediumText('description')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1=warranty;2=guaranty ');
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
        Schema::dropIfExists('warranties');
    }
}
