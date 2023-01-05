<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_descriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('expense_id')->nullable()->index('expense_descriptions_expense_id_foreign');
            $table->unsignedBigInteger('expense_category_id')->nullable()->index('expense_descriptions_expense_category_id_foreign');
            $table->decimal('amount', 22)->default(0);
            $table->boolean('is_delete_in_update')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_descriptions');
    }
}
