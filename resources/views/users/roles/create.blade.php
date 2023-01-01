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

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Users Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap ">
                                            <input type="checkbox" id="select_all" data-target="users"
                                                autocomplete="off"> &nbsp; @lang('menu.select_all')
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.users')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="user_view" class="users"> &nbsp; @lang('menu.view_user')
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="user_add" class="users"
                                                autocomplete="off"> &nbsp; @lang('menu.add_user')
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="user_edit" class="users"
                                                autocomplete="off"> &nbsp; @lang('menu.edit_user')
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="user_delete" class="users"
                                                autocomplete="off"> &nbsp; {{ __('Delete User') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.roles')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="role_view" class="users">
                                            &nbsp;{{ __('View Role') }}
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="role_add" class="users">
                                            &nbsp; @lang('menu.add_role')
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="role_edit" class="users"> &nbsp; {{ __('Edit Role') }}
                                        </p>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox" name="role_delete" class="users"> &nbsp; {{ __('Delete Role') }}
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
                                        <input type="checkbox" id="select_all" data-target="contacts" autocomplete="off"> &nbsp;  @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.supplier')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_all" class="contacts"> &nbsp; @lang('menu.view_all_supplier') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_add" class="contacts"> &nbsp; @lang('menu.add_supplier') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_import" class="contacts"> &nbsp; @lang('menu.import_suppliers')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_edit" class="contacts"> &nbsp; @lang('menu.edit_supplier') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_delete" class="contacts"> &nbsp; {{ __('Delete supplier') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="supplier_report" class="contacts"> &nbsp; @lang('menu.supplier_report')</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.customers')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="customer_all" class="contacts"> &nbsp; @lang('menu.view_all_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="customer_add" class="contacts"> &nbsp; @lang('menu.add_customer')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="customer_import" class="contacts"> &nbsp; @lang('menu.import_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="customer_edit" class=" contacts"> &nbsp; @lang('menu.edit_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="customer_delete" class="contacts"> &nbsp; @lang('menu.delete_customer') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row"><p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="customer_group" class="contacts"> &nbsp; @lang('menu.customer_group') -> {{ __('View/Add/Edit/Delete') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="customer_report" class="contacts"> &nbsp; @lang('menu.customer_report')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Products Permissions') }}</strong> </p>
                        </div>

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <p class="checkbox_input_wrap">
                                        <input type="checkbox" id="select_all" data-target="product" autocomplete="off"> &nbsp;  @lang('menu.select_all') </p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="col-md-12">
                                        <p><strong>@lang('menu.products')</strong></p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_all" class="product"> &nbsp; {{ __('View All Product') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_add" class="product"> &nbsp; @lang('menu.add_product') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_edit" class="product"> &nbsp; {{ __('Edit Product') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="openingStock_add" class="product"> &nbsp; {{ __('Add/Edit Opening Stock') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_delete" class="product"> &nbsp; {{ __('Delete Product') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_settings" class="product"> &nbsp; @lang('menu.product_settings')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_report" class="product"> &nbsp; @lang('menu.stock_report')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_in_out_report" class="product"> &nbsp; @lang('menu.stock_in_out_report')</p>
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
                                            <input type="checkbox" name="categories" class="product"> &nbsp; @lang('menu.categories')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="brand" class="product"> &nbsp; @lang('menu.brands')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="units" class="product"> &nbsp; @lang('menu.unit')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="variant" class="product"> &nbsp; @lang('menu.variants')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="warranties" class="product"> &nbsp; @lang('menu.warranties')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="selling_price_group" class="product"> &nbsp; @lang('menu.selling_price_group')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="generate_barcode" class="product"> &nbsp; @lang('menu.generate_barcode')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-3">
                        <div class="heading_area">
                            <p class="p-1 text-primary"><strong>{{ __('Purchases Permissions') }}</strong> </p>
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
                                            <input type="checkbox" name="purchase_all" class="purchase"> &nbsp; {{ __('View All Purchase') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_add" class="purchase"> &nbsp; @lang('menu.add_purchase') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_edit" class="purchase"> &nbsp; {{ __('Edit Purchase') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_delete" class="purchase" > &nbsp; {{ __('Delete purchase') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="status_update" class="purchase"> &nbsp; {{ __('Update Status') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_settings" class="purchase"> &nbsp; @lang('menu.purchase_settings') </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_statements" class="purchase"> &nbsp; @lang('menu.purchase_statements')</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_sale_report" class="purchase"> &nbsp; {{ __('Purchase & Sale Report') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="pro_purchase_report" class="purchase"> &nbsp; @lang('menu.product_purchase_report')</p>
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
                                            <input type="checkbox" name="purchase_payment" class="purchase"> &nbsp; {{ __('View/Add/Delete Purchase Payment') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_return" class="purchase"> &nbsp; {{ __('Access Purchase Return') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="purchase_payment_report" class="report purchase"> &nbsp; @lang('menu.purchase_payment_report')</p>
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

                    @if ($addons->hrm == 1)
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

                    @if ($addons->todo == 1)
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

                    @if ($addons->manufacturing == 1)
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
