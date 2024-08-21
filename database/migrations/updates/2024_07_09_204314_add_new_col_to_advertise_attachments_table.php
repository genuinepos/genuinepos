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
        Schema::table('advertise_attachments', function (Blueprint $table) {

            if (!Schema::hasColumn('advertise_attachments', 'is_delete_in_update')) {

                $table->boolean('is_delete_in_update')->after('video')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertise_attachments', function (Blueprint $table) {
            $table->dropColumn('is_delete_in_update');
        });
    }
};
