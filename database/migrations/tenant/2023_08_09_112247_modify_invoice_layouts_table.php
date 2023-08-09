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
        Schema::table('invoice_layouts', function (Blueprint $table) {

            $table->dropColumn('show_seller_info');
            $table->dropColumn('draft_heading');
            $table->dropColumn('branch_landmark');
            $table->dropColumn('product_img');
            $table->dropColumn('product_cate');
            $table->dropColumn('product_brand');
            $table->unsignedBigInteger('branch_id')->after('id')->nullable();
            $table->string('sales_order_heading')->nullable();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_layouts', function (Blueprint $table) {

            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
            $table->dropColumn('sales_order_heading');
            $table->tinyInteger('show_seller_info')->after('gap_from_top')->default(0);
            $table->string('draft_heading')->after('quotation_heading')->nullable();
            $table->tinyInteger('branch_landmark')->after('branch_landmark')->default(0);
            $table->tinyInteger('product_img')->after('branch_email')->default(0);
            $table->tinyInteger('product_cate')->after('branch_email')->default(0);
            $table->tinyInteger('product_brand')->after('branch_email')->default(0);
        });
    }
};
