@extends('layout.master')
@push('stylesheets')
<style>
    b {font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    p.checkbox_input_wrap {font-weight: 600;font-size: 10px;font-family: Arial, Helvetica, sans-serif;}
    .row1 {}
    .my_border {border: 1px solid rgb(99, 97, 97) !important;}
    p.checkbox_input_wrap {display: flex;gap: 5px;line-height: 1.8;position: relative;}
    .customers:checked {background-color: #3770eb;}
    .text-info {display: flex;gap: 5px;align-items: center;}
</style>
@endpush
@section('content')
<div class="body-woaper">
    {{-- <div class="sec-name">
        <h6>@lang('menu.add_role')</h6>
        <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
    </div> --}}
    <div class="container-fluid p-0">
        <form id="add_role_form" action="{{ route('users.role.store') }}" method="POST">
            @csrf
            <section class="p-15" id="accordion">
                <div class="container-fluid p-0">
                    <div class="row1">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="input-group align-items-center gap-2">
                                                <label for="inputEmail3"> <b>@lang('menu.role_name') </b> <span class="text-danger">*</span></label>
                                                <div class="w-input">
                                                    <input required type="text" name="role_name" required class="form-control add_input" id="role_name" placeholder="@lang('menu.role_name')">
                                                    <span class="error error_role_name">{{ $errors->first('role_name') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group align-items-center gap-2">
                                                <label for="inputEmail3"> <b> @lang('menu.select_all') </b> </label>
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox" class="select_all super_select_all" data-target="super_select_all" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="accordion-header">
                                <input id="customers" type="checkbox" class=" sale_checkbox select_all super_select_all sales_app_permission" data-target="sales_app_permission" autocomplete="off">
                                <a data-bs-toggle="collapse" class="sale_role" href="#collapseOne" href="">
                                    @lang('menu.sales_app_permissions')
                                </a>
                            </div>
                            <div id="collapseOne" class="collapse show" data-bs-parent="#accordion">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input id="customers" type="checkbox" class="select_all super_select_all sales_app_permission super_select_all" data-target="customers" autocomplete="off">
                                                <strong> @lang('menu.customer')</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_all" class="customers sales_app_permission super_select_all">
                                                @lang('menu.view_all_customer')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_add" class="customers sales_app_permission super_select_all"> @lang('menu.add_customer')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_import" class="customers sales_app_permission super_select_all">
                                                @lang('menu.import_customer')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_edit" class=" customers sales_app_permission super_select_all">
                                                @lang('menu.edit_customer')
                                            </p>


                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_delete" class="customers sales_app_permission super_select_all">
                                                @lang('menu.delete_customer')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_group" class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer_group')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_report" class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_manage" class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer_manage')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_payment_receive_voucher" class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer') @lang('menu.payment_receive_voucher')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_status_change" class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer_status_change')
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input id="pos" type="checkbox" class="select_all super_select_all sales_app_permission super_select_all" data-target="pos" autocomplete="off"><strong> @lang('menu.pos_sales')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_all" class="pos sales_app_permission super_select_all">@lang('menu.manage_pos_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_add" class="pos sales_app_permission super_select_all"> @lang('menu.add_pos_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_edit" class="pos sales_app_permission super_select_all">@lang('menu.edit_pos_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_delete" class="pos sales_app_permission super_select_all">@lang('menu.delete_pos_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_sale_settings" class="pos sales_app_permission super_select_all">@lang('menu.pos_sale_settings')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_price_pos_screen" class="pos sales_app_permission super_select_all">@lang('menu.edit_item_price_from_pos_screen')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_discount_pos_screen" class="pos sales_app_permission super_select_all">@lang('menu.edit_item_discount_from_pos_screen')
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox" class="select_all sales_app_permission super_select_all" data-target="sales_report" autocomplete="off"> <strong> @lang('menu.sales_report')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sales_report" class="sales_report sales_app_permission super_select_all"> @lang('menu.sale_statement')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="product_sale_report" class="sales_report super_select_all sales_app_permission"> @lang('menu.sold_item_statements')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sales_order_report" class="sales_report super_select_all sales_app_permission">@lang('menu.sales_order_statements')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="ordered_item_report" class="sales_report super_select_all sales_app_permission">@lang('menu.order_item_statement')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_vs_sales_report" class="sales_report super_select_all sales_app_permission">@lang('menu.do_vs_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="product_sale_report" class="sales_report super_select_all sales_app_permission super_select_all">@lang('menu.sale_item_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="received_against_sales_report" class="sales_report super_select_all sales_app_permission super_select_all"> @lang('menu.receive_payment') @lang('menu.report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="cash_register_report" class="sales_report super_select_all sales_app_permission super_select_all"> @lang('menu.cash_register_reports')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_representative_report" class="sales_report super_select_all sales_app_permission super_select_all">@lang('menu.sales_representative_report')
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox" class="select_all super_select_all sales_app_permission super_select_all" data-target="sales_return" autocomplete="off">
                                                <strong>@lang('menu.sale_return')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_sales_return" class="sales_return sales_app_permission super_select_all">@lang('menu.view_all') @lang('menu.sale_return')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="add_sales_return" class="sales_return sales_app_permission super_select_all">@lang('menu.add_sale_return')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_sales_return" class="sales_return sales_app_permission super_select_all">{{ __('Edit sales return') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_sales_return" class=" sales_return sales_app_permission super_select_all">{{ __('Delete sales return') }}
                                            </p>

                                            <div class="mt-3">
                                                <p class="text-info">
                                                    <input type="checkbox" class="select_all super_select_all sales_app_permission" data-target="recent_prices" autocomplete="off">
                                                    <strong>@lang('menu.recent_price')</strong>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="add_new_recent_price" class="recent_prices super_select_all sales_app_permission">@lang('menu.add_new_price')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="all_previous_recent_price" class="recent_prices super_select_all sales_app_permission"> @lang('menu.all_pre_price')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="today_recent_price" class="recent_prices super_select_all sales_app_permission">@lang('menu.today_price')
                                                </p>
                                            </div>
                                        </div>

                                        <hr class="my-2">

                                        <div class="col-lg-3 col-sm-6">

                                            <p class="text-info">
                                                <input type="checkbox" class="select_all super_select_all sales_app_permission super_select_all" data-target="sale" autocomplete="off">
                                                <strong>@lang('menu.sales')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="create_add_sale" class="sale sales_app_permission super_select_all">
                                                @lang('menu.create_add_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_sales" class="sale sales_app_permission super_select_all">@lang('menu.view_sales')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_sale" class="sale sales_app_permission super_select_all">  @lang('menu.edit_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_sale" class="sale sales_app_permission super_select_all">
                                                @lang('menu.delete_sale')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_settings" class="sale sales_app_permission super_select_all">@lang('menu.sale_return')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_payment_index" class="sale sales_app_permission super_select_all">
                                                @lang('menu.view_all_receive_payments')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_payment_create" class="sale sales_app_permission super_select_all"> @lang('menu.create_receive_payment')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_payment_view" class="sale sales_app_permission super_select_all">@lang('menu.single_receive_payment_view')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_payment_update" class="sale sales_app_permission super_select_all">@lang('menu.update_receive_payment')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_payment_delete" class="sale sales_app_permission super_select_all">
                                                @lang('menu.delete') @lang('menu.receive_payment')
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="add_quotation" class="sale sales_app_permission super_select_all">
                                                @lang('menu.create_quotation')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_quotation_list" class="sale sales_app_permission super_select_all">
                                                @lang('menu.manage_quotation')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_quotation_edit" class="sale sales_app_permission super_select_all">
                                                {{ __('Edit quotation') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_quotation_delete" class="sale sales_app_permission super_select_all">
                                                {{ __('Delete quotation') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_add" class="sale sales_app_permission super_select_all">
                                                @lang('menu.create_sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_all" class="sale sales_app_permission super_select_all">
                                                @lang('menu.manage') @lang('menu.sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_edit" class="sale sales_app_permission super_select_all"> @lang('menu.edit') @lang('menu.sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_do_approval" class="sale sales_app_permission super_select_all"> @lang('menu.do_approval')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_delete" class="sale sales_app_permission super_select_all"> @lang('menu.delete') @lang('menu.sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_add" class="sale sales_app_permission super_select_all"> @lang('menu.create')
                                                @lang('menu.delivery_order')
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_all" class="sale sales_app_permission super_select_all"> {{ __('Manage delivery order') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_edit" class="sale sales_app_permission super_select_all">@lang('menu.edit_delivery_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_delete" class="sale sales_app_permission super_select_all">{{ __('Delete delivery order') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="change_expire_date" class="sale sales_app_permission super_select_all">{{ __('Change expire date') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_to_final" class="sale sales_app_permission super_select_all">{{ __('Do to final') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="quotation_notification" class="sale sales_app_permission super_select_all">
                                                {{ __("Get notification after creating the quotation") }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sales_order_notification" class="sale sales_app_permission super_select_all">
                                                {{ __('Get notification after creating the sales order') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_notification" class="sale sales_app_permission super_select_all">{{ __('Get notification after creating the do') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="price_update_notification" class="sale sales_app_permission super_select_all"> {{ __('Get notification after creating the do') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">

                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_approval_notification" class="sale sales_app_permission super_select_all">{{ __('Get notification after do approval') }}
                                            </p>


                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_price_sale_screen" class="sale sales_app_permission super_select_all">{{ __('Edit product price from sales screen') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_discount_sale_screen" class="sale sales_app_permission super_select_all"> {{ __('Edit product discount in sale scr') }}.
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="shipment_access" class="sale sales_app_permission super_select_all">{{ __('Access shipments') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_product_cost_is_sale_screed" class="sale sales_app_permission super_select_all">{{ __('View Item Cost In sale screen') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" checked name="view_own_sale" class="sale sales_app_permission super_select_all">{{ __('View only own data') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="discounts" class="sale sales_app_permission super_select_all">@lang('menu.manage_offers')
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="procur_check select_all super_select_all procurement_permission super_select_all" data-target="procurement_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="procur_role" href="#collapseTwo" href="">
                            {{ __('Procurement Permissions') }}
                        </a>
                    </div>

                    <div id="collapseTwo" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">

                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all procurement_permission" data-target="purchase" autocomplete="off"><strong> @lang('menu.purchases')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_all" class="purchase procurement_permission super_select_all">
                                            @lang('menu.manage_purchases')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_add" class="purchase procurement_permission super_select_all">@lang('menu.add_purchase')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_edit" class="purchase procurement_permission super_select_all"> @lang('menu.edit_purchase')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_delete" class="purchase procurement_permission super_select_all">{{ __('Delete purchase') }}
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_settings" class="purchase procurement_permission super_select_all"> @lang('menu.purchase_settings')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all procurement_permission" data-target="requisition" autocomplete="off"><strong> @lang('menu.requisition')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="create_requisition" class="requisition procurement_permission super_select_all">@lang('menu.create_requisition')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="all_requisition" class="requisition procurement_permission super_select_all">{{ __('Manage requisition') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_requisition" class="requisition procurement_permission super_select_all">{{ __('Edit requisition') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="approve_requisition" class="requisition procurement_permission super_select_all">@lang('menu.Approve requisition')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="delete_requisition" class="requisition procurement_permission super_select_all">{{ __('Delete requisition') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="requisition_notification" class="requisition procurement_permission super_select_all">{{ __('Get notification after creating the requisition') }}
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all procurement_permission" data-target="purchase_order" autocomplete="off"><strong> @lang('menu.purchase_order')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="create_po" class="purchase_order procurement_permission super_select_all"> @lang('menu.create_purchase_order')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="all_po" class="purchase_order procurement_permission super_select_all"> @lang('menu.manage') @lang('menu.purchase_order')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_po" class="purchase_order procurement_permission super_select_all"> @lang('menu.edit') @lang('menu.purchase_order')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="delete_po" class="purchase_order procurement_permission super_select_all"> @lang('menu.delete') @lang('menu.purchase_order')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="po_notification" class="purchase_order procurement_permission super_select_all">{{ __('Get notification after creating purchase order') }}
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all procurement_permission" data-target="purchase_payment" autocomplete="off"><strong>{{ __('Purchase payments') }}</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_payment_index" class="purchase_payment procurement_permission super_select_all">{{ __('View all purchase payments') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_payment_create" class="purchase_payment procurement_permission super_select_all">{{ __('Create purchase payment') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_payment_view" class="purchase_payment procurement_permission super_select_all">{{ __('Single purchase payment view') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_payment_update" class="purchase_payment procurement_permission super_select_all">{{ __('Update purchase payment') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_payment_delete" class="purchase_payment procurement_permission super_select_all">{{ __('Delete purchase payment') }}
                                        </p>
                                    </div>
                                </div>

                                <hr class="my-2">

                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all procurement_permission super_select_all" data-target="suppliers" autocomplete="off"> <strong> @lang('menu.supplier')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_all" class="suppliers procurement_permission super_select_all"> @lang('menu.view_all_supplier')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_add" class="suppliers procurement_permission super_select_all">@lang('menu.add_supplier')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_import" class="suppliers procurement_permission super_select_all">@lang('menu.import_suppliers')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_edit" class="suppliers procurement_permission super_select_all"> @lang('menu.edit_supplier')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_delete" class="suppliers procurement_permission super_select_all">{{ __('Delete supplier') }}
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all procurement_permission super_select_all" data-target="purchase_by_scale" autocomplete="off"> <strong> @lang('menu.purchase_by_sale')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_by_scale_index" class="purchase_by_scale procurement_permission super_select_all"> {{ __('View all Purchase By Scale') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_by_scale_view" class="purchase_by_scale procurement_permission super_select_all"> {{ __('Single View Purchase By Scale') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_by_scale_create" class="purchase_by_scale procurement_permission super_select_all">{{ __('Add Purchase By Scale') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_by_scale_delete" class="purchase_by_scale procurement_permission super_select_all"> Delete Purchase By Scale
                                        </p>
                                    </div>

                                    <div class="col-md-3">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all procurement_permission super_select_all" data-target="purchase_return" autocomplete="off"> <strong> Purchase returns</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="view_purchase_return" class="purchase_return procurement_permission super_select_all"> View all purchase return
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="add_purchase_return" class="purchase_return procurement_permission super_select_all"> @lang('menu.add_purchase_return')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_purchase_return" class="purchase_return procurement_permission super_select_all"> {{ __('Edit Purchase Return') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="delete_purchase_return" class="purchase_return procurement_permission super_select_all"> Delete purchase return
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox" class="select_all super_select_all procurement_permission" data-target="stock_issue" autocomplete="off">
                                            <strong>Stock issue</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_issue" class="stock_issue procurement_permission super_select_all"> Stock issue
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_issue_index" class="stock_issue procurement_permission super_select_all"> Stock issue list
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_issue_create" class="stock_issue procurement_permission super_select_all"> Stock issue create
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_issue_view" class="stock_issue procurement_permission super_select_all"> Stock issue detail
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_issue_update" class="stock_issue procurement_permission super_select_all"> Stock issue update
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_issue_delete" class="stock_issue procurement_permission super_select_all"> Stock issue delete
                                        </p>
                                    </div>
                                </div>

                                <hr class="my-2">

                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all procurement_permission super_select_all" data-target="procurement_report" autocomplete="off"><strong> Procurement Report</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="requested_product_report" class="procurement_report procurement_permission super_select_all"> @lang('menu.requested_item_report')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="weighted_product_report" class="procurement_report procurement_permission super_select_all"> Weighted Item Report
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_statements" class="procurement_report procurement_permission super_select_all"> @lang('menu.purchase_statements')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_sale_report" class="procurement_report procurement_permission super_select_all"> {{ __('Purchase & Sale Report') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_purchase_report" class="procurement_report procurement_permission super_select_all"> Item purchased report
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_payment_report" class="procurement_report procurement_permission super_select_all"> @lang('menu.purchase_payment_report')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_issue_statement" class="procurement_report procurement_permission super_select_all"> Stock Issue Statement
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_issue_item_report" class="procurement_report procurement_permission super_select_all"> Stock Issue Item Report
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_report" class="procurement_report procurement_permission super_select_all"> @lang('menu.supplier_report')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="inven_check select_all inventory_permission super_select_all" data-target="inventory_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="inven_role" href="#collapseThree" href="">Inventory Permissions</a>
                    </div>

                    <div id="collapseThree" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all inventory_permission" data-target="product" autocomplete="off"> <strong>@lang('menu.items')</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="product_all" class="product inventory_permission super_select_all">
                                        @lang('menu.view_all_item')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="product_add" class="product inventory_permission super_select_all">@lang('menu.add_item')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="product_edit" class="product inventory_permission super_select_all"> Edit item
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="openingStock_add" class="product inventory_permission super_select_all"> Add/edit  @lang('menu.opening_stock')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="product_delete" class="product inventory_permission super_select_all"> Delete item
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="product_settings" class="product inventory_permission super_select_all"> @lang('menu.item_settings')
                                    </p>
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="hidden">
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="categories" class="product inventory_permission super_select_all"> Categories
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="brand" class="product inventory_permission super_select_all"> Brands
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="units" class="product inventory_permission super_select_all"> Unit
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="variant" class="product inventory_permission super_select_all"> Variants
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="warranties" class="product inventory_permission super_select_all"> Warranties
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="selling_price_group_index" class="product inventory_permission super_select_all">@lang('menu.selling_price_group_index')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="generate_barcode" class="product inventory_permission super_select_all"> @lang('menu.generate_barcode')
                                    </p>
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all inventory_permission super_select_all" data-target="stock_adjustment" autocomplete="off"> <strong> @lang('menu.stock_adjustment')</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="adjustment_all" class="stock_adjustment inventory_permission super_select_all"> {{ __('View All Adjustment') }}
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="adjustment_add_from_location" class="stock_adjustment inventory_permission super_select_all"> Add adjustment from b. location
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="adjustment_add_from_warehouse" class="stock_adjustment inventory_permission super_select_all"> {{ __('Add Adjustment From Warehouse') }}
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="adjustment_delete" class="stock_adjustment inventory_permission super_select_all"> {{ __('Delete Adjustment') }}
                                    </p>
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all inventory_permission " data-target="daily_stock" autocomplete="off">
                                        <strong>Daily stock</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="daily_stock" class="daily_stock inventory_permission super_select_all">
                                        Daily stock
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="daily_stock_index" class="daily_stock inventory_permission super_select_all">
                                        Daily stock list
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="daily_stock_create" class="daily_stock inventory_permission super_select_all">
                                        Daily stock create
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="daily_stock_view" class="daily_stock inventory_permission super_select_all">  Daily stock detail
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="daily_stock_update" class="daily_stock inventory_permission super_select_all">
                                        Daily stock update
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="daily_stock_delete" class="daily_stock inventory_permission super_select_all">
                                        Daily stock delete
                                    </p>

                                    {{-- <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="stock_out_report" class="daily_stock inventory_permission super_select_all">
                                        Stock out report
                                    </p> --}}
                                </div>

                                <hr class="my-2">

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all inventory_permission super_select_all" data-target="transfer_stock" autocomplete="off"><strong> Transfer stock</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="transfer_wh_to_bl" class="transfer_stock inventory_permission super_select_all"> Transfer stock WH to b. location
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="transfer_bl_wh" class="transfer_stock inventory_permission super_select_all"> Transfer stock s. location to WH
                                    </p>

                                    @if ($generalSettings['addons__branches'] == 1)
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="transfer_bl_bl" class="transfer_stock inventory_permission super_select_all"> Transfer stock b. l. to b. l
                                        </p>
                                    @endif
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all inventory_permission super_select_all" data-target="inventory_report" autocomplete="off"><strong> Inventory report</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="stock_adjustment_report" class="inventory_report inventory_permission super_select_all">@lang('menu.stock_adjustment_report')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="stock_report" class="inventory_report inventory_permission super_select_all"> @lang('menu.stock_report')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="daily_stock_report" class="inventory_report inventory_permission super_select_all"> {{ __('Daily stock report') }}
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="stock_in_out_report" class="inventory_report inventory_permission super_select_all"> @lang('menu.stock_in_out_report')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="finance_check select_all finance_permission super_select_all" data-target="finance_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="finance_role" href="#collapseFour" href="">
                            Finance Permissions
                        </a>
                    </div>
                    <div id="collapseFour" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all finance_permission" data-target="accounting" autocomplete="off"><strong> @lang('menu.accounting')</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="banks" class="accounting finance_permission super_select_all"> Banks
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="accounts" class="accounting finance_permission super_select_all"> Accounts
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="assets" class="accounting finance_permission super_select_all"> Assets
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="loans" class="accounting finance_permission super_select_all"> @lang('menu.loans')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="contra" class="accounting finance_permission super_select_all"> Contra
                                    </p>
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all finance_permission" data-target="expenses" autocomplete="off"><strong> @lang('menu.expense')</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="view_expense" class="expenses finance_permission super_select_all">
                                        {{ __('View Expense') }}
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="add_expense" class="expenses finance_permission super_select_all">
                                        @lang('menu.add_expense')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="edit_expense" class="expenses finance_permission super_select_all">
                                        @lang('menu.edit_expense')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="delete_expense" class="expenses finance_permission super_select_all">
                                        {{ __('Delete Expense') }}
                                    </p>
                                    {{-- <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="expense_category" class="expenses finance_permission super_select_all"> expense categories
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="category_wise_expense" class="expenses finance_permission super_select_all"> {{ __('View Category Wise Expense') }}
                                    </p> --}}
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all finance_permission" data-target="incomes" autocomplete="off"><strong> Incomes</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="incomes_index" class="incomes finance_permission super_select_all">
                                        income List
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="incomes_show" class="incomes finance_permission super_select_all">
                                        income Single View
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="incomes_create" class="incomes finance_permission super_select_all">
                                        Add Income
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="incomes_edit" class="incomes finance_permission super_select_all">
                                        Edit Income
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="incomes_delete" class="incomes finance_permission super_select_all">
                                        Delete Income
                                    </p>
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all finance_permission" data-target="finance_report" autocomplete="off"><strong> Finance report</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="balance_sheet" class="finance_report finance_permission super_select_all">@lang('menu.balance_sheet')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="trial_balance" class="finance_report finance_permission super_select_all">@lang('menu.trial_balance')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="cash_flow" class="finance_report finance_permission super_select_all"> Cash flow
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="profit_loss_ac" class="finance_report finance_permission super_select_all"> Profit/loss account
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="daily_profit_loss" class="finance_report finance_permission super_select_all"> @lang('menu.daily_profit')/loss
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="financial_report" class="finance_report finance_permission super_select_all"> @lang('menu.financial_report')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="expanse_report" class="finance_report finance_permission super_select_all"> @lang('menu.expense_report')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="income_report" class="finance_report finance_permission super_select_all"> @lang('menu.income_report')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($generalSettings['addons__manufacturing'] == 1)
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="manufacturing_check select_all super_select_all manufacturing_permission " data-target="manufacturing_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="manufacturing_role" href="#collapseFive" href="">
                                @lang('menu.manufacturing_permissions')
                            </a>
                        </div>
                        <div id="collapseFive" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox" class="select_all super_select_all manufacturing_permission" data-target="manage_production" autocomplete="off"><strong> Manage
                                                production</strong></strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="process_view" class="manage_production manufacturing_permission super_select_all">
                                                View process
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="process_add" class="manage_production manufacturing_permission super_select_all">
                                                Add process
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="process_edit" class="manage_production manufacturing_permission super_select_all">
                                            @lang('menu.edit_process')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="process_delete" class="manage_production manufacturing_permission super_select_all">  Delete process
                                        </p>


                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="hidden">
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="production_view" class="manage_production manufacturing_permission super_select_all">  @lang('menu.view_production')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="production_add" class="manage_production manufacturing_permission super_select_all">@lang('menu.add_production')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="production_edit" class="manage_production manufacturing_permission super_select_all">@lang('menu.edit_production')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="production_delete" class="manage_production manufacturing_permission super_select_all">  {{ __('Delete Production') }}
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="manufacturing_settings" class="manage_production manufacturing_permission super_select_all">  @lang('menu.manufacturing_setting')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"> <input type="checkbox" class="select_all super_select_all manufacturing_permission" data-target="menufacturing_report" autocomplete="off"><strong> Menufacturing report</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="process_report" class="menufacturing_report manufacturing_permission super_select_all">  Process report
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="manufacturing_report" class="menufacturing_report manufacturing_permission super_select_all">  @lang('menu.manufacturing_report')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="communication_check select_all super_select_all communication_permission" data-target="communication_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="communication_role" href="#collapseSix" href="">
                            Communication Permissions
                        </a>
                    </div>
                    <div id="collapseSix" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all communication_permission" data-target="communication" autocomplete="off"><strong> @lang('menu.communication')</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="notice_board" class="communication super_select_all communication_permission">@lang('menu.notice_board')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="email" class="communication super_select_all communication_permission"> Email
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="email_settings" class="communication super_select_all communication_permission"> @lang('menu.email_settings')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="sms" class="communication super_select_all communication_permission"> SMS
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="sms_settings" class="communication super_select_all communication_permission"> @lang('menu.sms_settings')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="utilities_check select_all super_select_all utilities_permission " data-target="utilities_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="utilities_role" href="#collapseSeven" href="">
                            Utilities Permissions
                        </a>
                    </div>
                    <div id="collapseSeven" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all utilities_permission" data-target="utilities" autocomplete="off"><strong> Utilities</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="media" class="utilities utilities_permission super_select_all">
                                        Medial
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="calender" class="utilities utilities_permission super_select_all">
                                        Calender
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="announcement" class="utilities utilities_permission super_select_all">
                                        Announcement
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="activity_log" class="utilities utilities_permission super_select_all">
                                        Activity log
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="database_backup" class="utilities utilities_permission super_select_all">

                                        @lang('menu.database_backup')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="asset_check select_all super_select_all asset_permission" data-target="asset_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="asset_role" href="#collapseEight" href="">
                            @lang('menu.asset_permissions')
                        </a>
                    </div>
                    <div id="collapseEight" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all asset_permission super_select_all" data-target="asset" autocomplete="off">
                                        <strong>@lang('menu.asset')</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_index" class="asset asset_permission super_select_all">
                                        Asset list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_create" class="asset asset_permission super_select_all">
                                        Asset create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_view" class="asset asset_permission super_select_all">  Asset
                                        detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_update" class="asset asset_permission super_select_all">
                                        Asset update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_delete" class="asset asset_permission super_select_all">
                                        Asset delete
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_settings" class="asset asset_permission super_select_all">
                                        Asset settings
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission" data-target="asset_allocation" autocomplete="off"> <strong>Asset
                                            allocation</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_allocation_index" class="asset_allocation asset_permission super_select_all">
                                        Asset allocation list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_allocation_create" class="asset_allocation asset_permission super_select_all">
                                        Asset allocation create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_allocation_view" class="asset_allocation asset_permission super_select_all">  Asset
                                        allocation detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_allocation_update" class="asset_allocation asset_permission super_select_all">
                                        Asset allocation update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_allocation_delete" class="asset_allocation asset_permission super_select_all">
                                        Asset allocation delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_depreciation" autocomplete="off"> <strong>Asset
                                            depreciation</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_depreciation_index" class="asset_depreciation asset_permission super_select_all">
                                        Asset depreciation list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_depreciation_create" class="asset_depreciation asset_permission super_select_all">
                                        Asset depreciation create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_depreciation_view" class="asset_depreciation asset_permission super_select_all">
                                        Asset depreciation detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_depreciation_update" class="asset_depreciation asset_permission super_select_all">
                                        Asset depreciation update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_depreciation_delete" class="asset_depreciation asset_permission super_select_all">
                                        Asset depreciation delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_licenses" autocomplete="off"> <strong>Asset licenses</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_index" class="asset_licenses asset_permission super_select_all">  Asset
                                        licenses list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_create" class="asset_licenses asset_permission super_select_all">  Asset
                                        licenses create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_view" class="asset_licenses asset_permission super_select_all">  Asset
                                        licenses detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_update" class="asset_licenses asset_permission super_select_all">  Asset
                                        licenses update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_delete" class="asset_licenses asset_permission super_select_all">  Asset
                                        licenses delete
                                    </p>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_manufacturer" autocomplete="off"> <strong>Asset
                                            manufacturer</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_manufacturer_index" class="asset_manufacturer asset_permission super_select_all">
                                        Asset manufacturer list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_manufacturer_create" class="asset_manufacturer asset_permission super_select_all">
                                        Asset manufacturer create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_manufacturer_view" class="asset_manufacturer asset_permission super_select_all">
                                        Asset manufacturer detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_manufacturer_update" class="asset_manufacturer asset_permission super_select_all">
                                        Asset manufacturer update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_manufacturer_delete" class="asset_manufacturer asset_permission super_select_all">
                                        Asset manufacturer delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_categories" autocomplete="off"> <strong>Asset
                                            categories</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_categories_index" class="asset_categories asset_permission super_select_all">
                                        Asset categories list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_categories_create" class="asset_categories asset_permission super_select_all">
                                        Asset categories create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_categories_view" class="asset_categories asset_permission super_select_all">  Asset
                                        categories detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_categories_update" class="asset_categories asset_permission super_select_all">
                                        Asset categories update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_categories_delete" class="asset_categories asset_permission super_select_all">
                                        Asset categories delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_locations" autocomplete="off"> <strong>Asset
                                            locations</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_locations_index" class="asset_locations asset_permission super_select_all">  Asset
                                        locations list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_locations_create" class="asset_locations asset_permission super_select_all">  Asset
                                        locations create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_locations_view" class="asset_locations asset_permission super_select_all">  Asset
                                        locations detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_locations_update" class="asset_locations asset_permission super_select_all">  Asset
                                        locations update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_locations_delete" class="asset_locations asset_permission super_select_all">  Asset
                                        locations delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_units" autocomplete="off">
                                        <strong>Asset units</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_units_index" class="asset_units asset_permission super_select_all">
                                            Asset units
                                        list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_units_create" class="asset_units asset_permission super_select_all">
                                            Asset units
                                        create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_units_view" class="asset_units asset_permission super_select_all">
                                            Asset units
                                        detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_units_update" class="asset_units asset_permission super_select_all">
                                            Asset units
                                        update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_units_delete" class="asset_units asset_permission super_select_all">
                                            Asset units
                                        delete
                                    </p>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_requests" autocomplete="off"> <strong>Asset requests</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_requests_index" class="asset_requests asset_permission super_select_all">  Asset
                                        requests list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_requests_create" class="asset_requests asset_permission super_select_all">  Asset
                                        requests create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_requests_view" class="asset_requests asset_permission super_select_all">  Asset
                                        requests detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_requests_update" class="asset_requests asset_permission super_select_all">  Asset
                                        requests update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_requests_delete" class="asset_requests asset_permission super_select_all">  Asset
                                        requests delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_warranties" autocomplete="off"> <strong>Asset
                                            warranties</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_warranties_index" class="asset_warranties asset_permission super_select_all">
                                        Asset warranties list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_warranties_create" class="asset_warranties asset_permission super_select_all">
                                        Asset warranties create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_warranties_view" class="asset_warranties asset_permission super_select_all">  Asset
                                        warranties detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_warranties_update" class="asset_warranties asset_permission super_select_all">
                                        Asset warranties update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_warranties_delete" class="asset_warranties asset_permission super_select_all">
                                        Asset warranties delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_audits" autocomplete="off">
                                        <strong>Asset audits</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_audits_index" class="asset_audits asset_permission super_select_all">  Asset audits
                                        list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_audits_create" class="asset_audits asset_permission super_select_all">  Asset
                                        audits create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_audits_view" class="asset_audits asset_permission super_select_all">  Asset audits
                                        detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_audits_update" class="asset_audits asset_permission super_select_all">  Asset
                                        audits update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_audits_delete" class="asset_audits asset_permission super_select_all">  Asset
                                        audits delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_revokes" autocomplete="off">
                                        <strong>Asset revokes</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_revokes_index" class="asset_revokes asset_permission super_select_all">  Asset
                                        revokes list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_revokes_create" class="asset_revokes asset_permission super_select_all">  Asset
                                        revokes create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_revokes_view" class="asset_revokes asset_permission super_select_all">  Asset
                                        revokes detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_revokes_update" class="asset_revokes asset_permission super_select_all">  Asset
                                        revokes update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_revokes_delete" class="asset_revokes asset_permission super_select_all">  Asset
                                        revokes delete
                                    </p>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_components" autocomplete="off">
                                        <strong>{{ __('Asset components') }}</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_components_index" class="asset_components asset_permission super_select_all">  {{ __('Asset components list') }}
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_components_create" class="asset_components asset_permission super_select_all">{{ __('Asset components create') }}
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_components_view" class="asset_components asset_permission super_select_all">  Asset components detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_components_update" class="asset_components asset_permission super_select_all">  Asset components update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_components_delete" class="asset_components asset_permission super_select_all">  Asset components delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_licenses_categories" autocomplete="off">
                                        <strong>Asset licenses categories</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_categories_index" class="asset_licenses_categories asset_permission super_select_all">  Asset licenses categories list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_categories_create" class="asset_licenses_categories asset_permission super_select_all">  Asset licenses categories create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_categories_view" class="asset_licenses_categories asset_permission super_select_all">  Asset licenses categories detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_categories_update" class="asset_licenses_categories asset_permission super_select_all">  Asset licenses categories update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_licenses_categories_delete" class="asset_licenses_categories asset_permission super_select_all">  Asset licenses categories delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_terms_and_conditions" autocomplete="off">
                                        <strong>Asset terms and condition</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_terms_and_conditions_index" class="asset_terms_and_conditions asset_permission super_select_all">  Asset terms and
                                        condition list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_terms_and_conditions_create" class="asset_terms_and_conditions asset_permission super_select_all">  Asset terms and
                                        condition create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_terms_and_conditions_view" class="asset_terms_and_conditions asset_permission super_select_all">  Asset terms and
                                        condition detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_terms_and_conditions_update" class="asset_terms_and_conditions asset_permission super_select_all">  Asset terms and
                                        condition update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_terms_and_conditions_delete" class="asset_terms_and_conditions asset_permission super_select_all">  Asset terms and
                                        condition delete
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_term_condition_categories" autocomplete="off"> <strong>Asset term & condition
                                            category</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_term_condition_categories_index" class="asset_term_condition_categories asset_permission super_select_all">  Asset term &
                                        condition category list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_term_condition_categories_create" class="asset_term_condition_categories asset_permission super_select_all">  Asset term &
                                        condition category create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_term_condition_categories_view" class="asset_term_condition_categories asset_permission super_select_all">  Asset term &
                                        condition category detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_term_condition_categories_update" class="asset_term_condition_categories asset_permission super_select_all">  Asset term &
                                        condition category update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="asset_term_condition_categories_delete" class="asset_term_condition_categories asset_permission super_select_all">  Asset term &
                                        condition category delete
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- LC Permission start --}}
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="lc_check select_all super_select_all lc_permission" data-target="lc_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="lc_role" href="#collapseLC" href="">
                            LC Permissions
                        </a>
                    </div>
                    <div id="collapseLC" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all lc_permission " data-target="opening_lc" autocomplete="off"><strong>Opening-LC</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="opening_lc" class="opening_lc lc_permission super_select_all"> Opening-LC
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="opening_lc_index" class="opening_lc lc_permission super_select_all"> Opening-LC list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="opening_lc_create" class="opening_lc lc_permission super_select_all"> Opening-LC create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="opening_lc_view" class="opening_lc lc_permission super_select_all">  Opening-LC detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="opening_lc_update" class="opening_lc lc_permission super_select_all">
                                        Opening-LC update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="opening_lc_delete" class="opening_lc lc_permission super_select_all">
                                        Opening-LC delete
                                    </p>
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all lc_permission " data-target="import_purchase_order" autocomplete="off">
                                        <strong>Import purchase order</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="import_purchase_order" class="import_purchase_order lc_permission super_select_all">
                                        Import purchase order
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="import_purchase_order_index" class="import_purchase_order lc_permission super_select_all">
                                        Import purchase order list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="import_purchase_order_create" class="import_purchase_order lc_permission super_select_all">
                                        Import purchase order create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="import_purchase_order_view" class="import_purchase_order lc_permission super_select_all">  Import purchase order
                                        detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="import_purchase_order_update" class="import_purchase_order lc_permission super_select_all">
                                        Import purchase order update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="import_purchase_order_delete" class="import_purchase_order lc_permission super_select_all">
                                        Import purchase order delete
                                    </p>
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all lc_permission " data-target="exporters" autocomplete="off">
                                        <strong>Exporters</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="exporters" class="exporters lc_permission super_select_all">
                                        Exporters
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="exporters_index" class="exporters lc_permission super_select_all">
                                        Exporters list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="exporters_create" class="exporters lc_permission super_select_all">
                                        Exporters create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="exporters_view" class="exporters lc_permission super_select_all">  Exporters
                                        detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="exporters_update" class="exporters lc_permission super_select_all">
                                        Exporters update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="exporters_delete" class="exporters lc_permission super_select_all">
                                        Exporters delete
                                    </p>
                                </div>

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all lc_permission " data-target="insurance_companies" autocomplete="off">
                                        <strong>Insurance companies</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="insurance_companies" class="insurance_companies lc_permission super_select_all">
                                        Insurance companies
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="insurance_companies_index" class="insurance_companies lc_permission super_select_all">
                                        Insurance companies list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="insurance_companies_create" class="insurance_companies lc_permission super_select_all">
                                        Insurance companies create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="insurance_companies_view" class="insurance_companies lc_permission super_select_all">  Insurance companies
                                        detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="insurance_companies_update" class="insurance_companies lc_permission super_select_all">
                                        Insurance companies update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="insurance_companies_delete" class="insurance_companies lc_permission super_select_all">
                                        Insurance companies delete
                                    </p>
                                </div>

                                <hr class="my-2">

                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all lc_permission" data-target="cnf_agents" autocomplete="off">
                                        <strong>CNF agent</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="cnf_agents" class="cnf_agents lc_permission super_select_all">
                                        CNF agent
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="cnf_agents_index" class="cnf_agents lc_permission super_select_all">
                                        CNF agent list
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="cnf_agents_create" class="cnf_agents lc_permission super_select_all">
                                        CNF agent create
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="cnf_agents_view" class="cnf_agents lc_permission super_select_all">  CNF agent
                                        detail
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="cnf_agents_update" class="cnf_agents lc_permission super_select_all">
                                        CNF agent update
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="cnf_agents_delete" class="cnf_agents lc_permission super_select_all">
                                        CNF agent delete
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- LC Permission end --}}
                @if ($generalSettings['addons__todo'] == 1)
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="project_check select_all super_select_all project_permission" data-target="project_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="project_role" href="#collapseNine" href="">
                            Project Management Permissions
                        </a>
                    </div>
                    <div id="collapseNine" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all project_permission" data-target="manage_task" autocomplete="off"><strong>
                                            @lang('menu.manage_task')</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="assign_todo" class="manage_task project_permission super_select_all">

                                        Todo
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="work_space" class="manage_task project_permission super_select_all">

                                        @lang('menu.work_space')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="memo" class="manage_task project_permission super_select_all">
                                            Memo
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="msg" class="manage_task project_permission super_select_all">

                                        @lang('menu.message')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="setup_check select_all super_select_all setup_permission" data-target="setup_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="setup_role" href="#collapseTen" href="">
                            Set-up Permissions
                        </a>
                    </div>
                    <div id="collapseTen" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all setup_permission" data-target="settings" autocomplete="off"> <strong>Settings</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="general_settings" class="settings setup_permission super_select_all">
                                        General
                                        settings
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="payment_settings" class="settings setup_permission super_select_all">
                                        Payment
                                        settings
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="barcode_settings" class="settings setup_permission super_select_all">

                                        @lang('menu.barcode_settings')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="reset" class="settings setup_permission super_select_all">
                                        @lang('menu.reset')
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all setup_permission " data-target="app_setup" autocomplete="off"> <strong> App set-up</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="tax" class="app_setup setup_permission super_select_all">
                                        Tax
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="branch" class="app_setup setup_permission super_select_all">
                                        Business
                                        location
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="warehouse" class="app_setup setup_permission super_select_all">
                                        @lang('menu.warehouse')
                                    </p>


                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="invoice_schema" class="app_setup setup_permission super_select_all">
                                        Invoice
                                        schemas
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="invoice_layout" class="app_setup setup_permission super_select_all">
                                        Invoice
                                        layout
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="cash_counters" class="app_setup setup_permission super_select_all">
                                            Cash
                                        counters
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all setup_permission " data-target="users" autocomplete="off"><strong> @lang('menu.users')</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="user_view" class="users setup_permission super_select_all">
                                        @lang('menu.view_user')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="user_add" class="users setup_permission super_select_all" autocomplete="off">
                                        @lang('menu.add_user')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="user_edit" class="users setup_permission super_select_all" autocomplete="off">
                                        @lang('menu.edit_user')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="user_delete" class="users setup_permission super_select_all" autocomplete="off">
                                        {{ __('Delete User') }}
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all setup_permission " data-target="roles" autocomplete="off"><strong> Roles</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="role_view" class="roles setup_permission super_select_all">
                                        {{ __('View Role') }}
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="role_add" class="roles setup_permission super_select_all">
                                        @lang('menu.add_role')
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="role_edit" class="roles setup_permission super_select_all">
                                        {{ __('Edit Role') }}
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="role_delete" class="roles setup_permission super_select_all">
                                        {{ __('Delete Role') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="cash_check select_all super_select_all cash_permission" data-target="cash_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="cash_role" href="#collapseEleven" href="">
                            {{ __('Cash Register Permissions') }}
                        </a>
                    </div>
                    <div id="collapseEleven" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all cash_permission" data-target="cash_register" autocomplete="off"><strong>
                                        {{ __('Cash Register') }}/strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="register_view" class="cash_register cash_permission super_select_all">

                                        {{ __('View Cash Register') }}
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="register_close" class="cash_register cash_permission super_select_all">
                                        {{ __('Close Cash Register') }}
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="another_register_close" class="cash_register cash_permission super_select_all"> {{ __('Close Another Cash Register') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="dash_chek select_all super_select_all dashboard_permission" data-target="dashboard_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="dash_role" href="#collapseTwelve" href="">
                            {{ __('Dashboard Permissions') }}
                        </a>
                    </div>
                    <div id="collapseTwelve" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all dashboard_permission" data-target="dashboard" autocomplete="off"><strong> @lang('menu.dashboard')</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="view_dashboard_data" class="dashboard dashboard_permission super_select_all">
                                        View
                                        dashboard Data
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($generalSettings['addons__hrm'] == 1)
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="hr_chek select_all super_select_all human_permission" data-target="human_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="hr_role" href="#collapseThirteen" href="">
                            Human resource Permissions
                        </a>
                    </div>
                    <div id="collapseThirteen" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><input type="checkbox" class="select_all super_select_all human_permission" data-target="hrm" autocomplete="off"><strong> HRM</strong>
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="hrm_dashboard" class="hrm human_permission super_select_all ">
                                            HRM
                                            @lang('menu.dashboard')
                                    </p>

                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="attendance" class="hrm human_permission super_select_all">

                                        @lang('menu.attendance')
                                    </p>

                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="payroll" class="hrm human_permission super_select_all">
                                        @lang('menu.payroll')
                                    </p>

                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="payroll_report" class="hrm human_permission super_select_all">

                                        @lang('menu.payroll_report')
                                    </p>

                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="payroll_payment_report" class="hrm human_permission super_select_all">
                                            @lang('menu.payroll_payment_report')
                                    </p>

                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="attendance_report" class="hrm human_permission super_select_all">
                                        @lang('menu.attendance_report')
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info">
                                        <input type="checkbox" class="select_all super_select_all human_permission " data-target="hrm_others" autocomplete="off"><strong>
                                            Others</strong>
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="leave_type" class="hrm_others human_permission super_select_all">

                                        @lang('menu.leave_type')
                                    </p>
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="leave_assign" class="hrm_others human_permission super_select_all">

                                        {{ __('Leave assign') }}
                                    </p>
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="shift" class="hrm_others human_permission super_select_all">
                                        @lang('menu.shift')
                                    </p>
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="view_allowance_and_deduction" class="hrm_others human_permission super_select_all">  {{  __('Allowance and deduction') }}
                                    </p>
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="holiday" class="hrm_others human_permission super_select_all">

                                        @lang('menu.holiday')
                                    </p>
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="department" class="hrm_others human_permission super_select_all">

                                        @lang('menu.departments')
                                    </p>
                                    <p class="checkbox_input_wrap">
                                        <input type="checkbox" name="designation" class="hrm_others human_permission super_select_all">

                                        Designation
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="form_element rounded mt-0 mb-1">
                    <div class="accordion-header">
                        <input type="checkbox" class="other_check select_all super_select_all others_permission" data-target="others_permission" autocomplete="off">
                        <a data-bs-toggle="collapse" class="other_role" href="#collapsefourtenn" href="">
                            {{ __('Others Permissions') }}
                        </a>
                    </div>
                    <div id="collapsefourtenn" class="collapse" data-bs-parent="#accordion">
                        <div class="element-body border-top">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <p class="text-info"><strong><input type="checkbox" class="select_all super_select_all others_permission" data-target="others">
                                            @lang('menu.others')</strong></p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="print_invoice" class="others others_permission super_select_all">
                                        @lang('menu.print_invoice')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="print_challan" class="others others_permission super_select_all">
                                        @lang('menu.print_challan')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="print_weight" class="others others_permission super_select_all">
                                        @lang('menu.print_weight')
                                    </p>
                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="today_summery" class="others others_permission super_select_all">
                                        {{ __('Today Summery') }}
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="website_link" class="others others_permission super_select_all">
                                        {{ __('Website link') }}
                                    </p>

                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="hrm_menu" class="others others_permission super_select_all">
                                        HRM Menus
                                    </p>


                                    <p class="checkbox_input_wrap mt-1">
                                        <input type="checkbox" name="modules_page" class="others others_permission super_select_all">
                                        {{ __('Modules page') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row1">
                    <div class="col-md-12 d-flex justify-content-end mt-2">
                        <div class="btn-box">
                            <button type="button" class="btn loading_button p-1 d-hide"><i class="fas fa-spinner"></i></button>
                            <button class="btn w-auto btn-success submit_button float-end ">@lang('menu.save')</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).on('click', '.select_all', function() {
        var target = $(this).data('target');
        if ($(this).is(':checked', true)) {
            $('.' + target).prop('checked', true);
        } else {
            $('.' + target).prop('checked', false);
        }
    });

</script>
@endpush
