@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link href="{{ asset('public/backend/asset/css/jquery.cleditor.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="add_product_form" action="{{ route('products.add.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form_element m-0 mt-4">
                                    <div class="py-2 px-2 form-header">
                                        <div class="row">
                                            <div class="col-6"><h5>Add Product</h5></div>
    
                                            <div class="col-6">
                                                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Product Name :</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <input type="text" name="name" class="form-control" id="name" placeholder="Product Name" autofocus>
                                                        <span class="error error_name"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Product code 
                                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Product code(SKU) must be unique." class="fas fa-info-circle tp"></i> :</b> <span class="text-danger">*
                                                    </span></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <input type="text" name="code" class="form-control scanable" autocomplete="off" id="code" value="" placeholder="Product Code">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text code_generate_btn input_i"><i class="fas fa-sync-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error error_code"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Unit :</b> <span class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <select class="form-control product_unit" name="unit_id" id="unit_id">
                                                                <option value="">Select Unit</option>
                                                                @php
                                                                    $defaultUnit = json_decode($generalSettings->product, true)['default_unit_id'];
                                                                @endphp
                                                                @foreach ($units as $unit)
                                                                    <option {{ $defaultUnit ==  $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name.' ('.$unit->code_name.')' }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" data-bs-toggle="modal"
                                                                    data-bs-target="#addUnitModal"><i class="fas fa-plus-square input_i"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error error_unit_id"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"> <b>Barcode Type  :</b> </label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="barcode_type" id="barcode_type">
                                                            <option value="CODE128">Code 128 (C128)</option>
                                                            <option value="CODE39">Code 39 (C39)</option>
                                                            <option value="EAN13">EAN-13</option>
                                                            <option value="UPC">UPC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Category :</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select class="form-control category" name="category_id"
                                                                    id="category_id">
                                                                    <option value="">Select Category</option>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-bs-toggle="modal"
                                                                        data-bs-target="#addCategoryModal"><i
                                                                            class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                            <span class="error error_category_id"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1' && json_decode($generalSettings->product, true)['is_enable_sub_categories'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"> <b>Sub-category :</b> </label>
                                                        <div class="col-8">
                                                            <select class="form-control" name="child_category_id"
                                                                id="child_category_id">
                                                                <option value="">Select Category First</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Brand :</b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group">
                                                            <select class="form-control" name="brand_id" id="brand_id">
                                                                <option value="">Select Brand</option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                    @endforeach
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" data-bs-toggle="modal"
                                                                    data-bs-target="#addBrandModal"><i
                                                                        class="fas fa-plus-square input_i"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"> <b>Alert quentity  :</b> </label>
                                                    <div class="col-8">
                                                        <input type="number" step="any" name="alert_quantity" class="form-control " autocomplete="off" id="alert_quantity" value="0">
                                                        <span class="error error_alert_quantity"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Warranty :</b> </label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select class="form-control" name="warranty_id" id="warranty_id">
                                                                    <option value="">Select Warranty</option>
                                                                    @foreach ($warranties as $warranty)
                                                                        <option value="{{ $warranty->id }}">{{ $warranty->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text add_button" data-bs-toggle="modal"
                                                                        data-bs-target="#addWarrantyModal"><i
                                                                            class="fas fa-plus-square input_i"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Expired Date :</b> </label>
                                                    <div class="col-8">
                                                        <input type="date" name="expired_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"> <b>Condition  :</b> </label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="product_condition"
                                                            id="product_condition">
                                                            <option value="New">New</option>
                                                            <option value="Used">Used</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"> <b>Description :</b> </label>
                                                    <div class="col-10">
                                                        <textarea name="product_details" id="myEditor" class="myEditor form-control" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-2"> <b>Photos <i data-bs-toggle="tooltip" data-bs-placement="top" title="This photo will be shown in e-commerce. You can upload multiple file. Per photo max size 2MB." class="fas fa-info-circle tp"></i> :</b> </label>
                                                    <div class="col-10">
                                                        <input type="file" name="image[]" class="form-control" id="image" accept="image" multiple>
                                                        <span class="error error_image"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"> <b>Weight :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="weight" class="form-control" id="weight" placeholder="Weight">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Custom Field1 :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="custom_field_1" class="form-control" placeholder="Custom field1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Custom Field2 :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="custom_field_2" class="form-control" placeholder="Custom field2">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Custom Field3 :</b> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="custom_field_3" class="form-control" placeholder="Custom field3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="is_show_in_ecom"> &nbsp; <b>Product wil be displayed in E-Commerce.</b></p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="is_show_emi_on_pos"> &nbsp; <b>Enable Product IMEI or Serial Number</b> </p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap"> 
                                                            <input type="checkbox" name="is_not_for_sale"> &nbsp; <b>Show Not For Sale</b> </p> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element m-0 mt-2">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Type :</b> </label>
                                                    <div class="col-8">
                                                        <select name="type" class="form-control" id="type">
                                                            <option value="1">General</option>
                                                            <option value="2">Combo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Tax :</b> </label>
                                                        <div class="col-8">
                                                            <select class="form-control" name="tax_id" id="tax_id">
                                                                <option value="">NoTax</option>
                                                                @foreach ($taxes as $tax)
                                                                    <option value="{{ $tax->id.'-'.$tax->tax_percent }}">{{ $tax->tax_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Tax Type :</b> </label>
                                                    <div class="col-8">
                                                        <select name="tax_type" class="form-control" id="tax_type">
                                                            <option value="1">Exclusive</option>
                                                            <option value="2">Inclusive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form_part">
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Unit Cost :</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="product_cost" class="form-control"
                                                            autocomplete="off" id="product_cost" placeholder="Unit cost" value="0.00">
                                                            <span class="error error_product_cost"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-md-6">                
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Unit Cost(Inc.Tax) :</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" readonly name="product_cost_with_tax" class="form-control" autocomplete="off" id="product_cost_with_tax" placeholder="Unit cost Inc.Tax" value="0.00">
                                                            <span class="error error_product_cost_with_tax"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Profit Margin(%) :</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="text" name="profit" class="form-control" autocomplete="off" id="profit" value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                    
                                                <div class="col-md-6">    
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Price Exc.Tax :</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <input type="text" name="product_price" class="form-control" autocomplete="off" id="product_price" placeholder="Selling Price Exc.Tax" value="0.00">
                                                        <span class="error error_product_price"></span>    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">                  
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>Thumbnail Photo :</b> </label>
                                                        <div class="col-8">
                                                            <input type="file" name="photo" class="form-control" id="photo">
                                                            <span class="error error_photo"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="col-12">
                                                            <div class="row">
                                                                <p class="checkbox_input_wrap"> 
                                                                <input type="checkbox" name="is_variant" id="is_variant"> &nbsp; <b>This product has varient.</b> </p> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">                  
                                                <div class="dynamic_variant_create_area d-none">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="add_more_btn">
                                                                <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-end" href="">Add More</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="table-responsive mt-1">
                                                                <table class="table modal-table table-sm">
                                                                    <thead>
                                                                        <tr class="text-center bg-primary variant_header">
                                                                            <th class="text-white text-start">Select Variant</th>
                                                                            <th class="text-white text-start">Varient code <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Variant code(SKU) must be unique." class="fas fa-info-circle tp"></i>
                                                                            </th>
                                                                            <th colspan="2" class="text-white text-start">Default Cost</th>
                                                                            <th class="text-white text-start">Profit(%)</th>
                                                                            <th class="text-white text-start">Default Price (Exc.Tax)</th>
                                                                            <th class="text-white text-start">Variant Image</th>
                                                                            <th><i class="fas fa-trash-alt text-white"></i></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="dynamic_variant_body">
                                                                        <tr>
                                                                            <td class="text-start">
                                                                                <select class="form-control form-control" name=""
                                                                                    id="variants">
                        
                                                                                </select>
                                                                                <input type="text" name="variant_combinations[]"
                                                                                    id="variant_combination" class="form-control"
                                                                                    placeholder="Variant Combination">
                                                                            </td>
            
                                                                            <td class="text-start">
                                                                                <input type="text" name="variant_codes[]" id="variant_code" class="form-control"
                                                                                    placeholder="Variant Code">
                                                                            </td>
            
                                                                            <td class="text-start">
                                                                                <input type="number" name="variant_costings[]"
                                                                                    class="form-control" placeholder="Cost" id="variant_costing">
                                                                            </td>
            
                                                                            <td class="text-start">
                                                                                <input type="number" name="variant_costings_with_tax[]"class="form-control" placeholder="Cost inc.tax" id="variant_costing_with_tax">
                                                                            </td>
            
                                                                            <td class="text-start">
                                                                                <input type="number" name="variant_profits[]" class="form-control" placeholder="Profit" value="0.00" id="variant_profit">
                                                                            </td>
                        
                                                                            <td class="text-start">
                                                                                <input type="text" name="variant_prices_exc_tax[]"
                                                                                    class="form-control" placeholder="Price inc.tax" id="variant_price_exc_tax">
                                                                            </td>
                        
                                                                            <td class="text-start">
                                                                                <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                                                            </td>
            
                                                                            <td class="text-start">
                                                                                <a href="#" id="variant_remove_btn"
                                                                                    class="btn btn-xs btn-sm btn-danger">X</a>
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
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-8 text-end">
                                    <button type="button" class="btn loading_button btn-sm d-none"><i class="fas fa-spinner"></i><strong>Loading</strong> </button>
                                    <button type="submit" name="action" value="save_and_new"
                                        class="btn btn-primary submit_button btn-sm">Save And Add Another</button>
                                    <button type="submit" name="action" value="save"
                                        class="btn btn-primary submit_button btn-sm">Save</button>
                                </div>
                            </div>
                        </div>
                </section>
            </form>
        </div>
    </div>

    <!-- Select modal  -->
    <div class="modal fade" id="VairantChildModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog select_variant_modal_dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Select variant Child</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
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

    <!-- Add Unit Modal -->
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
                            <label><b>Name :</b></label> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_unit_input"
                                data-name="Unit name" id="add_unit_name" placeholder="Unit name" />
                            <span class="error error_add_unit_name"></span>
                        </div>

                        <div class="form-group mt-1">
                           <label><b>Unit Code :</b></label>  <span class="text-danger">*</span>
                            <input type="text" name="code" class="form-control add_unit_input"
                                data-name="Unit code" id="add_unit_code" placeholder="Unit code" />
                            <span class="error error_add_unit_code"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Unit Modal End -->

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
                            <input type="text" name="name" class="form-control add_cate_input"
                                data-name="Category name" id="add_cate_name" placeholder="Category name" />
                            <span class="error error_add_cate_name"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
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
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_brand_form" action="{{ route('products.add.brand') }}">
                        <div class="form-group">
                            <b>Name :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_brand_input"
                                data-name="Brand name" id="add_brand_name" placeholder="Brand name" />
                            <span class="error error_add_brand_name"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Brand Modal End -->

    <!-- Add Warranty Modal -->
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
                            <label><b>Name :</b> </label> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_warranty_input" id="add_warranty_name" data-name="Warranty name" placeholder="Warranty name"/>
                            <span class="error error_add_warranty_name"></span>
                        </div>

                        <div class="row mt-1">
                            <div class="col-lg-4">
                                <label><b>Type : </b> </label> <span class="text-danger">*</span>
                                <select name="type" class="form-control" id="type">
                                    <option value="1">Warranty</option>
                                    <option value="2">Guaranty</option>
                                </select>
                            </div>
                            
                            <div class="col-lg-8">
                                <label><b>Duration :</b> </label> <span class="text-danger">*</span>
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

                        <div class="form-group mt-1">
                            <label><b>Description :</b></label> 
                            <textarea name="description" id="description" class="form-control" cols="10" rows="3" placeholder="Warranty description"></textarea>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Add Warranty Modal End -->
@endsection
@push('scripts')
<script src="{{asset('public/backend/asset/js/jquery.cleditor.js')}}"></script>
<script>
    var myEditorObj = $('#myEditor').cleditor();
    function clearEditor() {
        $("#myEditor").cleditor({width:800, height:300, updateTextArea:function (){}})[0].clear();
    }

    // Set parent category in parent category form field
    $('.combo_price').hide();
    $('.combo_pro_table_field').hide();

    var tax_percent = 0;
    $('#tax_id').on('change', function() {
        var tax = $(this).val();
        if (tax) {
            var split = tax.split('-');
            tax_percent = split[1];
        }else{
            tax_percent = 0;
        }
    });

    function costCalculate() {
        var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var tax_type = $('#tax_type').val();
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent);
        if (tax_type == 2) {
            var __tax_percent = 100 + parseFloat(tax_percent);
            var calc_tax = parseFloat(product_cost) / parseFloat(__tax_percent) * 100;
            var calc_product_cost_tax = parseFloat(product_cost) - parseFloat(calc_tax);
        }
        
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

    $('#tax_type').on('change', function() {
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
        var parentTableRow = $(this).closest('tr');
        variant_row_index = parentTableRow.index();
        $('.modal_variant_child').empty();
        var html = '';
        var variant = variantsWithChild.filter(function(variant) {
            return variant.id == id;
        });

        $.each(variant[0].bulk_variant_childs, function(key, child) {
            html += '<li class="modal_variant_child_list">';
            html += '<a class="select_variant_child" data-child="' + child.child_name + '" href="#">' +
                child.child_name + '</a>';
            html += '</li>';
        });
        $('.modal_variant_child').html(html);
        $('#VairantChildModal').modal('show');
        $(this).val('');
    });

    $(document).on('click', '.select_variant_child', function(e) {
        e.preventDefault();
        var child = $(this).data('child');
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
        html += '<select class="form-control" name="" id="variants">';
        html += '<option value="">Create Combination</option>';
        $.each(variantsWithChild, function(key, val) {
            html += '<option value="' + val.id + '">' + val.bulk_variant_name + '</option>';
        });
        html += '</select>';
        html +=
            '<input type="text" name="variant_combinations[]" id="variant_combination" class="form-control" placeholder="Variant Combination">';
        html += '</td>';
        html +=
            '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control" placeholder="Variant Code">';
        html += '</td>';
        html += '<td>';
        html +=
            '<input type="number" name="variant_costings[]" class="form-control" placeholder="Cost" id="variant_costing" value="' +
            parseFloat(product_cost).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html +=
            '<input type="number" name="variant_costings_with_tax[]" class="form-control" placeholder="Cost inc.tax" id="variant_costing_with_tax" value="' +
            parseFloat(product_cost_with_tax).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html +=
            '<input type="number" name="variant_profits[]" class="form-control" placeholder="Profit" value="' +
            parseFloat(profit).toFixed(2) + '" id="variant_profit">';
        html += '</td>';
        html += '<td>';
        html +=
            '<input type="text" name="variant_prices_exc_tax[]" class="form-control" placeholder="Price inc.tax" id="variant_price_exc_tax" value="' +
            parseFloat(product_price).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html +=
            '<input type="file" name="variant_image[]" class="form-control" id="variant_image">';
        html += '</td>';
        html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a></td>';
        html += '</tr>';
        $('.dynamic_variant_body').prepend(html);
    });
    // Variant all functionality end

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
            $('#code').val("{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}" + code);
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
                                    tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger">-</a>';
                                    tr += '</td>';

                                    tr += '</tr>';
                                    $('#combo_products').append(tr); 
                                    calculateTotalAmount(); 
                                }
                            }else{
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
                            var tax_percent = variant_product.product.tax_id != null ? variant_product.product.tax.percent : 0;
                            var tax_rate = parseFloat(variant_product.product.tax != null ? variant_product.variant_cost/100 * tax_percent : 0); 
                            var variant_ids = document.querySelectorAll('#variant_id');
                            var sameVariant = 0;
                            variant_ids.forEach(function(input){
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
                                tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger">-</a>';
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
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        // set sub category in form field
        $('#category_id').on('change', function() {
            var category_id = $(this).val();
            $.get("{{ url('product/all/sub/category/') }}"+"/"+category_id, function(subCategories) {
                $('#child_category_id').empty();
                $('#child_category_id').append('<option value="">Select Sub-Category</option>');
                $.each(subCategories, function(key, val) {
                    $('#child_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            });
        });

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
                            clearEditor();
                            $('#add_product_form')[0].reset();
                            get_form_part(1);
                            $('#profit').val(parseFloat(defaultProfit).toFixed(2));
                        }
                    } else {
                        toastr.error(data.errorMsg);
                        $('.error').html('');
                    }
                },
                error: function(err) {
                    $('.loading_button').addClass('d-none');
                    toastr.error('Please check again all form fields.',
                        'Some thing want wrong.');
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
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
