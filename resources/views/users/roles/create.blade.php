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
        .accordion-button { position: relative; display: flex; align-items: center; width: 100%; padding: 0rem 1.25rem; font-size: 1rem; color: #212529; text-align: left; background-color: #fff; border: 0; border-radius: 0; overflow-anchor: none; transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, border-radius .15s ease; }

        .form_element { border: 1px solid #adadad; padding: 0; background: #ffffff; border: 1px solid var(--brand-color); }

        .accordion-header { position: relative; margin-bottom: 0; }

        .accordion-header a { display: block;
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
@section('title', 'Add Role - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Add Role') }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_role_form" action="{{ route('users.role.store') }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Role Name') }} : <span class="text-danger">*</span></b> </label>
                                        <div class="col-8">
                                            <input required type="text" name="role_name" class="form-control" id="role_name" placeholder="{{ __('Role Name') }}">
                                            <span class="error error_role_name">{{ $errors->first('role_name') }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if ($generalSettings['subscription']->current_shop_count > 1 || $generalSettings['subscription']->has_business == 1)
                                    <div class="col-md-4">
                                        <div class="input-group align-items-center gap-2">
                                            <label> <b>{{ __("Has Access To All Store/Place") }}</b> </label>
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" name="has_access_to_all_area" id="has_access_to_all_area"  autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="input-group align-items-center gap-2">
                                        <label> <b> {{ __("Select All") }} </b> </label>
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" class="select_all super_select_all" id="super_select_all" data-target="super_select_all" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordionPanelsStayOpenExample">

                        @if ($generalSettings['subscription']->features['users'] == 1)
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
                                                            <strong> {{ __('Users') }}</strong>
                                                        </label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_view" id="user_view" class="users users_all">
                                                        <label for="user_view"> {{ __('View User') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_add" id="user_add" class="users users_all">
                                                        <label for="user_add">{{ __('Add User') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_edit" id="user_edit" class="users users_all">
                                                        <label for="user_edit"> {{ __('Edit User') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_delete" id="user_delete" class="users users_all">
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
                                                        <input type="checkbox" name="role_view" id="role_view" class="users all_role">
                                                        <label for="role_view">{{ __('View Role') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="role_add" id="role_add" class="users all_role">
                                                        <label for="role_add"> {{ __('Add Role') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="role_edit" id="role_edit" class="users all_role">
                                                        <label for="role_edit">{{ __('Edit Role') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="role_delete" id="role_delete" class="users all_role">
                                                        <label for="role_delete"> {{ __('Delete Role') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['contacts'] == 1)
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
                                                        <input type="checkbox" name="supplier_all" id="supplier_all" class="contacts contact_all">
                                                        <label for="supplier_all">{{ __('View All Supplier') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="supplier_manage" id="supplier_manage" class="contacts contact_all">
                                                        <label for="supplier_manage">{{ __('Manage Supplier') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="supplier_add" id="supplier_add" class="contacts contact_all">
                                                        <label for="supplier_add">{{ __('Add Supplier') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="supplier_edit" id="supplier_edit" class="contacts contact_all">
                                                        <label for="supplier_edit">{{ __('Edit Supplier') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="supplier_delete" id="supplier_delete" class="contacts contact_all">
                                                        <label for="supplier_delete">{{ __('Delete Supplier') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="supplier_import" id="supplier_import" class="contacts contact_all">
                                                        <label for="supplier_import"> {{ __('Import Suppliers') }}</label>
                                                    </p>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input id="select_all" type="checkbox" class="contacts" data-target="customer_all" autocomplete="off">
                                                            <strong>{{ __('Customers') }}</strong>
                                                        </label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="customer_all" id="customer_all" class="contacts customer_all">
                                                        <label for="customer_all">{{ __('View All Customer') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="customer_manage" id="customer_manage" class="contacts customer_all">
                                                        <label for="customer_manage">{{ __('Manage Customer') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="customer_add" id="customer_add" class="contacts customer_all">
                                                        <label for="customer_add">{{ __('Add Customer') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="customer_edit" id="customer_edit" class="contacts customer_all">
                                                        <label for="customer_edit">{{ __('Edit Customer') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="customer_delete" id="customer_delete" class="contacts customer_all">
                                                        <label for="customer_delete"> {{ __('Delete Customer') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="customer_import" id="customer_import" class="contacts customer_all">
                                                        <label for="customer_import">{{ __('Import Customers') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="customer_group" id="customer_group" class="contacts customer_all">
                                                        <label for="customer_group"> {{ __('Customer Group') }} &rarr; {{ __('View/Add/Edit/Delete') }}</label>
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
                                                        <input type="checkbox" name="money_receipt_index" id="money_receipt_index" class="contacts money_receipt_all">
                                                        <label for="money_receipt_index">{{ __('Money Receipt List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="money_receipt_add" id="money_receipt_add" class="contacts money_receipt_all">
                                                        <label for="money_receipt_add">{{ __('Money Receipt Generate') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="money_receipt_edit" id="money_receipt_edit" class="contacts money_receipt_all">
                                                        <label for="money_receipt_edit">{{ __('Money Receipt Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="money_receipt_delete" id="money_receipt_delete" class="contacts money_receipt_all">
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
                                                        <input type="checkbox" name="customer_report" id="customer_report" class="contacts contact_reports_all">
                                                        <label for="customer_report">{{ __('Customer Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="supplier_report" id="supplier_report" class="contacts contact_reports_all">
                                                        <label for="supplier_report">{{ __('Supplier Report') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['inventory'] == 1)
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
                                                        <input type="checkbox" name="product_all" id="product_all" class="products product_all">
                                                        <label for="product_all">{{ __('View All Product') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_add" id="product_add" class="products product_all">
                                                        <label for="product_add"> {{ __('Add Product') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_edit" id="product_edit" class="products product_all">
                                                        <label for="product_edit">{{ __('Edit Product') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_delete" id="product_delete" class="products product_all">
                                                        <label for="product_delete"> {{ __('Delete Product') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="manage_price_group" id="manage_price_group" class="products product_all">
                                                        <label for="manage_price_group">{{ __('Manage Price Group') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="openingStock_add" id="openingStock_add" class="products product_all">
                                                        <label for="openingStock_add"> {{ __('Add/Edit Opening Stock') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_import" id="product_import" class="products product_all">
                                                        <label for="product_import">{{ __('Import Product') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_expired_list" id="product_expired_list" class="products product_all">
                                                        <label for="product_expired_list">{{ __('Expired Product List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="generate_barcode" id="generate_barcode" class="products product_all">
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
                                                        <input type="checkbox" name="product_category_index" id="product_category_index" class="products product_category">
                                                        <label for="product_category_index">{{ __('View All Category') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_category_add" id="product_category_add" class="products product_category">
                                                        <label for="product_category_add">{{ __('Add Category') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_category_edit" id="product_category_edit" class="products product_category">
                                                        <label for="product_category_edit">{{ __('Edit Category') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_category_delete" id="product_category_delete" class="products product_category">
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
                                                        <input type="checkbox" name="product_brand_index" id="product_brand_index" class="products product_brand">
                                                        <label for="product_brand_index">{{ __('View All Brand') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_brand_add" id="product_brand_add" class="products product_brand">
                                                        <label for="product_brand_add">{{ __('Add Brand') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_brand_edit" id="product_brand_edit" class="products product_brand">
                                                        <label for="product_brand_edit">{{ __('Edit Brand') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_brand_delete" id="product_brand_delete" class="products product_brand">
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
                                                        <input type="checkbox" name="product_unit_index" id="product_unit_index" class="products product_unit">
                                                        <label for="product_unit_index">{{ __('View All Unit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_unit_add" id="product_unit_add" class="products product_unit">
                                                        <label for="product_unit_add">{{ __('Add Unit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_unit_edit" id="product_unit_edit" class="products product_unit">
                                                        <label for="product_unit_edit">{{ __('Edit Unit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_unit_delete" id="product_unit_delete" class="products product_unit">
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
                                                        <input type="checkbox" name="product_variant_index" id="product_variant_index" class="products product_variant">
                                                        <label for="product_variant_index">{{ __('View All Variant') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_variant_add" id="product_variant_add" class="products product_variant">
                                                        <label for="product_variant_add">{{ __('Add Variant') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_variant_edit" id="product_variant_edit" class="products product_variant">
                                                        <label for="product_variant_edit">{{ __('Edit Variant') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_variant_delete" id="product_variant_delete" class="products product_variant">
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
                                                        <input type="checkbox" name="product_warranty_index" id="product_warranty_index" class="products product_warranties">
                                                        <label for="product_warranty_index">{{ __('View All Warranty') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_warranty_add" id="product_warranty_add" class="products product_warranties">
                                                        <label for="product_warranty_add">{{ __('Add Warranty') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_warranty_edit" id="product_warranty_edit" class="products product_warranties">
                                                        <label for="product_warranty_edit">{{ __('Edit Warranty') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_warranty_delete" id="product_warranty_delete" class="products product_warranties">
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
                                                        <input type="checkbox" name="stock_report" id="stock_report" class="products product_report">
                                                        <label for="stock_report">{{ __('Stock Report') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="stock_in_out_report" id="stock_in_out_report" class="products product_report">
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
                                                        <input type="checkbox" name="selling_price_group_index" id="selling_price_group_index" class="products product_selling_price_group_index">
                                                        <label for="selling_price_group_index">{{ __('View All Selling Price Group') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="selling_price_group_add" id="selling_price_group_add" class="products product_selling_price_group_index">
                                                        <label for="selling_price_group_add">{{ __('Selling Price Group Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="selling_price_group_index_edit" id="selling_price_group_index_edit" class="products product_selling_price_group_index">
                                                        <label for="selling_price_group_edit">{{ __('Selling Price Group Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="selling_price_group_index_delete" id="selling_price_group_delete" class="products product_selling_price_group_index">
                                                        <label for="selling_price_group_index_delete">{{ __('Selling Price Group Delete') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['purchase'] == 1)
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
                                                        <input type="checkbox" name="purchase_all" id="purchase_all" class="purchase purchase_all">
                                                        <label for="purchase_all">{{ __('View All Purchase') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchased_product_list" id="purchased_product_list" class="purchase purchase_all">
                                                        <label for="purchase_all">{{ __('Purchased_product_list') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_add" id="purchase_add" class="purchase purchase_all">
                                                        <label for="purchase_add">{{ __('Add Purchase') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_edit" id="purchase_edit" class="purchase purchase_all">
                                                        <label for="purchase_edit">{{ __('Edit Purchase') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_delete" id="purchase_delete" class="purchase purchase_all">
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
                                                        <input type="checkbox" name="purchase_order_index" id="purchase_order_index" class="purchase purchase_order">
                                                        <label for="purchase_order_index">{{ __('View All Purchase Order') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_order_add" id="purchase_order_add" class="purchase purchase_order">
                                                        <label for="purchase_order_add">{{ __('Purchase Order Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_order_edit" id="purchase_order_edit" class="purchase purchase_order">
                                                        <label for="purchase_order_edit">{{ __('Purchase Order Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_order_delete" id="purchase_order_delete" class="purchase purchase_order">
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
                                                        <input type="checkbox" name="purchase_return_index" id="purchase_return_index" class="purchase purchase_return">
                                                        <label for="purchase_return_index">{{ __('View All Purchase Return') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_return_add" id="purchase_return_add" class="purchase purchase_return">
                                                        <label for="purchase_return_add">{{ __('Purchase Return Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_return_edit" id="purchase_return_edit" class="purchase purchase_return">
                                                        <label for="purchase_return_edit">{{ __('Purchase Return Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_return_delete" id="purchase_return_delete" class="purchase purchase_return">
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
                                                        <input type="checkbox" name="purchase_report" id="purchase_report" class="purchase purchase_report">
                                                        <label for="purchase_report">{{ __('Purchase Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_order_report" id="purchase_order_report" class="purchase purchase_report">
                                                        <label for="purchase_order_report">{{ __('Purchase Order Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_ordered_product_report" id="purchase_ordered_product_report" class="purchase purchase_report">
                                                        <label for="purchase_ordered_product_report">{{ __('Purchase Ordered Product Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_return_report" id="purchase_return_report" class="purchase purchase_report">
                                                        <label for="purchase_return_report">{{ __('Purchase Return Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_returned_product_report" id="purchase_returned_product_report" class="purchase purchase_report">
                                                        <label for="purchase_returned_product_report">{{ __('Purchase Returned Products Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_sale_report" id="purchase_sale_report" class="purchase purchase_report">
                                                        <label for="purchase_sale_report">{{ __('Purchase & Sale Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="product_purchase_report" id="product_purchase_report" class="purchase purchase_report">
                                                        <label for="product_purchase_report">{{ __('Purchased Product Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="purchase_payment_report" id="purchase_payment_report" class="purchase purchase_report">
                                                        <label for="purchase_payment_report"> {{ __('Payment Against Purchase Report') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['stock_adjustments'] == 1)
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
                                                        <input type="checkbox" name="stock_adjustment_all" id="stock_adjustment_all" class="adjustment adjustment_all">
                                                        <label for="stock_adjustment_all">{{ __('Stock Adjustment List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="stock_adjustment_add" id="stock_adjustment_add" class="adjustment adjustment_all">
                                                        <label for="adjustment_add"> {{ __('Stock Adjustment Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="stock_adjustment_delete" id="stock_adjustment_delete" class="adjustment adjustment_all">
                                                        <label for="stock_adjustment_delete"> {{ __('Stock Adjustment Delete') }}</label>
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
                                                        <input type="checkbox" name="stock_adjustment_report" id="stock_adjustment_report" class="adjustment adjustment_all">
                                                        <label for="stock_adjustment_report">{{ __('Stock Adjustment Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="stock_adjustment_product_report" id="stock_adjustment_product_report" class="adjustment adjustment_all">
                                                        <label for="stock_adjustment_product_report">{{ __('Stock Adjusted Products Report') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['sales'] == 1)
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
                                                        <input type="checkbox" name="create_add_sale" id="create_add_sale" class="sales sale_all">
                                                        <label for="create_add_sale">{{ __('Create Add Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="view_add_sale" id="view_add_sale" class="sales sale_all">
                                                        <label for="view_add_sale">{{ __('Manage Add Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="edit_add_sale" id="edit_add_sale" class="sales sale_all">
                                                        <label for="edit_add_sale">{{ __('Edit Add Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="delete_add_sale" id="delete_add_sale" class="sales sale_all">
                                                        <label for="delete_add_sale"> {{ __('Delete Add Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sale_draft" id="sale_draft" class="sales sale_all">
                                                        <label for="sale_draft">{{ __('List Draft') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sale_quotation" id="sale_quotation" class="sales sale_all">
                                                        <label for="sale_quotation"> {{ __('List Quotations') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sold_product_list" id="sold_product_list" class="sales sale_all">
                                                        <label for="sold_product_list"> {{ __('Sold Product List') }}</label>
                                                    </p>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="checkbox_input_wrap mt-4">
                                                        <input type="checkbox" name="edit_price_sale_screen" id="edit_price_sale_screen" class="sales sale_all">
                                                        <label for="edit_price_sale_screen"> {{ __('Edit Product Price from Sales Screen') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="edit_discount_sale_screen" id="edit_discount_sale_screen" class="sales sale_all">
                                                        <label for="edit_discount_sale_screen">{{ __('Edit Product Discount in Sale Scr') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="shipment_access" id="shipment_access" class="sales sale_all">
                                                        <label for="shipment_access"> {{ __('Access Shipments') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="view_product_cost_is_sale_screed" id="view_product_cost_is_sale_screed" class="sales sale_all">
                                                        <label for="view_product_cost_is_sale_screed"> {{ __('View Product Cost In Sale Screen') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="view_own_sale" id="view_own_sale" class="sales sale_all">
                                                        <label for="view_own_sale">{{ __('View only own Add/POS Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="discounts" id="discounts" class="sales sale_all">
                                                        <label for="discounts"> {{ __('Manage Discount') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sales_order_list" id="sales_order_list" class="sales sale_all">
                                                        <label for="sales_order_list"> {{ __('Sales Order List') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sales_order_to_invoice" id="sales_order_to_invoice" class="sales sale_all">
                                                        <label for="sales_order_to_invoice"> {{ __('Sales Order to Invoice') }}</label>
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
                                                        <input type="checkbox" name="sales_report" id="sales_report" class="sales sales_report">
                                                        <label for="sales_report"> {{ __('Sales Report') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sales_return_report" id="sales_return_report" class="sales sales_report">
                                                        <label for="sales_return_report">{{ __('Sales Return Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sold_product_report" id="sold_product_report" class="sales sales_report">
                                                        <label for="sold_product_report">{{ __('Sold Products Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sales_order_report" id="sales_order_report" class="sales sales_report">
                                                        <label for="sales_order_report">{{ __('Sales Order Report') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sales_ordered_products_report" id="sales_ordered_products_report" class="sales sales_report">
                                                        <label for="sales_ordered_products_report">{{ __('Sales Ordered Products Report') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sales_returned_products_report" id="sales_returned_products_report" class="sales sales_report">
                                                        <label for="sales_returned_products_report">{{ __('Sales Returned Products Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="received_against_sales_report" id="received_against_sales_report" class="sales sales_report">
                                                        <label for="received_against_sales_report">{{ __('Received Against Sales Report') }}</label>
                                                    </p>

                                                    @if ($generalSettings['subscription']->features['cash_counter_count'] > 0)
                                                        <p class="checkbox_input_wrap mt-1">
                                                            <input type="checkbox" name="cash_register_report" id="cash_register_report" class="sales sales_report">
                                                            <label for="cash_register_report">{{ __('Cash Register Reports') }}</label>
                                                        </p>
                                                    @endif

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sale_representative_report" id="sale_representative_report" class="sales sales_report">
                                                        <label for="sale_representative_report">{{ __('Sales Representative Report') }}</label>
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
                                                        <input type="checkbox" name="pos_all" id="pos_all" class="sales pos_sale_all">
                                                        <label for="pos_all">{{ __('Manage POS Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="pos_add" id="pos_add" class="sales pos_sale_all">
                                                        <label for="pos_add">{{ __('Add POS Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="pos_edit" id="pos_edit" class="sales pos_sale_all">
                                                        <label for="pos_edit">{{ __('Edit POS Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="pos_delete" id="pos_delete" class="sales pos_sale_all">
                                                        <label for="pos_delete">{{ __('Delete POS Sale') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="edit_price_pos_screen" id="edit_price_pos_screen" class="sales pos_sale_all">
                                                        <label for="edit_price_pos_screen"> {{ __('Edit Product Price From POS Screen') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="edit_discount_pos_screen" id="edit_discount_pos_screen" class="sales pos_sale_all">
                                                        <label for="edit_discount_pos_screen">{{ __('Edit Product Discount From POS Screen') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="mt-2">
                                            <div class="row">
                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="sales" id="select_all" data-target="sales_reurn" autocomplete="off">
                                                            <strong>{{ __('Sales Return') }}</strong>
                                                        </label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="sales_return_index" id="sales_return_index" class="sales sales_reurn">
                                                        <label for="sales_return_index">{{ __('Sales Return List') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="create_sales_return" id="create_sales_return" class="sales sales_reurn">
                                                        <label for="create_sales_return">{{ __('Create Sales Return') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="edit_sales_return" id="edit_sales_return" class="sales sales_reurn">
                                                        <label for="edit_sales_return">{{ __('Edit Sales Return') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="delete_add_sales_return" id="delete_sales_return" class="sales sales_reurn">
                                                        <label for="delete_sales_return">{{ __('Delete Sales Return') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['cash_counter_count'] > 0)
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
                                                        <input type="checkbox" name="register_view" id="register_view" class="cash_register cash_register_all">
                                                        <label for="register_view"> {{ __('View Cash Register') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="register_close" id="register_close" class="cash_register cash_register_all">
                                                        <label for="register_close">{{ __('Close Cash Register') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="another_register_close" id="another_register_close" class="cash_register cash_register_all">
                                                        <label for="another_register_close">{{ __('Close Another Cash Register') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (
                            $generalSettings['subscription']->has_business == 1 ||
                            $generalSettings['subscription']->current_shop_count > 1 ||
                            $generalSettings['subscription']->features['warehouse_count'] > 0
                        )
                            @if ($generalSettings['subscription']->features['transfer_stocks'] == 1)
                                <div class="accordion-item mb-1">
                                    <div class="form_element rounded mt-0 mb-0">
                                        <div class="accordion-header d-flex">
                                            <p class="checkbox_input_wrap ">
                                                <input type="checkbox" class="ms-2" id="select_all" data-target="transfer_stocks" autocomplete="off">
                                            </p>
                                            <a data-bs-toggle="collapse" class="collapsed" href="#transfer_stocks_permission" aria-expanded="false">
                                                {{ __('Transfer Stocks Permissions') }}
                                            </a>
                                        </div>
                                        <div id="transfer_stocks_permission" class="collapse" data-bs-parent="#transfer_stocks_permission" style="">
                                            <div class="element-body border-top">
                                                <div class="row">
                                                    <div class="col-lg-3 col-sm-6">
                                                        <p class="text-info checkbox_input_wrap">
                                                            <label>
                                                                <input type="checkbox" class="transfer_stock" id="select_all" data-target="transfer_stock" autocomplete="off">
                                                                <strong>{{ __('Transfer Stock') }}</strong>
                                                            </label>
                                                        </p>

                                                        <p class="checkbox_input_wrap mt-1">
                                                            <input type="checkbox" name="transfer_stock_index" id="transfer_stock_index" class="transfer_stock transfer_stocks">
                                                            <label for="transfer_stock_index">{{ __('Transfer Stock List') }}</label>
                                                        </p>

                                                        <p class="checkbox_input_wrap mt-1">
                                                            <input type="checkbox" name="transfer_stock_create" id="transfer_stock_create" class="transfer_stock transfer_stocks">
                                                            <label for="transfer_stock_create">{{ __('Transfer Stock Add') }}</label>
                                                        </p>

                                                        <p class="checkbox_input_wrap mt-1">
                                                            <input type="checkbox" name="transfer_stock_edit" id="transfer_stock_edit" class="transfer_stock transfer_stocks">
                                                            <label for="transfer_stock_edit">{{ __('Transfer Stock Edit') }}</label>
                                                        </p>

                                                        <p class="checkbox_input_wrap mt-1">
                                                            <input type="checkbox" name="transfer_stock_delete" id="transfer_stock_delete" class="transfer_stock transfer_stocks">
                                                            <label for="transfer_stock_delete">{{ __('Transfer Stock Delete') }}</label>
                                                        </p>
                                                    </div>

                                                    <div class="col-lg-3 col-sm-6">
                                                        <p class="text-info checkbox_input_wrap">
                                                            <label>
                                                                <input type="checkbox" class="transfer_stock_receive" id="select_all" data-target="transfer_stock_receive" autocomplete="off">
                                                                <strong>{{ __('Receive Transferred Stocks') }}</strong>
                                                            </label>
                                                        </p>

                                                        <p class="checkbox_input_wrap mt-1">
                                                            <input type="checkbox" name="transfer_stock_receive_from_warehouse" id="transfer_stock_receive_from_warehouse" class="transfer_stock_receive transfer_stocks">
                                                            <label for="transfer_stock_receive_from_warehouse">{{ __('Receive From Warehouse') }}</label>
                                                        </p>

                                                        <p class="checkbox_input_wrap mt-1">
                                                            <input type="checkbox" name="transfer_stock_receive_from_branch" id="transfer_stock_receive_from_branch" class="transfer_stock_receive transfer_stocks">
                                                            <label for="transfer_stock_receive_from_branch">{{ __('Receive From Shop/Business') }}</label>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="accordion-item mb-1">
                            <div class="form_element rounded mt-0 mb-0">
                                <div class="accordion-header d-flex">
                                    <p class="checkbox_input_wrap ">
                                        <input type="checkbox" class="ms-2" id="select_all" data-target="reports" autocomplete="off">
                                    </p>
                                    <a data-bs-toggle="collapse" class="collapsed" href="#reports_permission" aria-expanded="false">
                                        {{ __('All Report Permissions') }}
                                    </a>
                                </div>
                                <div id="reports_permission" class="collapse" data-bs-parent="#reports_permission" style="">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info checkbox_input_wrap">
                                                    <label>
                                                        <input type="checkbox" class="reports" id="select_all" data-target="report_all" autocomplete="off">
                                                        <strong>{{ __('Reports') }}</strong>
                                                    </label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="tax_report" id="tax_report" class="reports report_all">
                                                    <label for="tax_report">{{ __('Tax Report') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="production_report" id="production_report" class="reports report_all">
                                                    <label for="production_report">{{ __('Production Report') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($generalSettings['subscription']->features['setup'] == 1)
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
                                                        <div class="col-md-6">
                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="business_or_shop_settings" id="business_or_shop_settings" class="general_settings setup">
                                                                <label for="business_or_shop_settings">{{ __('Business/Shop Settings') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="dashboard_settings" id="dashboard_settings" class="general_settings setup">
                                                                <label for="dashboard_settings">{{ __('Dashboard Settings') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="product_settings" id="product_settings" class="general_settings setup">
                                                                <label for="product_settings">{{ __('Product Settings') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="purchase_settings" id="purchase_settings" class="general_settings setup">
                                                                <label for="purchase_settings">{{ __('Purchase Settings') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="manufacturing_settings" id="manufacturing_settings" class="general_settings setup">
                                                                <label for="manufacturing_settings">{{ __('Manufacturing Settings') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="add_sale_settings" id="add_sale_settings" class="general_settings setup">
                                                                <label for="add_sale_settings">{{ __('Add Sale Settings') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="pos_sale_settings" id="pos_sale_settings" class="general_settings setup">
                                                                <label for="pos_sale_settings">{{ __('Pos Sale Settings') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="prefix_settings" id="prefix_settings" class="general_settings setup">
                                                                <label for="prefix_settings"> {{ __('Prefix Setting') }}</label>
                                                            </p>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="invoice_layout_settings" id="invoice_layout_settings" class="general_settings setup">
                                                                <label for="invoice_layout_settings"> {{ __('Invoice Layout Setting') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="print_settings" id="print_settings" class="general_settings setup">
                                                                <label for="print_settings"> {{ __('Print Setting') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="system_settings" id="system_settings" class="general_settings setup">
                                                                <label for="system_settings"> {{ __('System Setting') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="reward_point_settings" id="reward_point_settings" class="general_settings setup">
                                                                <label for="reward_point_settings"> {{ __('Reward Point Setting') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="module_settings" id="module_settings" class="general_settings setup">
                                                                <label for="module_settings"> {{ __('Module Setting') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="send_email_settings" id="send_email_settings" class="general_settings setup">
                                                                <label for="send_email_settings"> {{ __('Send Email Setting') }}</label>
                                                            </p>

                                                            <p class="checkbox_input_wrap mt-1">
                                                                <input type="checkbox" name="send_sms_settings" id="send_sms_settings" class="general_settings setup">
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
                                                        <input type="checkbox" name="warehouses_index" id="warehouses_index" class="warehouses setup">
                                                        <label for="warehouses_index">{{ __('Warehouse List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="warehouses_add" id="warehouses_add" class="warehouses setup">
                                                        <label for="warehouses_add">{{ __('Warehouse Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="warehouses_edit" id="warehouses_edit" class="warehouses setup">
                                                        <label for="warehouses_edit">{{ __('Warehouse Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="warehouses_delete" id="warehouses_delete" class="warehouses setup">
                                                        <label for="warehouses_delete">{{ __('Warehouse Delete') }}</label>
                                                    </p>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="setup" id="select_all" data-target="shops" autocomplete="off">
                                                            <strong>{{ __('Shops') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="shops_index" id="shops_index" class="shops setup">
                                                        <label for="shops_index">{{ __('Shop List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="shops_create" id="shops_create" class="shops setup">
                                                        <label for="shops_create">{{ __('Shop Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="shops_edit" id="shops_edit" class="shops setup">
                                                        <label for="shops_edit">{{ __('Shop Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="shops_delete" id="shops_delete" class="shops setup">
                                                        <label for="shops_delete">{{ __('Shop Delete') }}</label>
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
                                                        <input type="checkbox" name="payment_methods_index" id="payment_methods_index" class="payment_methods setup">
                                                        <label for="payment_methods_index">{{ __('Payment Method List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payment_methods_add" id="payment_methods_add" class="payment_methods setup">
                                                        <label for="payment_methods_add">{{ __('Payment Method Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payment_methods_edit" id="payment_methods_edit" class="payment_methods setup">
                                                        <label for="payment_methods_edit">{{ __('Payment Method Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payment_methods_delete" id="payment_methods_delete" class="payment_methods setup">
                                                        <label for="payment_methods_delete">{{ __('Payment Method Delete') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payment_methods_settings" id="payment_methods_settings" class="payment_methods setup">
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
                                                        <input type="checkbox" name="invoice_layouts_index" id="invoice_layouts_index" class="invoice_layouts setup">
                                                        <label for="invoice_layouts_index">{{ __('Invoice Layout List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="invoice_layouts_add" id="invoice_layouts_add" class="invoice_layouts setup">
                                                        <label for="invoice_layouts_add">{{ __('Invoice Layout Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="invoice_layouts_edit" id="invoice_layouts_edit" class="invoice_layouts setup">
                                                        <label for="invoice_layouts_edit">{{ __('Invoice Layout Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="invoice_layouts_delete" id="invoice_layouts_delete" class="invoice_layouts setup">
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
                                                        <input type="checkbox" name="cash_counters_index" id="cash_counters_index" class="cash_counters setup">
                                                        <label for="cash_counters_index">{{ __('Cash Counter List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="cash_counters_add" id="cash_counters_add" class="cash_counters setup">
                                                        <label for="cash_counters_add">{{ __('Cash Counter Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="cash_counters_edit" id="cash_counters_edit" class="cash_counters setup">
                                                        <label for="cash_counters_edit">{{ __('Cash Counter Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="cash_counters_delete" id="cash_counters_delete" class="cash_counters setup">
                                                        <label for="cash_counters_delete">{{ __('Cash Counter Delete') }}</label>
                                                    </p>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="setup" id="select_all" data-target="billing" autocomplete="off">
                                                            <strong>{{ __('Billing') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="billing_index" id="billing_index" class="billing setup">
                                                        <label for="billing_index">{{ __('View Billing') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="billing_upgrade_plan" id="billing_upgrade_plan" class="billing setup">
                                                        <label for="billing_upgrade_plan">{{ __('Upgrade Plan') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="billing_business_add" id="billing_business_add" class="billing setup">
                                                        <label for="billing_business_add">{{ __('Add Multi Shop Management System/Company') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="billing_shop_add" id="billing_shop_add" class="billing setup">
                                                        <label for="billing_shop_add">{{ __('Add/Increase Shop') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="billing_renew_shop" id="billing_renew_shop" class="billing setup">
                                                        <label for="billing_renew_shop">{{ __('Renew Shop') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="billing_pay_due_payment" id="billing_pay_due_payment" class="billing setup">
                                                        <label for="billing_pay_due_payment">{{ __('Pay Due Payment') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                                    <input type="checkbox" name="view_dashboard_data" id="view_dashboard_data" class="dashboard dashboard_all">
                                                    <label for="view_dashboard_data">{{ __('View Dashboard Data') }}</label>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($generalSettings['subscription']->features['accounting'] == 1)
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
                                                        <input type="checkbox" name="banks_index" id="banks_index" class="accounting banks">
                                                        <label for="banks_index">{{ __('Bank List') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="banks_create" id="banks_create" class="accounting banks">
                                                        <label for="banks_create">{{ __('Bank Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="banks_edit" id="banks_edit" class="accounting banks">
                                                        <label for="banks_edit">{{ __('Bank Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="banks_edit" id="banks_delete" class="accounting banks">
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
                                                        <input type="checkbox" name="account_groups_index" id="account_groups_index" class="accounting account_groups">
                                                        <label for="account_groups_index">{{ __('Account Group List') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="account_groups_create" id="account_groups_create" class="accounting account_groups">
                                                        <label for="account_groups_create">{{ __('Account Group Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="account_groups_edit" id="account_groups_edit" class="accounting account_groups">
                                                        <label for="account_groups_edit">{{ __('Account Group Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="account_groups_delete" id="account_groups_delete" class="accounting account_groups">
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
                                                        <input type="checkbox" name="accounts_index" id="accounts_index" class="accounting accounts">
                                                        <label for="accounts_index">{{ __('Account List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="accounts_create" id="accounts_create" class="accounting accounts">
                                                        <label for="accounts_create">{{ __('Account Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="accounts_edit" id="accounts_edit" class="accounting accounts">
                                                        <label for="accounts_edit">{{ __('Account Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="accounts_delete" id="accounts_delete" class="accounting accounts">
                                                        <label for="accounts_delete">{{ __('Account Delete') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="accounts_bank_account_create" id="accounts_bank_account_create" class="accounting accounts">
                                                        <label for="accounts_bank_account_create">{{ __('Add Bank Account') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="accounts_ledger" id="accounts_ledger" class="accounting accounts">
                                                        <label for="accounts_ledger">{{ __('Account Ledger') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="capital_accounts_index" id="capital_accounts_index" class="accounting accounts">
                                                        <label for="capital_accounts_index">{{ __('Capital Accounts') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="duties_and_taxes_index" id="duties_and_taxes_index" class="accounting accounts">
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
                                                        <input type="checkbox" name="receipts_index" id="receipts_index" class="accounting receipts">
                                                        <label for="account_groups_index">{{ __('Receipt List') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="receipts_create" id="receipts_create" class="accounting receipts">
                                                        <label for="receipts_create">{{ __('Receipt Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="receipts_edit" id="receipts_edit" class="accounting receipts">
                                                        <label for="receipts_edit">{{ __('Receipt Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="receipts_delete" id="receipts_delete" class="accounting receipts">
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
                                                        <input type="checkbox" name="payments_index" id="receipts_index" class="accounting payments">
                                                        <label for="payments_index">{{ __('Payment List') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payments_create" id="payments_create" class="accounting payments">
                                                        <label for="payments_create">{{ __('Payment Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payments_edit" id="payments_edit" class="accounting payments">
                                                        <label for="payments_edit">{{ __('Payment Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payments_delete" id="payments_delete" class="accounting payments">
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
                                                        <input type="checkbox" name="expenses_index" id="expenses_index" class="accounting expenses">
                                                        <label for="expenses_index">{{ __('Expense List') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="expenses_create" id="expenses_create" class="accounting expenses">
                                                        <label for="expenses_create">{{ __('Expense Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="expenses_edit" id="expenses_edit" class="accounting expenses">
                                                        <label for="expenses_edit">{{ __('Expense Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="expenses_delete" id="expenses_delete" class="accounting expenses">
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
                                                        <input type="checkbox" name="contras_index" id="contras_index" class="accounting contras">
                                                        <label for="contras_index">{{ __('Contra List') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="contras_create" id="contras_create" class="accounting contras">
                                                        <label for="contras_create">{{ __('Contra Add') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="contras_edit" id="contras_edit" class="accounting contras">
                                                        <label for="contras_edit">{{ __('Contra Edit') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="contras_delete" id="contras_delete" class="accounting contras">
                                                        <label for="contras_delete">{{ __('Contra Delete') }}</label>
                                                    </p>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="accounting" id="select_all" data-target="account_reports" autocomplete="off">
                                                            <strong>{{ __('Account Reports') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="profit_loss" id="profit_loss" class="accounting account_reports">
                                                        <label for="profit_loss">{{ __('Profit/Loss') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="financial_report" id="financial_report" class="accounting account_reports">
                                                        <label for="financial_report">{{ __('Financial Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="profit_loss_account" id="profit_loss_account" class="accounting account_reports">
                                                        <label for="profit_loss_account">{{ __('Profit Loss Account') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="balance_sheet" id="balance_sheet" class="accounting account_reports">
                                                        <label for="balance_sheet">{{ __('Balance Sheet') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="trial_balance" id="trial_balance" class="accounting account_reports">
                                                        <label for="trial_balance">{{ __('Trial Balance') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="cash_flow" id="cash_flow" class="accounting account_reports">
                                                        <label for="cash_flow">{{ __('Cash Flow') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['hrm'] == 1)
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
                                                        <input type="checkbox" name="leaves_index" id="leaves_index" class="hrms leaves">
                                                        <label for="leaves_index">{{ __('Leave List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="leaves_create" id="leaves_create" class="hrms leaves">
                                                        <label for="leaves_create"> {{ __('Leave Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="leaves_edit" id="leaves_edit" class="hrms leaves">
                                                        <label for="leaves_edit"> {{ __('Leave Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="leaves_delete" id="leaves_delete" class="hrms leaves">
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
                                                        <input type="checkbox" name="leave_types_index" id="leave_types_index" class="hrms leave_types">
                                                        <label for="leave_types_index">{{ __('Leave Type List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="leave_types_create" id="leave_types_create" class="hrms leave_types">
                                                        <label for="leave_types_create"> {{ __('Leave Type Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="leave_types_edit" id="leave_types_edit" class="hrms leave_types">
                                                        <label for="leave_types_edit"> {{ __('Leave Type Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="leave_types_delete" id="leave_types_delete" class="hrms leave_types">
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
                                                        <input type="checkbox" name="shifts_index" id="shifts_index" class="hrms shifts">
                                                        <label for="shifts_index">{{ __('Shift List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="shifts_create" id="shifts_create" class="hrms shifts">
                                                        <label for="shifts_create"> {{ __('Shift Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="shifts_edit" id="shifts_edit" class="hrms shifts">
                                                        <label for="shifts_edit"> {{ __('Shift Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="shifts_delete" id="shifts_delete" class="hrms shifts">
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
                                                        <input type="checkbox" name="attendances_index" id="attendances_index" class="hrms attendances">
                                                        <label for="attendances_index">{{ __('Attendance List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="attendances_create" id="attendances_create" class="hrms attendances">
                                                        <label for="attendances_create"> {{ __('Attendance Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="attendances_edit" id="attendances_edit" class="hrms attendances">
                                                        <label for="attendances_edit"> {{ __('Attendance Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="attendances_delete" id="attendances_delete" class="hrms attendances">
                                                        <label for="attendances_delete">{{ __('Attendance Delete') }}</label>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="hrms" id="select_all" data-target="allowances" autocomplete="off">
                                                            <strong>{{ __('Allowances') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="allowances_index" id="allowances_index" class="hrms allowances">
                                                        <label for="allowances_index">{{ __('Allowance List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="allowances_create" id="allowances_index" class="hrms allowances">
                                                        <label for="allowances_index"> {{ __('Allowance Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="allowances_edit" id="allowances_edit" class="hrms allowances">
                                                        <label for="allowances_edit"> {{ __('Allowance Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="allowances_delete" id="allowances_delete" class="hrms allowances">
                                                        <label for="allowances_delete">{{ __('Allowance Delete') }}</label>
                                                    </p>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="hrms" id="select_all" data-target="deductions" autocomplete="off">
                                                            <strong>{{ __('Deductions') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="deductions_index" id="deductions_index" class="hrms deductions">
                                                        <label for="deductions_index">{{ __('Deduction List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="deductions_create" id="deductions_create" class="hrms deductions">
                                                        <label for="deductions_create"> {{ __('Deduction Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="deductions_edit" id="deductions_edit" class="hrms deductions">
                                                        <label for="deductions_edit"> {{ __('Deduction Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="deductions_delete" id="deductions_delete" class="hrms deductions">
                                                        <label for="deductions_delete">{{ __('Deduction Delete') }}</label>
                                                    </p>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="hrms" id="select_all" data-target="holidays" autocomplete="off">
                                                            <strong>{{ __('Holidays') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="holidays_index" id="holidays_index" class="hrms holidays">
                                                        <label for="holidays_index">{{ __('Holiday List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="holidays_create" id="holidays_create" class="hrms holidays">
                                                        <label for="holidays_create"> {{ __('Holiday Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="holidays_edit" id="holidays_edit" class="hrms holidays">
                                                        <label for="holidays_edit"> {{ __('Holiday Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="holidays_delete" id="holidays_delete" class="hrms holidays">
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
                                                        <input type="checkbox" name="departments_index" id="departments_index" class="hrms departments">
                                                        <label for="departments_index">{{ __('Department List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="departments_create" id="departments_create" class="hrms departments">
                                                        <label for="departments_create"> {{ __('Department Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="departments_edit" id="departments_edit" class="hrms departments">
                                                        <label for="departments_edit"> {{ __('Department Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="departments_delete" id="departments_delete" class="hrms departments">
                                                        <label for="departments_delete">{{ __('Department Delete') }}</label>
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
                                                        <input type="checkbox" name="designations_index" id="designations_index" class="hrms designations">
                                                        <label for="designations_index">{{ __('Designation List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="designations_create" id="designations_create" class="hrms designations">
                                                        <label for="designations_create"> {{ __('Designation Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="designations_edit" id="designations_edit" class="hrms designations">
                                                        <label for="designations_edit"> {{ __('Designation Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="designations_delete" id="designations_delete" class="hrms designations">
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
                                                        <input type="checkbox" name="payrolls_index" id="payrolls_index" class="hrms payrolls">
                                                        <label for="payrolls_index">{{ __('Payroll List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payrolls_create" id="payrolls_create" class="hrms payrolls">
                                                        <label for="payrolls_create"> {{ __('Payroll Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payrolls_edit" id="payrolls_edit" class="hrms payrolls">
                                                        <label for="payrolls_edit"> {{ __('Payroll Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payrolls_delete" id="payrolls_delete" class="hrms payrolls">
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
                                                        <input type="checkbox" name="payroll_payments_index" id="payroll_payments_index" class="hrms payroll_payments">
                                                        <label for="payroll_payments_index">{{ __('Payroll Payment List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payroll_payments_create" id="payroll_payments_create" class="hrms payroll_payments">
                                                        <label for="payroll_payments_create"> {{ __('Payroll Payment Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payroll_payments_edit" id="payroll_payments_edit" class="hrms payroll_payments">
                                                        <label for="payroll_payments_edit"> {{ __('Payroll Payment Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payroll_payments_delete" id="payroll_payments_delete" class="hrms payroll_payments">
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
                                                        <input type="checkbox" name="payroll_report" id="payroll_report" class="hrms payroll_reports">
                                                        <label for="payroll_report">{{ __('Payroll Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="payroll_payment_report" id="payroll_payment_report" class="hrms payroll_reports">
                                                        <label for="payroll_payment_report"> {{ __('Payroll Payment Report') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="attendance_report" id="attendance_report" class="hrms payroll_reports">
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
                                                        <input type="checkbox" name="hrm_dashboard" id="hrm_dashboard" class="hrms hrm_others_all">
                                                        <label for="hrm_dashboard">{{ __('HRM Dashboard') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['task_management'] == 1)
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
                                                        <input type="checkbox" name="todo_index" id="todo_index" class="todo task_management">
                                                        <label for="todo_index"> {{ __('Todo List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="todo_create" id="todo_create" class="todo task_management">
                                                        <label for="todo_create"> {{ __('Todo Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="todo_edit" id="todo_edit" class="todo task_management">
                                                        <label for="todo_edit">{{ __('Todo Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="todo_change_status" id="todo_change_status" class="todo task_management">
                                                        <label for="todo_change_status"> {{ __('Todo Change Status') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="todo_delete" id="todo_delete" class="todo task_management">
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
                                                        <input type="checkbox" name="workspaces_index" id="workspace_index" class="workspaces task_management">
                                                        <label for="workspaces_index"> {{ __('Project List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="workspaces_create" id="workspaces_create" class="workspaces task_management">
                                                        <label for="workspaces_create"> {{ __('Project Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="workspaces_edit" id="workspaces_edit" class="workspaces task_management">
                                                        <label for="workspaces_edit">{{ __('Project Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="workspaces_manage_task" id="workspaces_manage_task" class="workspaces task_management">
                                                        <label for="workspaces_manage_task">{{ __('Project Manage Task') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="workspaces_delete" id="workspaces_delete" class="workspaces task_management">
                                                        <label for="workspaces_delete">{{ __('Project Delete') }}</label>
                                                    </p>
                                                </div>

                                                <div class="col-lg-3 col-sm-6">
                                                    <p class="text-info checkbox_input_wrap">
                                                        <label>
                                                            <input type="checkbox" class="memos" id="select_all" data-target="memos" autocomplete="off">
                                                            <strong>{{ __('Memos') }}</strong>
                                                        </label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="memos_index" id="memos_index" class="memos task_management">
                                                        <label for="memos_index"> {{ __('Memo List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="memos_create" id="memos_create" class="memos task_management">
                                                        <label for="memos_create"> {{ __('Memo Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="memos_edit" id="memos_edit" class="memos task_management">
                                                        <label for="memos_edit">{{ __('Memo Edit') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="memos_delete" id="memos_delete" class="memos task_management">
                                                        <label for="msg">{{ __('Memo Delete') }}</label>
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
                                                        <input type="checkbox" name="messages_index" id="messages_index" class="messages task_management">
                                                        <label for="messages_index"> {{ __('Message List') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="messages_create" id="memos_create" class="messages task_management">
                                                        <label for="messages_create"> {{ __('Message Add') }}</label>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="messages_delete" id="messages_delete" class="messages task_management">
                                                        <label for="messages_delete">{{ __('Message Delete') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($generalSettings['subscription']->features['manufacturing'] == 1)
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
                                                        <input type="checkbox" name="process_view" id="process_view" class="manufacturings manufacturing_all">
                                                        <label for="process_view">{{ __('View process') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="process_add" id="process_add" class="manufacturings manufacturing_all">
                                                        <label for="process_add">{{ __('Add Process') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="process_edit" id="process_edit" class="manufacturings manufacturing_all">
                                                        <label for="process_edit">{{ __('Edit Process') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="process_delete" id="process_delete" class="manufacturings manufacturing_all">
                                                        <label for="process_delete"> {{ __('Delete Process') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="production_view" id="production_view" class="manufacturings manufacturing_all">
                                                        <label for="production_view">{{ __('View Production') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="production_add" id="production_add" class="manufacturings manufacturing_all">
                                                        <label for=""> {{ __('Add Production') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="production_edit" id="production_edit" class="manufacturings manufacturing_all">
                                                        <label for="production_edit"> {{ __('Edit Production') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="production_delete" id="production_delete" class="manufacturings manufacturing_all">
                                                        <label for="production_delete">{{ __('Delete Production') }}</label>
                                                    </p>
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="manufacturing_report" id="manufacturing_report" class="manufacturings manufacturing_all">
                                                        <label for="manufacturing_report">{{ __('Manufacturing Report') }}</label>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                                    <input type="checkbox" name="today_summery" id="today_summery" class="others other_all">
                                                    <label for="today_summery">{{ __('Today Summery') }}</label>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="communication" id="communication" class="others other_all">
                                                    <label for="communication">{{ __('Communication') }}</label>
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
                            <button class="btn btn-sm btn-success submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('change', '#super_select_all', function() {

                var checkboxes = document.querySelectorAll('.accordion input[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {

                    checkbox.checked = !checkbox.checked;
                });
            });
        });
    </script>
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
