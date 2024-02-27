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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name',100)->nullable();
            $table->string('mail_mailer',50)->default('smtp');
            $table->string('mail_host',50)->default('email@genuinepos.com');
            $table->integer('mail_port')->default(587);
            $table->string('mail_username',50)->default('email@genuinepos.com');
            $table->string('mail_password',50)->default('Password2020');
            $table->string('mail_encryption',50)->default('tls');
            $table->string('mail_from_address',50)->default('email@genuinepos.com');
            $table->string('mail_from_name',50)->default('SpeedDigit');
            $table->string('mail_active',50)->default(1);  
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
