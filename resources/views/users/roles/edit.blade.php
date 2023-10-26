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
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-user-edit"></span>
                    <h5>{{ __('Edit Role') }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                        class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
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
                                        <label for="inputEmail3" class="col-4"><strong>@lang('menu.role_name') : <span
                                                    class="text-danger">*</span></strong> </label>
                                        <div class="col-8">
                                            <input type="text" name="role_name" class="form-control add_input"
                                                id="role_name" placeholder="@lang('menu.role_name')" value="{{ $role->name }}">
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="users"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#users_permission"
                                        aria-expanded="false">
                                        {{ __(' Users Permissions') }}
                                    </a>
                                </div>
                                <div id="users_permission" class="collapse show" data-bs-parent="#users_permission">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="users users_all"
                                                        data-target="users_all" autocomplete="off">
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
                                                    <input type="checkbox" {{ $role->hasPermissionTo('user_edit') ? 'CHECKED' : '' }}
                                                    name="user_edit" id="user_edit" class="users users_all">
                                                   <label for="user_edit">{{ __('Edit User') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('user_delete') ? 'CHECKED':'' }}
                                                    name="user_delete" id="user_delete" class="users users_all">
                                                   <label for="user_delete">{{ __('Delete User') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="users"
                                                        data-target="all_role" autocomplete="off">
                                                    <strong>{{ __('Roles') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('role_view') ? 'CHECKED':'' }} name="role_view" id="role_view" class="users all_role">
                                                    <label for="role_view">{{ __('View Role') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('role_add') ? 'CHECKED':'' }} name="role_add" id="role_add" class="users all_role">
                                                   <label for="role_add"> {{ __('Add Role') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('role_edit') ? 'CHECKED':'' }} id="role_edit" name="role_edit" class="users all_role">
                                                    <label for="role_edit">{{ __('Edit Role') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" {{ $role->hasPermissionTo('role_delete') ? 'CHECKED':'' }} id="role_delete" name="role_delete" class="users all_role">
                                                   <label for="role_delete">{{ __('Delete Role') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="contacts"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#contact_permission"
                                        aria-expanded="false">
                                        {{ __('Contacts Permissions') }}
                                    </a>
                                </div>
                                <div id="contact_permission" class="collapse" data-bs-parent="#contact_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="contacts"
                                                        data-target="contact_all" autocomplete="off">
                                                    <strong>{{ __('Supplier') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('supplier_all') ? 'CHECKED' : '' }}
                                                    name="supplier_all" id="supplier_all" class="contacts contact_all">
                                                    <label for="supplier_all">{{ __('View All Supplier') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('supplier_add') ? 'CHECKED' : '' }}
                                                        name="supplier_add" id="supplier_add" class="contacts contact_all">
                                                   <label for="supplier_add">{{ __('Add Supplier') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('supplier_import') ? 'CHECKED' : '' }} name="supplier_import" id="supplier_import" class="contacts contact_all">
                                                    <label for="supplier_import">{{ __('Import Suppliers') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('supplier_edit') ? 'CHECKED' : '' }}
                                                    name="supplier_edit" id="supplier_edit" class="contacts contact_all">
                                                    <label for="supplier_edit">{{ __('Edit Supplier') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('supplier_delete') ? 'CHECKED' : '' }} name="supplier_delete" id="supplier_delete" class="contacts contact_all">
                                                   <label for="supplier_delete">{{ __('Delete Supplier') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('supplier_report') ? 'CHECKED' : '' }}
                                                    name="supplier_report" id="supplier_report" class="contacts contact_all">
                                                   <label for="supplier_report">{{ __('Supplier Report') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" id="select_all" class="contacts"
                                                        data-target="customer_all" autocomplete="off">
                                                    <strong>{{ __('Customers') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('customer_all') ? 'CHECKED' : '' }}
                                                     name="customer_all" id="customer_all" class="contacts customer_all">
                                                   <label for="customer_all">{{ __('View All Customer') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('customer_add') ? 'CHECKED' : '' }}
                                                    name="customer_add" id="customer_add" class="contacts customer_all">
                                                    <label for="customer_add">{{ __('Add Customer') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('customer_import') ? 'CHECKED' : '' }}
                                                    name="customer_import" id="customer_import" class="contacts customer_all">
                                                   <label for="customer_import"> {{ __('Import Customers') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('customer_edit') ? 'CHECKED' : '' }}
                                                    name="customer_edit" id="customer_edit" class="contacts customer_all">
                                                   <label for="customer_edit"> {{ __('Edit Customer') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('customer_delete') ? 'CHECKED' : '' }}
                                                     name="customer_delete" id="customer_delete" class="contacts customer_all">
                                                    <label for="customer_delete">{{ __('Delete Customer') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('customer_report') ? 'CHECKED' : '' }}
                                                     name="customer_report" id="customer_report" class="contacts customer_all">
                                                    <label for="customer_report">{{ __('Customer Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('customer_group') ? 'CHECKED' : '' }}
                                                    name="customer_group" id="customer_group" class="contacts customer_all">
                                                    <label for="customer_group">{{ __('Customer Group') }} &rarr; {{ __('View/Add/Edit/Delete') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="products"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#product_permission"
                                        aria-expanded="false">
                                        {{ __('Products Permissions') }}
                                    </a>
                                </div>
                                <div id="product_permission" class="collapse" data-bs-parent="#products" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="products"
                                                        data-target="product_all" autocomplete="off">
                                                    <strong>{{ __('Products') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_all') ? 'CHECKED' : '' }}
                                                     name="product_all" id="product_all"
                                                        class="products product_all">
                                                    <label for="product_all">{{ __('View All Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_add') ? 'CHECKED' : '' }}
                                                     name="product_add" id="product_add"
                                                        class="products product_all">
                                                <label for="product_add"> {{ __('Add Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_edit') ? 'CHECKED' : '' }}
                                                     name="product_edit" id="product_edit"
                                                        class="products product_all">
                                                    <label for="product_edit">{{ __('Edit Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_delete') ? 'CHECKED' : '' }}
                                                     name="product_delete" id="product_delete"
                                                        class="products product_all">
                                                <label for="product_delete"> {{ __('Delete Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('manage_price_group') ? 'CHECKED' : '' }}
                                                     name="manage_price_group" id="manage_price_group"
                                                        class="products product_all">
                                                    <label for="manage_price_group">{{ __('Manage Price Group') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('openingStock_add') ? 'CHECKED' : '' }}
                                                     name="openingStock_add" id="openingStock_add"
                                                        class="products product_all">
                                                    <label for="openingStock_add"> {{ __('Add/Edit Opening Stock') }}</label>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_import') ? 'CHECKED' : '' }}
                                                     name="product_import" id="product_import"
                                                        class="products product_all">
                                                    <label for="product_import">{{ __('Import Product') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_expired_list') ? 'CHECKED' : '' }}
                                                    name="product_expired_list" id="product_expired_list" class="products product_all">
                                                    <label for="product_expired_list">{{ __('Expired Product List') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_settings') ? 'CHECKED' : '' }}
                                                     name="product_settings" id="product_settings"
                                                        class="products product_all">
                                                    <label for="product_settings">{{ __('Product Settings') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('generate_barcode') ? 'CHECKED' : '' }}
                                                     name="generate_barcode" id="generate_barcode"
                                                        class="products product_all">
                                                    <label for="generate_barcode">{{ __('Generate Barcode') }}</label>
                                                </p>

                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="products"
                                                        data-target="product_category" autocomplete="off">
                                                    <strong>{{ __('Categories') }}</strong>
                                                </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_category_index') ? 'CHECKED' : '' }}
                                                     name="product_category_index" id="product_category_index"
                                                        class="products product_category">
                                                <label for="product_category_index">{{ __('View All Category') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_category_add') ? 'CHECKED' : '' }}
                                                     name="product_category_add" id="product_category_add"
                                                        class="products product_category">
                                                <label for="product_category_add">{{ __('Add Category') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_category_edit') ? 'CHECKED' : '' }}
                                                     name="product_category_edit" id="product_category_edit"
                                                        class="products product_category">
                                                <label for="product_category_edit">{{ __('Edit Category') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_category_delete') ? 'CHECKED' : '' }}
                                                     name="product_category_delete" id="product_category_delete"
                                                        class="products product_category">
                                                <label for="product_category_delete">{{ __('Delete Category') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="products"
                                                        data-target="product_brand" autocomplete="off">
                                                    <strong>{{ __('Brands') }}</strong>
                                                </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_brand_index') ? 'CHECKED' : '' }}
                                                     name="product_brand_index" id="product_brand_index"
                                                        class="products product_brand">
                                                <label for="product_brand_index">{{ __('View All Brand') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_brand_add') ? 'CHECKED' : '' }}
                                                     name="product_brand_add" id="product_brand_add"
                                                        class="products product_brand">
                                                <label for="product_brand_add">{{ __('Add Brand') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_brand_edit') ? 'CHECKED' : '' }}
                                                     name="product_brand_edit" id="product_brand_edit"
                                                        class="products product_brand">
                                                <label for="product_brand_edit">{{ __('Edit Brand') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_brand_delete') ? 'CHECKED' : '' }}
                                                     name="product_brand_delete" id="product_brand_delete"
                                                        class="products product_brand">
                                                <label for="product_brand_delete">{{ __('Delete Brand') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="products"
                                                        data-target="product_unit" autocomplete="off">
                                                    <strong>{{ __('Unit') }}</strong>
                                                </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_unit_index') ? 'CHECKED' : '' }}
                                                    name="product_unit_index" id="product_unit_index"
                                                        class="products product_unit">
                                                <label for="product_unit_index">{{ __('View All Unit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_unit_add') ? 'CHECKED' : '' }}
                                                    name="product_unit_add" id="product_unit_add"
                                                        class="products product_unit">
                                                <label for="product_unit_add">{{ __('Add Unit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_unit_edit') ? 'CHECKED' : '' }}
                                                     name="product_unit_edit" id="product_unit_edit"
                                                        class="products product_unit">
                                                <label for="product_unit_edit">{{ __('Edit Unit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_unit_delete') ? 'CHECKED' : '' }}
                                                     name="product_unit_delete" id="product_unit_delete"
                                                        class="products product_unit">
                                                <label for="product_unit_delete">{{ __('Delete Unit') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                        <hr class="mt-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="products"
                                                        data-target="product_variant" autocomplete="off">
                                                    <strong>{{ __('Bulk Variant') }}</strong>
                                                </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_variant_index') ? 'CHECKED' : '' }}
                                                    name="product_variant_index" id="product_variant_index"
                                                        class="products product_variant">
                                                <label for="product_variant_index">{{ __('View All Variant') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_variant_add') ? 'CHECKED' : '' }}
                                                    name="product_variant_add" id="product_variant_add"
                                                        class="products product_variant">
                                                <label for="product_variant_add">{{ __('Add Variant') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_variant_edit') ? 'CHECKED' : '' }}
                                                     name="product_variant_edit" id="product_variant_edit"
                                                        class="products product_variant">
                                                <label for="product_variant_edit">{{ __('Edit Variant') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_variant_delete') ? 'CHECKED' : '' }}
                                                     name="product_variant_delete" id="product_variant_delete"
                                                        class="products product_variant">
                                                <label for="product_variant_delete">{{ __('Delete Variant') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="products"
                                                        data-target="product_warranties" autocomplete="off">
                                                    <strong>{{ __('Warranties') }}</strong>
                                                </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_warranty_index') ? 'CHECKED' : '' }}
                                                     name="product_warranty_index" id="product_warranty_index"
                                                        class="products product_warranties">
                                                <label for="product_warranty_index">{{ __('View All Warranty') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_warranty_add') ? 'CHECKED' : '' }}
                                                     name="product_warranty_add" id="product_warranty_add"
                                                        class="products product_warranties">
                                                <label for="product_warranty_add">{{ __('Add Warranty') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_warranty_edit') ? 'CHECKED' : '' }}
                                                     name="product_warranty_edit" id="product_warranty_edit"
                                                        class="products product_warranties">
                                                <label for="product_warranty_edit">{{ __('Edit Warranty') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_warranty_delete') ? 'CHECKED' : '' }}
                                                    name="product_warranty_delete" id="product_warranty_delete"
                                                        class="products product_warranties">
                                                <label for="product_warranty_delete">{{ __('Delete Warranty') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="products"
                                                        data-target="product_report" autocomplete="off">
                                                    <strong>{{ __('Reports') }}</strong>
                                                </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_report') ? 'CHECKED' : '' }}
                                                    name="stock_report" id="stock_report"
                                                        class="products product_report">
                                                    <label for="stock_report">{{ __('Stock Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_in_out_report') ? 'CHECKED' : '' }}
                                                    name="stock_in_out_report" id="stock_in_out_report"
                                                        class="products product_report">
                                                <label for="stock_in_out_report"> {{ __('Stock In-Out Report') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input id="select_all" type="checkbox" class="products"
                                                        data-target="product_selling_price_group_index" autocomplete="off">
                                                    <strong>{{ __('Selling Price Group') }}</strong>
                                                  </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" 
                                                    {{ $role->hasPermissionTo('selling_price_group_index') ? 'CHECKED' : '' }}
                                                    name="selling_price_group_index" id="selling_price_group_index"
                                                        class="products product_selling_price_group_index">
                                                   <label for="selling_price_group_index">{{ __('View All Selling Price Group') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('selling_price_group_add') ? 'CHECKED' : '' }}
                                                     name="selling_price_group_add" id="selling_price_group_add"
                                                        class="products product_selling_price_group_index">
                                                   <label for="selling_price_group_add">{{ __('Selling Price Group Add') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('selling_price_group_edit') ? 'CHECKED' : '' }}
                                                     name="selling_price_group_edit" id="selling_price_group_edit"
                                                        class="products product_selling_price_group_index">
                                                   <label for="selling_price_group_edit">{{ __('Selling Price Group Edit') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('selling_price_group_delete') ? 'CHECKED' : '' }}
                                                     name="selling_price_group_delete" id="selling_price_group_delete"
                                                        class="products product_selling_price_group_index">
                                                   <label for="selling_price_group_delete">{{ __('Selling Price Group Delete') }}</label>
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
                                    <input type="checkbox" class="ms-2" id="select_all" data-target="purchase"
                                        autocomplete="off">
                                </p>
                                <a data-bs-toggle="collapse" class="collapsed" href="#purchase_permission"
                                    aria-expanded="false">
                                    {{ __('Purchases Permissions') }}
                                </a>
                            </div>
                            <div id="purchase_permission" class="collapse" data-bs-parent="#purchase_permission"
                                style="">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                <input type="checkbox" class="purchase" id="select_all"
                                                    data-target="purchase_all" autocomplete="off">
                                                <strong>{{ __('Purchases') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_all') ? 'CHECKED' : '' }}
                                                    name="purchase_all" id="purchase_all"
                                                    class="purchase purchase_all">
                                                <label for="purchase_all">{{ __('View All Purchase') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_add') ? 'CHECKED' : '' }}
                                                    name="purchase_add"  id="purchase_add"
                                                    class="purchase purchase_all">
                                                <label for="purchase_add">{{ __('Add Purchase') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_edit') ? 'CHECKED' : '' }}
                                                    name="purchase_edit"  id="purchase_edit"
                                                    class="purchase purchase_all">
                                                <label for="purchase_edit">{{ __('Edit Purchase') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_delete') ? 'CHECKED' : '' }}
                                                    name="purchase_delete" id="purchase_delete"
                                                    class="purchase purchase_all">
                                                <label for="purchase_delete">{{ __('Delete purchase') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('status_update') ? 'CHECKED' : '' }} 
                                                name="status_update" id="status_update"
                                                    class="purchase purchase_all">
                                                <label for="status_update">{{ __('Update Status') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_settings') ? 'CHECKED' : '' }} 
                                                    name="purchase_settings" id="purchase_settings"
                                                    class="purchase purchase_all">
                                                <label for="purchase_settings">{{ __('Purchase Settings') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_statements') ? 'CHECKED' : '' }} 
                                                    name="purchase_statements" id="purchase_statements"
                                                    class="purchase purchase_all">
                                                <label for="purchase_statements">{{ __('Purchase Statements') }}</label>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                <input type="checkbox" class="purchase" id="select_all"
                                                    data-target="purchase_order" autocomplete="off">
                                                <strong>{{ __('Purchase Order') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_order_index') ? 'CHECKED' : '' }} 
                                                    name="purchase_order_index" id="purchase_order_index"
                                                    class="purchase purchase_order">
                                                <label for="purchase_order_index">{{ __('View All Purchase Order') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_order_add') ? 'CHECKED' : '' }} 
                                                    name="purchase_order_add" id="purchase_order_add"
                                                    class="purchase purchase_order">
                                                <label for="purchase_order_add">{{ __('Purchase Order Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" 
                                                {{ $role->hasPermissionTo('purchase_order_edit') ? 'CHECKED' : '' }} 
                                                name="purchase_order_edit" id="purchase_order_edit"
                                                    class="purchase purchase_order">
                                                <label for="purchase_order_edit">{{ __('Purchase Order Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" 
                                                {{ $role->hasPermissionTo('purchase_order_delete') ? 'CHECKED' : '' }} 
                                                name="purchase_order_delete" id="purchase_order_delete"
                                                    class="purchase purchase_order">
                                                <label for="purchase_order_delete">{{ __('Purchase Order Delete') }}</label>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                <input type="checkbox" class="purchase" id="select_all"
                                                    data-target="purchase_return" autocomplete="off">
                                                <strong>{{ __('Purchase Return') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_return_index') ? 'CHECKED' : '' }} 
                                                    name="purchase_return_index" id="purchase_return_index"
                                                    class="purchase purchase_return">
                                                <label for="purchase_return_index">{{ __('View All Purchase Return') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_return_add') ? 'CHECKED' : '' }}
                                                    name="purchase_return_add" id="purchase_return_add"
                                                    class="purchase purchase_return">
                                                <label for="purchase_return_add">{{ __('Purchase Return Add') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_return_edit') ? 'CHECKED' : '' }}
                                                    name="purchase_return_edit" id="purchase_return_edit"
                                                    class="purchase purchase_return">
                                                <label for="purchase_return_edit">{{ __('Purchase Return Edit') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" 
                                                {{ $role->hasPermissionTo('purchase_return_delete') ? 'CHECKED' : '' }}
                                                name="purchase_return_delete" id="purchase_return_delete"
                                                    class="purchase purchase_return">
                                                <label for="purchase_return_delete">{{ __('Purchase Return Delete') }}</label>
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                <input type="checkbox" class="purchase" id="select_all"
                                                    data-target="purchase_report" autocomplete="off">
                                                <strong>{{ __('Purchase Reports') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_report') ? 'CHECKED' : '' }}
                                                    name="purchase_report" id="purchase_report"
                                                    class="purchase purchase_report">
                                                <label for="purchase_report">{{ __('Purchase Report') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_order_report') ? 'CHECKED' : '' }}
                                                    name="purchase_order_report" id="purchase_order_report"
                                                    class="purchase purchase_report">
                                                <label for="purchase_order_report">{{ __('Purchase Order Report') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" 
                                                {{ $role->hasPermissionTo('purchase_ordered_product_report') ? 'CHECKED' : '' }}
                                                name="purchase_ordered_product_report" id="purchase_ordered_product_report"
                                                    class="purchase purchase_report">
                                                <label for="purchase_ordered_product_report">{{ __('Purchase Ordered Product Report') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" 
                                                {{ $role->hasPermissionTo('purchase_return_report') ? 'CHECKED' : '' }}
                                                name="purchase_return_report" id="purchase_return_report"
                                                    class="purchase purchase_report">
                                                <label for="purchase_return_report">{{ __('Purchase Return Report') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" 
                                                {{ $role->hasPermissionTo('purchase_returned_product_report') ? 'CHECKED' : '' }}
                                                name="purchase_returned_product_report" id="purchase_returned_product_report"
                                                    class="purchase purchase_report">
                                                <label for="purchase_returned_product_report">{{ __('Purchase Returned Products Report') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_sale_report') ? 'CHECKED' : '' }}
                                                    name="purchase_sale_report" id="purchase_sale_report"
                                                    class="purchase purchase_report">
                                                <label for="purchase_sale_report">{{ __('Purchase & Sale Report') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('product_purchase_report') ? 'CHECKED' : '' }}
                                                    name="product_purchase_report" id="product_purchase_report"
                                                    class="purchase purchase_report">
                                                <label for="product_purchase_report">{{ __('Product Purchase Report') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_payment_report') ? 'CHECKED' : '' }}
                                                    name="purchase_payment_report" id="purchase_payment_report"
                                                    class="purchase purchase_report">
                                                <label for="purchase_payment_report"> {{ __(' Purchase Payment Report') }}</label>
                                            </p>
                                        </div>
                                    </div>
                                    <hr class="mt-2">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info checkbox_input_wrap">
                                                <label>
                                                <input type="checkbox" class="purchase" id="select_all"
                                                    data-target="other_purchase" autocomplete="off">
                                                <strong>{{ __('Others') }}</strong>
                                                </label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_payment') ? 'CHECKED' : '' }}
                                                    name="purchase_payment" id="purchase_payment"
                                                    class="purchase other_purchase">
                                                <label for="purchase_payment">{{ __('View/Add/Delete Purchase Payment') }}</label>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                {{ $role->hasPermissionTo('purchase_return') ? 'CHECKED' : '' }}
                                                    name="purchase_return" id="purchase_return"
                                                    class="purchase other_purchase">
                                                <label for="purchase_return">{{ __('Access Purchase Return') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="adjustment"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#adjustment_permission"
                                        aria-expanded="false">
                                        {{ __('Adjustment Permissions') }}
                                    </a>
                                </div>
                                <div id="adjustment_permission" class="collapse" data-bs-parent="#adjustment_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="adjustment" id="select_all"
                                                        data-target="adjustment_all" autocomplete="off">
                                                    <strong>{{ __('Stock Adjustment') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('adjustment_all') ? 'CHECKED' : '' }}
                                                     name="adjustment_all" id="adjustment_all" class="adjustment adjustment_all">
                                                    <label for="adjustment_all">{{ __('View All Adjustment') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('adjustment_add_from_location') ? 'CHECKED' : '' }}
                                                     name="adjustment_add_from_location" id="adjustment_add_from_location" class="adjustment adjustment_all">
                                                    <label for="adjustment_add_from_location">{{ __('Add Adjustment From Business Location') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('adjustment_add_from_warehouse') ? 'CHECKED' : '' }}
                                                    name="adjustment_add_from_warehouse" id="adjustment_add_from_warehouse" class="adjustment adjustment_all">
                                                  <label for="adjustment_add_from_warehouse">{{ __('Add Adjustment From Warehouse') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('delete_expense') ? 'CHECKED' : '' }}
                                                    name="delete_expense" id="adjust_delete_expense" class="adjustment adjustment_all">
                                                    <label for="adjust_delete_expense">{{ __('Delete Adjustment') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_adjustment_report') ? 'CHECKED' : '' }}
                                                    name="stock_adjustment_report" id="stock_adjustment_report" class="adjustment adjustment_all">
                                                   <label for="stock_adjustment_report">{{ __('Stock Adjustment Report') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="expenses"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#expenses_permission"
                                        aria-expanded="false">
                                        {{ __('Expenses Permissions') }}
                                    </a>
                                </div>
                                <div id="expenses_permission" class="collapse" data-bs-parent="#expenses_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="expenses" id="select_all"
                                                        data-target="expenses_all" autocomplete="off">
                                                    <strong>{{ __('Expenses') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('view_expense') ? 'CHECKED' : '' }}
                                                    name="view_expense" id="view_expense" class="expenses expenses_all">
                                                    <label for="view_expense">{{ __('View Expense') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('add_expense') ? 'CHECKED' : '' }}
                                                    name="add_expense" id="add_expense" class="expenses expenses_all">
                                                   <label for="add_expense">{{ __('Add Expense') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('edit_expense') ? 'CHECKED' : '' }}
                                                     name="edit_expense" id="edit_expense" class="expenses expenses_all">
                                                   <label for="edit_expense">{{ __('Edit expense') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('delete_expense') ? 'CHECKED' : '' }}
                                                     name="delete_expense" id="delete_expense" class="expenses expenses_all">
                                                    <label for="delete_expense"> {{ __('Delete Expense') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('expense_category') ? 'CHECKED' : '' }}
                                                     name="expense_category" id="expense_category" class="expenses expenses_all">
                                                    <label for="expense_category">{{ __('Expense Category') }} &rarr; {{ __('View/Add/Edit/Delete') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('category_wise_expense') ? 'CHECKED' : '' }}
                                                     name="category_wise_expense" id="category_wise_expense" class="expenses expenses_all">
                                                    <label for="category_wise_expense">{{ __('View Category Wise Expense') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('expanse_report') ? 'CHECKED' : '' }}
                                                    name="expanse_report" id="expanse_report" class="expenses expenses_all">
                                                    <label for="expanse_report">{{ __('Expense Reports') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="sales"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#sales_permission"
                                        aria-expanded="false">
                                        {{ __('Sales Permissions') }}
                                    </a>
                                </div>
                                <div id="sales_permission" class="collapse" data-bs-parent="#sales_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="sales" id="select_all"
                                                        data-target="sale_all" autocomplete="off">
                                                    <strong>{{ __('Sales') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('create_add_sale') ? 'CHECKED' : '' }}
                                                     name="create_add_sale" id="create_add_sale" class="sales sale_all">
                                                    <label for="create_add_sale">{{ __('Create Add Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('view_add_sale') ? 'CHECKED' : '' }}
                                                     name="view_add_sale" id="view_add_sale" class="sales sale_all">
                                                    <label for="view_add_sale">{{ __('Manage Add Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('edit_add_sale') ? 'CHECKED' : '' }}
                                                    name="edit_add_sale" id="edit_add_sale" class="sales sale_all">
                                                    <label for="edit_add_sale">{{ __('Edit Add Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('delete_add_sale') ? 'CHECKED' : '' }}
                                                    name="delete_add_sale" id="delete_add_sale" class="sales sale_all">
                                                   <label for="delete_add_sale"> {{ __('Delete Add Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_draft') ? 'CHECKED' : '' }}
                                                    name="sale_draft" id="sale_draft" class="sales sale_all">
                                                    <label for="sale_draft">{{ __('List Draft') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_quotation') ? 'CHECKED' : '' }}
                                                     name="sale_quotation" id="sale_quotation" class="sales sale_all">
                                                   <label for="sale_quotation"> {{ __('List Quotations') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sold_product_list') ? 'CHECKED' : '' }}
                                                    name="sold_product_list" id="sold_product_list" class="sales sale_all">
                                                   <label for="sold_product_list"> {{ __('Sold Product List') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('add_sale_settings') ? 'CHECKED' : '' }}
                                                    name="add_sale_settings" id="add_sale_settings"
                                                        class="sales sale_all">
                                                    <label for="add_sale_settings">{{ __('Add Sale Settings') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="checkbox_input_wrap mt-4">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('edit_price_sale_screen') ? 'CHECKED' : '' }}
                                                     name="edit_price_sale_screen" id="edit_price_sale_screen"
                                                      class="sales sale_all">
                                                   <label for="edit_price_sale_screen"> {{ __('Edit Product Price from Sales Screen') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('edit_discount_sale_screen') ? 'CHECKED' : '' }}
                                                    name="edit_discount_sale_screen" id="edit_discount_sale_screen"
                                                        class="sales sale_all">
                                                    <label for="edit_discount_sale_screen">{{ __('Edit Product Discount in Sale Scr') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('shipment_access') ? 'CHECKED' : '' }}
                                                    name="shipment_access" id="shipment_access" class="sales sale_all">
                                                   <label for="shipment_access"> {{ __('Access Shipments') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('view_product_cost_is_sale_screed') ? 'CHECKED' : '' }}
                                                    name="view_product_cost_is_sale_screed" id="view_product_cost_is_sale_screed"
                                                        class="sales sale_all">
                                                   <label for="view_product_cost_is_sale_screed"> {{ __('View Product Cost In Sale Screen') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('view_own_sale') ? 'CHECKED' : '' }}
                                                    name="view_own_sale" id="view_own_sale" class="sales sale_all">
                                                  <label for="view_own_sale">{{ __('View only own Add/POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('discounts') ? 'CHECKED' : '' }}
                                                    name="discounts" id="discounts" class="sales sale_all">
                                                   <label for="discounts"> {{ __('Manage Discount') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sales_order_list') ? 'CHECKED' : '' }}
                                                     name="sales_order_list" id="sales_order_list" class="sales sale_all">
                                                   <label for="sales_order_list"> {{ __('Sales Order List') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sales_order_to_invoice') ? 'CHECKED' : '' }}
                                                    name="sales_order_to_invoice" id="sales_order_to_invoice" class="sales sale_all">
                                                   <label for="sales_order_to_invoice"> {{ __('Sales Order to Invoice') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="sales" id="select_all"
                                                        data-target="sales_report" autocomplete="off">
                                                    <strong>{{ __('Sales Reports') }}</strong>
                                                   </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sales_report') ? 'CHECKED' : '' }}
                                                    name="sales_report" id="sales_report" class="sales sales_report">
                                                   <label for="sales_report"> {{ __('Sales Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sales_return_report') ? 'CHECKED' : '' }}
                                                     name="sales_return_report" id="sales_return_report"
                                                        class="sales sales_report">
                                                    <label for="sales_return_report">{{ __('Sales Return Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_sale_report') ? 'CHECKED' : '' }}
                                                    name="product_sale_report" id="product_sale_report" class="sales sales_report">
                                                    <label for="product_sale_report">{{ __('Sale Product Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sold_product_report') ? 'CHECKED' : '' }}
                                                     name="sold_product_report" id="sold_product_report" class="sales sales_report">
                                                    <label for="sold_product_report">{{ __('Sold Products Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sales_order_report') ? 'CHECKED' : '' }}
                                                     name="sales_order_report" id="sales_order_report" class="sales sales_report">
                                                    <label for="sales_order_report">{{ __('Sales Order Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sales_ordered_products_report') ? 'CHECKED' : '' }}
                                                     name="sales_ordered_products_report" id="sales_ordered_products_report" class="sales sales_report">
                                                    <label for="sales_ordered_products_report">{{ __('Sales Ordered Products Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sales_returned_products_report') ? 'CHECKED' : '' }}
                                                     name="sales_returned_products_report" id="sales_returned_products_report" class="sales sales_report">
                                                    <label for="sales_returned_products_report">{{ __('Sales Returned Products Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('received_against_sales_report') ? 'CHECKED' : '' }}
                                                     name="received_against_sales_report" id="received_against_sales_report" class="sales sales_report">
                                                    <label for="received_against_sales_report">{{ __('Received Against Sales Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('cash_register_report') ? 'CHECKED' : '' }}
                                                     name="cash_register_report" id="cash_register_report"
                                                        class="sales sales_report">
                                                   <label for="cash_register_report">{{ __('Cash Register Reports') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_representative_report') ? 'CHECKED' : '' }}
                                                    name="sale_representative_report" id="sale_representative_report"
                                                        class="sales sales_report">
                                                  <label for="sale_representative_report">{{ __('Sales Representative Report') }}</label>
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="sales" id="select_all"
                                                        data-target="pos_sale_all" autocomplete="off">
                                                    <strong>{{ __('POS Sales') }}</strong>
                                                   </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pos_all') ? 'CHECKED' : '' }}
                                                    name="pos_all" id="pos_all" class="sales pos_sale_all">
                                                    <label for="pos_all">{{ __('Manage POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pos_add') ? 'CHECKED' : '' }}
                                                    name="pos_add" id="pos_add" class="sales pos_sale_all">
                                                    <label for="pos_add">{{ __('Add POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pos_edit') ? 'CHECKED' : '' }}
                                                    name="pos_edit" id="pos_edit" class="sales pos_sale_all">
                                                   <label for="pos_edit">{{ __('Edit POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pos_delete') ? 'CHECKED' : '' }}
                                                     name="pos_delete" id="pos_delete" class="sales pos_sale_all">
                                                   <label for="pos_delete">{{ __('Delete POS Sale') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pos_sale_settings') ? 'CHECKED' : '' }}
                                                     name="pos_sale_settings" id="pos_sale_settings"
                                                        class="sales pos_sale_all">
                                                    <label for="pos_sale_settings">{{ __('POS Sale Settings') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('edit_price_pos_screen') ? 'CHECKED' : '' }}
                                                     name="edit_price_pos_screen" id="edit_price_pos_screen"
                                                        class="sales pos_sale_all">
                                                   <label for="edit_price_pos_screen"> {{ __('Edit Product Price From POS Screen') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('edit_discount_pos_screen') ? 'CHECKED' : '' }}
                                                     name="edit_discount_pos_screen" id="edit_discount_pos_screen"
                                                        class="sales pos_sale_all">
                                                    <label for="edit_discount_pos_screen">{{ __('Edit Product Discount From POS Screen') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                        <hr class="mt-2">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="sales" id="select_all"
                                                        data-target="sales_reurn" autocomplete="off">
                                                    <strong>{{ __('Sales Return') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('create_add_sales_return') ? 'CHECKED' : '' }}
                                                    name="create_add_sales_return" id="create_add_sales_return" class="sales sales_reurn">
                                                    <label for="create_add_sales_return">{{ __('Create Add Sale Return') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('edit_add_sales_return') ? 'CHECKED' : '' }}
                                                     name="edit_add_sales_return" id="edit_add_sales_return" class="sales sales_reurn">
                                                    <label for="edit_add_sales_return">{{ __('Edit Add Sale Return') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('delete_add_sales_return') ? 'CHECKED' : '' }}
                                                     name="delete_add_sales_return" id="delete_add_sales_return" class="sales sales_reurn">
                                                    <label for="delete_add_sales_return">{{ __('Delete Add Sale Return') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sales_return_index') ? 'CHECKED' : '' }}
                                                    name="sales_return_index" id="sales_return_index" class="sales sales_reurn">
                                                    <label for="sales_return_index">{{ __('Sales Return List') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all"
                                            data-target="cash_register" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#cash_register_permission"
                                        aria-expanded="false">
                                        {{ __('Cash Register Permissions') }}
                                    </a>
                                </div>
                                <div id="cash_register_permission" class="collapse"
                                    data-bs-parent="#cash_register_permission" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="cash_register" id="select_all"
                                                        data-target="cash_register_all" autocomplete="off">
                                                    <strong>{{ __('Cash Register') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('register_view') ? 'CHECKED' : '' }}
                                                     name="register_view" id="register_view" class="cash_register cash_register_all">
                                                    <label for="register_view">{{ __('View Cash Register') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('register_close') ? 'CHECKED' : '' }}
                                                     name="register_close" id="register_close" class="cash_register cash_register_all">
                                                    <label for="register_close">{{ __('Close Cash Register') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('another_register_close') ? 'CHECKED' : '' }}
                                                    name="another_register_close" id="another_register_close" class="cash_register cash_register_all">
                                                    <label for="another_register_close">{{ __('Close Another Cash Register') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="reports"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#reports_permission"
                                        aria-expanded="false">
                                        {{ __('All Report Permissions') }}
                                    </a>
                                </div>
                                <div id="reports_permission" class="collapse" data-bs-parent="#reports_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="reports" id="select_all"
                                                        data-target="report_all" autocomplete="off">
                                                    <strong>{{ __('Reports') }}</strong>
                                                     </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('tax_report') ? 'CHECKED' : '' }}
                                                    name="tax_report" id="tax_report" class="reports report_all">
                                                   <label for="tax_report">{{ __('Tax Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('production_report') ? 'CHECKED' : '' }}
                                                     name="production_report" id="production_report" class="reports report_all">
                                                    <label for="production_report">{{ __('Production Report') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="setup"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#setup_permission"
                                        aria-expanded="false">
                                        {{ __('Setup Permissions') }}
                                    </a>
                                </div>
                                <div id="setup_permission" class="collapse" data-bs-parent="#setup_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>                                                    <input type="checkbox" class="setup" id="select_all"
                                                        data-target="setup_all" autocomplete="off">
                                                    <strong>{{ __('Set-up') }}</strong>
                                                    </label>

                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('tax') ? 'CHECKED' : '' }}
                                                    name="tax" id="tax" class="setup setup_all">
                                                    <label for="tax">{{ __('Tax') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('branch') ? 'CHECKED' : '' }}
                                                    name="branch" id="branch" class="setup setup_all">
                                                   <label for="branch"> {{ __('Business Location') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('warehouse') ? 'CHECKED' : '' }}
                                                    name="warehouse" id="warehouse" class="setup setup_all">
                                                   <label for="warehouse">{{ __('Warehouse') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('general_settings') ? 'CHECKED' : '' }}
                                                    name="general_settings" id="general_settings" class="setup setup_all">
                                                    <label for="general_settings">{{ __('General Settings') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('payment_settings') ? 'CHECKED' : '' }}
                                                    name="payment_settings" id="payment_settings" class="setup setup_all">
                                                   <label for="payment_settings">{{ __('Payment Settings') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('invoice_schema') ? 'CHECKED' : '' }}
                                                    name="invoice_schema" id="invoice_schema" class="setup setup_all">
                                                    <label for="invoice_schema">{{ __('Invoice Schema') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('invoice_layout') ? 'CHECKED' : '' }}
                                                    name="invoice_layout" id="invoice_layout" class="setup setup_all">
                                                   <label for="invoice_layout"> {{ __('Invoice Layout') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('barcode_settings') ? 'CHECKED' : '' }}
                                                     name="barcode_settings" id="barcode_settings" class="setup setup_all">
                                                    <label for="barcode_settings">{{ __('Barcode Sticker Settings') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('cash_counters') ? 'CHECKED' : '' }}
                                                    name="cash_counters" id="cash_counters" class="setup setup_all">
                                                    <label for="cash_counters">{{ __('Cash Counter') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="dashboard"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#dashboard_permission"
                                        aria-expanded="false">
                                        {{ __('Dashboard Permissions') }}
                                    </a>
                                </div>
                                <div id="dashboard_permission" class="collapse" data-bs-parent="#dashboard_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="dashboard" id="select_all"
                                                        data-target="dashboard_all" autocomplete="off">
                                                    <strong>{{ __('Dashboard') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('view_dashboard_data') ? 'CHECKED' : '' }}
                                                    name="view_dashboard_data" id="view_dashboard_data" class="dashboard dashboard_all">
                                                   <label for="view_dashboard_data">{{ __('View Dashboard Data') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="accounting"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#accounting_permission"
                                        aria-expanded="false">
                                        {{ __('Accounting Permission') }}
                                    </a>
                                </div>
                                <div id="accounting_permission" class="collapse" data-bs-parent="#accounting_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="accounting" id="select_all"
                                                        data-target="accounting_all" autocomplete="off">
                                                    <strong>{{ __('Accounting') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('accounting_access') ? 'CHECKED' : '' }}
                                                    name="accounting_access" id="accounting_access" class="accounting accounting_all">
                                                    <label for="accounting_access">{{ __('Access Accounting') }}</label>
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
                                            <input type="checkbox" class="ms-2" id="select_all" data-target="hrms"
                                                autocomplete="off">
                                        </p>
                                        <a data-bs-toggle="collapse" class="collapsed" href="#hrm_permission"
                                            aria-expanded="false">
                                            {{ __('HRM Permissions') }}
                                        </a>
                                    </div>
                                    <div id="hrm_permission" class="collapse" data-bs-parent="#hrm_permission"
                                        style="">
                                        <div class="element-body border-top">
                                            <div class="row">
                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                        <input type="checkbox" class="hrms" id="select_all"
                                                            data-target="hrm_all" autocomplete="off">
                                                        <strong>{{ __('HRM') }}</strong>
                                                        </label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('hrm_dashboard') ? 'CHECKED' : '' }}
                                                        name="hrm_dashboard" id="hrm_dashboard" class="hrms hrm_all">
                                                        <label for="hrm_dashboard">{{ __('HRM Dashboard') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('attendance') ? 'CHECKED' : '' }}
                                                         name="attendance" id="attendance" class="hrms hrm_all">
                                                        <label for="attendance">{{ __('Attendance') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('payroll') ? 'CHECKED' : '' }}
                                                         name="payroll" id="payroll" class="hrms hrm_all">
                                                       <label for="payroll"> {{ __('Payroll') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('payroll_report') ? 'CHECKED' : '' }}
                                                         name="payroll_report" id="payroll_report" class="hrms hrm_all">
                                                        <label for="payroll_report">{{ __('Payroll Report') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('payroll_payment_report') ? 'CHECKED' : '' }}
                                                         name="payroll_payment_report" id="payroll_payment_report" class="hrms hrm_all">
                                                        <label for="payroll_payment_report">{{ __('Payroll Payment Report') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('attendance_report') ? 'CHECKED' : '' }}
                                                         name="attendance_report" id="attendance_report" class="hrms hrm_all">
                                                        <label for="attendance_report">{{ __('Attendance Report') }}</label>
                                                    </p>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="hrms" id="select_all"
                                                                data-target="hrm_others_all" autocomplete="off">
                                                            <strong>{{ __('Others') }}</strong>
                                                        </label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('leave_type') ? 'CHECKED' : '' }}
                                                        name="leave_type" id="leave_type" class="hrms hrm_others_all">
                                                       <label for="leave_type"> {{ __('Leave Type') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('leave_assign') ? 'CHECKED' : '' }}
                                                         name="leave_assign" id="leave_assign" class="hrms hrm_others_all">
                                                        <label for="leave_assign">{{ __('Leave Assign') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('shift') ? 'CHECKED' : '' }}
                                                         name="shift" id="shift" class="hrms hrm_others_all">
                                                        <label for="shift">{{ __('Shift') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('view_allowance_and_deduction') ? 'CHECKED' : '' }}
                                                        name="view_allowance_and_deduction" id="view_allowance_and_deduction" class="hrms hrm_others_all">
                                                        <label for="view_allowance_and_deduction">{{ __('Allowance and deduction') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('holiday') ? 'CHECKED' : '' }}
                                                         name="holiday" id="holiday" class="hrms hrm_others_all">
                                                       <label for="holiday"> {{ __('Holidays') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('department') ? 'CHECKED' : '' }}
                                                         name="department" id="department" class="hrms hrm_others_all">
                                                       <label for="department"> {{ __('Departments') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('designation') ? 'CHECKED' : '' }}
                                                         name="designation" id="designation" class="hrms hrm_others_all">
                                                       <label for="designation">{{ __('Designation') }}</label>
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
                                            <input type="checkbox" class="ms-2" id="select_all"
                                                data-target="manage_tasks" autocomplete="off">
                                        </p>
                                        <a data-bs-toggle="collapse" class="collapsed" href="#manage_tasks_permission"
                                            aria-expanded="false">
                                            {{ __('Manage Task Permissions') }}
                                        </a>
                                    </div>
                                    <div id="manage_tasks_permission" class="collapse"
                                        data-bs-parent="#manage_tasks_permission" style="">
                                        <div class="element-body border-top">
                                            <div class="row">
                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                        <input type="checkbox" class="manage_tasks" id="select_all"
                                                            data-target="manage_task_all" autocomplete="off">
                                                        <strong>{{ __('Manage Task') }}</strong>
                                                        </label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('assign_todo') ? 'CHECKED' : '' }}
                                                        name="assign_todo" id="assign_todo" class="manage_tasks manage_task_all">
                                                       <label for="assign_todo"> {{ __('Todo') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('work_space') ? 'CHECKED' : '' }}
                                                         name="work_space" id="work_space" class="manage_tasks manage_task_all">
                                                        <label for="work_space">{{ __('Work Spaces') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('memo') ? 'CHECKED' : '' }}
                                                         name="memo" id="memo" class="manage_tasks manage_task_all">
                                                       <label for="memo"> {{ __('Memo') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('msg') ? 'CHECKED' : '' }}
                                                         name="msg" id="msg" class="manage_tasks manage_task_all">
                                                        <label for="msg">{{ __('Message') }}</label>
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
                                            <input type="checkbox" class="ms-2" id="select_all"
                                                data-target="manufacturings" autocomplete="off">
                                        </p>
                                        <a data-bs-toggle="collapse" class="collapsed" href="#manufacturing_permission"
                                            aria-expanded="false">
                                            {{ __('Manufacturing Permissions') }}
                                        </a>
                                    </div>
                                    <div id="manufacturing_permission" class="collapse"
                                        data-bs-parent="#manufacturing_permission" style="">
                                        <div class="element-body border-top">
                                            <div class="row">
                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                        <input type="checkbox" class="manufacturings" id="select_all"
                                                            data-target="manufacturing_all" autocomplete="off">
                                                        <strong>{{ __('Manufacturing') }}</strong>
                                                        </label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('process_view') ? 'CHECKED' : '' }}
                                                        name="process_view" id="process_view" class="manufacturings manufacturing_all">
                                                        <label for="process_view">{{ __('View process') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('process_add') ? 'CHECKED' : '' }}
                                                         name="process_add" id="process_add" class="manufacturings manufacturing_all">
                                                        <label for="process_add">{{ __('Add Process') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('process_edit') ? 'CHECKED' : '' }}
                                                        name="process_edit" id="process_edit" class="manufacturings manufacturing_all">
                                                        <label for="process_edit">{{ __('Edit Process') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('process_delete') ? 'CHECKED' :'' }}
                                                        name="process_delete" id="process_delete" class="manufacturings manufacturing_all">
                                                      <label for="process_delete">{{ __('Delete Process') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('production_view') ? 'CHECKED' : '' }}
                                                         name="production_view" id="production_view" class="manufacturings manufacturing_all">
                                                       <label for="production_view">{{ __('View Production') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('production_add') ? 'CHECKED' :'' }}
                                                        name="production_add" id="production_add" class="manufacturings manufacturing_all">
                                                        <label for="production_add">{{ __('Add Production') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('production_edit') ? 'CHECKED':'' }}
                                                         name="production_edit" id="production_edit" class="manufacturings manufacturing_all">
                                                        <label for="production_edit">{{ __('Edit Production') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('production_delete') ? 'CHECKED' : '' }}
                                                        name="production_delete" id="production_delete" class="manufacturings manufacturing_all">
                                                        <label for="production_delete">{{ __('Delete Production') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('manufacturing_settings') ? 'CHECKED' : '' }}
                                                         name="manufacturing_settings" id="manufacturing_settings" class="manufacturings manufacturing_all">
                                                        <label for="manufacturing_settings">{{ __('Manufacturing Settings') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox"
                                                        {{ $role->hasPermissionTo('manufacturing_report') ? 'CHECKED' : '' }}
                                                        name="manufacturing_report" id="manufacturing_report" class="manufacturings manufacturing_all">
                                                       <label for="manufacturing_report">{{ __('Manufacturing Report') }}</label>
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
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="others"
                                            autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#other_permission"
                                        aria-expanded="false">
                                        {{ __('Others Permissions') }}
                                    </a>
                                </div>
                                <div id="other_permission" class="collapse" data-bs-parent="#other_permission"
                                    style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                    <input type="checkbox" class="others" id="select_all"
                                                        data-target="other_all" autocomplete="off">
                                                    <strong>{{ __('Others') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('today_summery') ? 'CHECKED' : '' }}
                                                     name="today_summery" id="today_summery" class="others other_all">
                                                    <label for="today_summery">{{ __('Today Summery') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                    {{ $role->hasPermissionTo('communication') ? 'CHECKED' : '' }}
                                                     name="communication" id="communication" class="others other_all">
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
                            <button type="button" class="btn loading_button d-hide"><i
                                    class="fas fa-spinner"></i></button>
                            <button class="btn btn-sm btn-success submit_button float-end">@lang('menu.save')</button>
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
