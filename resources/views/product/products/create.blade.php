@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('public') }}/assets/plugins/custom/dropify/css/dropify.min.css" rel="stylesheet" type="text/css"/>
<style>
    .card-custom {background: white;border-radius: 6px;border-top: 2px solid #641E16;}
</style>
@endpush
@section('content')
    <br>
    <div class="content body-woaper">
        <div class="container-fluid">
            <!--begin::Container-->

            <!-- Golmenu area -->
            <div class="menu_popup_area">
                <div class="menu_close">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="" id="close_popup_btn" class="ml-4"><i class="fas fa-times text-muted"></i></a>
                        </div>
                    </div>
                </div>
                <div class="menu_list_area">

                </div>
            </div>
            <!-- Golmenu area end-->
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3 style="color: #32325d">Add Product</h3>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-info float-end"><i
                                class="fas fa-long-arrow-alt-left"></i>Back</a>
                    </div>
                </div>

                <!--begin::Form-->
                <form id="add_product_form" action="{{ route('products.add.store') }}" enctype="multipart/form-data"
                    method="POST">
                    <div class="card-body card-custom ">
                        <input type="hidden" id="categories" data-categories="">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <b>Product Name :</b> <span class="text-danger">*</span>
                                <input type="text" name="name" class="form-control form-control-sm" id="name" placeholder="Product Name" autofocus>
                                <span class="error error_name"></span>
                            </div>

                            <div class="col-md-3">
                                <b>Product code (SKU) :</b> <span class="text-danger">*</span>
                                <div class="input-group">
                                    <input type="text" name="code" class="form-control form-control-sm scanable" autocomplete="off" id="code" value="{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}" placeholder="Product Code">
                                    {{-- <div class="input-group-prepend">
                                       <a class="code_generate_btn" href="">
                                          <i class="fas fa-sync-alt"></i>
                                       </a>
                                    </div> --}}
                                    <div class="input-group-prepend">
                                        <span class="input-group-text code_generate_btn"><i class="fas fa-sync-alt"></i></span>
                                    </div>
                                </div>
                                <span class="error error_code"></span>
                            </div>

                            <div class="col-md-3">
                                <b>Unit :</b> <span class="text-danger">*</span>
                                <div class="input-group">
                                    <select class="form-control form-control-sm product_unit" name="unit_id" id="unit_id">
                                    </select>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text add_button" data-bs-toggle="modal"
                                            data-bs-target="#addUnitModal"><i class="fas fa-plus-square text-navy-blue"></i></span>
                                    </div>
                                </div>
                                <span class="error error_unit_id"></span>
                            </div>

                            <div class="col-md-3">
                                <b>Barcode Type :</b>
                                <select class="form-control form-control-sm" name="barcode_type" id="barcode_type">
                                    <option value="CODE128">Code 128 (C128)</option>
                                    <option value="CODE39">Code 39 (C39)</option>
                                    <option value="EAN13">EAN-13</option>
                                    <option value="UPC">UPC</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')
                                <div class="col-md-3">
                                   <b>Category :</b> <span class="text-danger">*</span>
                                    <div class="input-group">
                                        <select class="form-control form-control-sm category" name="category_id"
                                            id="category_id">
                                        </select>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text add_button" data-bs-toggle="modal"
                                                data-bs-target="#addCategoryModal"><i
                                                    class="fas fa-plus-square text-navy-blue"></i></span>
                                        </div>
                                    </div>
                                    <span class="error error_category_id"></span>
                                </div>
                            @endif

                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1' && json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1')
                                <div class="col-md-3">
                                    <b>Sub-category :</b>
                                    <select class="form-control form-control-sm" name="child_category_id"
                                        id="child_category_id">
                                        <option value="">Select child category first</option>
                                    </select>
                                </div>
                            @endif

                            @if (json_decode($generalSettings->product, true)['is_enable_brands'] == '1')
                                <div class="col-md-3">
                                    <b>Brand :</b>
                                    <div class="input-group">
                                        <select class="form-control form-control-sm" data-live-search="true" name="brand_id"
                                            id="brand_id">
                                        </select>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text add_button" data-bs-toggle="modal"
                                                data-bs-target="#addBrandModal"><i
                                                    class="fas fa-plus-square text-navy-blue"></i></span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-3">
                                <b>Alert quentity :</b>
                                <input type="number" name="alert_quantity" class="form-control form-control-sm"
                                    autocomplete="off" id="alert_quantity" value="0">
                                <span class="error error_alert_quantity"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">
                                <b>Expired date :</b>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fas fa-calendar-week text-navy-blue"></i></span>
                                    </div>
                                    <input type="text" name="expired_date" class="form-control form-control-sm date-picker"
                                        autocomplete="off" placeholder="dd-mm-yyyy">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <b>Product Condition :</b>
                                <select class="form-control form-control-sm" name="product_condition"
                                    id="product_condition">
                                    <option value="New">New</option>
                                    <option value="Used">Used</option>
                                </select>
                            </div>

                            @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')
                                <div class="col-md-3">
                                    <b>Warranty :</b>
                                    <div class="input-group">
                                        <select class="form-control form-control-sm" name="warranty_id" id="warranty_id">
                                            <option value="">Select Warranty</option>
                                        </select>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text add_button" data-bs-toggle="modal"
                                                data-bs-target="#addWarrantyModal"><i
                                                    class="fas fa-plus-square text-navy-blue"></i></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div> 
                    </div>  

                    <div class="card-body card-custom mt-5">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <b>Description :</b>
                                <textarea id="summernote" name="product_details" class="form-control">
                                            </textarea>
                            </div>

                            <div class="col-md-4 product_dropify">
                                <b>Image :</b> 
                                <input type="file" name="image[]"  id="image" accept="image" multiple>
                                <span class="error error_image"></span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body card-custom mt-5">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <b>Weight :</b>
                                <input type="text" name="weight" class="form-control form-control-sm" id="weight" placeholder="Weight">
                            </div>

                            <div class="col-md-3">
                                <b>Custom Field1 :</b>
                                <input type="text" name="custom_field_1" class="form-control form-control-sm" placeholder="Custom field1">
                            </div>

                            <div class="col-md-3">
                                <b>Custom Field2 :</b>
                                <input type="text" name="custom_field_2" class="form-control form-control-sm" placeholder="Custom field2">
                            </div>

                            <div class="col-md-3">
                                <b>Custom Field3 :</b>
                                <input type="text" name="custom_field_3" class="form-control form-control-sm" placeholder="Custom field3">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-5">
                                &nbsp;&nbsp;<h6 class="checkbox_input_wrap"> <input type="checkbox" name="is_show_in_ecom" class="checkbox" id="is_show_in_ecom" value="1"> &nbsp; Product wil be displayed in E-Commerce. &nbsp; <i data-toggle="tooltip" data-placement="top" title="Product would be displayed in your e-commerce site" class="fas fa-info-circle tp"></i> </h6>
                                </div>
                                <div class="col-5">
                                &nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input type="checkbox" name="is_show_emi_on_pos" class="checkbox" id="is_show_emi_on_pos" value="1"> &nbsp; Enable Product description, IMEI or Serial Number &nbsp; <i data-toggle="tooltip" data-placement="top" title="Enable or disable adding product description, IMEI or Serial number while selling products in POS screen" class="fas fa-info-circle tp"></i></h6>
                                </div>
                                <div class="col-2">
                                &nbsp;&nbsp; <h6 class="checkbox_input_wrap"> <input type="checkbox" name="is_not_for_sale" class="checkbox" id="is_not_for_sale" value="0"> &nbsp; Not For Sale &nbsp; <i data-toggle="tooltip" data-placement="top" title="If checked, product will not be displayed in sales screen for selling purposes." class="fas fa-info-circle tp"></i></h6>
                                </div>
                            </div>
                        </div>
                    </div>    

                    <div class="card-body card-custom mt-5">
                        <div class="product_type_and_tax_area">
                          <div class="form-group row">
                            @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')
                                <div class="col-md-3">
                                    <b>Tax :</b>
                                    <select class="form-control form-control-sm" name="tax_id" id="tax_id">
                                        
                                    </select>
                                    <span class="error error_tax"></span>
                                </div>
                            @endif

                            <div class="col-md-3">
                                <b>Type :</b> <span class="text-danger">*</span>
                                <select name="type" class="form-control form-control-sm" id="type">
                                    <option value="1">General</option>
                                    <option value="2">Combo</option>
                                </select>
                                <span class="error error_type"></span>
                            </div>
                        </div>
                        </div>
                        <hr>

                        <div class="product_type_area">
                            <div class="form_part">
                                <div class="general_product_and_pricing_area">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Default Unit Cost</th>
                                                            <th>Profit(%)</th>
                                                            <th>Default Unit Price</th>
                                                            <th>Photo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <b>Unit Cost :</b> <span class="text-danger">*</span>
                                                                        <input type="text" name="product_cost" class="form-control form-control-sm"
                                                                        autocomplete="off" id="product_cost">
                                                                        <span class="error error_product_cost"></span>
                                                                    </div>
                            
                                                                    <div class="col-md-6">
                                                                        <b>Unit Cost (Inc.Tax) :</b><span class="text-danger">*</span>
                                                                        <input type="text" readonly name="product_cost_with_tax" class="form-control form-control-sm" autocomplete="off"
                                                                            id="product_cost_with_tax">
                                                                        <span class="error error_product_cost_with_tax"></span>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <br>
                                                                <input type="text" name="profit" class="form-control form-control-sm mt-2"
                                                                autocomplete="off" id="profit" value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
                                                            </td>

                                                            <td>
                                                                <b>Price Exc.Tax :</b><span class="text-danger">*</span>
                                                                <input type="text" name="product_price" class="form-control form-control-sm"
                                                                    autocomplete="off" id="product_price">
                                                                <span class="error error_product_price"></span>
                                                            </td>

                                                            <td>
                                                               <b>Photo :</b> <span class="text-danger">*</span>
                                                                <input type="file" name="photo" class="form-control form-control-sm"
                                                                    id="photo">
                                                                <span class="error error_photo"></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        &nbsp;&nbsp;&nbsp;&nbsp;<h6 class="checkbox_input_wrap"> <input type="checkbox" name="is_variant" class="checkbox" autocomplete="off" id="is_variant"> &nbsp; This product has varient. </h6>
                                    </div>

                                    <div class="dynamic_variant_create_area d-none">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="add_more_btn">
                                                    <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-right mb-1"
                                                        href="">Add</a>
                                                </div>
                                                <div class="table-responsive mt-3">
                                                    <table class="table variant_table table-sm table-hover form_table">
                                                        <thead>
                                                            <tr class="text-center bg-primary variant_header">
                                                                <th>Variant Combination</th>
                                                                <th>
                                                                    Varient code (SKU) 
                                                                    <i data-toggle="tooltip" data-placement="top"
                                                                        title="You can customize the variant code" class="fas fa-info-circle tp text-light"></i>
                                                                </th>
                                                                <th colspan="2">Default Cost</th>
                                                                <th>Profit(%)</th>
                                                                <th>Default Price (Exc.Tax)</th>
                                                                <th>Image</th>
                                                                <th><i class="fas fa-trash-alt text-white"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="dynamic_variant_body">
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control form-control form-control-sm" name=""
                                                                        id="variants">
            
                                                                    </select>
                                                                    <input type="text" name="variant_combinations[]"
                                                                        id="variant_combination" class="form-control form-control-sm"
                                                                        placeholder="Variant Combination">
                                                                </td>

                                                                <td>
                                                                    <input type="text" name="variant_codes[]" id="variant_code"
                                                                        class="form-control form-control form-control-sm mt-3"
                                                                        placeholder="Variant Code">
                                                                </td>

                                                                <td>
                                                                    <input type="number" name="variant_costings[]"
                                                                        class="form-control form-control form-control-sm mt-3"
                                                                        placeholder="Cost" id="variant_costing">
                                                                </td>

                                                                <td>
                                                                    <input type="number" name="variant_costings_with_tax[]"
                                                                        class="form-control form-control form-control-sm mt-3"
                                                                        placeholder="Cost inc.tax" id="variant_costing_with_tax">
                                                                </td>

                                                                <td>
                                                                    <input type="number" name="variant_profits[]"
                                                                        class="form-control form-control form-control-sm mt-3"
                                                                        placeholder="Profit" value="0.00" id="variant_profit">
                                                                </td>
            
                                                                <td>
                                                                    <input type="text" name="variant_prices_exc_tax[]"
                                                                        class="form-control form-control form-control-sm mt-3"
                                                                        placeholder="Price inc.tax" id="variant_price_exc_tax">
                                                                </td>
            
                                                                <td>
                                                                    <input type="file" name="variant_image[]"
                                                                        class="form-control form-control form-control-sm mt-3 "
                                                                        id="variant_image">
                                                                </td>

                                                                <td>
                                                                    <a href="#" id="variant_remove_btn"
                                                                        class="btn btn-xs btn-sm btn-danger mt-3">X</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- form part end -->
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn loading_button btn-sm d-none"><i class="fas fa-spinner"></i>
                                <strong>Loading</strong> </button>
                            <button type="submit" name="action" value="save_and_new"
                                class="btn btn-primary submit_button btn-sm">Save And Add Another</button>
                            <button type="submit" name="action" value="save"
                                class="btn btn-success submit_button btn-sm">Save</button>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div><br><br>

    <!-- Select modal  -->
    <div class="modal fade" id="VairantChildModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog select_variant_modal_dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Select variant Child</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="modal_variant_list_area">
                                <ul class="modal_variant_child">
                                    <li class="modal_variant_child_list">
                                        <a class="select_variant_product" data-child="" href="#">X</a>
                                    </li>

                                    <li class="modal_variant_child_list">
                                        <a class="select_variant_product" data-child="" href="#">X</a>
                                    </li>

                                    <li class="modal_variant_child_list">
                                        <a class="select_variant_product" data-child="" href="#">X</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Select variant modal -->

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Category</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_category_form" action="{{ route('products.add.category') }}">
                        <div class="form-group">
                            <b>Name :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control form-control-sm add_cate_input"
                                data-name="Category name" id="add_cate_name" placeholder="Category name" />
                            <span class="error error_add_cate_name"></span>
                        </div>

                        <div class="form-group text-right mt-3">
                            <button type="button" class="btn btn-sm btn-danger font-weight-bold"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit"
                                class="btn btn-sm btn-primary font-weight-bold submit_button">Submit</button>
                            <span class="btn loading_button d-none"><i class="fas fa-spinner"></i> Loading </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Category Modal End -->

    <!-- Add Brand Modal -->
    <div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Brand</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                       x
                    </button>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_brand_form" action="{{ route('products.add.brand') }}">
                        <div class="form-group">
                            <b>Name :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control form-control-sm add_brand_input"
                                data-name="Brand name" id="add_brand_name" placeholder="Brand name" />
                            <span class="error error_add_brand_name"></span>
                        </div>

                        <div class="form-group text-right mt-3">
                            <button type="button" class="btn btn-sm btn-danger font-weight-bold"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit"
                                class="btn btn-sm btn-primary font-weight-bold submit_button">Submit</button>
                            <span class="btn loading_button d-none"><i class="fas fa-spinner"></i> Loading </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Brand Modal End -->

    <!-- Add Brand Modal -->
    <div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Unit</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_unit_form" action="{{ route('products.add.unit') }}">
                        <div class="form-group">
                            <b>Name :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control form-control-sm add_unit_input"
                                data-name="Unit name" id="add_unit_name" placeholder="Unit name" />
                            <span class="error error_add_unit_name"></span>
                        </div>

                        <div class="form-group">
                            <b>Unit Code :</b> <span class="text-danger">*</span>
                            <input type="text" name="code" class="form-control form-control-sm add_unit_input"
                                data-name="Unit code" id="add_unit_code" placeholder="Unit code" />
                            <span class="error error_add_unit_code"></span>
                        </div>

                        <div class="form-group text-right mt-3">
                            <button type="button" class="btn btn-sm btn-danger font-weight-bold"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit"
                                class="btn btn-sm btn-primary font-weight-bold submit_button">Submit</button>
                            <span class="btn loading_button d-none"><i class="fas fa-spinner"></i> Loading </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Brand Modal End -->
    <!-- Add Brand Modal -->
    <div class="modal fade" id="addWarrantyModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Warranty</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_warranty_form" action="{{ route('products.add.warranty') }}">
                        <div class="form-group">
                            <strong>Name :</strong> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_warranty_input" id="add_warranty_name" data-name="Warranty name" placeholder="Warranty name"/>
                            <span class="error error_add_warranty_name"></span>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <strong>Type :</strong> <span class="text-danger">*</span>
                                <select name="type" class="form-control" id="type">
                                    <option value="1">Warranty</option>
                                    <option value="2">Guaranty</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-8">
                                <strong>Duration :</strong> <span class="text-danger">*</span>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <input type="number" name="duration" class="form-control w-50 add_warranty_input" data-name="Warranty duration" id="add_warranty_duration" placeholder="Warranty duration">
                                            <select name="duration_type" class="form-control w-50" id="duration_type">
                                                <option value="Months">Months</option>
                                                <option value="Days">Days</option>
                                                <option value="Year">Year</option>
                                            </select>
                                        </div> 
                                        <span class="error error_add_warranty_duration"></span>
                                    </div>
                                </div>
                            </div>  
                        </div>

                        <div class="form-group">
                            <strong>Description :</strong> 
                            <textarea name="description" id="description" class="form-control" cols="10" rows="3" placeholder="Warranty description"></textarea>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue float-end">Save</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Brand Modal End -->
@endsection

@push('scripts')
<script src="{{ asset('public') }}/assets/plugins/custom/dropify/js/dropify.min.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/dropify/js/dropify.active.js"></script>

    <script>
        // Set parent category in parent category form field
        $('.combo_price').hide();
        $('.combo_pro_table_field').hide();

        var tax_percent = 0;
        $('#tax_id').on('change', function() {
            var tax = $(this).val();
            if (tax) {
                var split = tax.split('-');
                tax_percent = split[1];
                console.log(split);
            }else{
                tax_percent = 0;
            }
        });

        function costCalculate() {
            console.log(tax_percent);
            var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
            var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent);
            var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
            $('#product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
            var profit = $('#profit').val() ? $('#profit').val() : 0;
            var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
            var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
            $('#product_price').val(parseFloat(product_price).toFixed(2));

            // calc package product profit
            var netTotalComboPrice = $('#total_combo_price').val() ? $('#total_combo_price').val() : 0;
            var calcTotalComboPrice = parseFloat(netTotalComboPrice) / 100 * parseFloat(profit) + parseFloat(netTotalComboPrice);
            $('#combo_price').val(parseFloat(calcTotalComboPrice).toFixed(2));
        }

        $(document).on('input', '#product_cost',function() {
            costCalculate();
        });

        $(document).on('input', '#product_price',function() {
            var selling_price = $(this).val();
            var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
            var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
            var calcProfit = parseFloat(profitAmount) / parseFloat(product_cost) * 100;
            $('#profit').val(parseFloat(calcProfit).toFixed(2));
        });

        $('#tax_id').on('change', function() {
            costCalculate();
        });

        $(document).on('input', '#profit',function() {
            costCalculate();
        });

        // Variant all functionality 
        var variantsWithChild = '';
        function getAllVariant() {
            $.ajax({
                url: "{{ route('products.add.get.all.from.variant') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(variants) {
                    console.log(variants);
                    variantsWithChild = variants;
                    $('#variants').append('<option value="">Create Combination</option>');
                    $.each(variants, function(key, val) {
                        $('#variants').append('<option value="' + val.id + '">' + val
                            .bulk_variant_name + '</option>');
                    });
                }
            });
        }
        getAllVariant();

        var variant_row_index = 0;
        $(document).on('change', '#variants', function() {
            var id = $(this).val();
            console.log(variantsWithChild);
            var parentTableRow = $(this).closest('tr');
            variant_row_index = parentTableRow.index();
            //console.log(id);
            $('.modal_variant_child').empty();
            var html = '';
            var variant = variantsWithChild.filter(function(variant) {
                return variant.id == id;
            });
            console.log(variant);
            $.each(variant[0].bulk_variant_childs, function(key, child) {
                html += '<li class="modal_variant_child_list">';
                html += '<a class="select_variant_child" data-child="' + child.child_name + '" href="#">' +
                    child.child_name + '</a>';
                html += '</li>';
            });
            //console.log(html);
            $('.modal_variant_child').html(html);
            $('#VairantChildModal').modal('show');
            $(this).val('');
        });

        $(document).on('click', '.select_variant_child', function(e) {
            e.preventDefault();
            var child = $(this).data('child');
            console.log(child);
            var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
            var child_value = parent_tr.find('#variant_combination').val();
            var filter = child_value == '' ? '' : ',';
            var variant_combination = parent_tr.find('#variant_combination').val(child_value + filter + child);
            var product_code = $('#code').val();
            parent_tr.find('#variant_code').val(parent_tr.find('#variant_combination').val().split(/\s/).join('').toLowerCase() + '-' +product_code);
            $('#VairantChildModal').modal('hide');
        });

        $(document).on('input', '#variant_costing', function() {
            var parentTableRow = $(this).closest('tr');
            variant_row_index = parentTableRow.index();
            calculateVariantAmount(variant_row_index);
        });

        $(document).on('input', '#variant_profit', function() {
            var parentTableRow = $(this).closest('tr');
            variant_row_index = parentTableRow.index();
            calculateVariantAmount(variant_row_index);
        });

        function calculateVariantAmount(variant_row_index) {
            var parent_tr = $('.dynamic_variant_body tr:nth-child(' + (variant_row_index + 1) + ')');
            var tax = tax_percent;
            var variant_costing = parent_tr.find('#variant_costing');
            var variant_costing_with_tax = parent_tr.find('#variant_costing_with_tax');
            var variant_profit = parent_tr.find('#variant_profit').val() ? parent_tr.find('#variant_profit').val() : 0.00;
            var variant_price_exc_tax = parent_tr.find('#variant_price_exc_tax');

            var tax_rate = parseFloat(variant_costing.val()) / 100 * tax;
            var cost_with_tax = parseFloat(variant_costing.val()) + tax_rate;
            variant_costing_with_tax.val(parseFloat(cost_with_tax).toFixed(2));

            var profit = parseFloat(variant_costing.val()) / 100 * parseFloat(variant_profit) + parseFloat(variant_costing
                .val());
            variant_price_exc_tax.val(parseFloat(profit).toFixed(2));
        }

        // Get default profit
        var defaultProfit = {{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }};
        $(document).on('click', '#add_more_variant_btn',function(e) {
            e.preventDefault();
            var product_cost = $('#product_cost').val();
            var product_cost_with_tax = $('#product_cost_with_tax').val();
            var profit = $('#profit').val();
            var product_price = $('#product_price').val();
            var html = '';
            html += '<tr id="more_new_variant">';
            html += '<td>';
            html += '<select class="form-control form-control form-control-sm" name="" id="variants">';
            html += '<option value="">Create Combination</option>';
            $.each(variantsWithChild, function(key, val) {
                html += '<option value="' + val.id + '">' + val.bulk_variant_name + '</option>';
            });
            html += '</select>';
            html +=
                '<input type="text" name="variant_combinations[]" id="variant_combination" class="form-control form-control-sm" placeholder="Variant Combination">';
            html += '</td>';
            html +=
                '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control form-control form-control-sm mt-3" placeholder="Variant Code">';
            html += '</td>';
            html += '<td>';
            html +=
                '<input type="number" name="variant_costings[]" class="form-control form-control form-control-sm mt-3" placeholder="Cost" id="variant_costing" value="' +
                parseFloat(product_cost).toFixed(2) + '">';
            html += '</td>';
            html += '<td>';
            html +=
                '<input type="number" name="variant_costings_with_tax[]" class="form-control form-control form-control-sm mt-3" placeholder="Cost inc.tax" id="variant_costing_with_tax" value="' +
                parseFloat(product_cost_with_tax).toFixed(2) + '">';
            html += '</td>';
            html += '<td>';
            html +=
                '<input type="number" name="variant_profits[]" class="form-control form-control form-control-sm mt-3" placeholder="Profit" value="' +
                parseFloat(profit).toFixed(2) + '" id="variant_profit">';
            html += '</td>';
            html += '<td>';
            html +=
                '<input type="text" name="variant_prices_exc_tax[]" class="form-control form-control form-control-sm mt-3" placeholder="Price inc.tax" id="variant_price_exc_tax" value="' +
                parseFloat(product_price).toFixed(2) + '">';
            html += '</td>';
            html += '<td>';
            html +=
                '<input type="file" name="variant_image[]" class="form-control form-control form-control-sm mt-3 " id="variant_image">';
            html += '</td>';
            html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger mt-3">X</a></td>';
            html += '</tr>';
            $('.dynamic_variant_body').prepend(html);
        });
        // Variant all functionality end

        // Get all parent category
        function setParentCategory() {
            $.ajax({
                url: "{{ route('products.add.get.all.form.categories') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(categories) {
                    $('#categories').data('categories', categories);
                    var parentCategories = categories.filter(function(category) {
                        return category.parent_category_id == null;
                    });
                    $('#category_id').append('<option value="">Select Category</option>');
                    $.each(parentCategories, function(key, val) {
                        $('#category_id').append('<option value="' + val.id + '">' + val.name +
                            '</option>');
                    });
                }
            });
        }
        setParentCategory();

        // Set brnads in brnad form field
        function setBrands() {
            $.ajax({
                url: "{{ route('products.add.get.all.form.brand') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(brands) {
                    $('#brand_id').append('<option value="">Select Brand</option>');
                    $.each(brands, function(key, val) {
                        $('#brand_id').append('<option value="' + val.id + '">' + val.name +
                            '</option>');
                    });
                }
            });
        }
        setBrands();

        // Set all units in form units field
        function setAllUnitsInFormUnitsField() {
            $.ajax({
                url: "{{ route('products.add.get.all.form.units') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(units) {
                    var defaultUnit = "{{ json_decode($generalSettings->product, true)['default_unit_id'] }}";
                    $('#unit_id').append('<option value="">Select Unit</option>');
                    $.each(units, function(key, val) {
                        var defaultt = parseInt(defaultUnit) == val.id ? 'SELECTED' : ''
                        $('#unit_id').append('<option '+defaultt+' value="' + val.id + '">' + val.name + ' (' + val
                            .code_name + ')' + '</option>');
                    });
                }
            });
        }
        setAllUnitsInFormUnitsField();

        // Set all vats in form units field
        function setVats() {
            $.ajax({
                url: "{{ route('products.add.get.all.form.taxes') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(taxes) {
                    $('#tax_id').append('<option value="">NoTax</option>');
                    $.each(taxes, function(key, val) {
                        $('#tax_id').append('<option value="' + val.id + '-' + val.tax_percent + '">' +
                            val.tax_name + '</option>');
                    });
                }
            });
        }
        setVats();

        function setWarranties() {
            $.ajax({
                url: "{{ route('products.add.get.all.form.warranties') }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(warranties) {
                    $.each(warranties, function(key, val) {
                        var type = val.type == 1 ? 'Warranty' : 'Guaranty'
                        $('#warranty_id').append('<option value="' + val.id + '">' + val.name + ' (' +
                            type + ')' + '</option>');
                    });
                }
            });
        }
        setWarranties();

        // This functionality of Count prackage product all prices
        function CountTotalComboProductPrice(allQuantities, allUnitPrices) {
            var allUnitPriceContainer = [];
            allUnitPrices.forEach(function(price) {
                allUnitPriceContainer.push(price.value);
            });
            var countedPrice = [];
            var i = 0;
            allQuantities.forEach(function(quantity) {
                countedPrice.push(parseFloat(parseFloat(quantity.value) * parseFloat(allUnitPriceContainer[i])));
                i++;
            });

            var totalPrice = 0;
            countedPrice.forEach(function(price) {
                totalPrice += parseFloat(price);
            });

            return parseFloat(parseFloat(totalPrice));
        }

        function get_form_part(type) {
            $.ajax({
                url: "{{ url('product/get/form/part/') }}"+"/"+type,
                async: true,
                type: 'get',
                success: function(html) {
                   $('.form_part').html(html);
                }
            });
        }

        // call jquery method 
        var action_direction = '';
        $(document).ready(function() {
            $(document).on('click', '.submit_button', function() {
                action_direction = $(this).val();
            });

            // Select product and show spacific product creation fields or area
            $('#type').on('change', function() {
                var value = $(this).val();
                get_form_part(value);
            });

            // Automatic generate product code
            $('.code_generate_btn').on('click', function(e) {
                e.preventDefault();
                var code = '';
                var x = 9; // can be any number
                var rand = Math.floor(Math.random() * x) + 1;
                var range = 8;
                var length = 0;
                while (length < range) {
                    var x = 9; // can be any number
                    var rand = Math.floor(Math.random() * x) + 1;
                    code += rand.toString();
                    length++;
                }
                $('#code').val(
                    "{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}" +
                    code);
            });

            // Select Variant and show variant creation area
            $(document).on('change', '#is_variant', function() {
                var product_code = $('#code').val();
                var product_cost = $('#product_cost').val();
                var product_cost_with_tax = $('#product_cost_with_tax').val();
                var profit = $('#profit').val();
                var product_price = $('#product_price').val();

                if (product_code == '' || product_cost == '' || product_price == '') {
                    alert(
                        'After creating the variant, product code, product cost and product price field must not be empty.'
                        );
                    $(this).prop('checked', false);
                    return;
                }

                $('#variant_costing').val(parseFloat(product_cost).toFixed(2));
                $('#variant_costing_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
                $('#variant_price_exc_tax').val(parseFloat(product_price).toFixed(2));
                $('#variant_profit').val(parseFloat(profit).toFixed(2));
                if ($(this).is(':CHECKED', true)) {
                    $('.dynamic_variant_create_area').show(500);
                    $('#variant_combination').prop('required', true);
                    $('#variant_costing').prop('required', true);
                    $('#variant_costing_with_tax').prop('required', true);
                    $('#variant_profit').prop('required', true);
                    $('#variant_price_exc_tax').prop('required', true);
                } else {
                    $('.dynamic_variant_create_area').hide(500);
                    $('#variant_combination').prop('required', false);
                    $('#variant_costing').prop('required', false);
                    $('#variant_costing_with_tax').prop('required', false);
                    $('#variant_profit').prop('required', false);
                    $('#variant_price_exc_tax').prop('required', false);
                }
            });

            // Search product for creating combo
            $(document).on('input', '#search_product',function(e) {
                $('.variant_list_area').empty();
                $('.select_area').hide();
                var productCode = $(this).val();
                if ((productCode === "")) {
                    $('.variant_list_area').empty();
                    $('.select_area').hide();
                    return;
                }
                $.ajax({
                    url: "{{ url('product/search/product') }}" + "/" + productCode,
                    dataType: 'json',
                    success: function(product) {
                        if (!$.isEmptyObject(product)) {
                            $('#search_product').addClass('is-valid');
                        } 

                        if(!$.isEmptyObject(product.product) || !$.isEmptyObject(product.variant_product)){
                            $('#search_product').addClass('is-valid');
                            if(!$.isEmptyObject(product.product)){
                                var product = product.product;
                                if(product.product_variants.length == 0){
                                    $('.select_area').hide();
                                    $('#search_product').val('');
                                    product_ids = document.querySelectorAll('#product_id');
                                    var sameProduct = 0;
                                    product_ids.forEach(function(input){
                                        if(input.value == product.id){
                                            sameProduct += 1;
                                            var className = input.getAttribute('class');
                                            // get closest table row for increasing qty and re calculate product amount
                                            var closestTr = $('.'+className).closest('tr');
                                            // update same product qty 
                                            var presentQty = closestTr.find('#combo_quantity').val();
                                            var updateQty = parseFloat(presentQty) + 1;
                                            closestTr.find('#combo_quantity').val(updateQty);

                                            // update unit cost with discount
                                            var unitPriceIncTax = closestTr.find('#unit_price_inc_tax').val();
                                            // update subtotal
                                            var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                                            var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                                            calculateTotalAmount();
                                            return;
                                        }
                                    });

                                    if(sameProduct == 0){
                                        var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0;
                                        var tax_amount = parseFloat(product.tax != null ? product.product_price/100 * product.tax.tax_percent : 0);
                                        var tr = '';
                                        tr += '<tr class="text-center">';
                                        tr += '<td>';
                                        tr += '<span class="product_name">'+product.name+'</span><br>';
                                        tr += '<span class="product_code">('+product.product_code+')</span><br>';
                                        tr += '<span class="product_variant"></span>';  
                                        tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                        tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                        tr += '</td>';

                                        tr += '<td>';
                                        tr += '<input value="1" required name="combo_quantities[]" type="number" class="form-control form-control-sm" id="combo_quantity">';
                                        tr += '</td>';

                                        var unitPriceIncTax = product.product_price + tax_amount;
                                        tr += '<td>';
                                        tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="unit_prices_inc_tax[]" type="text" class="form-control form-control-sm" id="unit_price_inc_tax">';
                                        tr += '</td>';

                                        tr += '<td>';
                                        tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="subtotals[]" type="text" class="form-control form-control-sm" id="subtotal">';
                                        tr += '</td>';

                                        tr += '<td class="text-right">';
                                        tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger mt-1">-</a>';
                                        tr += '</td>';

                                        tr += '</tr>';
                                        $('#combo_products').append(tr); 
                                        calculateTotalAmount(); 
                                    }
                                }else{
                                    console.log(product); 
                                    var li = "";
                                    var tax_percent = product.tax_id != null ? product.tax.tax_percent : 0.00;
                                    $.each(product.product_variants, function(key, variant){
                                        var tax_amount = parseFloat(product.tax != null ? variant.variant_price/100 * product.tax.tax_percent : 0.00);
                                        var variantPriceIncTax = variant.variant_price + tax_amount;
                                        li += '<li>';
                                        li += '<a class="select_variant_product" data-p_id="' +
                                        product.id + '" data-v_id="' + variant.id +
                                            '" data-p_name="' + product.name +
                                            '" data-v_code="' + variant.product_code +
                                            '" data-v_price="' + parseFloat(variantPriceIncTax).toFixed(2) +
                                            '" data-v_name="' + variant.variant_name +
                                            '" href="#">' + product.name + ' [' + variant
                                            .variant_name + ']' + '</a>';
                                        li += '</li>';
                                    });
                                    $('.variant_list_area').append(li);
                                    $('.select_area').show();
                                    $('#search_product').val('');
                                }

                            }else if(!$.isEmptyObject(product.variant_product)){
                                $('.select_area').hide();
                                $('#search_product').val('');
                                var variant_product = product.variant_product;
                                console.log(variant_product); 
                                var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.percent : 0;
                                var tax_rate = parseFloat(variant_product.product.tax != null ? variant_product.variant_cost/100 * tax_percent : 0); 
                                var variant_ids = document.querySelectorAll('#variant_id');
                                var sameVariant = 0;
                                variant_ids.forEach(function(input){
                                    console.log(input.value);
                                    if(input.value != 'noid'){
                                        if(input.value == variant_product.id){
                                            sameVariant += 1;
                                            var className = input.getAttribute('class');
                                            // get closest table row for increasing qty and re calculate product amount
                                            var closestTr = $('.'+className).closest('tr');
                                            // update same product qty 
                                            var presentQty = closestTr.find('#combo_quantity').val();
                                            var updateQty = parseFloat(presentQty) + 1;
                                            closestTr.find('#combo_quantity').val(updateQty);

                                            // update unit cost with discount
                                            var unitPriceIncTax = closestTr.find('#unit_price_inc_tax').val();
                                            // update subtotal
                                        var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                                            var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                                            calculateTotalAmount();
                                            return;
                                        }
                                    }    
                                });
                            
                                if(sameVariant == 0){
                                    var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.tax_percent : 0;
                                    var tax_amount = parseFloat(variant_product.product.tax != null ? variant_product.variant_price/100 * variant_product.product.tax.tax_percent : 0);
                                    var tr = '';
                                    tr += '<tr class="text-center">';
                                    tr += '<td>';
                                    tr += '<span class="product_name">'+variant_product.product.name+'</span><br>';
                                    tr += '<span class="product_code">('+variant_product.variant_code+')</span><br>';
                                    tr += '<span class="product_variant">('+variant_product.variant_name+')</span>';  
                                    tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                    tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="1.00" required name="combo_quantities[]" type="text" class="form-control form-control-sm" id="combo_quantity">';
                                    tr += '</td>';

                                    var unitPriceIncTax = variant_product.variant_price + tax_amount;
                                    tr += '<td>';
                                    tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="unit_prices_inc_tax[]" type="text" class="form-control form-control-sm" id="unit_price_inc_tax">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input readonly value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" type="text" name="subtotal[]" id="subtotal" class="form-control form-control-sm">';
                                    tr += '</td>';

                                    tr += '<td class="text-right">';
                                    tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger mt-1">-</a>';
                                    tr += '</td>';

                                    tr += '</tr>';
                                    $('#combo_products').append(tr);
                                    calculateTotalAmount(); 
                                }    
                            }
                        }else{
                            $('#search_product').addClass('is-invalid');
                        }
                    }
                });
            });

            // Select variant product for creating combo
            $(document).on('click', '.select_variant_product', function(e) {
                e.preventDefault();
                $('#selectVairantModal').modal('hide');
                var product_id = $(this).data('p_id');
                var product_name = $(this).data('p_name');
                var variant_id = $(this).data('v_id');
                var variant_name = $(this).data('v_name');
                var variant_code = $(this).data('v_code');
                var variant_price_inc_tax  = $(this).data('v_price'); 
                var variant_ids = document.querySelectorAll('#variant_id');
                var sameVariant = 0;
                variant_ids.forEach(function(input){
                    console.log(input.value);
                    if(input.value != 'noid'){
                        if(input.value == variant_id){
                            sameVariant += 1;
                            var className = input.getAttribute('class');
                            var className = input.getAttribute('class');
                            // get closest table row for increasing qty and re calculate product amount
                            var closestTr = $('.'+className).closest('tr');
                            // update same product qty 
                            var presentQty = closestTr.find('#combo_quantity').val();
                            var updateQty = parseFloat(presentQty) + 1;
                            closestTr.find('#combo_quantity').val(updateQty);

                            // update unit cost with discount
                            var unitPriceIncTax = closestTr.find('#unit_price_inc_tax').val();
                            // update subtotal
                            var calcSubTotal = parseFloat(unitPriceIncTax) * parseFloat(updateQty);
                            var subTatal = closestTr.find('#subtotal').val(parseFloat(calcSubTotal).toFixed(2));
                            calculateTotalAmount();
                            return;
                        }
                    }    
                });

                if(sameVariant == 0){
                    var tr = '';
                    tr += '<tr class="text-center">';
                    tr += '<td>';
                    tr += '<span class="product_name">'+product_name+'</span><br>';
                    tr += '<span class="product_code">('+variant_code+')</span><br>';
                    tr += '<span class="product_variant">('+variant_name+')</span>';  
                    tr += '<input value="'+product_id+'" type="hidden" class="productId-'+product_id+'" id="product_id" name="product_ids[]">';
                    tr += '<input value="'+variant_id+'" type="hidden" class="variantId-'+variant_id+'" id="variant_id" name="variant_ids[]">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input value="1.00" required name="combo_quantities[]" type="number" class="form-control form-control-sm" id="combo_quantity">';
                    tr += '</td>';

                    tr += '<td>';
                    tr += '<input readonly value="'+variant_price_inc_tax+'" required name="unit_prices_inc_tax[]" type="number" class="form-control form-control-sm" id="unit_price_inc_tax">';
                    tr += '</td>';
                  
                    tr += '<td>';
                    tr += '<input readonly value="'+variant_price_inc_tax+'" required name="subtotals[]" type="number" class="form-control form-control-sm" id="subtotal">';
                    tr += '</td>';

                    tr += '<td class="text-right">';
                    tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger mt-1">-</a>';
                    tr += '</td>';

                    tr += '</tr>';
                    $('#combo_products').append(tr); 
                    calculateTotalAmount();
                }
            });

            function calculateTotalAmount() {
                var subtotals = document.querySelectorAll('#subtotal');
                var netTotalAmount = 0;
                subtotals.forEach(function(subtotal){
                    netTotalAmount += parseFloat(subtotal.value);
                });
                $('.span_total_combo_price').html(parseFloat(netTotalAmount).toFixed(2));
                $('#total_combo_price').val(parseFloat(netTotalAmount).toFixed(2));
                var profit = $('#profit').val();
                var combo_price_exc_tax = parseFloat(netTotalAmount) / 100 * parseFloat(profit) + parseFloat(netTotalAmount);
                $('#combo_price').val(parseFloat(combo_price_exc_tax).toFixed(2));
            }

            // Combo product total price increase or dicrease by quantity
            $(document).on('input', '#combo_quantity', function() {
                var qty = $(this).val() ? $(this).val() : 0;
                var tr = $(this).closest('tr');
                //Update subtotal 
                var unitPriceIncTax = $(this).closest('tr').find('#unit_price_inc_tax').val();
                var calcSubtotal = parseFloat(unitPriceIncTax) * parseFloat(qty);
                var subtotal = tr.find('#subtotal').val(parseFloat(calcSubtotal).toFixed(2));
                calculateTotalAmount();
            });

            $(document).on('click', '#remove_combo_product_btn', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                calculateTotalAmount();
            });

            // Dispose Select area 
            $(document).on('click', '.remove_select_area_btn', function(e) {
                e.preventDefault();
                $('.select_area').hide();
            });

            // Romove variant table row
            $(document).on('click', '#variant_remove_btn', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });

            // Setup ajax for csrf token.
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // set child category in category form field
            $('#category_id').on('change', function() {
                var category_id = $(this).val();
                var catesInput = $('#categories').data('categories');
                var childCategories = catesInput.filter(function(category) {
                    return category.parent_category_id == category_id;
                });
                $('#child_category_id').empty();
                $('#child_category_id').append('<option value="">Select child category</option>');
                $.each(childCategories, function(key, val) {
                    $('#child_category_id').append('<option value="' + val.id + '">' + val.name +
                        '</option>');
                });
            })

            // Add product by ajax
            $('#add_product_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').removeClass('d-none');
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $('.loading_button').addClass('d-none');
                        if ($.isEmptyObject(data.errorMsg)) {
                            toastr.success(data);
                            if (action_direction == 'save') {
                                window.location = "{{ route('products.all.product') }}";
                            } else {
                                $('#add_product_form')[0].reset();
                                get_form_part(1);
                                $('#profit').val(parseFloat(defaultProfit).toFixed(2));
                            }
                        } else {
                            toastr.error(data.errorMsg);
                            $('.error').html('');
                            $('.form-control').removeClass('is-invalid');
                        }
                    },
                    error: function(err) {
                        $('.loading_button').addClass('d-none');
                        toastr.error('Please check again all form fields.',
                            'Some thing want wrong.');
                        $('.error').html('');
                        $('.form-control').removeClass('is-invalid');
                        $.each(err.responseJSON.errors, function(key, error) {
                            //console.log(key);
                            $('.error_' + key + '').html(error[0]);
                            $('#' + key).addClass('is-invalid');
                        });
                    }
                });
            });

            // Automatic remove searching product not found signal 
            setInterval(function() {
                $('#search_product').removeClass('is-invalid');
            }, 350);

            // Automatic remove searching product is found signal 
            setInterval(function() {
                $('#search_product').removeClass('is-valid');
            }, 1000);
        });

        // Add category from create product by ajax
        $(document).on('submit', '#add_category_form', function(e) {
            e.preventDefault();
            $('.loading_button').removeClass('d-none');
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_cate_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();
                if (idValue == '') {
                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').addClass('d-none');
                return;
            }
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.loading_button').addClass('d-none');
                    $('#addCategoryModal').modal('hide');
                    $('#add_category_form')[0].reset();
                    
                    $('#category_id').append('<option value="' + data.id + '">' + data.name +
                        '</option>');
                    $('#category_id').val(data.id);
                    toastr.success(data);    
                }
            });
        });

        // Add brand from create product by ajax
        $(document).on('submit', '#add_brand_form', function(e) {
            e.preventDefault();
            $('.loading_button').removeClass('d-none');
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_brand_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();
                if (idValue == '') {
                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.loading_button').addClass('d-none');
                    $('#brand_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                    $('#brand_id').val(data.id);
                    $('#addBrandModal').modal('hide');
                    $('#add_brand_form')[0].reset();
                    toastr.success(data, 'Successfully brand is added.'); 
                }
            });
        });

        // Add category from create product by ajax
        $(document).on('submit', '#add_unit_form', function(e) {
            e.preventDefault();
             $('.loading_button').removeClass('d-none');
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_unit_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();
                if (idValue == '') {
                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                 $('.loading_button').addClass('d-none');
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.loading_button').addClass('d-none');
                    toastr.success('Successfully brand is added.');
                    $('#unit_id').append('<option value="' + data.id + '">' + data.name + ' (' + data
                        .code_name + ')' + '</option>');
                    $('#unit_id').val(data.id);
                    $('#addUnitModal').modal('hide');
                    $('#add_unit_form')[0].reset();
                }
            });
        });

         // Add category from create product by ajax
         $(document).on('submit', '#add_warranty_form', function(e) {
            e.preventDefault();
             $('.loading_button').removeClass('d-none');
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_warranty_input');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();
                if (idValue == '') {
                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                 $('.loading_button').addClass('d-none');
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.loading_button').addClass('d-none');
                    toastr.success('Successfully warranty is added.');
                    $('#warranty_id').append('<option value="' + data.id + '">' + data.name + ' (' + data
                        .type+' '+data.duration_type+ ')' + '</option>');
                    $('#warranty_id').val(data.id);
                    $('#addWarrantyModal').modal('hide');
                    $('#add_warranty_form')[0].reset();
                }
            });
        });
        
        $(document).keypress(".scanable",function(event){
            if (event.which == '10' || event.which == '13') {
                event.preventDefault();
            }
        });
    </script>
@endpush
