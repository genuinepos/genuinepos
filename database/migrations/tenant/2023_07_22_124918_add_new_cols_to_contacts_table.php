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
        Schema::table('contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->after('id')->nullable();
            $table->boolean('is_chain_shop_contact')->after('is_fixed')->default(0);
            $table->decimal('opening_balance', 22, 2)->change()->default(0);
            $table->string('opening_balance_type')->after('opening_balance')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
            $table->dropColumn('is_chain_shop_contact');
            $table->dropColumn('opening_balance_type');
            $table->string('opening_balance', 255)->change();
        });
    }
};
