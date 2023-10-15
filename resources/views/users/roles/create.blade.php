@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        p.checkbox_input_wrap {font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        /* Custom Accordion button */
        .accordion-button {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0rem 1.25rem;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            background-color: #fff;
            border: 0;
            border-radius: 0;
            overflow-anchor: none;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,border-radius .15s ease;
        }
        .form_element {
            border: 1px solid #adadad;
            padding: 0;
            background: #ffffff;
            border: 1px solid var(--brand-color);
        }
        .accordion-header {
            position: relative;
            margin-bottom: 0;
        }

        .accordion-header a {
            display: block;
            height: 35px;
            line-height: 35px;
            padding-left: 8px;
        }

        .dark-theme .form_element {
            border: 0px solid #adadad;
            background: #fffefe;
        }
        p.checkbox_input_wrap {
            display: flex;
            gap: 5px;
            line-height: 1.8;
            position: relative;
        }
        p.checkbox_input_wrap {
            font-weight: 600;
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }

    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-plus-circle"></span>
                    <h5>@lang('menu.add_role')</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_role_form" action="{{ route('users.role.store') }}"  method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-4"><b>@lang('menu.role_name') : <span
                                            class="text-danger">*</span></b> </label>
                                        <div class="col-8">
                                            <input type="text" name="role_name" class="form-control add_input" id="role_name"
                                                placeholder="@lang('menu.role_name')">
                                            <span class="error error_role_name">{{ $errors->first('role_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Accordian --}}
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        {{-- Users Permission --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="users" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#users_permission" aria-expanded="false">
                                    {{__(' Users Permissions')}}
                                </a>
                            </div>
                            <div id="users_permission" class="collapse show" data-bs-parent="#users_permission">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input id="select_all" type="checkbox" class="users users_all" data-target="users_all" autocomplete="off">
                                                <strong>{{ __('Users') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="user_view" class="users users_all">
                                                {{ __('View User') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="user_add" class="users users_all">
                                                {{ __('Add User') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="user_edit" class="users users_all">
                                                {{ __('Edit User') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="user_delete" class="users users_all">
                                                {{ __('Delete User') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input id="select_all" type="checkbox" class="users" data-target="all_role" autocomplete="off">
                                                <strong>{{ __('Roles') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="role_view" class="users all_role">
                                                {{ __('View Role') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="role_add" class="users all_role">
                                                {{ __('Add Role') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="role_edit" class="users all_role">
                                                {{ __('Edit Role') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="role_delete" class="users all_role">
                                                {{ __('Delete Role') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Contact Permission --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="contacts" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#contact_permission" aria-expanded="false">
                                    {{ __('Contacts Permissions') }}
                                </a>
                            </div>
                            <div id="contact_permission" class="collapse" data-bs-parent="#contact_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input id="select_all" type="checkbox" class="contacts" data-target="contact_all" autocomplete="off">
                                                <strong>{{ __('Supplier') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_all" class="contacts contact_all">
                                                {{ __('View All Supplier') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_add" class="contacts contact_all">
                                                {{ __('Add Supplier') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_import" class="contacts contact_all">
                                                {{ __('Import Suppliers') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_edit" class="contacts contact_all">
                                                {{ __('Edit Supplier') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_delete" class="contacts contact_all">
                                                {{ __('Delete Supplier') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_report" class="contacts contact_all">
                                                {{ __('Supplier Report') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input id="select_all" type="checkbox" class="contacts" data-target="customer_all" autocomplete="off">
                                                <strong>{{ __('Customers') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_all" class="contacts customer_all">
                                                {{ __('View All Customer') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_add" class="contacts customer_all">
                                                {{ __('Add Customer') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_import" class="contacts customer_all">
                                                {{ __('Import Customers') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_edit" class="contacts customer_all">
                                                {{ __('Edit Customer') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_delete" class="contacts customer_all">
                                                {{ __('Delete Customer') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_report" class="contacts customer_all">
                                                {{ __('Customer Report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_group" class="contacts customer_all">
                                                {{ __('Customer Group') }} &rarr; {{ __('View/Add/Edit/Delete') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Products Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="products" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#product_permission" aria-expanded="false">
                                    {{__('Products Permissions')}}
                                </a>
                            </div>
                            <div id="product_permission" class="collapse" data-bs-parent="#products" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input  id="select_all" type="checkbox" class="products" data-target="product_all" autocomplete="off">
                                                <strong>{{ __('Products') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="product_all" class="products product_all">
                                                {{ __('View All Product') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="product_add" class="products product_all">
                                                {{ __('Add Product') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="product_edit" class="products product_all">
                                                {{ __('Edit Product') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="openingStock_add" class="products product_all">
                                                &nbsp;{{ __('Add/Edit Opening Stock') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="product_delete" class="products product_all">
                                               {{ __('Delete Product') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="product_settings" class="products product_all">
                                               {{ __('Product Settings') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_report" class="products product_all">
                                               {{ __('Stock Report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_in_out_report" class="products product_all">
                                               {{ __('Stock In-Out Report') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input  id="select_all" type="checkbox" class="products" data-target="product_others" autocomplete="off">
                                                <strong>{{ __('Others') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="categories" class="products product_others">
                                                {{ __('Categories') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="brand" class="products product_others">
                                                {{ __('Brands') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="units" class="products product_others">
                                                {{ __('Unit') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="variant" class="products product_others">
                                                &nbsp;{{ __('Variant') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="warranties" class="products product_others">
                                               {{ __('Warranties') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="selling_price_group" class="products product_others">
                                               {{ __('Selling Price Group') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="generate_barcode" class="products product_others">
                                               {{ __('Generate Barcode') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Purchases Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="purchase" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#purchase_permission" aria-expanded="false">
                                    {{__('Purchases Permissions')}}
                                </a>
                            </div>
                            <div id="purchase_permission" class="collapse" data-bs-parent="#purchase_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="purchase" id="select_all" data-target="purchase_all" autocomplete="off">
                                                <strong>{{ __('Purchases') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_all" class="purchase purchase_all">
                                                {{ __('View All Purchase') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_add" class="purchase purchase_all">
                                                {{ __('Add Purchase') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_edit" class="purchase purchase_all">
                                                {{ __('Edit Purchase') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_delete" class="purchase purchase_all">
                                                {{ __('Delete purchase') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="status_update" class="purchase purchase_all">
                                                {{ __('Update Status') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_settings" class="purchase purchase_all">
                                                {{ __('Purchase Settings') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_statements" class="purchase purchase_all">
                                                {{ __('Purchase Statements') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_sale_report" class="purchase purchase_all">
                                                {{ __('Purchase & Sale Report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pro_purchase_report" class="purchase purchase_all">
                                                {{ __('Product Purchase Report') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="purchase" id="select_all" data-target="other_purchase" autocomplete="off">
                                                <strong>{{ __('Others') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment" class="purchase other_purchase">
                                                {{ __('View/Add/Delete Purchase Payment') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_return" class="purchase other_purchase">
                                                {{ __('Access Purchase Return') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_report" class="purchase other_purchase">
                                                {{ __(' Purchase Payment Report') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Adjustment Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="adjustment" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#adjustment_permission" aria-expanded="false">
                                    {{__('Adjustment Permissions')}}
                                </a>
                            </div>
                            <div id="adjustment_permission" class="collapse" data-bs-parent="#adjustment_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="adjustment" id="select_all" data-target="adjustment_all" autocomplete="off">
                                                <strong>{{ __('Stock Adjustment') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="adjustment_all" class="adjustment adjustment_all">
                                                {{ __('View All Adjustment') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="adjustment_add_from_location" class="adjustment adjustment_all">
                                                {{ __('Add Adjustment From Business Location') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="adjustment_add_from_warehouse" class="adjustment adjustment_all">
                                                {{ __('Add Adjustment From Warehouse') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_expense" class="adjustment adjustment_all">
                                                {{ __('Delete Adjustment') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_adjustment_report" class="adjustment adjustment_all">
                                                {{ __('Stock Adjustment Report') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Expenses Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="expenses" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#expenses_permission" aria-expanded="false">
                                    {{__('Expenses Permissions')}}
                                </a>
                            </div>
                            <div id="expenses_permission" class="collapse" data-bs-parent="#expenses_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="expenses" id="select_all" data-target="expenses_all" autocomplete="off">
                                                <strong>{{ __('Expenses') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_expense" class="expenses expenses_all">
                                                {{ __('View Expense') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="add_expense" class="expenses expenses_all">
                                                {{ __('Add Expense') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_expense" class="expenses expenses_all">
                                                {{ __('Edit expense') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_expense" class="expenses expenses_all">
                                                {{ __('Delete Expense') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="expense_category" class="expenses expenses_all">
                                                {{ __('Expense Category')}} &rarr; {{ __('View/Add/Edit/Delete') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="category_wise_expense" class="expenses expenses_all">
                                                {{ __('View Category Wise Expense') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="expanse_report" class="expenses expenses_all">
                                                {{ __('Expense Reports') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Sales Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="sales" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#sales_permission" aria-expanded="false">
                                    {{__('Sales Permissions')}}
                                </a>
                            </div>
                            <div id="sales_permission" class="collapse" data-bs-parent="#sales_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="sales" id="select_all" data-target="sale_all" autocomplete="off">
                                                <strong>{{ __('Sales') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="create_add_sale" class="sales sale_all">
                                                {{ __('Create add sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_add_sale" class="sales sale_all">
                                                {{ __('Manage Add Sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_add_sale" class="sales sale_all">
                                                {{ __('Edit Add Sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_add_sale" class="sales sale_all">
                                                {{ __('Delete Add Sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="add_sale_settings" class="sales sale_all">
                                                {{ __('Add Sale Settings') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_draft" class="sales sale_all">
                                                {{ __('List Draft') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_quotation" class="sales sale_all">
                                                {{ __('List Quotations') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-4">
                                                <input type="checkbox" name="sale_payment" class="sales sale_all">
                                                {{ __('View/Add/Edit Payment') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_price_sale_screen" class="sales sale_all">
                                                {{ __('Edit Product Price from Sales Screen') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_discount_sale_screen" class="sales sale_all">
                                                {{ __('Edit Product Discount in Sale Scr') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="shipment_access" class="sales sale_all">
                                                {{ __('Access Shipments') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_product_cost_is_sale_screed" class="sales sale_all">
                                                {{ __('View Product Cost In Sale Screen') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_own_sale" class="sales sale_all">
                                                {{ __('View only own Add/POS Sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="return_access" class="sales sale_all">
                                                {{ __('Access Sale Return') }}
                                            </p>

                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-4">
                                                <input type="checkbox" name="discounts" class="sales sale_all">
                                                {{ __('Manage Offers') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_statements" class="sales sale_all">
                                                {{ __('Sale Statements') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_return_statements" class="sales sale_all">
                                                {{ __('Sale Return Statements') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pro_sale_report" class="sales sale_all">
                                                {{ __('Sale Product Report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_payment_report" class="sales sale_all">
                                                {{ __('Receive Payment Report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="c_register_report" class="sales sale_all">
                                                {{ __('Cash Register Reports') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_representative_report" class="sales sale_all">
                                                {{ __('Sales Representative Report') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="sales" id="select_all" data-target="pos_sale_all" autocomplete="off">
                                                <strong>{{ __('POS Sales') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_all" class="sales pos_sale_all">
                                                {{ __('Manage POS Sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_add" class="sales pos_sale_all">
                                                {{ __('Add POS Sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_edit" class="sales pos_sale_all">
                                                {{ __('Edit POS Sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_delete" class="sales pos_sale_all">
                                                {{ __('Delete POS Sale') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_sale_settings" class="sales pos_sale_all">
                                                {{ __('POS Sale Settings') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_price_pos_screen" class="sales pos_sale_all">
                                                {{ __('Edit Product Price From POS Screen') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_discount_pos_screen" class="sales pos_sale_all">
                                                {{ __('Edit Product Discount From POS Screen') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Cash Register Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="cash_register" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#cash_register_permission" aria-expanded="false">
                                    {{__('Cash Register Permissions')}}
                                </a>
                            </div>
                            <div id="cash_register_permission" class="collapse" data-bs-parent="#cash_register_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="cash_register" id="select_all" data-target="cash_register_all" autocomplete="off">
                                                <strong>{{ __('Cash Register') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="register_view" class="cash_register cash_register_all">
                                                {{ __('View Cash Register') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="register_close" class="cash_register cash_register_all">
                                                {{ __('Close Cash Register') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="another_register_close" class="cash_register cash_register_all">
                                                {{ __('Close Another Cash Register') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- All Report Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="reports" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#reports_permission" aria-expanded="false">
                                    {{__('All Report Permissions')}}
                                </a>
                            </div>
                            <div id="reports_permission" class="collapse" data-bs-parent="#reports_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="reports" id="select_all" data-target="report_all" autocomplete="off">
                                                <strong>{{ __('Reports') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="tax_report" class="reports report_all">
                                                {{ __('Tax Report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_report" class="reports report_all">
                                                {{ __('Production Report') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Setup Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="setup" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#setup_permission" aria-expanded="false">
                                    {{__('Setup Permissions')}}
                                </a>
                            </div>
                            <div id="setup_permission" class="collapse" data-bs-parent="#setup_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="setup" id="select_all" data-target="setup_all" autocomplete="off">
                                                <strong>{{ __('Set-up') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="tax" class="setup setup_all">
                                                {{ __('Tax') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="branch" class="setup setup_all">
                                                {{ __('Business Location') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="warehouse" class="setup setup_all">
                                                {{ __('Warehouse') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="g_settings" class="setup setup_all">
                                                {{ __('General Settings') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="p_settings" class="setup setup_all">
                                                {{ __('Payment Settings') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="inv_sc" class="setup setup_all">
                                                {{ __('Invoice Schema') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="inv_lay" class="setup setup_all">
                                                {{ __('Invoice Layout') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="barcode_settings" class="setup setup_all">
                                                {{ __('Barcode Sticker Settings') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="cash_counters" class="setup setup_all">
                                                {{ __('Cash Counter') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Dashboard Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="dashboard" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#dashboard_permission" aria-expanded="false">
                                    {{__('Dashboard Permissions')}}
                                </a>
                            </div>
                            <div id="dashboard_permission" class="collapse" data-bs-parent="#dashboard_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="dashboard" id="select_all" data-target="dashboard_all" autocomplete="off">
                                                <strong>{{ __('Dashboard') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="dash_data" class="dashboard dashboard_all">
                                                {{ __('View Dashboard Data') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- Accounting Permission --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="accounting" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#accounting_permission" aria-expanded="false">
                                    {{__('Accounting Permission')}}
                                </a>
                            </div>
                            <div id="accounting_permission" class="collapse" data-bs-parent="#accounting_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="accounting" id="select_all" data-target="accounting_all" autocomplete="off">
                                                <strong>{{ __('Accounting') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="ac_access" class="accounting accounting_all">
                                                {{ __('Access Accounting') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                         {{-- HRM Permissions --}}
                         @if ($generalSettings['addons__hrm'] == 1)
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="hrms" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#hrm_permission" aria-expanded="false">
                                    {{__('HRM Permissions')}}
                                </a>
                            </div>
                            <div id="hrm_permission" class="collapse" data-bs-parent="#hrm_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="hrms" id="select_all" data-target="hrm_all" autocomplete="off">
                                                <strong>{{ __('HRM') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_dashboard" class="hrms hrm_all">
                                                {{ __('HRM Dashboard') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="attendance" class="hrms hrm_all">
                                                {{ __('Attendance') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="payroll" class="hrms hrm_all">
                                                {{ __('Payroll') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="payroll_report" class="hrms hrm_all">
                                                {{ __('Payroll Report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="payroll_payment_report" class="hrms hrm_all">
                                                {{ __('Payroll Payment Report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="attendance_report" class="hrms hrm_all">
                                                {{ __('Attendance Report') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="hrms" id="select_all" data-target="hrm_others_all" autocomplete="off">
                                                <strong>{{ __('Others') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="leave_type" class="hrms hrm_others_all">
                                                {{ __('Leave Type') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="leave_assign" class="hrms hrm_others_all">
                                                {{ __('Leave Assign') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="shift" class="hrms hrm_others_all">
                                                {{ __('Shift') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_allowance_and_deduction" class="hrms hrm_others_all">
                                                {{ __('Allowance and deduction') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="holiday" class="hrms hrm_others_all">
                                                {{ __('Holidays') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="department" class="hrms hrm_others_all">
                                                {{ __('Departments') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="designation" class="hrms hrm_others_all">
                                                {{ __('Designation') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                        @endif
                         {{-- Manage Task Permissions --}}
                         @if ($generalSettings['addons__todo'] == 1)
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="manage_tasks" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#manage_tasks_permission" aria-expanded="false">
                                    {{__('Manage Task Permissions')}}
                                </a>
                            </div>
                            <div id="manage_tasks_permission" class="collapse" data-bs-parent="#manage_tasks_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="manage_tasks" id="select_all" data-target="manage_task_all" autocomplete="off">
                                                <strong>{{ __('Manage Task') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="assign_todo" class="manage_tasks manage_task_all">
                                                {{ __('Todo') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="work_space" class="manage_tasks manage_task_all">
                                                {{ __('Work Spaces') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="memo" class="manage_tasks manage_task_all">
                                                {{ __('Memo') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="msg" class="manage_tasks manage_task_all">
                                                {{ __('Message') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                        @endif
                         {{-- Manufacturing Permissions --}}
                         @if ($generalSettings['addons__manufacturing'] == 1)
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="manufacturings" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#manufacturing_permission" aria-expanded="false">
                                    {{__('Manufacturing Permissions')}}
                                </a>
                            </div>
                            <div id="manufacturing_permission" class="collapse" data-bs-parent="#manufacturing_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="manufacturings" id="select_all" data-target="manufacturing_all" autocomplete="off">
                                                <strong>{{ __('Manufacturing') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_view" class="manufacturings manufacturing_all">
                                                {{ __('View process') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_add" class="manufacturings manufacturing_all">
                                                {{ __('Add Process') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_edit" class="manufacturings manufacturing_all">
                                                {{ __('Edit Process') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_delete" class="manufacturings manufacturing_all">
                                                {{ __('Delete Process') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_view" class="manufacturings manufacturing_all">
                                                {{ __('View Production') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_add" class="manufacturings manufacturing_all">
                                                {{ __('Add Production') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_edit" class="manufacturings manufacturing_all">
                                                {{ __('Edit Production') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_delete" class="manufacturings manufacturing_all">
                                                {{ __('Delete Production') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="manuf_settings" class="manufacturings manufacturing_all">
                                                {{ __('Manufacturing Settings') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="manuf_report" class="manufacturings manufacturing_all">
                                                {{ __('Manufacturing Report') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                        @endif
                         {{-- Others Permissions --}}
                        <div class="accordion-item mb-1">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="others" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#other_permission" aria-expanded="false">
                                    {{__('Others Permissions')}}
                                </a>
                            </div>
                            <div id="other_permission" class="collapse" data-bs-parent="#other_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="others" id="select_all" data-target="other_all" autocomplete="off">
                                                <strong>{{ __('Others') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="today_summery" class="others other_all">
                                                {{ __('Today Summery') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="communication" class="others other_all">
                                                {{ __('Communication') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>

                      </div>
                    {{-- Closed Accordian --}}
                    <div class="d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                            <button class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#select_all', function() {

            var target = $(this).data('target');
            if ($(this).is(':CHECKED', true)) {

                $('.' + target).prop('checked', true);
            } else {

                $('.' + target).prop('checked', false);
            }
        });
    </script>
@endpush
