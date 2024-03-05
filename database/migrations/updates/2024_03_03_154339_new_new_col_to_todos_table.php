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
        Schema::table('todos', function (Blueprint $table) {

            if (!Schema::hasColumn('todos', 'created_by_id')) {

                $table->unsignedBigInteger('created_by_id')->after('branch_id')->nullable();
                $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {

            $table->dropForeign(['created_by_id']);
            $table->dropColumn('created_by_id');
        });
    }
};
