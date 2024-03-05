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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('task');
            $table->string('todo_no');
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index('todos_branch_id_foreign');
            $table->unsignedBigInteger('created_by_id')->after('branch_id')->nullable();
            $table->timestamps();

            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
};
