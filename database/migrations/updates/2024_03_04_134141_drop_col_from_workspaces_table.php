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
        Schema::table('workspaces', function (Blueprint $table) {

            if (Schema::hasColumn('workspaces', 'admin_id')) {

                $table->dropForeign(['admin_id']);
                $table->dropColumn('admin_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspaces', function (Blueprint $table) {

            $table->unsignedBigInteger('admin_id')->after('end_date')->nullable();
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
