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
        Schema::table('branches', function (Blueprint $table) {

            if (!Schema::hasColumn('branches', 'shop_expire_date_history_id')) {

                $table->unsignedBigInteger('shop_expire_date_history_id')->after('expire_date')->nullable();
                $table->foreign('shop_expire_date_history_id')->references('id')->on('shop_expire_date_histories')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {

            $table->dropForeign(['shop_expire_date_history_id']);
            $table->dropColumn('shop_expire_date_history_id');
        });
    }
};
