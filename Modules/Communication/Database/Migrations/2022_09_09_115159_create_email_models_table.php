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
        Schema::dropIfExists('email_models');

        if (! Schema::hasTable('emails')) {
            Schema::create('emails', function (Blueprint $table) {
                $table->id();
                $table->string('mail')->nullable();
                $table->string('subject')->nullable();
                $table->text('message')->nullable();
                $table->string('attachment')->nullable();
                $table->boolean('status')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
};
