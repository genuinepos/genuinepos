@extends('layout.pos_master')
@section('pos_content')
<!-- Pos Header -->
@include('sales.pos.partials.body_partials.add_partials.pos_header')
<!-- Pos Header End-->
<div class="body-wraper">
    <div class="container-fluid p-0 h-100">
        <div class="pos-content p-1">
            <div class="row g-1">
                <div class="col-lg-9">
                    <div class="row g-1">
                        <!-- Select Category, Brand and Product Area -->
                        @include('sales.pos.partials.body_partials.add_partials.select_product_section')
                        <!-- Select Category, Brand and Product Area -->
                        <div class="col-lg-7">
                            <div class="cart-table">
                                <div class="cart-table-inner-pos">
                                    <div class="tbl-head">
                                        <ul class="tbl-head-shortcut-menus" id="pos-shortcut-menus">
                                            <li>
                                                <a href="{{ route('pos.short.menus.modal.form') }}"
                                                    id="addPosShortcutBtn" class="head-tbl-icon border-none" tabindex="-1">
                                                    <span class="fas fa-plus"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Sale Product Table -->
                                    @include('sales.pos.partials.body_partials.add_partials.sale_product_table')
                                    <!-- Sale Product Table End -->

                                    <!-- Total Item & Qty section -->
                                    @include('sales.pos.partials.body_partials.add_partials.total_item_and_qty')
                                    <!-- Total Item & Qty section End-->

                                    <div class="row g-0 d-lg-flex d-hide" style="height: 90px">
                                        <div class="col-lg-4">
                                            <div class="bg-white h-100 border-end"></div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="bg-white h-100 border-end"></div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="bg-white h-100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pos Footer -->
                    @include('sales.pos.partials.body_partials.add_partials.pos_footer')
                    <!-- Pos Footer End -->
                </div>

                <!-- Pos Total Sum And Buttons section -->
                @include('sales.pos.partials.body_partials.add_partials.total_sum_and_butten')
                <!-- Pos Total Sum And Buttons section End -->
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
    @include('sales.pos.partials.js_partials.common_js.add_customer_and_product_js')
    @include('sales.pos.partials.js_partials.common_js.selectable_product_list_js')
    @include('sales.pos.partials.js_partials.common_js.reedem_point_js')
    @include('sales.pos.partials.js_partials.common_js.recent_transactions_js')
    @include('sales.pos.partials.js_partials.common_js.active_selected_products_js')
    @include('sales.pos.partials.js_partials.common_js.show_stock_js')
    @include('sales.pos.partials.js_partials.common_js.add_shortcut_menu_js')
    @include('sales.pos.partials.js_partials.common_js.other_payment_method_js')
    @include('sales.pos.partials.js_partials.common_js.pick_hold_invoice_js')
    @include('sales.pos.partials.js_partials.common_js.delete_hold_invoice_and_suspended_sale_js')
    @include('sales.pos.partials.js_partials.add_js_partials.product_search_and_add_js')
    @include('sales.pos.partials.js_partials.add_js_partials.amount_calculation_js')
    @include('sales.pos.partials.js_partials.add_js_partials.edit_and_delete_table_product')
    @include('sales.pos.partials.js_partials.add_js_partials.cancel_sale_js')
    @include('sales.pos.partials.js_partials.add_js_partials.shortcust_key_js')
    @include('sales.pos.partials.js_partials.add_js_partials.submit_and_other_js')
@endpush
