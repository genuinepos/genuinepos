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
        Schema::create('email_servers', function (Blueprint $table) {
            $table->id();
            $table->string('server_name')->nullable();
            $table->string('host')->nullable();
            $table->string('port')->nullable();
            $table->string('user_name')->nullable();
            $table->string('password')->nullable();
            $table->string('encryption')->nullable();
            $table->string('address')->nullable();
            $table->string('name')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('email_servers');
    }
};
