@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {
            border: 1px solid #7e0d3d;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        p.checkbox_input_wrap {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

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
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, border-radius .15s ease;
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
@section('title', 'Edit Role - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Edit Role') }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="p-3">
            <form id="edit_role_form" action="{{ route('users.role.update', $role->id) }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label class="col-4"><strong>{{ __('Role Name') }} : <span class="text-danger">*</span></strong> </label>
                                        <div class="col-8">
                                            <input required type="text" name="role_name" class="form-control" id="role_name" placeholder="{{ __('Role Name') }}" value="{{ $role->name }}">
                                            <span class="error error_role_name">{{ $errors->first('role_name') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group align-items-center gap-2">
                                        <label> <b>{{ __('Has Access To All Store/Place') }}</b> </label>
                                        <div class="d-flex align-items-center">
                                            <input {{ $role->hasPermissionTo('has_access_to_all_area') ? 'CHECKED' : '' }} type="checkbox" name="has_access_to_all_area" id="has_access_to_all_area" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="users" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#users_permission" aria-expanded="false">
                                        {{ __(' Users Permissions') }}
                                    </a>
                                </div>
                                <div id="users_permission" class="collapse show" data-bs-parent="#users_permission">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="users users_all" data-target="users_all" autocomplete="off">
                                                        <strong>{{ __('Users') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('user_view') ? 'CHECKED' : '' }} name="user_view" id="user_view" class="users users_all">
                                                    <label for="user_view"> {{ __('View User') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('user_add') ? 'CHECKED' : '' }} name="user_add" id="user_add" class="users users_all">
                                                    <label for="user_add">{{ __('Add User') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('user_edit') ? 'CHECKED' : '' }} name="user_edit" id="user_edit" class="users users_all">
                                                    <label for="user_edit">{{ __('Edit User') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('user_delete') ? 'CHECKED' : '' }} name="user_delete" id="user_delete" class="users users_all">
                                                    <label for="user_delete">{{ __('Delete User') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="users" data-target="all_role" autocomplete="off">
                                                        <strong>{{ __('Roles') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('role_view') ? 'CHECKED' : '' }} name="role_view" id="role_view" class="users all_role">
                                                    <label for="role_view">{{ __('View Role') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('role_add') ? 'CHECKED' : '' }} name="role_add" id="role_add" class="users all_role">
                                                    <label for="role_add"> {{ __('Add Role') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('role_edit') ? 'CHECKED' : '' }} id="role_edit" name="role_edit" class="users all_role">
                                                    <label for="role_edit">{{ __('Edit Role') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('role_delete') ? 'CHECKED' : '' }} id="role_delete" name="role_delete" class="users all_role">
                                                    <label for="role_delete">{{ __('Delete Role') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="users" data-target="user_activities_log" autocomplete="off">
                                                        <strong>{{ __('User Activities Log') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('user_activities_log_index') ? 'CHECKED' : '' }} name="user_activities_log_index" id="user_activities_log_index" class="users user_activities_log">
                                                    <label for="user_activities_log_index">{{ __('View User Activities Log') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('user_activities_log_only_own_log') ? 'CHECKED' : '' }} name="user_activities_log_only_own_log" id="user_activities_log_only_own_log" class="users user_activities_log">
                                                    <label for="user_activities_log_only_own_log">{{ __('View Only Own Activities Log') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="contacts" data-target="contact_all" autocomplete="off">
                                                        <strong>{{ __('Supplier') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('supplier_all') ? 'CHECKED' : '' }} name="supplier_all" id="supplier_all" class="contacts contact_all">
                                                    <label for="supplier_all">{{ __('View All Supplier') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('supplier_manage') ? 'CHECKED' : '' }} name="supplier_manage" id="supplier_manage" class="contacts contact_all">
                                                    <label for="supplier_manage">{{ __('Supplier Manage') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('supplier_ledger') ? 'CHECKED' : '' }} name="supplier_ledger" id="supplier_ledger" class="contacts contact_all">
                                                    <label for="supplier_ledger">{{ __('Supplier Ledger') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('supplier_add') ? 'CHECKED' : '' }} name="supplier_add" id="supplier_add" class="contacts contact_all">
                                                    <label for="supplier_add">{{ __('Add Supplier') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('supplier_import') ? 'CHECKED' : '' }} name="supplier_import" id="supplier_import" class="contacts contact_all">
                                                    <label for="supplier_import">{{ __('Import Suppliers') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('supplier_edit') ? 'CHECKED' : '' }} name="supplier_edit" id="supplier_edit" class="contacts contact_all">
                                                    <label for="supplier_edit">{{ __('Edit Supplier') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('supplier_delete') ? 'CHECKED' : '' }} name="supplier_delete" id="supplier_delete" class="contacts contact_all">
                                                    <label for="supplier_delete">{{ __('Delete Supplier') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" id="select_all" class="contacts" data-target="customer_all" autocomplete="off">
                                                        <strong>{{ __('Customers') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_all') ? 'CHECKED' : '' }} name="customer_all" id="customer_all" class="contacts customer_all">
                                                    <label for="customer_all">{{ __('View All Customer') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_manage') ? 'CHECKED' : '' }} name="customer_manage" id="customer_manage" class="contacts customer_all">
                                                    <label for="customer_manage">{{ __('Customer Manage') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_ledger') ? 'CHECKED' : '' }} name="customer_ledger" id="customer_ledger" class="contacts customer_all">
                                                    <label for="customer_ledger">{{ __('Customer Ledger') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_add') ? 'CHECKED' : '' }} name="customer_add" id="customer_add" class="contacts customer_all">
                                                    <label for="customer_add">{{ __('Add Customer') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_import') ? 'CHECKED' : '' }} name="customer_import" id="customer_import" class="contacts customer_all">
                                                    <label for="customer_import"> {{ __('Import Customers') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_edit') ? 'CHECKED' : '' }} name="customer_edit" id="customer_edit" class="contacts customer_all">
                                                    <label for="customer_edit"> {{ __('Edit Customer') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_delete') ? 'CHECKED' : '' }} name="customer_delete" id="customer_delete" class="contacts customer_all">
                                                    <label for="customer_delete">{{ __('Delete Customer') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_group') ? 'CHECKED' : '' }} name="customer_group" id="customer_group" class="contacts customer_all">
                                                    <label for="customer_group">{{ __('Customer Group') }} &rarr; {{ __('View/Add/Edit/Delete') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="contacts" data-target="money_receipt_all" autocomplete="off">
                                                        <strong>{{ __('Money Receipt Voucher') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('money_receipt_index') ? 'CHECKED' : '' }} name="money_receipt_index" id="money_receipt_index" class="contacts money_receipt_all">
                                                    <label for="money_receipt_index">{{ __('Money Receipt List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('money_receipt_add') ? 'CHECKED' : '' }} name="money_receipt_add" id="money_receipt_add" class="contacts money_receipt_all">
                                                    <label for="money_receipt_add">{{ __('Money Receipt Generate') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('money_receipt_edit') ? 'CHECKED' : '' }} name="money_receipt_edit" id="money_receipt_edit" class="contacts money_receipt_all">
                                                    <label for="money_receipt_edit">{{ __('Money Receipt Edit') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('money_receipt_delete') ? 'CHECKED' : '' }} name="money_receipt_delete" id="money_receipt_delete" class="contacts money_receipt_all">
                                                    <label for="money_receipt_delete">{{ __('Money Receipt Delete') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="contacts" data-target="contact_reports_all" autocomplete="off">
                                                        <strong>{{ __('Contact Reports') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('customer_report') ? 'CHECKED' : '' }} name="customer_report" id="customer_report" class="contacts contact_reports_all">
                                                    <label for="customer_report">{{ __('Customer Report') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('supplier_report') ? 'CHECKED' : '' }} name="supplier_report" id="supplier_report" class="contacts contact_reports_all">
                                                    <label for="supplier_report">{{ __('Supplier Report') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="products" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#product_permission" aria-expanded="false">
                                        {{ __('Products Permissions') }}
                                    </a>
                                </div>
                                <div id="product_permission" class="collapse" data-bs-parent="#products" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="product_all" autocomplete="off">
                                                        <strong>{{ __('Products') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_all') ? 'CHECKED' : '' }} name="product_all" id="product_all" class="products product_all">
                                                    <label for="product_all">{{ __('View All Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_add') ? 'CHECKED' : '' }} name="product_add" id="product_add" class="products product_all">
                                                    <label for="product_add"> {{ __('Add Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_edit') ? 'CHECKED' : '' }} name="product_edit" id="product_edit" class="products product_all">
                                                    <label for="product_edit">{{ __('Edit Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_delete') ? 'CHECKED' : '' }} name="product_delete" id="product_delete" class="products product_all">
                                                    <label for="product_delete"> {{ __('Delete Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('manage_price_group') ? 'CHECKED' : '' }} name="manage_price_group" id="manage_price_group" class="products product_all">
                                                    <label for="manage_price_group">{{ __('Manage Price Group') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('openingStock_add') ? 'CHECKED' : '' }} name="openingStock_add" id="openingStock_add" class="products product_all">
                                                    <label for="openingStock_add"> {{ __('Add/Edit Opening Stock') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_import') ? 'CHECKED' : '' }} name="product_import" id="product_import" class="products product_all">
                                                    <label for="product_import">{{ __('Import Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_expired_list') ? 'CHECKED' : '' }} name="product_expired_list" id="product_expired_list" class="products product_all">
                                                    <label for="product_expired_list">{{ __('Expired Product List') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('generate_barcode') ? 'CHECKED' : '' }} name="generate_barcode" id="generate_barcode" class="products product_all">
                                                    <label for="generate_barcode">{{ __('Generate Barcode') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="product_category" autocomplete="off">
                                                        <strong>{{ __('Categories') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_category_index') ? 'CHECKED' : '' }} name="product_category_index" id="product_category_index" class="products product_category">
                                                    <label for="product_category_index">{{ __('View All Category') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_category_add') ? 'CHECKED' : '' }} name="product_category_add" id="product_category_add" class="products product_category">
                                                    <label for="product_category_add">{{ __('Add Category') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_category_edit') ? 'CHECKED' : '' }} name="product_category_edit" id="product_category_edit" class="products product_category">
                                                    <label for="product_category_edit">{{ __('Edit Category') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_category_delete') ? 'CHECKED' : '' }} name="product_category_delete" id="product_category_delete" class="products product_category">
                                                    <label for="product_category_delete">{{ __('Delete Category') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="product_brand" autocomplete="off">
                                                        <strong>{{ __('Brands') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_brand_index') ? 'CHECKED' : '' }} name="product_brand_index" id="product_brand_index" class="products product_brand">
                                                    <label for="product_brand_index">{{ __('View All Brand') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_brand_add') ? 'CHECKED' : '' }} name="product_brand_add" id="product_brand_add" class="products product_brand">
                                                    <label for="product_brand_add">{{ __('Add Brand') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_brand_edit') ? 'CHECKED' : '' }} name="product_brand_edit" id="product_brand_edit" class="products product_brand">
                                                    <label for="product_brand_edit">{{ __('Edit Brand') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_brand_delete') ? 'CHECKED' : '' }} name="product_brand_delete" id="product_brand_delete" class="products product_brand">
                                                    <label for="product_brand_delete">{{ __('Delete Brand') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="product_unit" autocomplete="off">
                                                        <strong>{{ __('Unit') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_unit_index') ? 'CHECKED' : '' }} name="product_unit_index" id="product_unit_index" class="products product_unit">
                                                    <label for="product_unit_index">{{ __('View All Unit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_unit_add') ? 'CHECKED' : '' }} name="product_unit_add" id="product_unit_add" class="products product_unit">
                                                    <label for="product_unit_add">{{ __('Add Unit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_unit_edit') ? 'CHECKED' : '' }} name="product_unit_edit" id="product_unit_edit" class="products product_unit">
                                                    <label for="product_unit_edit">{{ __('Edit Unit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_unit_delete') ? 'CHECKED' : '' }} name="product_unit_delete" id="product_unit_delete" class="products product_unit">
                                                    <label for="product_unit_delete">{{ __('Delete Unit') }}</label>
                                                </p>
                                            </div>
                                        </div>

                                        <hr class="mt-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="product_variant" autocomplete="off">
                                                        <strong>{{ __('Bulk Variant') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_variant_index') ? 'CHECKED' : '' }} name="product_variant_index" id="product_variant_index" class="products product_variant">
                                                    <label for="product_variant_index">{{ __('View All Variant') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_variant_add') ? 'CHECKED' : '' }} name="product_variant_add" id="product_variant_add" class="products product_variant">
                                                    <label for="product_variant_add">{{ __('Add Variant') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_variant_edit') ? 'CHECKED' : '' }} name="product_variant_edit" id="product_variant_edit" class="products product_variant">
                                                    <label for="product_variant_edit">{{ __('Edit Variant') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_variant_delete') ? 'CHECKED' : '' }} name="product_variant_delete" id="product_variant_delete" class="products product_variant">
                                                    <label for="product_variant_delete">{{ __('Delete Variant') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="product_warranties" autocomplete="off">
                                                        <strong>{{ __('Warranties') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_warranty_index') ? 'CHECKED' : '' }} name="product_warranty_index" id="product_warranty_index" class="products product_warranties">
                                                    <label for="product_warranty_index">{{ __('View All Warranty') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_warranty_add') ? 'CHECKED' : '' }} name="product_warranty_add" id="product_warranty_add" class="products product_warranties">
                                                    <label for="product_warranty_add">{{ __('Add Warranty') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_warranty_edit') ? 'CHECKED' : '' }} name="product_warranty_edit" id="product_warranty_edit" class="products product_warranties">
                                                    <label for="product_warranty_edit">{{ __('Edit Warranty') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_warranty_delete') ? 'CHECKED' : '' }} name="product_warranty_delete" id="product_warranty_delete" class="products product_warranties">
                                                    <label for="product_warranty_delete">{{ __('Delete Warranty') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="product_report" autocomplete="off">
                                                        <strong>{{ __('Reports') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('stock_report') ? 'CHECKED' : '' }} name="stock_report" id="stock_report" class="products product_report">
                                                    <label for="stock_report">{{ __('Stock Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('stock_in_out_report') ? 'CHECKED' : '' }} name="stock_in_out_report" id="stock_in_out_report" class="products product_report">
                                                    <label for="stock_in_out_report"> {{ __('Stock In-Out Report') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="product_selling_price_group_index" autocomplete="off">
                                                        <strong>{{ __('Selling Price Group') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('selling_price_group_index') ? 'CHECKED' : '' }} name="selling_price_group_index" id="selling_price_group_index" class="products product_selling_price_group_index">
                                                    <label for="selling_price_group_index">{{ __('View All Selling Price Group') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('selling_price_group_add') ? 'CHECKED' : '' }} name="selling_price_group_add" id="selling_price_group_add" class="products product_selling_price_group_index">
                                                    <label for="selling_price_group_add">{{ __('Selling Price Group Add') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('selling_price_group_edit') ? 'CHECKED' : '' }} name="selling_price_group_edit" id="selling_price_group_edit" class="products selling_price_group_edit">
                                                    <label for="selling_price_group_edit">{{ __('Selling Price Group Edit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('selling_price_group_delete') ? 'CHECKED' : '' }} name="selling_price_group_delete" id="selling_price_group_delete" class="products selling_price_group_delete">
                                                    <label for="selling_price_group_delete">{{ __('Selling Price Group Delete') }}</label>
                                                </p>
                                            </div>
                                        </div>

                                        <hr class="mt-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input id="select_all" type="checkbox" class="products" data-target="stock_issues" autocomplete="off">
                                                        <strong>{{ __('Stock Issues') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" @checked($role->hasPermissionTo('stock_issues_index')) name="stock_issues_index" id="stock_issues_index" class="stock_issues products">
                                                    <label for="stock_issues_index">{{ __('Stock Issue List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" @checked($role->hasPermissionTo('stock_issues_products_index')) name="stock_issues_products_index" id="stock_issues_products_index" class="stock_issues products">
                                                    <label for="stock_issues_products_index">{{ __('Stock Issued Products List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" @checked($role->hasPermissionTo('stock_issues_add')) name="stock_issues_add" id="stock_issues_add" class="stock_issues products">
                                                    <label for="stock_issues_add"> {{ __('Stock Issue Add') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" @checked($role->hasPermissionTo('stock_issues_edit')) name="stock_issues_edit" id="stock_issues_edit" class="stock_issues products">
                                                    <label for="stock_issues_edit">{{ __('Stock Issue Edit') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" @checked($role->hasPermissionTo('stock_issues_delete')) name="stock_issues_delete" id="stock_issues_delete" class="stock_issues products">
                                                    <label for="stock_issues_delete"> {{ __('Stock Issue Delete') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="purchase" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#purchase_permission" aria-expanded="false">
                                        {{ __('Purchases Permissions') }}
                                    </a>
                                </div>
                                <div id="purchase_permission" class="collapse" data-bs-parent="#purchase_permission" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="purchase" id="select_all" data-target="purchase_all" autocomplete="off">
                                                        <strong>{{ __('Purchases') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_all') ? 'CHECKED' : '' }} name="purchase_all" id="purchase_all" class="purchase purchase_all">
                                                    <label for="purchase_all">{{ __('View All Purchase') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchased_product_list') ? 'CHECKED' : '' }} name="purchased_product_list" id="purchased_product_list" class="purchase purchase_all">
                                                    <label for="purchase_all">{{ __('Purchased_product_list') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_add') ? 'CHECKED' : '' }} name="purchase_add" id="purchase_add" class="purchase purchase_all">
                                                    <label for="purchase_add">{{ __('Add Purchase') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_edit') ? 'CHECKED' : '' }} name="purchase_edit" id="purchase_edit" class="purchase purchase_all">
                                                    <label for="purchase_edit">{{ __('Edit Purchase') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_delete') ? 'CHECKED' : '' }} name="purchase_delete" id="purchase_delete" class="purchase purchase_all">
                                                    <label for="purchase_delete">{{ __('Delete purchase') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="purchase" id="select_all" data-target="purchase_order" autocomplete="off">
                                                        <strong>{{ __('Purchase Order') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_order_index') ? 'CHECKED' : '' }} name="purchase_order_index" id="purchase_order_index" class="purchase purchase_order">
                                                    <label for="purchase_order_index">{{ __('View All Purchase Order') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_order_add') ? 'CHECKED' : '' }} name="purchase_order_add" id="purchase_order_add" class="purchase purchase_order">
                                                    <label for="purchase_order_add">{{ __('Purchase Order Add') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_order_edit') ? 'CHECKED' : '' }} name="purchase_order_edit" id="purchase_order_edit" class="purchase purchase_order">
                                                    <label for="purchase_order_edit">{{ __('Purchase Order Edit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_order_to_invoice') ? 'CHECKED' : '' }} name="purchase_order_to_invoice" id="purchase_order_to_invoice" class="purchase purchase_order">
                                                    <label for="purchase_order_to_invoice">{{ __('P/o To Purchase Invoice') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_order_delete') ? 'CHECKED' : '' }} name="purchase_order_delete" id="purchase_order_delete" class="purchase purchase_order">
                                                    <label for="purchase_order_delete">{{ __('Purchase Order Delete') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="purchase" id="select_all" data-target="purchase_return" autocomplete="off">
                                                        <strong>{{ __('Purchase Return') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_return_index') ? 'CHECKED' : '' }} name="purchase_return_index" id="purchase_return_index" class="purchase purchase_return">
                                                    <label for="purchase_return_index">{{ __('View All Purchase Return') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_return_add') ? 'CHECKED' : '' }} name="purchase_return_add" id="purchase_return_add" class="purchase purchase_return">
                                                    <label for="purchase_return_add">{{ __('Purchase Return Add') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_return_edit') ? 'CHECKED' : '' }} name="purchase_return_edit" id="purchase_return_edit" class="purchase purchase_return">
                                                    <label for="purchase_return_edit">{{ __('Purchase Return Edit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_return_delete') ? 'CHECKED' : '' }} name="purchase_return_delete" id="purchase_return_delete" class="purchase purchase_return">
                                                    <label for="purchase_return_delete">{{ __('Purchase Return Delete') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="purchase" id="select_all" data-target="purchase_report" autocomplete="off">
                                                        <strong>{{ __('Purchase Reports') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_report') ? 'CHECKED' : '' }} name="purchase_report" id="purchase_report" class="purchase purchase_report">
                                                    <label for="purchase_report">{{ __('Purchase Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_order_report') ? 'CHECKED' : '' }} name="purchase_order_report" id="purchase_order_report" class="purchase purchase_report">
                                                    <label for="purchase_order_report">{{ __('Purchase Order Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_ordered_product_report') ? 'CHECKED' : '' }} name="purchase_ordered_product_report" id="purchase_ordered_product_report" class="purchase purchase_report">
                                                    <label for="purchase_ordered_product_report">{{ __('Purchase Ordered Product Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_return_report') ? 'CHECKED' : '' }} name="purchase_return_report" id="purchase_return_report" class="purchase purchase_report">
                                                    <label for="purchase_return_report">{{ __('Purchase Return Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_returned_product_report') ? 'CHECKED' : '' }} name="purchase_returned_product_report" id="purchase_returned_product_report" class="purchase purchase_report">
                                                    <label for="purchase_returned_product_report">{{ __('Purchase Returned Products Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_sale_report') ? 'CHECKED' : '' }} name="purchase_sale_report" id="purchase_sale_report" class="purchase purchase_report">
                                                    <label for="purchase_sale_report">{{ __('Purchase & Sale Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('product_purchase_report') ? 'CHECKED' : '' }} name="product_purchase_report" id="product_purchase_report" class="purchase purchase_report">
                                                    <label for="product_purchase_report">{{ __('Product Purchase Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('purchase_payment_report') ? 'CHECKED' : '' }} name="purchase_payment_report" id="purchase_payment_report" class="purchase purchase_report">
                                                    <label for="purchase_payment_report"> {{ __(' Purchase Payment Report') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="adjustment" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#adjustment_permission" aria-expanded="false">
                                        {{ __('Stock Adjustment Permissions') }}
                                    </a>
                                </div>
                                <div id="adjustment_permission" class="collapse" data-bs-parent="#adjustment_permission" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="adjustment" id="select_all" data-target="adjustment_all" autocomplete="off">
                                                        <strong>{{ __('Stock Adjustments') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('stock_adjustment_all') ? 'CHECKED' : '' }} name="stock_adjustment_all" id="stock_adjustment_all" class="adjustment adjustment_all">
                                                    <label for="stock_adjustment_all">{{ __('Stock Adjustment List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('stock_adjustment_add') ? 'CHECKED' : '' }} name="stock_adjustment_add" id="stock_adjustment_add" class="adjustment adjustment_all">
                                                    <label for="stock_adjustment_add">{{ __('Stock Adjustment Add') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('stock_adjustment_delete') ? 'CHECKED' : '' }} name="stock_adjustment_delete" id="stock_adjustment_delete" class="adjustment adjustment_all">
                                                    <label for="stock_adjustment_delete">{{ __('Stock Adjustment Delete') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="adjustment" id="select_all" data-target="adjustment_all" autocomplete="off">
                                                        <strong>{{ __('Stock Adjustment Reports') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('stock_adjustment_report') ? 'CHECKED' : '' }} name="stock_adjustment_report" id="stock_adjustment_report" class="adjustment adjustment_all">
                                                    <label for="stock_adjustment_report">{{ __('Stock Adjustment Report') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('stock_adjustment_product_report') ? 'CHECKED' : '' }} name="stock_adjustment_product_report" id="stock_adjustment_product_report" class="adjustment adjustment_all">
                                                    <label for="stock_adjustment_product_report">{{ __('Stock Adjusted Products Report') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="sales" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#sales_permission" aria-expanded="false">
                                        {{ __('Sales Permissions') }}
                                    </a>
                                </div>
                                <div id="sales_permission" class="collapse" data-bs-parent="#sales_permission" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="sales" id="select_all" data-target="sale_all" autocomplete="off">
                                                        <strong>{{ __('Sales') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('create_add_sale') ? 'CHECKED' : '' }} name="create_add_sale" id="create_add_sale" class="sales sale_all">
                                                    <label for="create_add_sale">{{ __('Create Add Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('view_add_sale') ? 'CHECKED' : '' }} name="view_add_sale" id="view_add_sale" class="sales sale_all">
                                                    <label for="view_add_sale">{{ __('Manage Add Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('edit_add_sale') ? 'CHECKED' : '' }} name="edit_add_sale" id="edit_add_sale" class="sales sale_all">
                                                    <label for="edit_add_sale">{{ __('Edit Add Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('delete_add_sale') ? 'CHECKED' : '' }} name="delete_add_sale" id="delete_add_sale" class="sales sale_all">
                                                    <label for="delete_add_sale"> {{ __('Delete Add Sale') }}</label>
                                                </p>
                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('sale_draft') ? 'CHECKED' : '' }} name="sale_draft" id="sale_draft" class="sales sale_all">
                                                        <label for="sale_draft">{{ __('List Draft') }}</label>
                                                    </p> --}}
                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('sale_quotation') ? 'CHECKED' : '' }} name="sale_quotation" id="sale_quotation" class="sales sale_all">
                                                        <label for="sale_quotation"> {{ __('List Quotations') }}</label>
                                                    </p> --}}
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sold_product_list') ? 'CHECKED' : '' }} name="sold_product_list" id="sold_product_list" class="sales sale_all">
                                                    <label for="sold_product_list"> {{ __('Sold Product List') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="checkbox_input_wrap mt-4">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('edit_price_sale_screen') ? 'CHECKED' : '' }} name="edit_price_sale_screen" id="edit_price_sale_screen" class="sales sale_all">
                                                    <label for="edit_price_sale_screen"> {{ __('Edit Product Price from Sales Screen') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('edit_discount_sale_screen') ? 'CHECKED' : '' }} name="edit_discount_sale_screen" id="edit_discount_sale_screen" class="sales sale_all">
                                                    <label for="edit_discount_sale_screen">{{ __('Edit Product Discount in Sale Scr') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('shipment_access') ? 'CHECKED' : '' }} name="shipment_access" id="shipment_access" class="sales sale_all">
                                                    <label for="shipment_access"> {{ __('Access Shipments') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('view_product_cost_is_sale_screed') ? 'CHECKED' : '' }} name="view_product_cost_is_sale_screed" id="view_product_cost_is_sale_screed" class="sales sale_all">
                                                    <label for="view_product_cost_is_sale_screed"> {{ __('View Product Cost In Sale Screen') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('view_own_sale') ? 'CHECKED' : '' }} name="view_own_sale" id="view_own_sale" class="sales sale_all">
                                                    <label for="view_own_sale">{{ __('View only own Add/POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('discounts') ? 'CHECKED' : '' }} name="discounts" id="discounts" class="sales sale_all">
                                                    <label for="discounts"> {{ __('Manage Discount') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="sales" id="select_all" data-target="sale_quotations" autocomplete="off">
                                                        <strong>{{ __('Quotations') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_quotations_index') ? 'CHECKED' : '' }} name="sale_quotations_index" id="sale_quotations_index" class="sales sale_quotations">
                                                    <label for="sale_quotations_index">{{ __('Quotation List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_quotations_only_own') ? 'CHECKED' : '' }} name="sale_quotations_only_own" id="sale_quotations_only_own" class="sales sale_quotations">
                                                    <label for="sale_quotations_only_own">{{ __('Quotation List Only Created By Own') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_quotations_edit') ? 'CHECKED' : '' }} name="sale_quotations_edit" id="sale_quotations_edit" class="sales sale_quotations">
                                                    <label for="sale_quotations_edit">{{ __('Quotation Edit') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_quotations_change_status') ? 'CHECKED' : '' }} name="sale_quotations_change_status" id="sale_quotations_change_status" class="sales sale_quotations">
                                                    <label for="sale_quotations_change_status">{{ __('Quotation Change Status') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_quotations_delete') ? 'CHECKED' : '' }} name="sale_quotations_delete" id="sale_quotations_delete" class="sales sale_quotations">
                                                    <label for="sale_quotations_delete">{{ __('Quotation Delete') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="sales" id="select_all" data-target="sale_drafts" autocomplete="off">
                                                        <strong>{{ __('Drafts') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_drafts_index') ? 'CHECKED' : '' }} name="sale_drafts_index" id="sale_drafts_index" class="sales sale_drafts">
                                                    <label for="sale_drafts_index">{{ __('Draft List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_drafts_only_own') ? 'CHECKED' : '' }} name="sale_drafts_only_own" id="sale_drafts_only_own" class="sales sale_drafts">
                                                    <label for="sale_drafts_only_own">{{ __('Draft List Only Created By Own') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_drafts_edit') ? 'CHECKED' : '' }} name="sale_drafts_edit" id="sale_drafts_edit" class="sales sale_drafts">
                                                    <label for="sale_drafts_edit">{{ __('Draft Edit') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_drafts_delete') ? 'CHECKED' : '' }} name="sale_drafts_delete" id="sale_drafts_delete" class="sales sale_drafts">
                                                    <label for="sale_drafts_delete">{{ __('Draft Delete') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                        <hr class="mt-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="sales" id="select_all" data-target="sales_orders" autocomplete="off">
                                                        <strong>{{ __('Sales Orders') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_orders_index') ? 'CHECKED' : '' }} name="sales_orders_index" id="sales_orders_index" class="sales sales_orders">
                                                    <label for="sales_orders_index">{{ __('Sales Order List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_orders_only_own') ? 'CHECKED' : '' }} name="sales_orders_only_own" id="sales_orders_only_own" class="sales sales_orders">
                                                    <label for="sales_orders_only_own">{{ __('Sales Order List Only Created By Own') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_orders_edit') ? 'CHECKED' : '' }} name="sales_orders_edit" id="sales_orders_edit" class="sales sales_orders">
                                                    <label for="sales_orders_edit">{{ __('Sales Order Edit') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_orders_delete') ? 'CHECKED' : '' }} name="sales_orders_delete" id="sales_orders_delete" class="sales sales_orders">
                                                    <label for="sales_orders_delete">{{ __('Sales Order Delete') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_order_to_invoice') ? 'CHECKED' : '' }} name="sales_order_to_invoice" id="sales_order_to_invoice" class="sales sales_orders">
                                                    <label for="sales_order_to_invoice">{{ __('Sales Order To Invoice') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="sales" id="select_all" data-target="pos_sale_all" autocomplete="off">
                                                        <strong>{{ __('POS Sales') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('pos_all') ? 'CHECKED' : '' }} name="pos_all" id="pos_all" class="sales pos_sale_all">
                                                    <label for="pos_all">{{ __('Manage POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('pos_add') ? 'CHECKED' : '' }} name="pos_add" id="pos_add" class="sales pos_sale_all">
                                                    <label for="pos_add">{{ __('Add POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('pos_edit') ? 'CHECKED' : '' }} name="pos_edit" id="pos_edit" class="sales pos_sale_all">
                                                    <label for="pos_edit">{{ __('Edit POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('pos_delete') ? 'CHECKED' : '' }} name="pos_delete" id="pos_delete" class="sales pos_sale_all">
                                                    <label for="pos_delete">{{ __('Delete POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('edit_price_pos_screen') ? 'CHECKED' : '' }} name="edit_price_pos_screen" id="edit_price_pos_screen" class="sales pos_sale_all">
                                                    <label for="edit_price_pos_screen"> {{ __('Edit Product Price From POS Screen') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('edit_discount_pos_screen') ? 'CHECKED' : '' }} name="edit_discount_pos_screen" id="edit_discount_pos_screen" class="sales pos_sale_all">
                                                    <label for="edit_discount_pos_screen">{{ __('Edit Product Discount From POS Screen') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="sales" id="select_all" data-target="sales_reurn" autocomplete="off">
                                                        <strong>{{ __('Sales Return') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_return_index') ? 'CHECKED' : '' }} name="sales_return_index" id="sales_return_index" class="sales sales_reurn">
                                                    <label for="sales_return_index">{{ __('Sales Return List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_return_only_own') ? 'CHECKED' : '' }} name="sales_return_only_own" id="sales_return_only_own" class="sales sales_reurn">
                                                    <label for="sales_return_only_own">{{ __('Sales Return List Only Create By Own') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('create_sales_return') ? 'CHECKED' : '' }} name="create_sales_return" id="create_sales_return" class="sales sales_reurn">
                                                    <label for="create_sales_return">{{ __('Create Sales Return') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('edit_sales_return') ? 'CHECKED' : '' }} name="edit_sales_return" id="edit_sales_return" class="sales sales_reurn">
                                                    <label for="edit_sales_return">{{ __('Edit Sales Return') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('delete_sales_return') ? 'CHECKED' : '' }} name="delete_sales_return" id="delete_sales_return" class="sales sales_reurn">
                                                    <label for="delete_sales_return">{{ __('Delete Sales Return') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="sales" id="select_all" data-target="sales_report" autocomplete="off">
                                                        <strong>{{ __('Sales Reports') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_report') ? 'CHECKED' : '' }} name="sales_report" id="sales_report" class="sales sales_report">
                                                    <label for="sales_report"> {{ __('Sales Report') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_return_report') ? 'CHECKED' : '' }} name="sales_return_report" id="sales_return_report" class="sales sales_report">
                                                    <label for="sales_return_report">{{ __('Sales Return Report') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sold_product_report') ? 'CHECKED' : '' }} name="sold_product_report" id="sold_product_report" class="sales sales_report">
                                                    <label for="sold_product_report">{{ __('Sold Products Report') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_order_report') ? 'CHECKED' : '' }} name="sales_order_report" id="sales_order_report" class="sales sales_report">
                                                    <label for="sales_order_report">{{ __('Sales Order Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_ordered_products_report') ? 'CHECKED' : '' }} name="sales_ordered_products_report" id="sales_ordered_products_report" class="sales sales_report">
                                                    <label for="sales_ordered_products_report">{{ __('Sales Ordered Products Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sales_returned_products_report') ? 'CHECKED' : '' }} name="sales_returned_products_report" id="sales_returned_products_report" class="sales sales_report">
                                                    <label for="sales_returned_products_report">{{ __('Sales Returned Products Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('received_against_sales_report') ? 'CHECKED' : '' }} name="received_against_sales_report" id="received_against_sales_report" class="sales sales_report">
                                                    <label for="received_against_sales_report">{{ __('Received Against Sales Report') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('cash_register_report') ? 'CHECKED' : '' }} name="cash_register_report" id="cash_register_report" class="sales sales_report">
                                                    <label for="cash_register_report">{{ __('Cash Register Reports') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('sale_representative_report') ? 'CHECKED' : '' }} name="sale_representative_report" id="sale_representative_report" class="sales sales_report">
                                                    <label for="sale_representative_report">{{ __('Sales Representative Report') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="cash_register" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#cash_register_permission" aria-expanded="false">
                                        {{ __('Cash Register Permissions') }}
                                    </a>
                                </div>
                                <div id="cash_register_permission" class="collapse" data-bs-parent="#cash_register_permission" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="cash_register" id="select_all" data-target="cash_register_all" autocomplete="off">
                                                        <strong>{{ __('Cash Register') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('register_view') ? 'CHECKED' : '' }} name="register_view" id="register_view" class="cash_register cash_register_all">
                                                    <label for="register_view">{{ __('View Cash Registers List') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('register_close') ? 'CHECKED' : '' }} name="register_close" id="register_close" class="cash_register cash_register_all">
                                                    <label for="register_close">{{ __('Close Cash Register') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('another_register_close') ? 'CHECKED' : '' }} name="another_register_close" id="another_register_close" class="cash_register cash_register_all">
                                                    <label for="another_register_close">{{ __('Close Another Cash Register') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="transfer_stocks" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#transfer_stocks_permission" aria-expanded="false">
                                        {{ __('Transfer Stock Permissions') }}
                                    </a>
                                </div>
                                <div id="transfer_stocks_permission" class="collapse" data-bs-parent="#transfer_stocks_permission" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="transfer_stock" id="select_all" data-target="transfer_stocks" autocomplete="off">
                                                        <strong>{{ __('Transfer Stock') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('transfer_stock_index') ? 'CHECKED' : '' }} name="transfer_stock_index" id="transfer_stock_index" class="transfer_stock transfer_stocks">
                                                    <label for="transfer_stock_index">{{ __('Transfer Stock List') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('transfer_stock_create') ? 'CHECKED' : '' }} name="transfer_stock_create" id="transfer_stock_create" class="transfer_stock transfer_stocks">
                                                    <label for="transfer_stock_create">{{ __('Transfer Stock Add') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('transfer_stock_edit') ? 'CHECKED' : '' }} name="transfer_stock_edit" id="transfer_stock_edit" class="transfer_stock transfer_stocks">
                                                    <label for="transfer_stock_edit">{{ __('Transfer Stock Edit') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('transfer_stock_delete') ? 'CHECKED' : '' }} name="transfer_stock_delete" id="transfer_stock_delete" class="transfer_stock transfer_stocks">
                                                    <label for="transfer_stock_delete">{{ __('Transfer Stock Delete') }}</label>
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="transfer_stock_receive" id="select_all" data-target="transfer_stock_receive" autocomplete="off">
                                                        <strong>{{ __('Receive Transferred Stock') }}</strong>
                                                    </label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('transfer_stock_receive_from_warehouse') ? 'CHECKED' : '' }} name="transfer_stock_receive_from_warehouse" id="transfer_stock_receive_from_warehouse" class="transfer_stock_receive transfer_stocks">
                                                    <label for="transfer_stock_receive_from_warehouse">{{ __('Receive From Warehouse') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('transfer_stock_receive_from_branch') ? 'CHECKED' : '' }} name="transfer_stock_receive_from_branch" id="transfer_stock_receive_from_branch" class="transfer_stock_receive transfer_stocks">
                                                    <label for="transfer_stock_receive_from_branch">{{ __('Receive From Store/Company') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="setup" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#setup_permission" aria-expanded="false">
                                        {{ __('Setup Permissions') }}
                                    </a>
                                </div>
                                <div id="setup_permission" class="collapse" data-bs-parent="#setup_permission" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="row">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="setup" id="select_all" data-target="general_settings" autocomplete="off">
                                                            <strong>{{ __('General Settings') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('dashboard_settings') ? 'CHECKED' : '' }} type="checkbox" name="dashboard_settings" id="dashboard_settings" class="general_settings setup">
                                                        <label for="dashboard_settings">{{ __('Dashboard Settings') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('product_settings') ? 'CHECKED' : '' }} type="checkbox" name="product_settings" id="product_settings" class="general_settings setup">
                                                        <label for="product_settings">{{ __('Product Settings') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('purchase_settings') ? 'CHECKED' : '' }} type="checkbox" name="purchase_settings" id="purchase_settings" class="general_settings setup">
                                                        <label for="purchase_settings">{{ __('Purchase Settings') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('manufacturing_settings') ? 'CHECKED' : '' }} type="checkbox" name="manufacturing_settings" id="manufacturing_settings" class="general_settings setup">
                                                        <label for="manufacturing_settings">{{ __('Manufacturing Settings') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('add_sale_settings') ? 'CHECKED' : '' }} type="checkbox" name="add_sale_settings" id="add_sale_settings" class="general_settings setup">
                                                        <label for="add_sale_settings">{{ __('Add Sale Settings') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('pos_sale_settings') ? 'CHECKED' : '' }} type="checkbox" name="pos_sale_settings" id="pos_sale_settings" class="general_settings setup">
                                                        <label for="pos_sale_settings">{{ __('POS Sale Settings') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('prefix_settings') ? 'CHECKED' : '' }} type="checkbox" name="prefix_settings" id="prefix_settings" class="general_settings setup">
                                                        <label for="prefix_settings"> {{ __('Prefix Setting') }}</label>
                                                    </p>
                                                </div>

                                                <div class="col-md-6">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('invoice_layout_settings') ? 'CHECKED' : '' }} type="checkbox" name="invoice_layout_settings" id="invoice_layout_settings" class="general_settings setup">
                                                        <label for="invoice_layout_settings"> {{ __('Invoice Layout Setting') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('print_settings') ? 'CHECKED' : '' }} type="checkbox" name="print_settings" id="print_settings" class="general_settings setup">
                                                        <label for="print_settings"> {{ __('Print Setting') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('system_settings') ? 'CHECKED' : '' }} type="checkbox" name="system_settings" id="system_settings" class="general_settings setup">
                                                        <label for="system_settings"> {{ __('System Setting') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('reward_point_settings') ? 'CHECKED' : '' }} type="checkbox" name="reward_point_settings" id="reward_point_settings" class="general_settings setup">
                                                        <label for="reward_point_settings"> {{ __('Reward Point Setting') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('module_settings') ? 'CHECKED' : '' }} type="checkbox" name="module_settings" id="module_settings" class="general_settings setup">
                                                        <label for="module_settings"> {{ __('Module Setting') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('send_email_settings') ? 'CHECKED' : '' }} type="checkbox" name="send_email_settings" id="send_email_settings" class="general_settings setup">
                                                        <label for="send_email_settings"> {{ __('Send Email Setting') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input {{ $role->hasPermissionTo('send_sms_settings') ? 'CHECKED' : '' }} type="checkbox" name="send_sms_settings" id="send_sms_settings" class="general_settings setup">
                                                        <label for="send_sms_settings"> {{ __('Send SMS Setting') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="setup" id="select_all" data-target="warehouses" autocomplete="off">
                                                    <strong>{{ __('Warehouses') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('warehouses_index') ? 'CHECKED' : '' }} type="checkbox" name="warehouses_index" id="warehouses_index" class="warehouses setup">
                                                <label for="warehouses_index">{{ __('Warehouse List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('warehouses_add') ? 'CHECKED' : '' }} type="checkbox" name="warehouses_add" id="warehouses_add" class="warehouses setup">
                                                <label for="warehouses_add">{{ __('Warehouse Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('warehouses_edit') ? 'CHECKED' : '' }} type="checkbox" name="warehouses_edit" id="warehouses_edit" class="warehouses setup">
                                                <label for="warehouses_edit">{{ __('Warehouse Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('warehouses_delete') ? 'CHECKED' : '' }} type="checkbox" name="warehouses_delete" id="warehouses_delete" class="warehouses setup">
                                                <label for="warehouses_delete">{{ __('Warehouse Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="setup" id="select_all" data-target="shops" autocomplete="off">
                                                    <strong>{{ __('Stores') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('branches_index') ? 'CHECKED' : '' }} type="checkbox" name="branches_index" id="branches_index" class="shops setup">
                                                <label for="branches_index">{{ __('Store List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('branches_create') ? 'CHECKED' : '' }} type="checkbox" name="branches_create" id="branches_create" class="shops setup">
                                                <label for="branches_create">{{ __('Store Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('branches_edit') ? 'CHECKED' : '' }} type="checkbox" name="branches_edit" id="branches_edit" class="shops setup">
                                                <label for="branches_edit">{{ __('Store Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('branches_delete') ? 'CHECKED' : '' }} type="checkbox" name="branches_delete" id="branches_delete" class="shops setup">
                                                <label for="branches_delete">{{ __('Store Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="setup" id="select_all" data-target="payment_methods" autocomplete="off">
                                                    <strong>{{ __('Payment Methods') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('payment_methods_index') ? 'CHECKED' : '' }} type="checkbox" name="payment_methods_index" id="payment_methods_index" class="payment_methods setup">
                                                <label for="payment_methods_index">{{ __('Payment Method List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('payment_methods_add') ? 'CHECKED' : '' }} type="checkbox" name="payment_methods_add" id="payment_methods_add" class="payment_methods setup">
                                                <label for="payment_methods_add">{{ __('Payment Method Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('payment_methods_edit') ? 'CHECKED' : '' }} type="checkbox" name="payment_methods_edit" id="payment_methods_edit" class="payment_methods setup">
                                                <label for="payment_methods_edit">{{ __('Payment Method Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('payment_methods_delete') ? 'CHECKED' : '' }} type="checkbox" name="payment_methods_delete" id="payment_methods_delete" class="payment_methods setup">
                                                <label for="payment_methods_delete">{{ __('Payment Method Delete') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('payment_methods_settings') ? 'CHECKED' : '' }} type="checkbox" name="payment_methods_settings" id="payment_methods_settings" class="payment_methods setup">
                                                <label for="payment_methods_settings">{{ __('Payment Method Settings') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="setup" id="select_all" data-target="invoice_layouts" autocomplete="off">
                                                    <strong>{{ __('Invoice Layouts') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('invoice_layouts_index') ? 'CHECKED' : '' }} type="checkbox" name="invoice_layouts_index" id="invoice_layouts_index" class="invoice_layouts setup">
                                                <label for="invoice_layouts_index">{{ __('Invoice Layout List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('invoice_layouts_add') ? 'CHECKED' : '' }} type="checkbox" name="invoice_layouts_add" id="invoice_layouts_add" class="invoice_layouts setup">
                                                <label for="invoice_layouts_add">{{ __('Invoice Layout Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('invoice_layouts_edit') ? 'CHECKED' : '' }} type="checkbox" name="invoice_layouts_edit" id="invoice_layouts_edit" class="invoice_layouts setup">
                                                <label for="invoice_layouts_edit">{{ __('Invoice Layout Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('invoice_layouts_delete') ? 'CHECKED' : '' }} type="checkbox" name="invoice_layouts_delete" id="invoice_layouts_delete" class="invoice_layouts setup">
                                                <label for="invoice_layouts_delete">{{ __('Invoice Layout Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="setup" id="select_all" data-target="cash_counters" autocomplete="off">
                                                    <strong>{{ __('Cash Counters') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('cash_counters_index') ? 'CHECKED' : '' }} type="checkbox" name="cash_counters_index" id="cash_counters_index" class="cash_counters setup">
                                                <label for="cash_counters_index">{{ __('Cash Counter List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('cash_counters_add') ? 'CHECKED' : '' }} type="checkbox" name="cash_counters_add" id="cash_counters_add" class="cash_counters setup">
                                                <label for="cash_counters_add">{{ __('Cash Counter Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('cash_counters_edit') ? 'CHECKED' : '' }} type="checkbox" name="cash_counters_edit" id="cash_counters_edit" class="cash_counters setup">
                                                <label for="cash_counters_edit">{{ __('Cash Counter Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('cash_counters_delete') ? 'CHECKED' : '' }} type="checkbox" name="cash_counters_delete" id="cash_counters_delete" class="cash_counters setup">
                                                <label for="cash_counters_delete">{{ __('Cash Counter Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="setup" id="select_all" data-target="currencies" autocomplete="off">
                                                    <strong>{{ __('Currencies') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('currencies_index') ? 'CHECKED' : '' }} name="currencies_index" id="currencies_index" class="currencies setup">
                                                <label for="currencies_index">{{ __('Currency List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('currencies_create') ? 'CHECKED' : '' }} name="currencies_create" id="currencies_create" class="currencies setup">
                                                <label for="currencies_create">{{ __('Currency Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('currencies_edit') ? 'CHECKED' : '' }} name="currencies_edit" id="currencies_edit" class="currencies setup">
                                                <label for="currencies_edit">{{ __('Currency Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('currencies_delete') ? 'CHECKED' : '' }} name="currencies_delete" id="currencies_delete" class="currencies setup">
                                                <label for="currencies_delete">{{ __('Currency Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-1">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="dashboard" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#dashboard_permission" aria-expanded="false">
                                    {{ __('Dashboard Permissions') }}
                                </a>
                            </div>
                            <div id="dashboard_permission" class="collapse" data-bs-parent="#dashboard_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="dashboard" id="select_all" data-target="dashboard_all" autocomplete="off">
                                                    <strong>{{ __('Dashboard') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('view_dashboard_data') ? 'CHECKED' : '' }} name="view_dashboard_data" id="view_dashboard_data" class="dashboard dashboard_all">
                                                <label for="view_dashboard_data">{{ __('View Dashboard Data') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-1">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="accounting" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#accounting_permission" aria-expanded="false">
                                    {{ __('Accounting Permission') }}
                                </a>
                            </div>
                            <div id="accounting_permission" class="collapse" data-bs-parent="#accounting_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="accounting" id="select_all" data-target="banks" autocomplete="off">
                                                    <strong>{{ __('Banks') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('banks_index') ? 'CHECKED' : '' }} name="banks_index" id="banks_index" class="accounting banks">
                                                <label for="banks_index">{{ __('Bank List') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('banks_create') ? 'CHECKED' : '' }} name="banks_create" id="banks_create" class="accounting banks">
                                                <label for="banks_create">{{ __('Bank Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('banks_edit') ? 'CHECKED' : '' }} name="banks_edit" id="banks_edit" class="accounting banks">
                                                <label for="banks_edit">{{ __('Bank Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('banks_delete') ? 'CHECKED' : '' }} name="banks_edit" id="banks_delete" class="accounting banks">
                                                <label for="banks_delete">{{ __('Bank Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="accounting" id="select_all" data-target="account_groups" autocomplete="off">
                                                    <strong>{{ __('Account Groups') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('account_groups_index') ? 'CHECKED' : '' }} name="account_groups_index" id="account_groups_index" class="accounting account_groups">
                                                <label for="account_groups_index">{{ __('Account Group List') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('account_groups_create') ? 'CHECKED' : '' }} name="account_groups_create" id="account_groups_create" class="accounting account_groups">
                                                <label for="account_groups_create">{{ __('Account Group Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('account_groups_edit') ? 'CHECKED' : '' }} name="account_groups_edit" id="account_groups_edit" class="accounting account_groups">
                                                <label for="account_groups_edit">{{ __('Account Group Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('account_groups_delete') ? 'CHECKED' : '' }} name="account_groups_delete" id="account_groups_delete" class="accounting account_groups">
                                                <label for="account_groups_delete">{{ __('Account Group Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="accounting" id="select_all" data-target="accounts" autocomplete="off">
                                                    <strong>{{ __('Accounts') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('accounts_index') ? 'CHECKED' : '' }} name="accounts_index" id="accounts_index" class="accounting accounts">
                                                <label for="accounts_index">{{ __('Account List') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('accounts_create') ? 'CHECKED' : '' }} name="accounts_create" id="accounts_create" class="accounting accounts">
                                                <label for="accounts_create">{{ __('Account Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('accounts_edit') ? 'CHECKED' : '' }} name="accounts_edit" id="accounts_edit" class="accounting accounts">
                                                <label for="accounts_edit">{{ __('Account Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('accounts_delete') ? 'CHECKED' : '' }} name="accounts_delete" id="accounts_delete" class="accounting accounts">
                                                <label for="accounts_delete">{{ __('Account Delete') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('accounts_bank_account_create') ? 'CHECKED' : '' }} type="checkbox" name="accounts_bank_account_create" id="accounts_bank_account_create" class="accounting accounts">
                                                <label for="accounts_bank_account_create">{{ __('Add Bank Account') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('accounts_ledger') ? 'CHECKED' : '' }} name="accounts_ledger" id="accounts_ledger" class="accounting accounts">
                                                <label for="accounts_ledger">{{ __('Account Ledger') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('capital_accounts_index') ? 'CHECKED' : '' }} name="capital_accounts_index" id="capital_accounts_index" class="accounting accounts">
                                                <label for="capital_accounts_index">{{ __('Capital Accounts') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('duties_and_taxes_index') ? 'CHECKED' : '' }} name="duties_and_taxes_index" id="duties_and_taxes_index" class="accounting accounts">
                                                <label for="duties_and_taxes_index">{{ __('Duties And Taxes') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="accounting" id="select_all" data-target="receipts" autocomplete="off">
                                                    <strong>{{ __('Receipts') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('receipts_index') ? 'CHECKED' : '' }} name="receipts_index" id="receipts_index" class="accounting receipts">
                                                <label for="receipts_index">{{ __('Receipt List') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('receipts_create') ? 'CHECKED' : '' }} name="receipts_create" id="receipts_create" class="accounting receipts">
                                                <label for="receipts_create">{{ __('Receipt Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('receipts_edit') ? 'CHECKED' : '' }} name="receipts_edit" id="receipts_edit" class="accounting receipts">
                                                <label for="receipts_edit">{{ __('Receipt Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('receipts_delete') ? 'CHECKED' : '' }} name="receipts_delete" id="receipts_delete" class="accounting receipts">
                                                <label for="receipts_delete">{{ __('Receipt Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="accounting" id="select_all" data-target="payments" autocomplete="off">
                                                    <strong>{{ __('Payments') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payments_index') ? 'CHECKED' : '' }} name="payments_index" id="receipts_index" class="accounting payments">
                                                <label for="payments_index">{{ __('Payment List') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payments_create') ? 'CHECKED' : '' }} name="payments_create" id="payments_create" class="accounting payments">
                                                <label for="payments_create">{{ __('Payment Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payments_edit') ? 'CHECKED' : '' }} name="payments_edit" id="payments_edit" class="accounting payments">
                                                <label for="payments_edit">{{ __('Payment Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payments_delete') ? 'CHECKED' : '' }} name="payments_delete" id="payments_delete" class="accounting payments">
                                                <label for="payments_delete">{{ __('Payment Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="accounting" id="select_all" data-target="expenses" autocomplete="off">
                                                    <strong>{{ __('Expenses') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('expenses_index') ? 'CHECKED' : '' }} name="expenses_index" id="expenses_index" class="accounting expenses">
                                                <label for="expenses_index">{{ __('Expense List') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('expenses_create') ? 'CHECKED' : '' }} name="expenses_create" id="expenses_create" class="accounting expenses">
                                                <label for="expenses_create">{{ __('Expense Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('expenses_edit') ? 'CHECKED' : '' }} name="expenses_edit" id="expenses_edit" class="accounting expenses">
                                                <label for="expenses_edit">{{ __('Expense Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('expenses_delete') ? 'CHECKED' : '' }} name="expenses_delete" id="expenses_delete" class="accounting expenses">
                                                <label for="expenses_delete">{{ __('Expense Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="accounting" id="select_all" data-target="contras" autocomplete="off">
                                                    <strong>{{ __('Contras') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('contras_index') ? 'CHECKED' : '' }} name="contras_index" id="contras_index" class="accounting contras">
                                                <label for="contras_index">{{ __('Contra List') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('contras_create') ? 'CHECKED' : '' }} name="contras_create" id="contras_create" class="accounting contras">
                                                <label for="contras_create">{{ __('Contra Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('contras_edit') ? 'CHECKED' : '' }} name="contras_edit" id="contras_edit" class="accounting contras">
                                                <label for="contras_edit">{{ __('Contra Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('contras_delete') ? 'CHECKED' : '' }} name="contras_delete" id="contras_delete" class="accounting contras">
                                                <label for="contras_delete">{{ __('Contra Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="accounting" id="select_all" data-target="account_reports" autocomplete="off">
                                                    <strong>{{ __('Accounts Reports') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('profit_loss') ? 'CHECKED' : '' }} name="profit_loss" id="profit_loss" class="accounting account_reports">
                                                <label for="profit_loss">{{ __('Profit/Loss') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('financial_report') ? 'CHECKED' : '' }} name="financial_report" id="financial_report" class="accounting account_reports">
                                                <label for="financial_report">{{ __('Financial Report') }}</label>
                                            </p>

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('profit_loss_account') ? 'CHECKED' : '' }} name="profit_loss_account" id="profit_loss_account" class="accounting account_reports">
                                                        <label for="profit_loss_account">{{ __('Profit Loss Account') }}</label>
                                                    </p> --}}

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('balance_sheet') ? 'CHECKED' : '' }} name="balance_sheet" id="balance_sheet" class="accounting account_reports">
                                                        <label for="balance_sheet">{{ __('Balance Sheet') }}</label>
                                                    </p> --}}

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('trial_balance') ? 'CHECKED' : '' }} name="trial_balance" id="trial_balance" class="accounting account_reports">
                                                <label for="trial_balance">{{ __('Trial Balance') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('vat_tax_report') ? 'CHECKED' : '' }} name="vat_tax_report" id="vat_tax_report" class="accounting account_reports">
                                                <label for="vat_tax_report">{{ __('Vat/Tax Report') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('cash_flow') ? 'CHECKED' : '' }} name="cash_flow" id="cash_flow" class="accounting account_reports">
                                                <label for="cash_flow">{{ __('Cash Flow') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('expense_report') ? 'CHECKED' : '' }} name="expense_report" id="expense_report" class="accounting account_reports">
                                                <label for="cash_flow">{{ __('Expense Report') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('day_book') ? 'CHECKED' : '' }} name="day_book" id="day_book" class="accounting account_reports">
                                                <label for="day_book">{{ __('Day Book') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-1">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="hrms" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#hrm_permission" aria-expanded="false">
                                    {{ __('HRM Permissions') }}
                                </a>
                            </div>
                            <div id="hrm_permission" class="collapse" data-bs-parent="#hrm_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="leaves" autocomplete="off">
                                                    <strong>{{ __('Leaves') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leaves_index') ? 'CHECKED' : '' }} name="leaves_index" id="leaves_index" class="hrms leaves">
                                                <label for="leaves_index">{{ __('Leave List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leaves_create') ? 'CHECKED' : '' }} name="leaves_create" id="leaves_create" class="hrms leaves">
                                                <label for="leaves_create"> {{ __('Leave Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leaves_edit') ? 'CHECKED' : '' }} name="leaves_edit" id="leaves_edit" class="hrms leaves">
                                                <label for="leaves_edit"> {{ __('Leave Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leaves_delete') ? 'CHECKED' : '' }} name="leaves_delete" id="leaves_delete" class="hrms leaves">
                                                <label for="leaves_delete">{{ __('Leave Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="leave_types" autocomplete="off">
                                                    <strong>{{ __('Leave Types') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leave_types_index') ? 'CHECKED' : '' }} name="leave_types_index" id="leave_types_index" class="hrms leave_types">
                                                <label for="leave_types_index">{{ __('Leave Type List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leave_types_create') ? 'CHECKED' : '' }} name="leave_types_create" id="leave_types_create" class="hrms leave_types">
                                                <label for="leave_types_create"> {{ __('Leave Type Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leave_types_edit') ? 'CHECKED' : '' }} name="leave_types_edit" id="leave_types_edit" class="hrms leave_types">
                                                <label for="leave_types_edit"> {{ __('Leave Type Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leave_types_delete') ? 'CHECKED' : '' }} name="leave_types_delete" id="leave_types_delete" class="hrms leave_types">
                                                <label for="leave_types_delete">{{ __('Leave Type Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="shifts" autocomplete="off">
                                                    <strong>{{ __('Shifts') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('shifts_index') ? 'CHECKED' : '' }} name="shifts_index" id="shifts_index" class="hrms shifts">
                                                <label for="shifts_index">{{ __('Shift List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('shifts_create') ? 'CHECKED' : '' }} name="shifts_create" id="shifts_create" class="hrms shifts">
                                                <label for="shifts_create"> {{ __('Shift Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('shifts_edit') ? 'CHECKED' : '' }} name="shifts_edit" id="shifts_edit" class="hrms shifts">
                                                <label for="shifts_edit"> {{ __('Shift Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('shifts_delete') ? 'CHECKED' : '' }} name="shifts_delete" id="shifts_delete" class="hrms shifts">
                                                <label for="shifts_delete">{{ __('Shift Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="attendances" autocomplete="off">
                                                    <strong>{{ __('Attendences') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('attendances_index') ? 'CHECKED' : '' }} name="attendances_index" id="attendances_index" class="hrms attendances">
                                                <label for="attendances_index">{{ __('Attendance List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('attendances_create') ? 'CHECKED' : '' }} name="attendances_create" id="attendances_create" class="hrms attendances">
                                                <label for="attendances_create"> {{ __('Attendance Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('attendances_edit') ? 'CHECKED' : '' }} name="attendances_edit" id="attendances_edit" class="hrms attendances">
                                                <label for="attendances_edit"> {{ __('Attendance Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('attendances_delete') ? 'CHECKED' : '' }} name="attendances_delete" id="attendances_delete" class="hrms attendances">
                                                <label for="attendances_delete">{{ __('Attendance Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="allowances_and_deductions" autocomplete="off">
                                                    <strong>{{ __('Allowances & Deductions') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('allowances_and_deductions_index')) name="allowances_and_deductions_index" id="allowances_and_deductions_index" class="hrms allowances_and_deductions">
                                                <label for="allowances_and_deductions_index">{{ __('Allowance & Deduction List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('allowances_and_deductions_create')) name="allowances_and_deductions_create" id="allowances_and_deductions_create" class="hrms allowances_and_deductions">
                                                <label for="allowances_and_deductions_create"> {{ __('Allowance & Deduction Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('allowances_and_deductions_edit')) name="allowances_and_deductions_edit" id="allowances_and_deductions_edit" class="hrms allowances_and_deductions">
                                                <label for="allowances_and_deductions_edit"> {{ __('Allowance & Deduction Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('allowances_and_deductions_delete')) name="allowances_and_deductions_delete" id="allowances_and_deductions_delete" class="hrms allowances_and_deductions">
                                                <label for="allowances_and_deductions_delete">{{ __('Allowance & Deduction Delete') }}</label>
                                            </p>
                                        </div>

                                        {{-- <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="hrms" id="select_all" data-target="deductions" autocomplete="off">
                                                            <strong>{{ __('Deductions') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('deductions_index') ? 'CHECKED' : '' }} name="deductions_index" id="deductions_index" class="hrms deductions">
                                                        <label for="deductions_index">{{ __('Deduction List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('deductions_create') ? 'CHECKED' : '' }} name="deductions_create" id="deductions_create" class="hrms deductions">
                                                        <label for="deductions_create"> {{ __('Deduction Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('deductions_edit') ? 'CHECKED' : '' }} name="deductions_edit" id="deductions_edit" class="hrms deductions">
                                                        <label for="deductions_edit"> {{ __('Deduction Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('deductions_delete') ? 'CHECKED' : '' }} name="deductions_delete" id="deductions_delete" class="hrms deductions">
                                                        <label for="deductions_delete">{{ __('Deduction Delete') }}</label>
                                                    </p>
                                                </div> --}}

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="holidays" autocomplete="off">
                                                    <strong>{{ __('Holidays') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('holidays_index') ? 'CHECKED' : '' }} name="holidays_index" id="holidays_index" class="hrms holidays">
                                                <label for="holidays_index">{{ __('Holiday List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('holidays_create') ? 'CHECKED' : '' }} name="holidays_create" id="holidays_create" class="hrms holidays">
                                                <label for="holidays_create"> {{ __('Holiday Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('holidays_edit') ? 'CHECKED' : '' }} name="holidays_edit" id="holidays_edit" class="hrms holidays">
                                                <label for="holidays_edit"> {{ __('Holiday Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('holidays_delete') ? 'CHECKED' : '' }} name="holidays_delete" id="holidays_delete" class="hrms holidays">
                                                <label for="holidays_delete">{{ __('Holiday Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="departments" autocomplete="off">
                                                    <strong>{{ __('Departments') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('departments_index') ? 'CHECKED' : '' }} name="departments_index" id="departments_index" class="hrms departments">
                                                <label for="departments_index">{{ __('Department List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('departments_create') ? 'CHECKED' : '' }} name="departments_create" id="departments_create" class="hrms departments">
                                                <label for="departments_create"> {{ __('Department Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('departments_edit') ? 'CHECKED' : '' }} name="departments_edit" id="departments_edit" class="hrms departments">
                                                <label for="departments_edit"> {{ __('Department Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('departments_delete') ? 'CHECKED' : '' }} name="departments_delete" id="departments_delete" class="hrms departments">
                                                <label for="departments_delete">{{ __('Department Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="designations" autocomplete="off">
                                                    <strong>{{ __('Designations') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designations_index') ? 'CHECKED' : '' }} name="designations_index" id="designations_index" class="hrms designations">
                                                <label for="designations_index">{{ __('Designation List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designations_create') ? 'CHECKED' : '' }} name="designations_create" id="designations_create" class="hrms designations">
                                                <label for="designations_create"> {{ __('Designation Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designations_edit') ? 'CHECKED' : '' }} name="designations_edit" id="designations_edit" class="hrms designations">
                                                <label for="designations_edit"> {{ __('Designation Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designations_delete') ? 'CHECKED' : '' }} name="designations_delete" id="designations_delete" class="hrms designations">
                                                <label for="designations_delete">{{ __('Designation Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="designations" autocomplete="off">
                                                    <strong>{{ __('Designations') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designations_index') ? 'CHECKED' : '' }} name="designations_index" id="designations_index" class="hrms designations">
                                                <label for="designations_index">{{ __('Designation List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designations_create') ? 'CHECKED' : '' }} name="designations_create" id="designations_create" class="hrms designations">
                                                <label for="designations_create"> {{ __('Designation Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designations_edit') ? 'CHECKED' : '' }} name="designations_edit" id="designations_edit" class="hrms designations">
                                                <label for="designations_edit"> {{ __('Designation Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designations_delete') ? 'CHECKED' : '' }} name="designations_delete" id="designations_delete" class="hrms designations">
                                                <label for="designations_delete">{{ __('Designation Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="payrolls" autocomplete="off">
                                                    <strong>{{ __('Payrolls') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payrolls_index') ? 'CHECKED' : '' }} name="payrolls_index" id="payrolls_index" class="hrms payrolls">
                                                <label for="payrolls_index">{{ __('Payroll List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payrolls_create') ? 'CHECKED' : '' }} name="payrolls_create" id="payrolls_create" class="hrms payrolls">
                                                <label for="payrolls_create"> {{ __('Payroll Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payrolls_edit') ? 'CHECKED' : '' }} name="payrolls_edit" id="payrolls_edit" class="hrms payrolls">
                                                <label for="payrolls_edit"> {{ __('Payroll Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payrolls_delete') ? 'CHECKED' : '' }} name="payrolls_delete" id="payrolls_delete" class="hrms payrolls">
                                                <label for="payrolls_delete">{{ __('Payroll Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="payroll_payments" autocomplete="off">
                                                    <strong>{{ __('Payroll Payment') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payroll_payments_index') ? 'CHECKED' : '' }} name="payroll_payments_index" id="payroll_payments_index" class="hrms payroll_payments">
                                                <label for="payroll_payments_index">{{ __('Payroll Payment List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payroll_payments_create') ? 'CHECKED' : '' }} name="payroll_payments_create" id="payroll_payments_create" class="hrms payroll_payments">
                                                <label for="payroll_payments_create"> {{ __('Payroll Payment Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payroll_payments_edit') ? 'CHECKED' : '' }} name="payroll_payments_edit" id="payroll_payments_edit" class="hrms payroll_payments">
                                                <label for="payroll_payments_edit"> {{ __('Payroll Payment Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payroll_payments_delete') ? 'CHECKED' : '' }} name="payroll_payments_delete" id="payroll_payments_delete" class="hrms payroll_payments">
                                                <label for="payroll_payments_delete">{{ __('Payroll Payment Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="payroll_reports" autocomplete="off">
                                                    <strong>{{ __('Payroll Reports') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payroll_report') ? 'CHECKED' : '' }} name="payroll_report" id="payroll_report" class="hrms payroll_reports">
                                                <label for="payroll_report">{{ __('Payroll Report') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payroll_payment_report') ? 'CHECKED' : '' }} name="payroll_payment_report" id="payroll_payment_report" class="hrms payroll_reports">
                                                <label for="payroll_payment_report"> {{ __('Payroll Payment Report') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('attendance_report') ? 'CHECKED' : '' }} name="attendance_report" id="attendance_report" class="hrms payroll_reports">
                                                <label for="attendance_report"> {{ __('Attendance Report') }}</label>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="hrms" id="select_all" data-target="hrm_others_all" autocomplete="off">
                                                    <strong>{{ __('Others') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('hrm_dashboard') ? 'CHECKED' : '' }} name="hrm_dashboard" id="hrm_dashboard" class="hrms hrm_others_all">
                                                <label for="hrm_dashboard">{{ __('HRM Dashboard') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-1">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="task_management" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#manage_tasks_permission" aria-expanded="false">
                                    {{ __('Manage Task Permissions') }}
                                </a>
                            </div>

                            <div id="manage_tasks_permission" class="collapse" data-bs-parent="#manage_tasks_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="todo" id="select_all" data-target="todo" autocomplete="off">
                                                    <strong>{{ __('Todo') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('todo_index') ? 'CHECKED' : '' }} type="checkbox" name="todo_index" id="todo_index" class="todo task_management">
                                                <label for="todo_index"> {{ __('Todo List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('todo_create') ? 'CHECKED' : '' }} type="checkbox" name="todo_create" id="todo_create" class="todo task_management">
                                                <label for="todo_create"> {{ __('Todo Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('todo_edit') ? 'CHECKED' : '' }} type="checkbox" name="todo_edit" id="todo_edit" class="todo task_management">
                                                <label for="todo_edit">{{ __('Todo Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('todo_change_status') ? 'CHECKED' : '' }} type="checkbox" name="todo_change_status" id="todo_change_status" class="todo task_management">
                                                <label for="todo_change_status"> {{ __('Todo Change Status') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('todo_delete') ? 'CHECKED' : '' }} type="checkbox" name="todo_delete" id="todo_delete" class="todo task_management">
                                                <label for="todo_delete">{{ __('Todo Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="workspaces" id="select_all" data-target="workspaces" autocomplete="off">
                                                    <strong>{{ __('Project Management') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('workspaces_index') ? 'CHECKED' : '' }} type="checkbox" name="workspaces_index" id="workspace_index" class="workspaces task_management">
                                                <label for="workspaces_index"> {{ __('Project List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('workspaces_create') ? 'CHECKED' : '' }} type="checkbox" name="workspaces_create" id="workspaces_create" class="workspaces task_management">
                                                <label for="workspaces_create"> {{ __('Project Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('workspaces_edit') ? 'CHECKED' : '' }} type="checkbox" name="workspaces_edit" id="workspaces_edit" class="workspaces task_management">
                                                <label for="workspaces_edit">{{ __('Project Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('workspaces_manage_task') ? 'CHECKED' : '' }} type="checkbox" name="workspaces_manage_task" id="workspaces_manage_task" class="workspaces task_management">
                                                <label for="workspaces_manage_task">{{ __('Project Manage Task') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('workspaces_delete') ? 'CHECKED' : '' }} type="checkbox" name="workspaces_delete" id="workspaces_delete" class="workspaces task_management">
                                                <label for="workspaces_delete">{{ __('Project Delete') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="messages" id="select_all" data-target="messages" autocomplete="off">
                                                    <strong>{{ __('Messages') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('messages_index') ? 'CHECKED' : '' }} type="checkbox" name="messages_index" id="messages_index" class="messages task_management">
                                                <label for="messages_index"> {{ __('Message List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('messages_create') ? 'CHECKED' : '' }} type="checkbox" name="messages_create" id="memos_create" class="messages task_management">
                                                <label for="messages_create"> {{ __('Message Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input {{ $role->hasPermissionTo('messages_delete') ? 'CHECKED' : '' }} type="checkbox" name="messages_delete" id="messages_delete" class="messages task_management">
                                                <label for="messages_delete">{{ __('Message Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-1">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="manufacturings" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#manufacturing_permission" aria-expanded="false">
                                    {{ __('Manufacturing Permissions') }}
                                </a>
                            </div>
                            <div id="manufacturing_permission" class="collapse" data-bs-parent="#manufacturing_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="manufacturings" id="select_all" data-target="manufacturing_all" autocomplete="off">
                                                    <strong>{{ __('Manufacturing') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_view') ? 'CHECKED' : '' }} name="process_view" id="process_view" class="manufacturings manufacturing_all">
                                                <label for="process_view">{{ __('View process') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_add') ? 'CHECKED' : '' }} name="process_add" id="process_add" class="manufacturings manufacturing_all">
                                                <label for="process_add">{{ __('Add Process') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_edit') ? 'CHECKED' : '' }} name="process_edit" id="process_edit" class="manufacturings manufacturing_all">
                                                <label for="process_edit">{{ __('Edit Process') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_delete') ? 'CHECKED' : '' }} name="process_delete" id="process_delete" class="manufacturings manufacturing_all">
                                                <label for="process_delete">{{ __('Delete Process') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_view') ? 'CHECKED' : '' }} name="production_view" id="production_view" class="manufacturings manufacturing_all">
                                                <label for="production_view">{{ __('View Production') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_add') ? 'CHECKED' : '' }} name="production_add" id="production_add" class="manufacturings manufacturing_all">
                                                <label for="production_add">{{ __('Add Production') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_edit') ? 'CHECKED' : '' }} name="production_edit" id="production_edit" class="manufacturings manufacturing_all">
                                                <label for="production_edit">{{ __('Edit Production') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_delete') ? 'CHECKED' : '' }} name="production_delete" id="production_delete" class="manufacturings manufacturing_all">
                                                <label for="production_delete">{{ __('Delete Production') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('manufacturing_report') ? 'CHECKED' : '' }} name="manufacturing_report" id="manufacturing_report" class="manufacturings manufacturing_all">
                                                <label for="manufacturing_report">{{ __('Manufacturing Report') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-1">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="services" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#service_permission" aria-expanded="false">
                                    {{ __('Service Permissions') }}
                                </a>
                            </div>
                            <div id="service_permission" class="collapse" data-bs-parent="#service_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="service_settings" id="select_all" data-target="service_settings" autocomplete="off">
                                                    <strong>{{ __('Settings') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('status_index')) name="status_index" id="status_index" class="service_settings services">
                                                <label for="status_index">{{ __('Status View') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('status_create')) name="status_create" id="status_create" class="service_settings services">
                                                <label for="status_create">{{ __('Status Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('status_edit')) name="status_edit" id="status_edit" class="service_settings services">
                                                <label for="status_edit">{{ __('Status Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('status_delete')) name="status_delete" id="status_delete" class="service_settings services">
                                                <label for="status_delete"> {{ __('Status Delete') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('devices_index')) name="devices_index" id="devices_index" class="service_settings services">
                                                <label for="devices_index">{{ __('Device View') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('devices_create')) name="devices_create" id="devices_create" class="service_settings services">
                                                <label for="devices_create"> {{ __('Device Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('devices_edit')) name="devices_edit" id="devices_edit" class="service_settings services">
                                                <label for="devices_edit"> {{ __('Device Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('devices_delete')) name="devices_delete" id="devices_delete" class="service_settings services">
                                                <label for="devices_delete">{{ __('Device Delete') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('device_models_index')) name="device_models_index" id="device_models_index" class="service_settings services">
                                                <label for="device_models_index">{{ __('Device Model View') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('device_models_create')) name="device_models_create" id="device_models_create" class="service_settings services">
                                                <label for="device_models_create"> {{ __('Device Model Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('device_models_edit')) name="device_models_edit" id="device_models_edit" class="service_settings services">
                                                <label for="device_models_edit"> {{ __('Device Model Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('device_models_delete')) name="device_models_delete" id="device_models_delete" class="service_settings services">
                                                <label for="device_models_delete">{{ __('Device Model Delete') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('servicing_settings')) name="servicing_settings" id="servicing_settings" class="service_settings services">
                                                <label for="servicing_settings">{{ __('Servicing Settings') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('job_card_pdf_print_label_settings')) name="job_card_pdf_print_label_settings" id="job_card_pdf_print_label_settings" class="service_settings services">
                                                <label for="job_card_pdf_print_label_settings">{{ __('Job Card Print/Pdf & Label Settings') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="job_cards" id="select_all" data-target="job_cards" autocomplete="off">
                                                    <strong>{{ __('Job Cards') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('job_cards_index')) name="job_cards_index" id="job_cards_index" class="job_cards services">
                                                <label for="job_cards_index">{{ __('Job Card View') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('job_cards_create')) name="job_cards_create" id="job_cards_create" class="job_cards services">
                                                <label for="job_cards_create">{{ __('Job Card Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('job_cards_edit')) name="job_cards_edit" id="job_cards_edit" class="job_cards services">
                                                <label for="job_cards_edit">{{ __('Job Card Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('job_cards_delete')) name="job_cards_delete" id="job_cards_delete" class="job_cards services">
                                                <label for="job_cards_delete"> {{ __('Job Card Delete') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('job_cards_generate_pdf')) name="job_cards_generate_pdf" id="job_cards_generate_pdf" class="job_cards services">
                                                <label for="job_cards_generate_pdf">{{ __('Job Card Generate Pdf') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('job_cards_generate_label')) name="job_cards_generate_label" id="job_cards_generate_label" class="job_cards services">
                                                <label for="job_cards_generate_label">{{ __('Job Card Generate Label') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('job_cards_change_status')) name="job_cards_change_status" id="job_cards_change_status" class="job_cards services">
                                                <label for="job_cards_change_status"> {{ __('Job Card Change Status') }}</label>
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="service_invoices" id="select_all" data-target="service_invoices" autocomplete="off">
                                                    <strong>{{ __('Invoices') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                @php
                                                    $serviceInvoicesIndexExists = false;

                                                    try {
                                                        $serviceInvoicesIndexExists = $role?->hasPermissionTo('service_invoices_index');
                                                    } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                                                        // Permission does not exist, handle this gracefully
                                                        $serviceInvoicesIndexExists = false;
                                                    }
                                                @endphp
                                                <input type="checkbox" @checked($serviceInvoicesIndexExists) name="service_invoices_index" id="service_invoices_index" class="service_invoices services">
                                                <label for="service_invoices_index">{{ __('Invoice List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                @php
                                                    $serviceInvoicesOnlyOwnExists = false;

                                                    try {
                                                        $serviceInvoicesOnlyOwnExists = $role?->hasPermissionTo('service_invoices_only_own');
                                                    } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                                                        // Permission does not exist, handle this gracefully
                                                        $serviceInvoicesOnlyOwnExists = false;
                                                    }
                                                @endphp

                                                <input type="checkbox" @checked($serviceInvoicesOnlyOwnExists) name="service_invoices_only_own" id="service_invoices_only_own" class="service_invoices services">
                                                <label for="service_invoices_only_own">{{ __('Invoice Only Created By Own') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                @php
                                                    $serviceInvoicesCreateExists = false;

                                                    try {
                                                        $serviceInvoicesCreateExists = $role?->hasPermissionTo('service_invoices_create');
                                                    } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                                                        // Permission does not exist, handle this gracefully
                                                        $serviceInvoicesCreateExists = false;
                                                    }
                                                @endphp
                                                <input type="checkbox" @checked($serviceInvoicesCreateExists) name="service_invoices_create" id="job_cards_create" class="service_invoices services">
                                                <label for="service_invoices_create">{{ __('Invoice Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                @php
                                                    $serviceInvoicesEditExists = false;

                                                    try {
                                                        $serviceInvoicesEditExists = $role?->hasPermissionTo('service_invoices_edit');
                                                    } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                                                        // Permission does not exist, handle this gracefully
                                                        $serviceInvoicesEditExists = false;
                                                    }
                                                @endphp
                                                <input type="checkbox" @checked($serviceInvoicesEditExists) name="service_invoices_edit" id="service_invoices_edit" class="service_invoices services">
                                                <label for="service_invoices_edit">{{ __('Invoice Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                @php
                                                    $serviceInvoicesDeleteExists = false;

                                                    try {
                                                        $serviceInvoicesDeleteExists = $role?->hasPermissionTo('service_invoices_delete');
                                                    } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                                                        // Permission does not exist, handle this gracefully
                                                        $serviceInvoicesDeleteExists = false;
                                                    }
                                                @endphp
                                                <input type="checkbox" @checked($serviceInvoicesDeleteExists) name="service_invoices_delete" id="service_invoices_delete" class="service_invoices services">
                                                <label for="service_invoices_delete"> {{ __('Invoice Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-1">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="advertisements" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#advertisements_permission" aria-expanded="false">
                                    {{ __('Advertisement Permissions') }}
                                </a>
                            </div>
                            <div id="advertisements_permission" class="collapse" data-bs-parent="#advertisements_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="advertisements" id="select_all" data-target="advertisements" autocomplete="off">
                                                    <strong>{{ __('Advertisements') }}</strong>
                                                </label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('advertisements_index')) name="advertisements_index" id="advertisements_index" class="advertisements">
                                                <label for="advertisements_index">{{ __('Advertisement List') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('advertisements_create')) name="advertisements_create" id="advertisements_create" class="advertisements">
                                                <label for="advertisements_create">{{ __('Advertisement Add') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('advertisements_edit')) name="advertisements_edit" id="advertisements_edit" class="advertisements">
                                                <label for="advertisements_edit">{{ __('Advertisement Edit') }}</label>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" @checked($role->hasPermissionTo('advertisements_delete')) name="advertisements_delete" id="advertisements_delete" class="advertisements">
                                                <label for="advertisements_delete"> {{ __('Advertisement Delete') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Others Permissions --}}
                    <div class="accordion-item mb-1">
                        <div class="form_element rounded mt-0 mb-0">
                            <div class="accordion-header d-flex">
                                <p class="checkbox_input_wrap ">
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="others" autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#other_permission" aria-expanded="false">
                                    {{ __('Others Permissions') }}
                                </a>
                            </div>
                            <div id="other_permission" class="collapse" data-bs-parent="#other_permission" style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                    <input type="checkbox" class="others" id="select_all" data-target="other_all" autocomplete="off">
                                                    <strong>{{ __('Others') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('today_summery') ? 'CHECKED' : '' }} name="today_summery" id="today_summery" class="others other_all">
                                                <label for="today_summery">{{ __('Today Summery') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('communication') ? 'CHECKED' : '' }} name="communication" id="communication" class="others other_all">
                                                <label for="communication">{{ __('Communication') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        {{-- Accordian --}}
        <div class="submit-area d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                @if ($role->id == 1)
                    <button type="button" class="btn btn-sm btn-secondary submit_button float-end" disabled>{{ __('Save Changes') }}</button>
                @else
                    <button type="submit" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                @endif
            </div>
        </div>
        </section>
        </form>
    </div>
    </div>
@endsection
@push('scripts')
    @include('users.roles.js_partials.edit_js')
@endpush
