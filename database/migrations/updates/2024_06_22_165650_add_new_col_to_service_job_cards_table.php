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
        Schema::table('service_job_cards', function (Blueprint $table) {

            if (!Schema::hasColumn('service_job_cards', 'completed_at_ts')) {

                $table->timestamp('completed_at_ts')->after('due_date_ts')->nullable();
            }

            if (Schema::hasColumn('service_job_cards', 'job_no')) {

                $table->string('job_no', 255)->change()->nullable()->default(null);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_job_cards', function (Blueprint $table) {
            //
        });
    }
};
