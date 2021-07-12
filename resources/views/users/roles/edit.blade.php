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
        <div class="container-fluid">
            <form id="edit_role_form" action="{{ route('users.role.update', $role->id) }}"  method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-4">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Edit Role</h5>
                                                </div>

                                                <div class="col-md-6">
                                                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-3"><span
                                                        class="text-danger">*</span> <b>Role Name :</b> </label>
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
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Users Permission</b> </p>
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

                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['user_view'] == '1' ? 'CHECKED' : '' }} name="user_view" class="users"> &nbsp; View
                                                        User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['user_add'] == '1' ? 'CHECKED' : '' }} name="user_add" class="users"> &nbsp; Add User
                                                    </p>

                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['user_edit'] == '1' ? 'CHECKED' : '' }} name="user_edit" class="users"> &nbsp; Edit User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" {{ $role->permission->user['user_delete'] == '1' ? 'CHECKED' : '' }} name="user_delete" class="users"> &nbsp; Delete User
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Role Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">

                                                <p class="checkbox_input_wrap ">
                                                    <input type="checkbox" id="select_all" data-target="roles"> &nbsp; Select All
                                                </p>

                                            </div>

                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $role->permission->roles['role_view'] == '1' ? 'CHECKED' : '' }}  name="role_view" class="roles">
                                                        &nbsp; View Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $role->permission->roles['role_add'] == '1' ? 'CHECKED' : '' }} name="role_add" class="roles">
                                                        &nbsp; Add Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $role->permission->roles['role_edit'] == '1' ? 'CHECKED' : '' }} name="role_edit" class="roles">
                                                        &nbsp; Edit Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" {{ $role->permission->roles['role_delete'] == '1' ? 'CHECKED' : '' }} name="role_delete"
                                                            class="roles"> &nbsp; Delete Role
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Suppliers Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="suppliers"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->supplier['supplier_all'] == '1' ? 'CHECKED' : '' }} name="supplier_all" class="suppliers"> &nbsp; View All Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->supplier['supplier_add'] == '1' ? 'CHECKED' : '' }} name="supplier_add" class="suppliers"> &nbsp; Add Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->supplier['supplier_edit'] == '1' ? 'CHECKED' : '' }} name="supplier_edit" class="suppliers"> &nbsp; Edit Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->supplier['supplier_delete'] == '1' ? 'CHECKED' : '' }} name="supplier_delete" class="suppliers"> &nbsp; Delete Supplier </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Customers Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                   <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="customers"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->customers['customer_all'] == '1' ? 'CHECKED' : '' }} name="customer_all" class="customers"> &nbsp; View All Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->customers['customer_add'] == '1' ? 'CHECKED' : '' }} name="customer_add" class="customers"> &nbsp; Add Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->customers['customer_edit'] == '1' ? 'CHECKED' : '' }} name="customer_edit" class=" customers"> &nbsp; Edit Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->customers['customer_delete'] == '1' ? 'CHECKED' : '' }} name="customer_delete" class="customers"> &nbsp; Delete Customer </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Product Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="product" autocomplete="off"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->product['product_all'] == '1' ? 'CHECKED' : '' }} name="product_all" class="product"> &nbsp; View All Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['product_add'] == '1' ? 'CHECKED' : '' }} name="product_add" class="product"> &nbsp; Add Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['product_edit'] == '1' ? 'CHECKED' : '' }} name="product_edit" class="product"> &nbsp; Edit Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['openingStock_add'] == '1' ? 'CHECKED' : '' }} name="openingStock_add" class="product"> &nbsp; Add Opening Stock </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['product_delete'] == '1' ? 'CHECKED' : '' }} name="product_delete" class="product"  value=""> &nbsp; Delete Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->product['pro_unit_cost'] == '1' ? 'CHECKED' : '' }} name="pro_unit_cost" class="product"> &nbsp; View Purchase Price </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Purchase Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="purchase" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_all'] == '1' ? 'CHECKED' : '' }} name="purchase_all" class="purchase"> &nbsp; View All Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_add'] == '1' ? 'CHECKED' : '' }} name="purchase_add" class="purchase"> &nbsp; Add Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_edit'] == '1' ? 'CHECKED' : '' }} name="purchase_edit" class="purchase"> &nbsp; Edit Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_delete'] == '1' ? 'CHECKED' : '' }} name="purchase_delete" class="purchase" > &nbsp; Delete Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_payment'] == '1' ? 'CHECKED' : '' }} name="purchase_payment" class="purchase"> &nbsp; View/Add/Delete Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['purchase_return'] == '1' ? 'CHECKED' : '' }} name="purchase_return" class="purchase"> &nbsp; Access Purchase Return </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->purchase['status_update'] == '1' ? 'CHECKED' : '' }} name="status_update" class="purchase"> &nbsp; Update Status </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Stock Adjustment Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="adjustment" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->s_adjust['adjustment_all'] == '1' ? 'CHECKED' : '' }} name="adjustment_all" class="adjustment"> &nbsp; View All Adjustment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->s_adjust['adjustment_add'] == '1' ? 'CHECKED' : '' }} name="adjustment_add" class="adjustment"> &nbsp; Add Adjustment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->s_adjust['adjustment_delete'] == '1' ? 'CHECKED' : '' }} name="adjustment_delete" class="adjustment" > &nbsp; Delete Adjustment </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Sales Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="sale" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['pos_all'] == '1' ? 'CHECKED' : '' }} name="pos_all" class="sale"> &nbsp; View Pos Sale</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['pos_add'] == '1' ? 'CHECKED' : '' }} name="pos_add" class="sale"> &nbsp; Add POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['pos_edit'] == '1' ? 'CHECKED' : '' }} name="pos_edit" class="sale"> &nbsp; Edit POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['pos_delete'] == '1' ? 'CHECKED' : '' }} name="pos_delete" class="sale"> &nbsp; Delete POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['sale_access'] == '1' ? 'CHECKED' : '' }} name="sale_access" class="sale"> &nbsp; Access Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['sale_draft'] == '1' ? 'CHECKED' : '' }} name="sale_draft" class="sale"> &nbsp; List Draft </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['sale_quotation'] == '1' ? 'CHECKED' : '' }} name="sale_quotation" class="sale"> &nbsp; List Quotations </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['sale_all_own'] == '1' ? 'CHECKED' : '' }} name="sale_all_own" class="sale"> &nbsp; View Own Sale Only </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['sale_payment'] == '1' ? 'CHECKED' : '' }} name="sale_payment" class="sale"> &nbsp; View/Add/Edit Payment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_price_sale_screen'] == '1' ? 'CHECKED' : '' }}name="edit_price_sale_screen" class="sale"> &nbsp; Edit product price from sales screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_price_pos_screen'] == '1' ? 'CHECKED' : '' }} name="edit_price_pos_screen" class="sale"> &nbsp; Edit Product Price From POS Screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_discount_sale_screen'] == '1' ? 'CHECKED' : '' }} name="edit_discount_sale_screen" class="sale"> &nbsp; Edit Product Discount From Sale Screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['edit_discount_pos_screen'] == '1' ? 'CHECKED' : '' }} name="edit_discount_pos_screen" class="sale"> &nbsp; Edit Product Discount From POS Screen </p> 
                                                    </div>
                                                </div>
                
                                      
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['shipment_access'] == '1' ? 'CHECKED' : '' }} name="shipment_access" class="sale"> &nbsp; Access Shipments </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" {{ $role->permission->sale['return_access'] == '1' ? 'CHECKED' : '' }} name="return_access" class="sale"> &nbsp; Access Sale Return </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Cash Register Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="cash_register" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->register['register_view'] == '1' ? 'CHECKED' : '' }} name="register_view" class="cash_register"> &nbsp; View Cash Register</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" {{ $role->permission->register['register_close'] == '1' ? 'CHECKED' : '' }} name="register_close" class="cash_register"> &nbsp; Close Cash Register </p> 
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Brands Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="brand" autocomplete="off"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->brand['brand_all'] == '1' ? 'CHECKED' : '' }} name="brand_all" class="brand"> &nbsp; View All Brand </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->brand['brand_add'] == '1' ? 'CHECKED' : '' }} name="brand_add" class="brand"> &nbsp; Add Brand </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->brand['brand_edit'] == '1' ? 'CHECKED' : '' }} name="brand_edit" class="brand"> &nbsp; Edit Brand </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->brand['brand_delete'] == '1' ? 'CHECKED' : '' }} name="brand_delete" class="brand"> &nbsp; Delete Brand </p> 
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Categories Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="category" autocomplete="off"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>

                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->category['category_all'] == '1' ? 'CHECKED' : '' }} name="category_all" class="category"> &nbsp; View All Category </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->category['category_add'] == '1' ? 'CHECKED' : '' }} name="category_add" class="category"> &nbsp; Add Category </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->category['category_edit'] == '1' ? 'CHECKED' : '' }} name="category_edit" class="category"> &nbsp; Edit Category </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->category['category_delete'] == '1' ? 'CHECKED' : '' }} name="category_delete" class="category"> &nbsp; Delete Category </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Unit Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="unit"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->unit['unit_all'] == '1' ? 'CHECKED' : '' }} name="unit_all" class="unit"> &nbsp; View All Unit </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->unit['unit_add'] == '1' ? 'CHECKED' : '' }} name="unit_add" class="unit"> &nbsp; Add Unit </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->unit['unit_edit'] == '1' ? 'CHECKED' : '' }} name="unit_edit" class="unit"> &nbsp; Edit Unit </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->unit['unit_delete'] == '1' ? 'CHECKED' : '' }} name="unit_delete" class="unit"> &nbsp; Delete Unit </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Peport Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <h6 class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="report"> &nbsp; Select All </h6> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                       <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['loss_profit_report'] == '1' ? 'CHECKED' : '' }} name="loss_profit_report" class="report"> &nbsp; View Profit/Loss Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['purchase_sale_report'] == '1' ? 'CHECKED' : '' }} name="purchase_sale_report" class="report"> &nbsp; View Purchase & Sale Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['tax_report'] == '1' ? 'CHECKED' : '' }} name="tax_report" class="report"> &nbsp; View Tax Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['cus_sup_report'] == '1' ? 'CHECKED' : '' }} name="cus_sup_report" class="report"> &nbsp; View Customer & Supplier Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['stock_report'] == '1' ? 'CHECKED' : '' }} name="stock_report" class="report"> &nbsp; View stock report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['stock_adjustment_report'] == '1' ? 'CHECKED' : '' }} name="stock_adjustment_report" class="report"> &nbsp; Stock adjustment report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['tranding_report'] == '1' ? 'CHECKED' : '' }} name="tranding_report" class="report"> &nbsp; View Trending Product Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['item_report'] == '1' ? 'CHECKED' : '' }} name="item_report" class="report"> &nbsp; Item Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['pro_purchase_report'] == '1' ? 'CHECKED' : '' }} name="pro_purchase_report" class="report"> &nbsp; Product purchase Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['pro_sale_report'] == '1' ? 'CHECKED' : '' }} name="pro_sale_report" class="report"> &nbsp; Product sale Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['purchase_payment_report'] == '1' ? 'CHECKED' : '' }} name="purchase_payment_report" class="report"> &nbsp; Purchase payment Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['sale_payment_report'] == '1' ? 'CHECKED' : '' }} name="sale_payment_report" class="report"> &nbsp; Sale payment Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['expanse_report'] == '1' ? 'CHECKED' : '' }} name="expanse_report" class="report"> &nbsp; View Expanse Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['register_report'] == '1' ? 'CHECKED' : '' }} name="register_report" class="report"> &nbsp; View register report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->report['representative_report'] == '1' ? 'CHECKED' : '' }} name="representative_report" class="report"> &nbsp; View Sales Representative Report</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Setup Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="settings"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['tax'] == '1' ? 'CHECKED' : '' }} name="tax" class="settings"> &nbsp; Tax</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                       <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['branch'] == '1' ? 'CHECKED' : '' }} name="branch" class="settings"> &nbsp; Branch</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['warehouse'] == '1' ? 'CHECKED' : '' }} name="warehouse" class="settings"> &nbsp; Warehouse</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['g_settings'] == '1' ? 'CHECKED' : '' }} name="g_settings" class="settings"> &nbsp; General Settings</p> 
                                                    </div>
                                                </div>

                                                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input {{ $role->permission->setup['p_settings'] == '1' ? 'CHECKED' : '' }} type="checkbox" name="p_settings" class="settings"> &nbsp; Payment settings</p> 
                                                    </div>
                                                </div>
                                           
                                               
                                             
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['inv_sc'] == '1' ? 'CHECKED' : '' }} name="inv_sc" class="settings"> &nbsp; Invoice Schemas</p> 
                                                    </div>
                                                </div>
                                          
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['inv_lay'] == '1' ? 'CHECKED' : '' }} name="inv_lay" class="settings"> &nbsp; Invoice Layout</p> 
                                                    </div>
                                                </div>
                                         
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['barcode_settings'] == '1' ? 'CHECKED' : '' }} name="barcode_settings" class="settings"> &nbsp; Barcode Settings</p> 
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->setup['cash_counters'] == '1' ? 'CHECKED' : '' }} name="cash_counters" class="settings"> &nbsp; Cash Counters</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Dashboard Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                
                                            </div>
                                            
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->dashboard['dash_data'] == '1' ? 'CHECKED' : '' }} name="dash_data"> &nbsp; View Dashboard Data </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Accounting Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                
                                            </div>
                                            
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->accounting['ac_access'] == '1' ? 'CHECKED' : '' }} name="ac_access"> &nbsp; Access Accounting </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>HRM Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all"  data-target="HRMS"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->hrms['leave_type'] == '1' ? 'CHECKED' : '' }} name="leave_type" class="HRMS"> &nbsp; Add/Edit/View/Delete leave type</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->hrms['view_own_leave'] == '1' ? 'CHECKED' : '' }} name="view_own_leave" class="HRMS"> &nbsp; Add/View own leave</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->hrms['leave_approve'] == '1' ? 'CHECKED' : '' }} name="leave_approve" class="HRMS"> &nbsp; Approve Leave</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->hrms['attendance_all'] == '1' ? 'CHECKED' : '' }} name="attendance_all" class="HRMS"> &nbsp; Add/Edit/View/Delete all attendance</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->hrms['view_own_attendance'] == '1' ? 'CHECKED' : '' }} name="view_own_attendance" class="HRMS"> &nbsp;  View own attendance</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->hrms['view_a_d'] == '1' ? 'CHECKED' : '' }} name="view_a_d" class="HRMS"> &nbsp; View allowance and deduction</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->hrms['department'] == '1' ? 'CHECKED' : '' }} name="department" class="HRMS"> &nbsp; Add/Edit/View/Delete department</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->hrms['designation'] == '1' ? 'CHECKED' : '' }} name="designation" class="HRMS"> &nbsp; Add/Edit/View/Delete designation</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Essentials Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all"  data-target="Essentials"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->essential['assign_todo'] == '1' ? 'CHECKED' : '' }} name="assign_todo" class="Essentials"> &nbsp; Assign To Do's to others</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->essential['create_msg'] == '1' ? 'CHECKED' : '' }} name="create_msg" class="Essentials"> &nbsp; Create Message</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->essential['view_msg'] == '1' ? 'CHECKED' : '' }} name="view_msg" class="Essentials"> &nbsp; View Message</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
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
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->manufacturing['menuf_view'] == '1' ? 'CHECKED' : '' }} name="menuf_view" class=" Manufacturing"> &nbsp; View Recipe</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->manufacturing['menuf_add'] == '1' ? 'CHECKED' : '' }} name="menuf_add" class="Manufacturing"> &nbsp; Add Recipe</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->manufacturing['menuf_edit'] == '1' ? 'CHECKED' : '' }} name="menuf_edit" class="Manufacturing"> &nbsp;  Edit Recipe</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->manufacturing['menuf_delete'] == '1' ? 'CHECKED' : '' }} name="menuf_delete" class="Manufacturing"> &nbsp; Delete Recipe</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Projects Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="Project"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->project['proj_view'] == '1' ? 'CHECKED' : '' }} name="proj_view" class="Project"> &nbsp; View Project</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->project['proj_create'] == '1' ? 'CHECKED' : '' }} name="proj_create" class="Project"> &nbsp; Create Project</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->project['proj_edit'] == '1' ? 'CHECKED' : '' }} name="proj_edit" class="Project"> &nbsp; Edit Project</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->project['proj_delete'] == '1' ? 'CHECKED' : '' }} name="proj_delete" class="Project"> &nbsp; Delete Project</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Repair Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="Repair"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_add_invo'] == '1' ? 'CHECKED' : '' }} name="ripe_add_invo" class=" Repair"> &nbsp; Add Invoice</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_edit_invo'] == '1' ? 'CHECKED' : '' }} name="ripe_edit_invo" class="Repair"> &nbsp; Edit Invoice</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_view_invo'] == '1' ? 'CHECKED' : '' }} name="ripe_view_invo" class="Repair"> &nbsp; View Invoice</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_delete_invo'] == '1' ? 'CHECKED' : '' }} name="ripe_delete_invo" class="Repair"> &nbsp; Delete Invoice</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['change_invo_status'] == '1' ? 'CHECKED' : '' }} name="change_invo_status" class="Repair"> &nbsp; Change Invoice Status</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_jop_sheet_status'] == '1' ? 'CHECKED' : '' }} name="ripe_jop_sheet_status" class="Repair"> &nbsp; Add/Edit/Delete Job Sheet Status</p> 
                                                    </div>
                                                </div>
                
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_jop_sheet_add'] == '1' ? 'CHECKED' : '' }} name="ripe_jop_sheet_add" class="Repair"> &nbsp; Add job sheet</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_jop_sheet_edit'] == '1' ? 'CHECKED' : '' }} name="ripe_jop_sheet_edit" class="Repair"> &nbsp; Edit Job Sheet</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_jop_sheet_delete'] == '1' ? 'CHECKED' : '' }} name="ripe_jop_sheet_delete" class="Repair"> &nbsp; Delete Job Sheet</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_only_assinged_job_sheet'] == '1' ? 'CHECKED' : '' }} name="ripe_only_assinged_job_sheet" class="Repair"> &nbsp; View Only Assigned Job Sheet</p> 
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->repair['ripe_view_all_job_sheet'] == '1' ? 'CHECKED' : '' }} name="ripe_view_all_job_sheet" class="Repair"> &nbsp; View All Job Sheets</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>Superadmin Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                
                                            </div>

                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->superadmin['superadmin_access_pack_subscrip'] == '1' ? 'CHECKED' : '' }} name="superadmin_access_pack_subscrip"> &nbsp; Access package subscriptions</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>E-commerce Permission</b> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="E-commerce"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->e_commerce['e_com_sync_pro_cate'] == '1' ? 'CHECKED' : '' }} name="e_com_sync_pro_cate" class="E-commerce"> &nbsp; Sync Product Categories</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->e_commerce['e_com_sync_pro'] == '1' ? 'CHECKED' : '' }} name="e_com_sync_pro" class="E-commerce"> &nbsp; Sync Products</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->e_commerce['e_com_sync_order'] == '1' ? 'CHECKED' : '' }} name="e_com_sync_order" class="E-commerce"> &nbsp;  Sync Orders</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" {{ $role->permission->e_commerce['e_com_map_tax_rate'] == '1' ? 'CHECKED' : '' }} name="e_com_map_tax_rate" class="E-commerce"> &nbsp; Map Tax Rates</p> 
                                                    </div>
                                                </div>
                 
                                                {{--<div class="col-md-12">
                                                    <div class="row">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<h6 class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="e_com_map_tax_rate" class="form-control E-commerce"> &nbsp; Access Woocommerce API settings</h6> 
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="submit-area py-3 mb-4">
                                    <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                    <button class="btn btn-sm btn-primary submit_button float-end">Save</button>
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
