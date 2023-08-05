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
        Schema::table('bank_access_branches', function (Blueprint $table) {
            $table->boolean('is_delete_in_update')->after('bank_account_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_access_branches', function (Blueprint $table) {
            $table->dropColumn('is_delete_in_update');
        });
    }
};
