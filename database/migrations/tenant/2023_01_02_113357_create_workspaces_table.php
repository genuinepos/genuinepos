<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index('workspaces_branch_id_foreign');
            $table->string('workspace_no', 255);
            $table->string('name', 255);
            $table->string('priority', 20);
            $table->string('status', 20);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('workspaces_created_by_id_foreign');
            $table->text('description')->nullable();
            $table->string('estimated_hours', 191)->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workspaces');
    }
};
