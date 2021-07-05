<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkspacesTable extends Migration
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
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('ws_id');
            $table->string('name');
            $table->string('priority');
            $table->string('status');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('admin_id')->references('id')->on('admin_and_users')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
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
}
