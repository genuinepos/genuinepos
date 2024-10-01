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
        Schema::table('shop_expire_date_histories', function (Blueprint $table) {
            $table->date('main_expire_date')->after('expire_date')->nullable(); // = 04-02-25;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_expire_date_histories', function (Blueprint $table) {
            $table->dropColumn('main_expire_date');
        });
    }
};
