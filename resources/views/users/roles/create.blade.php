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
            <form id="add_role_form" action="{{ route('users.role.store') }}"  method="POST">
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
                                                    <h5>Add Role</h5>
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
                                                            placeholder="Role Name">
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
                                                        <input type="checkbox" id="select_all" data-target="users"
                                                            autocomplete="off"> &nbsp; Select All
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_view" class="users"> &nbsp; View
                                                        User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_add" class="users"
                                                            autocomplete="off"> &nbsp; Add User
                                                    </p>

                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_edit" class="users"
                                                            autocomplete="off"> &nbsp; Edit User
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="user_delete" class="users"
                                                            autocomplete="off"> &nbsp; Delete User
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
                                                    <input type="checkbox" id="select_all" class=""
                                                        data-target="roles"> &nbsp; Select All
                                                </p>

                                            </div>

                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="role_view" class="roles">
                                                        &nbsp; View Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="role_add" class="roles">
                                                        &nbsp; Add Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="role_edit" class="roles">
                                                        &nbsp; Edit Role
                                                    </p>
                                                </div>

                                                <div class="col-md-12">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" name="role_delete"
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
                                                    <input type="checkbox" id="select_all" data-target="suppliers" autocomplete="off"> &nbsp;  Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="supplier_all" class="suppliers"> &nbsp; View All Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_add" class="suppliers"> &nbsp; Add Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_edit" class="suppliers"> &nbsp; Edit Supplier </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="supplier_delete" class="suppliers"> &nbsp; Delete Supplier </p> 
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
                                                        <input type="checkbox" name="customer_all" class="customers"> &nbsp; View All Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_add" class="customers"> &nbsp; Add Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_edit" class=" customers"> &nbsp; Edit Customer </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row"><p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="customer_delete" class="customers"> &nbsp; Delete Customer </p> 
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
                                                        <input type="checkbox" name="product_all" class="product"> &nbsp; View All Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="product_add" class="product"> &nbsp; Add Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="product_edit" class="product"> &nbsp; Edit Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="openingStock_add" class="product"> &nbsp; Add Opening Stock </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="product_delete" class="product"  value=""> &nbsp; Delete Product </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="pro_unit_cost" class="product"> &nbsp; View Purchase Price </p> 
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
                                                        <input type="checkbox" name="purchase_all" class="purchase"> &nbsp; View All Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_add" class="purchase"> &nbsp; Add Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_edit" class="purchase"> &nbsp; Edit Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_delete" class="purchase" > &nbsp; Delete Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_payment" class="purchase"> &nbsp; View/Add/Delete Purchase </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="purchase_return" class="purchase"> &nbsp; Access Purchase Return </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="status_update" class="purchase"> &nbsp; Update Status </p> 
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
                                                        <input type="checkbox" name="adjustment_all" class="adjustment"> &nbsp; View All Adjustment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="adjustment_add" class="adjustment"> &nbsp; Add Adjustment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="adjustment_delete" class="adjustment" > &nbsp; Delete Adjustment </p> 
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
                                                        <input type="checkbox" name="pos_all" class="sale"> &nbsp; View Pos Sale</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="pos_add" class="sale"> &nbsp; Add POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="pos_edit" class="sale"> &nbsp; Edit POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="pos_delete" class="sale"> &nbsp; Delete POS Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="sale_access" class="sale"> &nbsp; Access Sale </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="sale_draft" class="sale"> &nbsp; List Draft </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="sale_quotation" class="sale"> &nbsp; List Quotations </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="sale_all_own" class="sale"> &nbsp; View Own Sale Only </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="sale_payment" class="sale"> &nbsp; View/Add/Edit Payment </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="edit_price_sale_screen" class="sale"> &nbsp; Edit product price from sales screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="edit_price_pos_screen" class="sale"> &nbsp; Edit Product Price From POS Screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="edit_discount_sale_screen" class="sale"> &nbsp; Edit Product Discount From Sale Screen </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="edit_discount_pos_screen" class="sale"> &nbsp; Edit Product Discount From POS Screen </p> 
                                                    </div>
                                                </div>
                
                                      
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="shipment_access" class="sale"> &nbsp; Access Shipments </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-2"> 
                                                        <input type="checkbox" name="return_access" class="sale"> &nbsp; Access Sale Return </p> 
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
                                                        <input type="checkbox" name="register_view" class="cash_register"> &nbsp; View Cash Register</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="register_close" class="cash_register"> &nbsp; Close Cash Register </p> 
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
                                                        <input type="checkbox" name="brand_all" class="brand"> &nbsp; View All Brand </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="brand_add" class="brand"> &nbsp; Add Brand </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="brand_edit" class="brand"> &nbsp; Edit Brand </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="brand_delete" class="brand"> &nbsp; Delete Brand </p> 
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
                                                        <input type="checkbox" name="category_all" class="category"> &nbsp; View All Category </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="category_add" class="category"> &nbsp; Add Category </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="category_edit" class="category"> &nbsp; Edit Category </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="category_delete" class="category"> &nbsp; Delete Category </p> 
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
                                                        <input type="checkbox" name="unit_all" class="unit"> &nbsp; View All Unit </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="unit_add" class="unit"> &nbsp; Add Unit </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="unit_edit" class="unit"> &nbsp; Edit Unit </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="unit_delete" class="unit"> &nbsp; Delete Unit </p> 
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
                                                    <p class="checkbox_input_wrap"> 
                                                    <input type="checkbox" id="select_all" data-target="report"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                       <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="loss_profit_report" class="report"> &nbsp; View Profit/Loss Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="purchase_sale_report" class="report"> &nbsp; View Purchase & Sale Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="tax_report" class="report"> &nbsp; View Tax Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="cus_sup_report" class="report"> &nbsp; View Customer & Supplier Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="stock_report" class="report"> &nbsp; View stock report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="stock_adjustment_report" class="report"> &nbsp; Stock adjustment report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="tranding_report" class="report"> &nbsp; View Trending Product Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="item_report" class="report"> &nbsp; Item Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="pro_purchase_report" class="report"> &nbsp; Product purchase Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="pro_sale_report" class="report"> &nbsp; Product sale Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="purchase_payment_report" class="report"> &nbsp; Purchase payment Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="sale_payment_report" class="report"> &nbsp; Sale payment Peport</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="expanse_report" class="report"> &nbsp; View Expanse Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="register_report" class="report"> &nbsp; View register report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="representative_report" class="report"> &nbsp; View Sales Representative Report</p> 
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
                                                    <input type="checkbox" id="select_all" data-target="settings"> &nbsp; Select All</p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="tax" class="settings"> &nbsp; Tax</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                       <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="branch" class="settings"> &nbsp; Business Location</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="warehouse" class="settings"> &nbsp; Warehouse</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="g_settings" class="settings"> &nbsp; General Settings</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="p_settings" class="settings"> &nbsp; Payment settings</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="inv_sc" class="settings"> &nbsp; Invoice Schemas</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="inv_lay" class="settings"> &nbsp; Invoice Layout</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="barcode_settings" class="settings"> &nbsp; Barcode Settings</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="cash_counters" class="settings"> &nbsp; Cash Counters</p> 
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
                                                        <input type="checkbox" name="dash_data"> &nbsp; View Dashboard Data </p> 
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
                                                        <input type="checkbox" name="ac_access"> &nbsp; Access Accounting </p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($addons->hrm == 1)
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
                                                            <input type="checkbox" name="leave_type" class="HRMS"> &nbsp; Add/Edit/View/Delete leave type</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="view_own_leave" class="HRMS"> &nbsp; Add/View own leave</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="leave_approve" class="HRMS"> &nbsp; Approve Leave</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="attendance_all" class="HRMS"> &nbsp; Add/Edit/View/Delete all attendance</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="view_own_attendance" class="HRMS"> &nbsp;  View own attendance</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="view_a_d" class="HRMS"> &nbsp; View allowance and deduction</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="department" class="HRMS"> &nbsp; Add/Edit/View/Delete department</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="designation" class="HRMS"> &nbsp; Add/Edit/View/Delete designation</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($addons->todo == 1)
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
                                                            <input type="checkbox" name="assign_todo" class="Essentials"> &nbsp; Assign To Do's</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="create_msg" class="Essentials"> &nbsp; Create Message</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="view_msg" class="Essentials"> &nbsp; View Message</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($addons->service == 1)
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
                                                            <input type="checkbox" name="menuf_view" class=" Manufacturing"> &nbsp; View Recipe</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="menuf_add" class="Manufacturing"> &nbsp; Add Recipe</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="menuf_edit" class="Manufacturing"> &nbsp;  Edit Recipe</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="menuf_delete" class="Manufacturing"> &nbsp; Delete Recipe</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- <div class="col-md-8">
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
                                                        <input type="checkbox" name="proj_view" class="Project"> &nbsp; View Project</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="proj_create" class="Project"> &nbsp; Create Project</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="proj_edit" class="Project"> &nbsp; Edit Project</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="proj_delete" class="Project"> &nbsp; Delete Project</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            @if ($addons->service == 1)
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
                                                            <input type="checkbox" name="ripe_add_invo" class=" Repair"> &nbsp; Add Invoice</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_edit_invo" class="Repair"> &nbsp; Edit Invoice</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_view_invo" class="Repair"> &nbsp; View Invoice</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_delete_invo" class="Repair"> &nbsp; Delete Invoice</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="change_invo_status" class="Repair"> &nbsp; Change Invoice Status</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_jop_sheet_status" class="Repair"> &nbsp; Add/Edit/Delete Job Sheet Status</p> 
                                                        </div>
                                                    </div>
                    
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_jop_sheet_add" class="Repair"> &nbsp; Add job sheet</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_jop_sheet_edit" class="Repair"> &nbsp; Edit Job Sheet</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_jop_sheet_delete" class="Repair"> &nbsp; Delete Job Sheet</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_only_assinged_job_sheet" class="Repair"> &nbsp; View Only Assigned Job Sheet</p> 
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="ripe_view_all_job_sheet" class="Repair"> &nbsp; View All Job Sheets</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- <div class="col-md-8">
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
                                                        <input type="checkbox" name="superadmin_access_pack_subscrip"> &nbsp; Access package subscriptions</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            @if ($addons->e_commerce == 1)
                                <div class="col-md-8">
                                    <div class="form_element m-0 mt-2">
                                        <div class="heading_area">
                                            <p class="p-1 text-primary"><b>E-Commerce Permission</b> </p>
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
                                                            <input type="checkbox" name="e_com_sync_pro_cate" class="E-commerce"> &nbsp; Sync Product Categories</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="e_com_sync_pro" class="E-commerce"> &nbsp; Sync Products</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="e_com_sync_order" class="E-commerce"> &nbsp;  Sync Orders</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="e_com_map_tax_rate" class="E-commerce"> &nbsp; Map Tax Rates</p> 
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
                            @endif
                          
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
