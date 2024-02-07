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
        Schema::table('users', function (Blueprint $table) {

            $table->unsignedBigInteger('currency_id')->after('id_proof_number')->nullable();
            $table->string('city')->after('currency_id')->nullable();
            $table->string('postal_code')->after('city')->nullable();

            $table->foreign(['currency_id'])->references(['id'])->on('currencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
            $table->dropColumn('city');
            $table->dropColumn('postal_code');
        });
    }
};
