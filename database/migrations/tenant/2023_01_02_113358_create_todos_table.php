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
            $table->bigIncrements('id');
            $table->string('task');
            $table->string('todo_id');
            $table->string('priority')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->index('todos_branch_id_foreign');
            $table->unsignedBigInteger('admin_id')->nullable()->index('todos_admin_id_foreign');
            $table->timestamps();

            $table->foreign(['admin_id'])->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
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
