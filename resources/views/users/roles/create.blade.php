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
                        <div class="accordion-item mb-3">
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
                        <div class="accordion-item mb-3">
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
                        <div class="accordion-item mb-3">
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
                        <div class="accordion-item mb-3">
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
                         {{-- Example --}}
                        <div class="accordion-item mb-3">
                          <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="users" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#users_permission" aria-expanded="false">
                                    {{__(' Users Permissions')}}
                                </a>
                            </div>
                            <div id="users_permission" class="collapse" data-bs-parent="#users_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <input type="checkbox" class="users" id="select_all" data-target="users" autocomplete="off">
                                                <strong>{{ __('Users') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_all" class="users">
                                                {{ __('View user') }} 
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
       
                      </div>
                    {{-- Closed Accordian --}}

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Adjustment Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="adjustment" autocomplete="off"> &nbsp; @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.stock_adjustment')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="adjustment_all" class="adjustment"> &nbsp; {{ __('View All Adjustment') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="adjustment_add_from_location" class="adjustment"> &nbsp; {{ __('Add Adjustment From Business Location') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="adjustment_add_from_warehouse" class="adjustment"> &nbsp; {{ __('Add Adjustment From Warehouse') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="adjustment_delete" class="adjustment" > &nbsp; {{ __('Delete Adjustment') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="stock_adjustment_report" class="adjustment"> &nbsp; @lang('menu.stock_adjustment_report')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Expenses Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="expense" autocomplete="off"> &nbsp; @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.expenses')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="view_expense" class="expense"> &nbsp; {{ __('View Expense') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="add_expense" class="expense"> &nbsp; {{ __('Add Expense') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="edit_expense" class="expense"> &nbsp; @lang('menu.edit_expense') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="delete_expense" class="expense"> &nbsp; {{ __('Delete Expense') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="expense_category" class="expense"> &nbsp; @lang('menu.expense_category') -> {{ __('View/Add/Edit/Delete') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="category_wise_expense" class="expense"> &nbsp; {{ __('View Category Wise Expense') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="expanse_report" class="expense"> &nbsp;@lang('menu.expense_report')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Sales Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="sale" autocomplete="off"> &nbsp; @lang('menu.select_all') </p>
                                    </div>
                                    {{-- <div class="col-md-4"> --}}
                                        <div class="col-md-12">
                                            <p><strong>@lang('menu.sales')</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="create_add_sale" class="sale"> &nbsp; @lang('menu.create_add_sale') </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_add_sale" class="sale"> &nbsp; {{ __('Manage Add Sale') }} </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_add_sale" class="sale"> &nbsp; {{ __('Edit Add Sale') }} </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_add_sale" class="sale"> &nbsp; {{ __('Delete Add Sale') }} </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="add_sale_settings" class="sale"> &nbsp; @lang('menu.add_sale_settings') </p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_draft" class="sale"> &nbsp;{{ __('List Draft') }} </p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_quotation" class="sale"> &nbsp; {{ __('List Quotations') }} </p>
                                            </div>
                                        </div>
                                    {{-- </div> --}}
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12 d-inline-block"></div>
                                    <div class="col-md-12 d-inline-block"></div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="sale_payment" class="sale"> &nbsp; {{ __('View/Add/Edit Payment') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_price_sale_screen" class="sale"> &nbsp; {{ __('Edit product price from sales screen') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_discount_sale_screen" class="sale"> &nbsp; {{ __('Edit product discount in sale scr') }}. </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="shipment_access" class="sale"> &nbsp; {{ __('Access shipments') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="view_product_cost_is_sale_screed" class="sale"> &nbsp; {{ __('View Product Cost In sale screen') }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="view_own_sale" class="sale"> &nbsp; {{ __('View only own Add/POS Sale') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="return_access" class="sale"> &nbsp; {{ __('Access Sale Return') }} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12 d-inline-block"></div>
                                    <div class="col-md-12 d-inline-block"></div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="discounts" class="sale"> &nbsp; @lang('menu.manage_offers') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="sale_statements" class="sale"> &nbsp;  @lang('menu.sale_statement')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="sale_return_statements" class="sale"> &nbsp;  @lang('menu.sale_return_statement')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="pro_sale_report" class="sale"> &nbsp;{{ __('Sale Product Report') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="sale_payment_report" class="sale"> &nbsp; @lang('menu.receive_payment_report')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="c_register_report" class="sale"> &nbsp; @lang('menu.cash_register_reports')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="sale_representative_report" class="sale"> &nbsp; @lang('menu.sales_representative_report')</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" d-inline-block"></div>

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.pos_sales')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="pos_all" class="sale"> &nbsp; @lang('menu.manage_pos_sale')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="pos_add" class="sale"> &nbsp; @lang('menu.add_pos_sale') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="pos_edit" class="sale"> &nbsp; @lang('menu.edit_pos_sale') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="pos_delete" class="sale"> &nbsp; @lang('menu.delete_pos_sale') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="pos_sale_settings" class="sale"> &nbsp; @lang('menu.pos_sale_settings') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_price_pos_screen" class="sale"> &nbsp;{{ __('Edit Product Price From POS Screen') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_discount_pos_screen" class="sale"> &nbsp; {{ __('Edit Product Discount From POS Screen') }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Cash Register Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="cash_register" autocomplete="off"> &nbsp; @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <p><strong>{{ __('Cash Register') }}</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="register_view" class="cash_register"> &nbsp; {{ __('View Cash Register') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="register_close" class="cash_register"> &nbsp; {{ __('Close Cash Register') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="another_register_close" class="another_register_close cash_register"> &nbsp; {{ __('Close Another Cash Register') }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('All Report Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="report"> &nbsp; @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.reports')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="tax_report" class="report"> &nbsp; @lang('menu.tax_report')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="production_report" class="report"> &nbsp; @lang('menu.production_report')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Setup Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="settings"> &nbsp; @lang('menu.select_all')</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.setup')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="tax" class="settings"> &nbsp; @lang('menu.tax')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="branch" class="settings"> &nbsp; @lang('menu.business_location')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="warehouse" class="settings"> &nbsp; @lang('menu.warehouse')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="g_settings" class="settings"> &nbsp; @lang('menu.general_settings')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="p_settings" class="settings"> &nbsp; {{ __('Payment settings') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="inv_sc" class="settings"> &nbsp; @lang('menu.invoice_schema')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="inv_lay" class="settings"> &nbsp; {{ __('Invoice Layout') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="barcode_settings" class="settings"> &nbsp; {{ __('Barcode Sticker Settings') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="cash_counters" class="settings"> &nbsp; @lang('menu.cash_counter')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Dashboard Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">

                                </div>

                                <div class="col-md-6">

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.dashboard')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="dash_data"> &nbsp; {{ __('View Dashboard Data') }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Accounting Permission') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">

                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.accounting')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="ac_access"> &nbsp; {{ __('Access Accounting') }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($generalSettings['addons__hrm'] == 1)
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="heading_area">
                                <p class="p-1 text-primary"><strong>{{ __('HRM Permissions') }}</strong> </p>
                            </div>

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" id="select_all"  data-target="HRMS"> &nbsp; @lang('menu.select_all') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4">

                                        <div class="col-md-12">
                                            <p><strong>HRM</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_dashboard" class="HRMS"> &nbsp; HRM @lang('menu.dashboard')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="attendance" class="HRMS"> &nbsp;  @lang('menu.attendance')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="payroll" class="HRMS"> &nbsp; @lang('menu.payroll')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="payroll_report" class="HRMS"> &nbsp; @lang('menu.payroll_report')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="payroll_payment_report" class="HRMS"> &nbsp; @lang('menu.payroll_payment_report')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="attendance_report" class="HRMS"> &nbsp; @lang('menu.attendance_report')</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="col-md-12">
                                            <p><strong>@lang('menu.others')</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="leave_type" class="HRMS"> &nbsp; @lang('menu.leave_type')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="leave_assign" class="HRMS"> &nbsp; {{ __('Leave assign') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="shift" class="HRMS"> &nbsp; @lang('menu.shift')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="view_allowance_and_deduction" class="HRMS"> &nbsp; {{  __('Allowance and deduction') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="holiday" class="HRMS"> &nbsp; {{ __('Holidays') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="department" class="HRMS"> &nbsp; @lang('menu.departments')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="designation" class="HRMS"> &nbsp; @lang('menu.designation')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($generalSettings['addons__todo'] == 1)
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="heading_area">
                                <p class="p-1 text-primary"><strong>{{ __('Manage Task Permissions') }}</strong> </p>
                            </div>

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" id="select_all"  data-target="Essentials"> &nbsp; @lang('menu.select_all') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <p><strong>@lang('menu.manage_task')</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="assign_todo" class="Essentials"> &nbsp; @lang('menu.todo')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="work_space" class="Essentials"> &nbsp; @lang('menu.work_space')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="memo" class="Essentials"> &nbsp; @lang('menu.memo')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="msg" class="Essentials"> &nbsp; @lang('menu.message')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($generalSettings['addons__manufacturing'] == 1)
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="heading_area">
                                <p class="p-1 text-primary"><strong>@lang('menu.manufacturing_permissions')</strong> </p>
                            </div>

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" id="select_all" data-target="Manufacturing"> &nbsp; @lang('menu.select_all') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="col-md-12">
                                            <p><strong>@lang('menu.manufacturing')</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_view" class=" Manufacturing"> &nbsp; @lang('menu.view_process')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="process_add" class="Manufacturing"> &nbsp; @lang('menu.add_process')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="process_edit" class="Manufacturing"> &nbsp;  @lang('menu.edit_process')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="process_delete" class="Manufacturing"> &nbsp; {{ __('Delete Process') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="production_view" class=" Manufacturing"> &nbsp; @lang('menu.view_production')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="production_add" class="Manufacturing"> &nbsp; @lang('menu.add_production')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="production_edit" class="Manufacturing"> &nbsp; @lang('menu.edit_production')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="production_delete" class="Manufacturing"> &nbsp; {{ __('Delete Production') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="manuf_settings" class="Manufacturing"> &nbsp; @lang('menu.manufacturing_setting')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="manuf_report" class="Manufacturing"> &nbsp; @lang('menu.manufacturing_report')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Others Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="others"> &nbsp; @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.others')</strong></p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="today_summery" class="others"> &nbsp; {{ __('Today Summery') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="communication" class="others"> &nbsp; @lang('menu.communication')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
