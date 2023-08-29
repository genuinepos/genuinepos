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
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('dimension');
            $table->unsignedBigInteger('base_unit_id')->after('code_name')->nullable();
            $table->decimal('base_unit_multiplier', 22, 4)->after('base_unit_id')->nullable();
            $table->unsignedBigInteger('created_by_id')->after('base_unit_multiplier')->nullable();
            $table->timestamp('deleted_at')->after('created_by_id')->nullable();
            $table->foreign('base_unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('dimension')->after('code_name');
            $table->dropForeign(['base_unit_id']);
            $table->dropColumn('base_unit_id');
            $table->dropForeign(['created_by_id']);
            $table->dropColumn('created_by_id');
            $table->dropColumn('base_unit_multiplier');
            $table->dropColumn('deleted_at');
        });
    }
};
