<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_layouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->tinyInteger('layout_design')->comment('1=normal_printer;2=pos_printer');
            $table->boolean('show_shop_logo')->default(false);
            $table->text('header_text')->nullable();
            $table->boolean('is_header_less')->default(false);
            $table->bigInteger('gap_from_top')->nullable();
            $table->boolean('show_seller_info')->default(false);
            $table->boolean('customer_name')->default(true);
            $table->boolean('customer_tax_no')->default(false);
            $table->boolean('customer_address')->default(false);
            $table->boolean('customer_phone')->default(false);
            $table->string('sub_heading_1')->nullable();
            $table->string('sub_heading_2')->nullable();
            $table->string('sub_heading_3')->nullable();
            $table->string('invoice_heading')->nullable();
            $table->string('quotation_heading')->nullable();
            $table->string('draft_heading')->nullable();
            $table->string('challan_heading')->nullable();
            $table->boolean('branch_landmark')->default(false);
            $table->boolean('branch_city')->default(false);
            $table->boolean('branch_state')->default(false);
            $table->boolean('branch_country')->default(false);
            $table->boolean('branch_zipcode')->default(false);
            $table->boolean('branch_phone')->default(false);
            $table->boolean('branch_alternate_number')->default(false);
            $table->boolean('branch_email')->default(false);
            $table->boolean('product_img')->default(false);
            $table->boolean('product_cate')->default(false);
            $table->boolean('product_brand')->default(false);
            $table->boolean('product_imei')->default(false);
            $table->boolean('product_w_type')->default(false);
            $table->boolean('product_w_duration')->default(false);
            $table->boolean('product_w_discription')->default(false);
            $table->boolean('product_discount')->default(false);
            $table->boolean('product_tax')->default(false);
            $table->boolean('product_price_inc_tax')->default(false);
            $table->boolean('product_price_exc_tax')->default(false);
            $table->text('invoice_notice')->nullable();
            $table->boolean('sale_note')->default(false);
            $table->boolean('show_total_in_word')->default(false);
            $table->text('footer_text')->nullable();
            $table->string('bank_name', 191)->nullable();
            $table->string('bank_branch', 191)->nullable();
            $table->string('account_name', 191)->nullable();
            $table->string('account_no', 191)->nullable();
            $table->boolean('is_default')->default(false);
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
        Schema::dropIfExists('invoice_layouts');
    }
}
