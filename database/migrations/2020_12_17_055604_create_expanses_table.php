<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpansesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expanses', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('expanse_category_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('attachment')->nullable();
            $table->mediumText('note')->nullable();
            $table->decimal('tax_percent', 22, 2)->default(0.00);
            $table->decimal('tax_amount', 22, 2)->default(0.00);
            $table->decimal('total_amount', 22, 2)->default(0.00);
            $table->decimal('paid', 22, 2)->default(0.00);
            $table->decimal('due', 22, 2)->default(0.00);
            $table->string('date');
            $table->string('month');
            $table->string('year');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamp('report_date');
            $table->timestamps();
            $table->foreign('expanse_category_id')->references('id')->on('expanse_categories')->onDelete('cascade');
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
        Schema::dropIfExists('expanses');
    }
}
