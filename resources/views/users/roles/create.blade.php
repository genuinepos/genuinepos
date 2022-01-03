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
                                                        <input type="checkbox" name="openingStock_add" class="product"> &nbsp; Add/Edit Opening Stock </p> 
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
                                                        <input type="checkbox" name="categories" class="product"> &nbsp; Categories</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="brand" class="product"> &nbsp; Brands</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="units" class="product"> &nbsp; Unit</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="variant" class="product"> &nbsp; Variants</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="warranties" class="product"> &nbsp; Warranties</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="selling_price_group" class="product"> &nbsp; Selling Price Group</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="generate_barcode" class="product"> &nbsp; Generate Barcode</p> 
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
                                                    <p class="checkbox_input_wrap"> 
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
                                                        <input type="checkbox" name="adjustment_add_from_location" class="adjustment"> &nbsp; Add Adjustment From Business Location</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="adjustment_add_from_warehouse" class="adjustment"> &nbsp; Add Adjustment From Warehouse</p> 
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
                                        <p class="p-1 text-primary"><strong>Expense Permission</strong> </p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap "> 
                                                    <input type="checkbox" id="select_all" data-target="expense" autocomplete="off"> &nbsp; Select All </p> 
                                                </div>
                                            </div>
                
                                            <div class="col-md-6 offset-2">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="view_expense" class="expense"> &nbsp; View Expense </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="add_expense" class="expense"> &nbsp; Add Expense </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="edit_expense" class="expense"> &nbsp; Edit Expense </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="delete_expense" class="expense"> &nbsp; Delete Expense </p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="expense_category" class="expense"> &nbsp; Expense Category -> View/Add/Edit/Delete </p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="category_wise_expense" class="expense"> &nbsp; View Category Wise Expense </p> 
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

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap mt-1"> 
                                                        <input type="checkbox" name="another_register_close" class="another_register_close"> &nbsp; Close Another Cash Register </p> 
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
                                                        <input type="checkbox" name="loss_profit_report" class="report"> &nbsp; Profit/Loss Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="purchase_sale_report" class="report"> &nbsp; Purchase & Sale Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="tax_report" class="report"> &nbsp; Tax Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="customer_report" class="report"> &nbsp; Customer Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="supplier_report" class="report"> &nbsp; Supplier Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="stock_report" class="report"> &nbsp; stock Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="stock_adjustment_report" class="report"> &nbsp; Stock Adjustment Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="pro_purchase_report" class="report"> &nbsp; Product Purchase Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="pro_sale_report" class="report"> &nbsp; Product Sale Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="purchase_payment_report" class="report"> &nbsp; Purchase Payment Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="sale_payment_report" class="report"> &nbsp; Receive Payment Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="expanse_report" class="report"> &nbsp; Expanse Report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="register_report" class="report"> &nbsp; Cash Register report</p> 
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="representative_report" class="report"> &nbsp; Sales Representative Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="payroll_report" class="report"> &nbsp; Payroll Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="payroll_payment_report" class="report"> &nbsp; Payroll Payment Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="payroll_payment_report" class="report"> &nbsp; Attendance Report</p> 
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap"> 
                                                        <input type="checkbox" name="production_report" class="report"> &nbsp; Production Report</p> 
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
                                            <p class="p-1 text-primary"><b>Manage Task Permission</b> </p>
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
                                                            <input type="checkbox" name="assign_todo" class="Essentials"> &nbsp; Todo</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="work_space" class="Essentials"> &nbsp; Work Space</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="memo" class="Essentials"> &nbsp; Memo</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="msg" class="Essentials"> &nbsp; Message</p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($addons->manufacturing == 1)
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
                                                            <input type="checkbox" name="process_view" class=" Manufacturing"> &nbsp; View Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="process_add" class="Manufacturing"> &nbsp; Add Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="process_edit" class="Manufacturing"> &nbsp;  Edit Process</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="process_delete" class="Manufacturing"> &nbsp; Delete Process</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="production_view" class=" Manufacturing"> &nbsp; View Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="production_add" class="Manufacturing"> &nbsp; Add Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="production_edit" class="Manufacturing"> &nbsp;  Edit Production</p> 
                                                        </div>
                                                    </div>
                    
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="production_delete" class="Manufacturing"> &nbsp; Delete Production</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="manuf_settings" class="Manufacturing"> &nbsp; Manufacturing Settings</p> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="manuf_report" class="Manufacturing"> &nbsp; Manufacturing Report</p> 
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
