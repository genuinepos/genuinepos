<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_send', function (Blueprint $table) {
            $table->id();
            $table->string('mail')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 means not send, 1 means send, 2 means draft, 3 means junk, 4 means trash');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_send');
    }
};
