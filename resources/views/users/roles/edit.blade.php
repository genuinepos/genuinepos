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
                    <h5>{{ __('Edit Role') }}</h5>
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
                                        <label for="inputEmail3" class="col-4"><strong>@lang('menu.role_name') : <span
                                            class="text-danger">*</span></strong> </label>
                                        <div class="col-8">
                                            <input type="text" name="role_name" class="form-control add_input" id="role_name"
                                                placeholder="@lang('menu.role_name')" value="{{ $role->name }}">
                                            <span class="error error_role_name">{{ $errors->first('role_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Users Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                            <input type="checkbox" id="select_all" data-target="users"> &nbsp; @lang('menu.select_all')
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.users')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('user_view') ? 'CHECKED' : '' }} name="user_view" class="users"> &nbsp; @lang('menu.view_user')
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('user_add') ? 'CHECKED' : '' }} name="user_add" class="users"> &nbsp; @lang('menu.add_user')
                                        </p>

                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('user_edit') ? 'CHECKED' : '' }} name="user_edit" class="users"> &nbsp; @lang('menu.edit_user')
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('user_delete') ? 'CHECKED' : '' }} name="user_delete" class="users"> &nbsp; {{ __('Delete User') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.roles')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('role_view') ? 'CHECKED' : '' }}  name="role_view" class="users">
                                            &nbsp; {{ __('View Role') }}
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('role_add') ? 'CHECKED' : '' }} name="role_add" class="users">
                                            &nbsp; @lang('menu.add_role')
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('role_edit') ? 'CHECKED' : '' }} name="role_edit" class="users">
                                            &nbsp; {{ __('Edit Role') }}
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('role_delete') ? 'CHECKED' : '' }} name="role_delete"
                                                class="users"> &nbsp; {{ __('Delete Role') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Contacts Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="contacts"> &nbsp;  @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.supplier')</strong></p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_all') ? 'CHECKED' : '' }} name="supplier_all" class="contacts"> &nbsp; @lang('menu.view_all_supplier') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_add') ? 'CHECKED' : '' }} name="supplier_add" class="contacts"> &nbsp; @lang('menu.add_supplier') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_import') ? 'CHECKED' : '' }} name="supplier_import" class="contacts"> &nbsp; @lang('menu.import_suppliers') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_edit') ? 'CHECKED' : '' }} name="supplier_edit" class="contacts"> &nbsp; @lang('menu.edit_supplier') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('supplier_delete') ? 'CHECKED' : '' }} name="supplier_delete" class="contacts"> &nbsp; {{ __('Delete supplier') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('supplier_report') ? 'CHECKED' : '' }}
                                                name="supplier_report" class="report contacts"> &nbsp; @lang('menu.supplier_report')</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.customers')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_all') ? 'CHECKED' : '' }} name="customer_all" class="contacts"> &nbsp; @lang('menu.view_all_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_add') ? 'CHECKED' : '' }} name="customer_add" class="contacts"> &nbsp; @lang('menu.add_customer')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_import') ? 'CHECKED' : '' }} name="customer_import" class="contacts"> &nbsp; @lang('menu.import_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_edit') ? 'CHECKED' : '' }} name="customer_edit" class=" contacts"> &nbsp; @lang('menu.edit_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_delete') ? 'CHECKED' : '' }} name="customer_delete" class="contacts"> &nbsp; @lang('menu.delete_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_group') ? 'CHECKED' : '' }} name="customer_group" class="contacts"> &nbsp; @lang('menu.customer_group') -> {{ __('View/Add/Edit/Delete') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                        {{ $role->hasPermissionTo('customer_report') ? 'CHECKED' : '' }}
                                                name="customer_report" class="report contacts"> &nbsp; @lang('menu.customer_report')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Product Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="product" autocomplete="off"> &nbsp;  @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.products')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('product_all') ? 'CHECKED' : '' }} name="product_all" class="product"> &nbsp; {{ __('View All Product') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('product_add') ? 'CHECKED' : '' }} name="product_add" class="product"> &nbsp; @lang('menu.add_product') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('product_edit') ? 'CHECKED' : '' }} name="product_edit" class="product"> &nbsp; {{ __('Edit Product') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('openingStock_add') ? 'CHECKED' : '' }} name="openingStock_add" class="product"> &nbsp; {{ __('Add opening stock') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('product_delete') ? 'CHECKED' : '' }} name="product_delete" class="product"> &nbsp; {{ __('Delete Product') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('product_settings') ? 'CHECKED' : '' }}
                                            name="product_settings" class="product"> &nbsp; @lang('menu.product_settings')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_report') ? 'CHECKED' : '' }}
                                                name="stock_report" class="report product"> &nbsp; @lang('menu.stock_report')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_in_out_report') ? 'CHECKED' : '' }}
                                            name="stock_in_out_report" class="product"> &nbsp; @lang('menu.stock_in_out_report')</p>
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
                                            <input type="checkbox" {{ $role->hasPermissionTo('categories') ? 'CHECKED' : '' }} name="categories" class="product"> &nbsp; @lang('menu.categories')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('brand') ? 'CHECKED' : '' }} name="brand" class="product"> &nbsp; @lang('menu.brands')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('units') ? 'CHECKED' : '' }} name="units" class="product"> &nbsp; @lang('menu.unit')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('variant') ? 'CHECKED' : '' }} name="variant" class="product"> &nbsp; @lang('menu.variants')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('warranties') ? 'CHECKED' : '' }} name="warranties" class="product"> &nbsp; @lang('menu.warranties')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('selling_price_group') ? 'CHECKED' : '' }} name="selling_price_group" class="product"> &nbsp; @lang('menu.selling_price_group')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('generate_barcode') ? 'CHECKED' : '' }}  name="generate_barcode" class="product"> &nbsp; @lang('menu.generate_barcode')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Purchase Permission') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                        <input type="checkbox" id="select_all" data-target="purchase" autocomplete="off"> &nbsp; @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.purchases')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_all') ? 'CHECKED' : '' }} name="purchase_all" class="purchase"> &nbsp; {{ __('View All Purchase') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_add') ? 'CHECKED' : '' }} name="purchase_add" class="purchase"> &nbsp; @lang('menu.add_purchase') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_edit') ? 'CHECKED' : '' }} name="purchase_edit" class="purchase"> &nbsp; @lang('menu.edit_purchase') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_delete') ? 'CHECKED' : '' }} name="purchase_delete" class="purchase" > &nbsp; {{ __('Delete purchase') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('status_update') ? 'CHECKED' : '' }} name="status_update" class="purchase"> &nbsp; {{ __('Update Status') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('purchase_settings') ? 'CHECKED' : '' }}
                                            name="purchase_settings" class="purchase"> &nbsp; @lang('menu.purchase_settings') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('purchase_statements') ? 'CHECKED' : '' }}
                                                name="purchase_statements" class="purchase"> &nbsp; @lang('menu.purchase_statements')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('purchase_sale_report') ? 'CHECKED' : '' }}
                                            name="purchase_sale_report" class="purchase"> &nbsp; {{ __('Purchase & Sale Report') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pro_purchase_report') ? 'CHECKED' : '' }}
                                                name="pro_purchase_report" class="purchase"> &nbsp; @lang('menu.product_purchase_report')</p>
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
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_payment') ? 'CHECKED' : '' }} name="purchase_payment" class="purchase"> &nbsp; {{ __('View/Add/Delete Purchase Payment') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('purchase_return') ? 'CHECKED' : '' }} name="purchase_return" class="purchase"> &nbsp; {{ __('Access Purchase Return') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('purchase_payment_report') ? 'CHECKED' : '' }}
                                            name="purchase_payment_report" class="purchase"> &nbsp; @lang('menu.purchase_payment_report')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Adjustment Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
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
                                            <input type="checkbox" {{ $role->hasPermissionTo('adjustment_all') ? 'CHECKED' : '' }} name="adjustment_all" class="adjustment"> &nbsp;{{ __('View All Adjustment') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('adjustment_add_from_location') ? 'CHECKED' : '' }} name="adjustment_add_from_location" class="adjustment"> &nbsp; {{ __('Add Adjustment From Business Location') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('adjustment_add_from_warehouse') ? 'CHECKED' : '' }} name="adjustment_add_from_warehouse" class="adjustment"> &nbsp; {{ __('Add Adjustment From Warehouse') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('adjustment_delete') ? 'CHECKED' : '' }} name="adjustment_delete" class="adjustment" > &nbsp; {{ __('Delete Adjustment') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('stock_adjustment_report') ? 'CHECKED' : '' }}
                                            name="stock_adjustment_report" class="adjustment"> &nbsp; @lang('menu.stock_adjustment_report')</p>
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
                                            <input type="checkbox" {{ $role->hasPermissionTo('view_expense') ? 'CHECKED' : '' }}  name="view_expense" class="expense"> &nbsp; {{ __('View Expense') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('add_expense') ? 'CHECKED' : '' }}  name="add_expense" class="expense"> &nbsp; {{ __('Add Expense') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_expense') ? 'CHECKED' : '' }}  name="edit_expense" class="expense"> &nbsp; @lang('menu.edit_expense') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('delete_expense') ? 'CHECKED' : '' }}  name="delete_expense" class="expense"> &nbsp; {{ __('Delete Expense') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('expense_category') ? 'CHECKED' : '' }} name="expense_category" class="expense"> &nbsp; @lang('menu.expense_category') -> {{ __('View/Add/Edit/Delete') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('category_wise_expense') ? 'CHECKED' : '' }} name="category_wise_expense" class="expense"> &nbsp; {{ __('View Category Wise Expense') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('expanse_report') ? 'CHECKED' : '' }}
                                                name="expanse_report" class="expense"> &nbsp; @lang('menu.expense_report')</p>
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
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.sales')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('create_add_sale') ? 'CHECKED' : '' }} name="create_add_sale" class="sale"> &nbsp; @lang('menu.create_add_sale') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('view_add_sale') ? 'CHECKED' : '' }} name="view_add_sale" class="sale"> &nbsp; {{ __('Manage Add Sale') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_add_sale') ? 'CHECKED' : '' }} name="edit_add_sale" class="sale"> &nbsp; {{ __('Edit Add Sale') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('delete_add_sale') ? 'CHECKED' : '' }} name="delete_add_sale" class="sale"> &nbsp; {{ __('Delete Add Sale') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('add_sale_settings') ? 'CHECKED' : '' }}
                                            name="add_sale_settings" class="sale"> &nbsp; @lang('menu.add_sale_settings') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('sale_draft') ? 'CHECKED' : '' }} name="sale_draft" class="sale"> &nbsp; {{ __('List Draft') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('sale_quotation') ? 'CHECKED' : '' }} name="sale_quotation" class="sale"> &nbsp; {{ __('List Quotations') }} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12 d-inline-block"></div>
                                    <div class="col-md-12 d-inline-block"></div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('sale_payment') ? 'CHECKED' : '' }} name="sale_payment" class="sale"> &nbsp; {{ __('View/Add/Edit Payment') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_price_sale_screen') ? 'CHECKED' : '' }} name="edit_price_sale_screen" class="sale"> &nbsp; {{ __('Edit product price from sales screen') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_discount_sale_screen') ? 'CHECKED' : '' }} name="edit_discount_sale_screen" class="sale"> &nbsp; {{ __('Edit product discount in sale scr') }}. </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('shipment_access') ? 'CHECKED' : '' }}  name="shipment_access" class="sale"> &nbsp; {{ __('Access shipments') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('view_product_cost_is_sale_screed') ? 'CHECKED' : '' }} name="view_product_cost_is_sale_screed" class="sale"> &nbsp; {{ __('View Product Cost In sale screen') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('view_own_sale') ? 'CHECKED' : '' }} name="view_own_sale" class="sale"> &nbsp; {{ __('View only own Add/POS Sale') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('return_access') ? 'CHECKED' : '' }} name="return_access" class="sale"> &nbsp; {{ __('Access Sale Return') }} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12 d-inline-block"></div>
                                    <div class="col-md-12 d-inline-block"></div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input
                                                {{ $role->hasPermissionTo('discounts') ? 'CHECKED' : '' }}
                                        type="checkbox" name="discounts" class="sale"> &nbsp; @lang('menu.manage_offers') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_statements') ? 'CHECKED' : '' }}
                                            name="sale_statements" class="sale"> &nbsp; @lang('menu.sale_statement')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_return_statements') ? 'CHECKED' : '' }}
                                            name="sale_return_statements" class="sale"> &nbsp; @lang('menu.sale_return_statement')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pro_sale_report') ? 'CHECKED' : '' }}
                                            name="pro_sale_report" class="sale"> &nbsp;  {{ __('Sale Product Report') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_payment_report') ? 'CHECKED' : '' }}
                                            name="sale_payment_report" class="sale"> &nbsp; @lang('menu.receive_payment_report')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('c_register_report') ? 'CHECKED' : '' }}
                                            name="c_register_report" class="sale"> &nbsp; @lang('menu.cash_register_reports')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('sale_representative_report') ? 'CHECKED' : '' }}
                                            name="sale_representative_report" class="sale"> &nbsp; @lang('menu.sales_representative_report')</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-md-12 d-inline-block"></div>

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.pos_sales')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('pos_all') ? 'CHECKED' : '' }} name="pos_all" class="sale"> &nbsp; @lang('menu.manage_pos_sale')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('pos_add') ? 'CHECKED' : '' }} name="pos_add" class="sale"> &nbsp; @lang('menu.add_pos_sale') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('pos_edit') ? 'CHECKED' : '' }} name="pos_edit" class="sale"> &nbsp; @lang('menu.edit_pos_sale') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('pos_delete') ? 'CHECKED' : '' }} name="pos_delete" class="sale"> &nbsp; @lang('menu.delete_pos_sale') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox"
                                                    {{ $role->hasPermissionTo('pos_sale_settings') ? 'CHECKED' : '' }}
                                            name="pos_sale_settings" class="sale"> &nbsp; @lang('menu.pos_sale_settings') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_price_pos_screen') ? 'CHECKED' : '' }} name="edit_price_pos_screen" class="sale"> &nbsp; {{ __('Edit Product Price From POS Screen') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('edit_discount_pos_screen') ? 'CHECKED' : '' }} name="edit_discount_pos_screen" class="sale"> &nbsp; {{ __('Edit Product Discount From POS Screen') }} </p>
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

                                <div class="col-md-3">

                                    <div class="col-md-12">
                                        <p><strong>{{ __('Cash Register') }}</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('register_view') ? 'CHECKED' : '' }} name="register_view" class="cash_register"> &nbsp; {{ __('View Cash Register') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('register_close') ? 'CHECKED' : '' }} name="register_close" class="cash_register"> &nbsp; {{ __('Close Cash Register') }} </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('another_register_close') ? 'CHECKED' : '' }} name="another_register_close" class="another_register_close cash_register"> &nbsp; {{ __('Close Another Cash Register') }} </p>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.customers')</strong></p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_all') ? 'CHECKED' : '' }} name="customer_all" class="contacts"> &nbsp; @lang('menu.view_all_customer') </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_add') ? 'CHECKED' : '' }} name="customer_add" class="contacts"> &nbsp; @lang('menu.add_customer')</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_import') ? 'CHECKED' : '' }} name="customer_import" class="contacts"> &nbsp; @lang('menu.import_customer') </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_edit') ? 'CHECKED' : '' }} name="customer_edit" class=" contacts"> &nbsp; @lang('menu.edit_customer') </p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_delete') ? 'CHECKED' : '' }} name="customer_delete" class="contacts"> &nbsp; @lang('menu.delete_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('customer_group') ? 'CHECKED' : '' }} name="customer_group" class="contacts"> &nbsp; @lang('menu.customer_group') -> {{ __('View/Add/Edit/Delete') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox"
                                                        {{ $role->hasPermissionTo('customer_report') ? 'CHECKED' : '' }}
                                                name="customer_report" class="report contacts"> &nbsp; @lang('menu.customer_report')</p>
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
                                            <input type="checkbox" {{ $role->hasPermissionTo('tax_report') ? 'CHECKED' : '' }}  name="tax_report" class="report"> &nbsp; @lang('menu.tax_report')</p>
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
                                        <input type="checkbox" id="select_all" data-target="settings"> &nbsp; @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.setup')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" {{ $role->hasPermissionTo('tax') ? 'CHECKED' : '' }} name="tax" class="settings"> &nbsp; @lang('menu.tax')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('branch') ? 'CHECKED' : '' }} name="branch" class="settings"> &nbsp; {{ __('Branch') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('warehouse') ? 'CHECKED' : '' }} name="warehouse" class="settings"> &nbsp; @lang('menu.warehouse')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('g_settings') ? 'CHECKED' : '' }} name="g_settings" class="settings"> &nbsp; @lang('menu.general_settings')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input {{ $role->hasPermissionTo('p_settings') ? 'CHECKED' : '' }} type="checkbox" name="p_settings" class="settings"> &nbsp; {{ __('Payment settings') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('inv_sc') ? 'CHECKED' : '' }} name="inv_sc" class="settings"> &nbsp; @lang('menu.invoice_schemas')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('inv_lay') ? 'CHECKED' : '' }} name="inv_lay" class="settings"> &nbsp; {{ __('Invoice Layout') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('barcode_settings') ? 'CHECKED' : '' }} name="barcode_settings" class="settings"> &nbsp;@lang('menu.barcode_settings')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('cash_counters') ? 'CHECKED' : '' }} name="cash_counters" class="settings"> &nbsp; @lang('menu.cash_counter')</p>
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
                                            <input type="checkbox" {{ $role->hasPermissionTo('dash_data') ? 'CHECKED' : '' }} name="dash_data"> &nbsp; {{ __('View Dashboard Data') }} </p>
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
                                            <input type="checkbox" {{ $role->hasPermissionTo('ac_access') ? 'CHECKED' : '' }} name="ac_access"> &nbsp; {{ __('Access Accounting') }} </p>
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
                                                <input type="checkbox" {{ $role->hasPermissionTo('hrm_dashboard') ? 'CHECKED' : '' }}  name="hrm_dashboard" class="HRMS"> &nbsp; Hrm @lang('menu.dashboard')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('attendance') ? 'CHECKED' : '' }} name="attendance" class="HRMS"> &nbsp; @lang('menu.attendance')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('payroll') ? 'CHECKED' : '' }} name="payroll" class="HRMS"> &nbsp; {{ __('Payroll') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                name="payroll_report"
                                                        {{ $role->hasPermissionTo('payroll_report') ? 'CHECKED' : '' }}
                                                    class="HRMS"> &nbsp; @lang('menu.payroll_report')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                    {{ $role->hasPermissionTo('payroll_payment_report') ? 'CHECKED' : '' }}
                                                name="payroll_payment_report" class="HRMS"> &nbsp; @lang('menu.payroll_payment_report')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox"
                                                        {{ $role->hasPermissionTo('attendance_report') ? 'CHECKED' : '' }}
                                                name="attendance_report" class="HRMS"> &nbsp; @lang('menu.attendance_report')</p>
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
                                                <input type="checkbox" {{ $role->hasPermissionTo('leave_type') ? 'CHECKED' : '' }}  name="leave_type" class="HRMS"> &nbsp; @lang('menu.leave_type')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('leave_assign') ? 'CHECKED' : '' }}  name="leave_assign" class="HRMS"> &nbsp; {{ __('Leave assign') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('shift') ? 'CHECKED' : '' }} name="shift" class="HRMS"> &nbsp; @lang('menu.shift')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('view_allowance_and_deduction') ? 'CHECKED' : '' }} name="view_allowance_and_deduction" class="HRMS"> &nbsp; {{  __('Allowance and deduction') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('holiday') ? 'CHECKED' : '' }} name="holiday" class="HRMS"> &nbsp; {{ __('Holidays') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('department') ? 'CHECKED' : '' }} name="department" class="HRMS"> &nbsp; @lang('menu.departments')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('designation') ? 'CHECKED' : '' }} name="designation" class="HRMS"> &nbsp; @lang('menu.designation')</p>
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

                                    <div class="col-md-4">

                                        <div class="col-md-12">
                                            <p><strong>@lang('menu.manage_task')</strong></p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" {{ $role->hasPermissionTo('assign_todo') ? 'CHECKED' : '' }} name="assign_todo" class="Essentials"> &nbsp; @lang('menu.todo')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input
                                                        {{ $role->hasPermissionTo('work_space') ? 'CHECKED' : '' }}
                                                type="checkbox" name="work_space" class="Essentials">
                                                &nbsp; @lang('menu.work_space')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                    <input
                                                            {{ $role->hasPermissionTo('memo') ? 'CHECKED' : '' }}
                                                    type="checkbox" name="memo" class="Essentials">
                                                    &nbsp; @lang('menu.memo')
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('msg') ? 'CHECKED' : '' }} name="msg" class="Essentials"> &nbsp; @lang('menu.message')</p>
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
                                <p class="p-1 text-primary"><b>@lang('menu.manufacturing_permissions')</b> </p>
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
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_view') ? 'CHECKED' : '' }} name="process_view" class=" Manufacturing"> &nbsp; @lang('menu.view_process')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_add') ? 'CHECKED' : '' }} name="process_add" class="Manufacturing"> &nbsp; @lang('menu.add_process')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_edit') ? 'CHECKED' : '' }} name="process_edit" class="Manufacturing"> &nbsp;  @lang('menu.edit_process')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('process_delete') ? 'CHECKED' : '' }} name="process_delete" class="Manufacturing"> &nbsp; {{ __('Delete Process') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_view') ? 'CHECKED' : '' }} name="production_view" class=" Manufacturing"> &nbsp; @lang('menu.view_production')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_add') ? 'CHECKED' : '' }} name="production_add" class="Manufacturing"> &nbsp; @lang('menu.add_production')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_edit') ? 'CHECKED' : '' }} name="production_edit" class="Manufacturing"> &nbsp;  @lang('menu.edit_production')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('production_delete') ? 'CHECKED' : '' }} name="production_delete" class="Manufacturing"> &nbsp; {{ __('Delete Production') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('manuf_settings') ? 'CHECKED' : '' }} name="manuf_settings" class="Manufacturing"> &nbsp; @lang('menu.manufacturing_setting')</p>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <p class="checkbox_input_wrap">
                                                <input type="checkbox" {{ $role->hasPermissionTo('manuf_report') ? 'CHECKED' : '' }} name="manuf_report" class="Manufacturing"> &nbsp; @lang('menu.manufacturing_report')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><b>{{ __('Others Permissions') }}</b> </p>
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
                                            <input type="checkbox" {{ $role->hasPermissionTo('today_summery') ? 'CHECKED' : '' }} name="today_summery" class="others"> &nbsp; {{ __('Today Summery') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input type="checkbox" {{ $role->hasPermissionTo('communication') ? 'CHECKED' : '' }}   name="communication" class="others"> &nbsp; @lang('menu.communication')</p>
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
