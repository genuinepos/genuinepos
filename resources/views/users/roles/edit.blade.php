@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        p.checkbox_input_wrap {font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-user-edit"></span>
                    <h5>Edit Role</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <form id="edit_role_form" action="{{ route('users.role.update', $role->id) }}"  method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-3"><span
                                            class="text-danger">*</span> <strong>Role Name :</strong> </label>
                                        <div class="col-8">
                                            <input type="text" name="role_name" class="form-control add_input" id="role_name"
                                                placeholder="Role Name" value="{{ $role->name }}">
                                            <span class="error error_role_name">{{ $errors->first('role_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Users Permissions</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                            <input type="checkbox" id="select_all" data-target="users"> &nbsp; Select All
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4 offset-1">
                                    <div class="col-md-12">
                                        <p><strong>Users</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('user_view') ? 'CHECKED' : '' }} name="user_view" class="users"> &nbsp; View
                                            User
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('user_add') ? 'CHECKED' : '' }} name="user_add" class="users"> &nbsp; Add User
                                        </p>

                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('user_edit') ? 'CHECKED' : '' }} name="user_edit" class="users"> &nbsp; Edit User
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('user_delete') ? 'CHECKED' : '' }} name="user_delete" class="users"> &nbsp; Delete User
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="col-md-12">
                                        <p><strong>Roles</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('role_view') ? 'CHECKED' : '' }}  name="role_view" class="users">
                                            &nbsp; View Role
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('role_add') ? 'CHECKED' : '' }} name="role_add" class="users">
                                            &nbsp; Add Role
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('role_edit') ? 'CHECKED' : '' }} name="role_edit" class="users">
                                            &nbsp; Edit Role
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('role_delete') ? 'CHECKED' : '' }} name="role_delete"
                                                class="users"> &nbsp; Delete Role
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Contacts Permissions</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="contacts"> &nbsp;  Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-4 offset-1">
                                    <div class="col-md-12">
                                        <p><strong>Suppliers</strong></p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_all') ? 'CHECKED' : '' }} name="supplier_all" class="contacts"> &nbsp; View All Supplier </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_add') ? 'CHECKED' : '' }} name="supplier_add" class="contacts"> &nbsp; Add Supplier </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_import') ? 'CHECKED' : '' }} name="supplier_import" class="contacts"> &nbsp; Import Supplier </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_edit') ? 'CHECKED' : '' }} name="supplier_edit" class="contacts"> &nbsp; Edit Supplier </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_delete') ? 'CHECKED' : '' }} name="supplier_delete" class="contacts"> &nbsp; Delete Supplier </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('supplier_report') ? 'CHECKED' : '' }}
                                                name="supplier_report" class="report contacts"> &nbsp; Supplier Report</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">

                                    <div class="col-md-12">
                                        <p><strong>Customers</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_all') ? 'CHECKED' : '' }} name="customer_all" class="contacts"> &nbsp; View All Customer </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_add') ? 'CHECKED' : '' }} name="customer_add" class="contacts"> &nbsp; Add Customer </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_import') ? 'CHECKED' : '' }} name="customer_import" class="contacts"> &nbsp; Import Customer </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_edit') ? 'CHECKED' : '' }} name="customer_edit" class=" contacts"> &nbsp; Edit Customer </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_delete') ? 'CHECKED' : '' }} name="customer_delete" class="contacts"> &nbsp; Delete Customer </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_group') ? 'CHECKED' : '' }} name="customer_group" class="contacts"> &nbsp; Customer Group -> View/Add/Edit/Delete</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                        {{ $role->hasPermissionTo('customer_report') ? 'CHECKED' : '' }}
                                                name="customer_report" class="report contacts"> &nbsp; Customer Report</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Product Permissions</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="product" autocomplete="off"> &nbsp;  Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-4 offset-1">
                                    <div class="col-md-12">
                                        <p><strong>Products</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('product_all') ? 'CHECKED' : '' }} name="product_all" class="product"> &nbsp; View All Product </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('product_add') ? 'CHECKED' : '' }} name="product_add" class="product"> &nbsp; Add Product </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('product_edit') ? 'CHECKED' : '' }} name="product_edit" class="product"> &nbsp; Edit Product </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('openingStock_add') ? 'CHECKED' : '' }} name="openingStock_add" class="product"> &nbsp; Add Opening Stock </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('product_delete') ? 'CHECKED' : '' }} name="product_delete" class="product"> &nbsp; Delete Product </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_settings') ? 'CHECKED' : '' }}
                                            name="product_settings" class="product"> &nbsp; Product Settings</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_report') ? 'CHECKED' : '' }}
                                                name="stock_report" class="report product"> &nbsp; stock Report</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_in_out_report') ? 'CHECKED' : '' }}
                                            name="stock_in_out_report" class="product"> &nbsp; Stock In-Out Report</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="col-md-12">
                                        <p><strong>Others</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('categories') ? 'CHECKED' : '' }} name="categories" class="product"> &nbsp; Categories</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('brand') ? 'CHECKED' : '' }} name="brand" class="product"> &nbsp; Brands</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('units') ? 'CHECKED' : '' }} name="units" class="product"> &nbsp; Unit</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('variant') ? 'CHECKED' : '' }} name="variant" class="product"> &nbsp; Variants</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('warranties') ? 'CHECKED' : '' }} name="warranties" class="product"> &nbsp; Warranties</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('selling_price_group') ? 'CHECKED' : '' }} name="selling_price_group" class="product"> &nbsp; Selling Price Group</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('generate_barcode') ? 'CHECKED' : '' }}  name="generate_barcode" class="product"> &nbsp; Generate Barcode</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Purchase Permission</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="purchase" autocomplete="off"> &nbsp; Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-4 offset-1">

                                    <div class="col-md-12">
                                        <p><strong>Purchases</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_all') ? 'CHECKED' : '' }} name="purchase_all" class="purchase"> &nbsp; View All Purchase </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_add') ? 'CHECKED' : '' }} name="purchase_add" class="purchase"> &nbsp; Add Purchase </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_edit') ? 'CHECKED' : '' }} name="purchase_edit" class="purchase"> &nbsp; Edit Purchase </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_delete') ? 'CHECKED' : '' }} name="purchase_delete" class="purchase" > &nbsp; Delete Purchase </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('status_update') ? 'CHECKED' : '' }} name="status_update" class="purchase"> &nbsp; Update Status </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('purchase_settings') ? 'CHECKED' : '' }}
                                            name="purchase_settings" class="purchase"> &nbsp; Purchase Settings </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('purchase_statements') ? 'CHECKED' : '' }}
                                                name="purchase_statements" class="purchase"> &nbsp; Purchase Statements</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('purchase_sale_report') ? 'CHECKED' : '' }}
                                            name="purchase_sale_report" class="purchase"> &nbsp; Purchase & Sale Report</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pro_purchase_report') ? 'CHECKED' : '' }}
                                                name="pro_purchase_report" class="purchase"> &nbsp; Product Purchase Report</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="col-md-12">
                                        <p><strong>Others</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_payment') ? 'CHECKED' : '' }} name="purchase_payment" class="purchase"> &nbsp; View/Add/Delete Purchase Payment </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_return') ? 'CHECKED' : '' }} name="purchase_return" class="purchase"> &nbsp; Access Purchase Return </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('purchase_payment_report') ? 'CHECKED' : '' }}
                                            name="purchase_payment_report" class="purchase"> &nbsp; Purchase Payment Report</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Adjustment Permissions</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="adjustment" autocomplete="off"> &nbsp; Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-1">

                                    <div class="col-md-12">
                                        <p><strong>Stock Adjustments</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('adjustment_all') ? 'CHECKED' : '' }} name="adjustment_all" class="adjustment"> &nbsp; View All Adjustment </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('adjustment_add_from_location') ? 'CHECKED' : '' }} name="adjustment_add_from_location" class="adjustment"> &nbsp; Add Adjustment From Business Locaton </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('adjustment_add_from_warehouse') ? 'CHECKED' : '' }} name="adjustment_add_from_warehouse" class="adjustment"> &nbsp; Add Adjustment From Warehouse </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('adjustment_delete') ? 'CHECKED' : '' }} name="adjustment_delete" class="adjustment" > &nbsp; Delete Adjustment </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_adjustment_report') ? 'CHECKED' : '' }}
                                            name="stock_adjustment_report" class="adjustment"> &nbsp; Stock Adjustment Report</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Expenses Permission</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="expense" autocomplete="off"> &nbsp; Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-1">
                                    <div class="col-md-12">
                                        <p><strong>Expenses</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('view_expense') ? 'CHECKED' : '' }}  name="view_expense" class="expense"> &nbsp; View Expense </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('add_expense') ? 'CHECKED' : '' }}  name="add_expense" class="expense"> &nbsp; Add Expense </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_expense') ? 'CHECKED' : '' }}  name="edit_expense" class="expense"> &nbsp; Edit Expense </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('delete_expense') ? 'CHECKED' : '' }}  name="delete_expense" class="expense"> &nbsp; Delete Expense </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('expense_category') ? 'CHECKED' : '' }} name="expense_category" class="expense"> &nbsp; Expense Category -> View/Add/Edit/Delete </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('category_wise_expense') ? 'CHECKED' : '' }} name="category_wise_expense" class="expense"> &nbsp; View Category Wise Expense </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('expanse_report') ? 'CHECKED' : '' }}
                                                name="expanse_report" class="expense"> &nbsp; Expanse Report</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Sales Permission</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="sale" autocomplete="off"> &nbsp; Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-4 offset-1">
                                    <div class="col-md-12">
                                        <p><strong>Sales</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('create_add_sale') ? 'CHECKED' : '' }} name="create_add_sale" class="sale"> &nbsp; Create Add Sale </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('view_add_sale') ? 'CHECKED' : '' }} name="view_add_sale" class="sale"> &nbsp; Manage Add Sale </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_add_sale') ? 'CHECKED' : '' }} name="edit_add_sale" class="sale"> &nbsp; Edit Add Sale </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('delete_add_sale') ? 'CHECKED' : '' }} name="delete_add_sale" class="sale"> &nbsp; Delete Add Sale </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('add_sale_settings') ? 'CHECKED' : '' }}
                                            name="add_sale_settings" class="sale"> &nbsp; Add Sale Settings </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('sale_draft') ? 'CHECKED' : '' }} name="sale_draft" class="sale"> &nbsp; List Draft </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('sale_quotation') ? 'CHECKED' : '' }} name="sale_quotation" class="sale"> &nbsp; List Quotations </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('sale_payment') ? 'CHECKED' : '' }} name="sale_payment" class="sale"> &nbsp; View/Add/Edit Payment </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_price_sale_screen') ? 'CHECKED' : '' }} name="edit_price_sale_screen" class="sale"> &nbsp; Edit product price from sales screen </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_discount_sale_screen') ? 'CHECKED' : '' }} name="edit_discount_sale_screen" class="sale"> &nbsp; Edit Product Discount In Sale Scr. </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('shipment_access') ? 'CHECKED' : '' }}  name="shipment_access" class="sale"> &nbsp; Access Shipments </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('view_product_cost_is_sale_screed') ? 'CHECKED' : '' }} name="view_product_cost_is_sale_screed" class="sale"> &nbsp; View Product Cost is sale screen </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('view_own_sale') ? 'CHECKED' : '' }} name="view_own_sale" class="sale"> &nbsp; View only own Add/POS Sale </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('return_access') ? 'CHECKED' : '' }} name="return_access" class="sale"> &nbsp; Access Sale Return </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input
                                                {{ $role->hasPermissionTo('discounts') ? 'CHECKED' : '' }}
                                        type="checkbox" name="discounts" class="sale"> &nbsp; Manage Offers </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_statements') ? 'CHECKED' : '' }}
                                            name="sale_statements" class="sale"> &nbsp; Sale Statements</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_return_statements') ? 'CHECKED' : '' }}
                                            name="sale_return_statements" class="sale"> &nbsp; Sale Return Statements</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pro_sale_report') ? 'CHECKED' : '' }}
                                            name="pro_sale_report" class="sale"> &nbsp;  Sale Product Report</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_payment_report') ? 'CHECKED' : '' }}
                                            name="sale_payment_report" class="sale"> &nbsp; Receive Payment Report</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('c_register_report') ? 'CHECKED' : '' }}
                                            name="c_register_report" class="sale"> &nbsp; Cash Register report</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_representative_report') ? 'CHECKED' : '' }}
                                            name="sale_representative_report" class="sale"> &nbsp; Sales Representative Report</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">

                                    <div class="col-md-12">
                                        <p><strong>POS Sale</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('pos_all') ? 'CHECKED' : '' }} name="pos_all" class="sale"> &nbsp; Manage Pos Sale</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('pos_add') ? 'CHECKED' : '' }} name="pos_add" class="sale"> &nbsp; Add POS Sale </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('pos_edit') ? 'CHECKED' : '' }} name="pos_edit" class="sale"> &nbsp; Edit POS Sale </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('pos_delete') ? 'CHECKED' : '' }} name="pos_delete" class="sale"> &nbsp; Delete POS Sale </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pos_sale_settings') ? 'CHECKED' : '' }}
                                            name="pos_sale_settings" class="sale"> &nbsp; POS Sale Settings </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_price_pos_screen') ? 'CHECKED' : '' }} name="edit_price_pos_screen" class="sale"> &nbsp; Edit Product Price From POS Screen </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_discount_pos_screen') ? 'CHECKED' : '' }} name="edit_discount_pos_screen" class="sale"> &nbsp; Edit Product Discount From POS Screen </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Cash Register Permission</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="cash_register" autocomplete="off"> &nbsp; Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-1">

                                    <div class="col-md-12">
                                        <p><strong>Cash Register</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('register_view') ? 'CHECKED' : '' }} name="register_view" class="cash_register"> &nbsp; View Cash Register</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('register_close') ? 'CHECKED' : '' }} name="register_close" class="cash_register"> &nbsp; Close Cash Register </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('another_register_close') ? 'CHECKED' : '' }}
                                            name="another_register_close" class="another_register_close cash_register"> &nbsp; Close Another Cash Register </p>
                                            <div class="col-md-5">

                                                <div class="col-md-12">
                                                    <p><strong>@lang('menu.customers')</strong></p>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('customer_all') ? 'CHECKED' : '' }} name="customer_all" class="contacts"> &nbsp; View All Customer </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('customer_add') ? 'CHECKED' : '' }} name="customer_add" class="contacts"> &nbsp; Add Customer </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('customer_import') ? 'CHECKED' : '' }} name="customer_import" class="contacts"> &nbsp; Import Customer </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('customer_edit') ? 'CHECKED' : '' }} name="customer_edit" class=" contacts"> &nbsp; Edit Customer </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('customer_delete') ? 'CHECKED' : '' }} name="customer_delete" class="contacts"> &nbsp; Delete Customer </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->hasPermissionTo('customer_group') ? 'CHECKED' : '' }} name="customer_group" class="contacts"> &nbsp; Customer Group -> View/Add/Edit/Delete</p>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1">
                                                            <input type="checkbox"
                                                                    {{ $role->hasPermissionTo('customer_report') ? 'CHECKED' : '' }}
                                                            name="customer_report" class="report contacts"> &nbsp; Customer Report</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>All Report Permission</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="report"> &nbsp; Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-1">

                                    <div class="col-md-12">
                                        <p><strong>Reports</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('tax_report') ? 'CHECKED' : '' }}  name="tax_report" class="report"> &nbsp; Tax Report</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Setup Permission</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="settings"> &nbsp; Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-1">

                                    <div class="col-md-12">
                                        <p><strong>Setup</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('tax') ? 'CHECKED' : '' }} name="tax" class="settings"> &nbsp; Tax</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('branch') ? 'CHECKED' : '' }} name="branch" class="settings"> &nbsp; Branch</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('warehouse') ? 'CHECKED' : '' }} name="warehouse" class="settings"> &nbsp; Warehouse</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('g_settings') ? 'CHECKED' : '' }} name="g_settings" class="settings"> &nbsp; General Settings</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input {{ $role->hasPermissionTo('p_settings') ? 'CHECKED' : '' }} type="checkbox" name="p_settings" class="settings"> &nbsp; Payment settings</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('inv_sc') ? 'CHECKED' : '' }} name="inv_sc" class="settings"> &nbsp; Invoice Schemas</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('inv_lay') ? 'CHECKED' : '' }} name="inv_lay" class="settings"> &nbsp; Invoice Layout</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('barcode_settings') ? 'CHECKED' : '' }} name="barcode_settings" class="settings"> &nbsp; Barcode Settings</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('cash_counters') ? 'CHECKED' : '' }} name="cash_counters" class="settings"> &nbsp; Cash Counters</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Dashboard Permission</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">

                                </div>

                                <div class="col-md-6 offset-1">

                                    <div class="col-md-12">
                                        <p><strong>Dashboard</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('dash_data') ? 'CHECKED' : '' }} name="dash_data"> &nbsp; View Dashboard Data </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>Accounting Permission</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">

                                </div>

                                <div class="col-md-6 offset-1">

                                    <div class="col-md-12">
                                        <p><strong>Accounting</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('ac_access') ? 'CHECKED' : '' }} name="ac_access"> &nbsp; Access Accounting </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($addons->hrm == 1)
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="heading_area">
                                <p class="p-1 text-primary"><strong>HRM Permission</strong> </p>
                            </div>

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" id="select_all"  data-target="HRMS"> &nbsp; Select All </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4 offset-1">

                                        <div class="col-md-12">
                                            <p><strong>HRM</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('hrm_dashboard') ? 'CHECKED' : '' }}  name="hrm_dashboard" class="HRMS"> &nbsp; Hrm Dashboard</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('attendance') ? 'CHECKED' : '' }} name="attendance" class="HRMS"> &nbsp; Attendance</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payroll') ? 'CHECKED' : '' }} name="payroll" class="HRMS"> &nbsp; Payroll</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                name="payroll_report"
                                                        {{ $role->hasPermissionTo('payroll_report') ? 'CHECKED' : '' }}
                                                    class="HRMS"> &nbsp; Payroll Report</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                    {{ $role->hasPermissionTo('payroll_payment_report') ? 'CHECKED' : '' }}
                                                name="payroll_payment_report" class="HRMS"> &nbsp; Payroll Payment Report</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                        {{ $role->hasPermissionTo('attendance_report') ? 'CHECKED' : '' }}
                                                name="attendance_report" class="HRMS"> &nbsp; Attendance Report</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="col-md-12">
                                            <p><strong>Others</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leave_type') ? 'CHECKED' : '' }}  name="leave_type" class="HRMS"> &nbsp; Leave Type</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leave_assign') ? 'CHECKED' : '' }}  name="leave_assign" class="HRMS"> &nbsp; Leave Assign</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('shift') ? 'CHECKED' : '' }} name="shift" class="HRMS"> &nbsp; Shift</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('view_allowance_and_deduction') ? 'CHECKED' : '' }} name="view_allowance_and_deduction" class="HRMS"> &nbsp; Allowance and deduction</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('holiday') ? 'CHECKED' : '' }} name="holiday" class="HRMS"> &nbsp; Holiday</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('department') ? 'CHECKED' : '' }} name="department" class="HRMS"> &nbsp; Departments</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designation') ? 'CHECKED' : '' }} name="designation" class="HRMS"> &nbsp; Designation</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($addons->todo == 1)
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="heading_area">
                                <p class="p-1 text-primary"><strong>Manage Task Permission</strong> </p>
                            </div>

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" id="select_all"  data-target="Essentials"> &nbsp; Select All </p>
                                        </div>
                                    </div>

                                    <div class="col-md-4 offset-1">

                                        <div class="col-md-12">
                                            <p><strong>Manage Task</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('assign_todo') ? 'CHECKED' : '' }} name="assign_todo" class="Essentials"> &nbsp; Todo</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input
                                                        {{ $role->hasPermissionTo('work_space') ? 'CHECKED' : '' }}
                                                type="checkbox" name="work_space" class="Essentials">
                                                &nbsp; Work Space</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input
                                                            {{ $role->hasPermissionTo('memo') ? 'CHECKED' : '' }}
                                                    type="checkbox" name="memo" class="Essentials">
                                                    &nbsp; Memo
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('msg') ? 'CHECKED' : '' }} name="msg" class="Essentials"> &nbsp; Message</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($addons->manufacturing == 1)
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="heading_area">
                                <p class="p-1 text-primary"><b>Manufacturing Permission</b> </p>
                            </div>

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" id="select_all" data-target="Manufacturing"> &nbsp; Select All </p>
                                        </div>
                                    </div>

                                    <div class="col-md-6 offset-1">

                                        <div class="col-md-12">
                                            <p><strong>Manufacturing</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_view') ? 'CHECKED' : '' }} name="process_view" class=" Manufacturing"> &nbsp; View Process</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_add') ? 'CHECKED' : '' }} name="process_add" class="Manufacturing"> &nbsp; Add Process</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_edit') ? 'CHECKED' : '' }} name="process_edit" class="Manufacturing"> &nbsp;  Edit Process</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_delete') ? 'CHECKED' : '' }} name="process_delete" class="Manufacturing"> &nbsp; Delete Process</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_view') ? 'CHECKED' : '' }} name="production_view" class=" Manufacturing"> &nbsp; View Production</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_add') ? 'CHECKED' : '' }} name="production_add" class="Manufacturing"> &nbsp; Add Production</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_edit') ? 'CHECKED' : '' }} name="production_edit" class="Manufacturing"> &nbsp;  Edit Production</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_delete') ? 'CHECKED' : '' }} name="production_delete" class="Manufacturing"> &nbsp; Delete Production</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('manuf_settings') ? 'CHECKED' : '' }} name="manuf_settings" class="Manufacturing"> &nbsp; Manufacturing Settings</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('manuf_report') ? 'CHECKED' : '' }} name="manuf_report" class="Manufacturing"> &nbsp; Manufacturing Report</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>Others Permission</b> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="others"> &nbsp; Select All </p>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-1">
                                    <div class="col-md-12">
                                        <p><strong>Others</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('today_summery') ? 'CHECKED' : '' }} name="today_summery" class="others"> &nbsp; Today Summery</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('communication') ? 'CHECKED' : '' }}   name="communication" class="others"> &nbsp; Communication</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-area d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
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
