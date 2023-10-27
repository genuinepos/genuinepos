@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        .dataTables_filter {width: 50%!important;}
        .dataTables_filter input {width: 50%;}
        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }
        .checkbox_input_wrap {
            text-align: right;
        }

        table.table.modal-table.table-sm th {
            font-size: 9px;
        }
    </style>
    <link href="{{ asset('backend/asset/css/jquery.cleditor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/asset/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('title', 'Add Product - ')

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-plus-circle"></span>
                    <h6>{{ __("Add Product") }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>
        <form id="add_product_form" action="{{ route('products.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" id="product_serial" value="{{ $lastProductSerialCode }}">
            <input type="hidden" id="code_prefix" value="{{ $generalSettings['product__product_code_prefix'] }}">
            <section class="p-lg-1 p-1">
                <div class="row g-1">
                    <div class="col-lg-8">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Product Name") }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input required type="text" name="name" class="form-control" id="name" data-next="code" placeholder="{{ __("Product Name") }}" autofocus >
                                                    <span class="error error_name"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Product Code") }}
                                                    <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __("Also known as SKU. Product code(SKU) must be unique. If you leave this field empty, it will be generated automatically.") }}" class="fas fa-info-circle tp"></i> </b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="code" class="form-control" autocomplete="off" id="code" data-next="unit_id" placeholder="{{ __("Product Code") }}">
                                                    <input type="hidden" name="auto_generated_code" id="auto_generated_code">
                                                    <span class="error error_code"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Unit") }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select required class="form-control select2" name="unit_id" id="unit_id" data-next="barcode_type">
                                                            <option value="">{{ __("Select Unit") }}</option>
                                                            @php
                                                                $defaultUnit = $generalSettings['product__default_unit_id'];
                                                            @endphp
                                                            @foreach ($units as $unit)
                                                                <option {{ $defaultUnit ==  $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name.' ('.$unit->code_name.')' }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text {{ !auth()->user()->can('product_unit_add')? 'disabled_element': '' }} add_button" id="{{ auth()->user()->can('product_unit_add')? 'addUnit': '' }}"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                    <span class="error error_unit_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Barcode Type") }}</b></label>
                                                <div class="col-8">
                                                    <select class="form-control" name="barcode_type" id="barcode_type" data-next="category_id">
                                                        <option value="CODE128">{{ __("Code 128 (C128)") }}</option>
                                                        <option value="CODE39">{{ __("Code 39 (C39)") }}</option>
                                                        <option value="EAN13">{{ __("EAN-13") }}</option>
                                                        <option value="UPC">{{ __("UPC") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        @if ($generalSettings['product__is_enable_categories'] == '1')
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Category") }}</b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select class="form-control select2 flex-nowrap" name="category_id" id="category_id" data-next="sub_category_id">
                                                                <option value="">{{ __("Select Category") }}</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text {{ !auth()->user()->can('product_category_add')? 'disabled_element': '' }} add_button" id="{{ auth()->user()->can('product_brand_add')? 'addCategory': '' }}"><i class="fas fa-plus-square input_i"></i></span>
                                                            </div>
                                                        </div>
                                                        <span class="error error_category_id"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($generalSettings['product__is_enable_categories'] == '1' && $generalSettings['product__is_enable_sub_categories'] == '1')
                                            <div class="col-md-6">
                                                <div class="input-group flex-nowrap">
                                                    <label class="col-4"><b>{{ __("Subcategory") }}</b></label>
                                                    <div class="col-8">
                                                        <select class="form-control select2" name="sub_category_id" id="sub_category_id" data-next="brand_id">
                                                            <option value="">{{ __("Select Category First") }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        @if ($generalSettings['product__is_enable_brands'] == '1')
                                            <div class="col-md-6">
                                                <div class="input-group flex-nowrap">
                                                    <label class="col-4"><b>{{ __("Brand.") }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select class="form-control select2" name="brand_id" id="brand_id" data-next="alert_quantity">
                                                                <option value="">{{ __("Select Brand") }}</option>
                                                                @foreach ($brands as $brand)
                                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button {{ !auth()->user()->can('product_brand_add')? 'disabled_element': '' }}" id="{{ auth()->user()->can('product_brand_add')? 'addBrand': '' }}"><i class="fas fa-plus-square input_i"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Alert Quantity") }}</b></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="alert_quantity" class="form-control" id="alert_quantity" value="0" data-next="warranty_id" autocomplete="off">
                                                    <span class="error error_alert_quantity"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        @if ($generalSettings['product__is_enable_warranty'] == '1')
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Warranty") }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select class="form-control select2" name="warranty_id" id="warranty_id" data-next="branch_id">
                                                                <option value="">{{ __("Select Warranty") }}</option>
                                                                @foreach ($warranties as $warranty)
                                                                    <option value="{{ $warranty->id }}">{{ $warranty->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text {{ !auth()->user()->can('product_warranty_add')? 'disabled_element': '' }} add_button" id="{{ auth()->user()->can('product_warranty_add')? 'addWarranty': '' }}"><i class="fas fa-plus-square input_i"></i><span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($generalSettings['addons__branch_limit'] > 1)
                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Access Shop") }}</b> </label>
                                                        <div class="col-8">
                                                            <input type="hidden" name="branch_count" value="branch_count">
                                                            <select class="form-control select2" name="branch_ids[]" id="branch_id" multiple>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_branch_ids"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Condition") }}</b></label>
                                                <div class="col-8">
                                                    <select class="form-control" name="product_condition" id="product_condition" data-next="is_manage_stock">
                                                        <option value="New">{{ __("New") }}</option>
                                                        <option value="Used">{{ __("Used") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Stock Type") }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('menu.stock_type_msg')" class="fas fa-info-circle tp"></i></b> </label>
                                                <div class="col-8">
                                                    <select class="form-control" name="is_manage_stock" id="is_manage_stock" data-next="product_cost">
                                                        <option value="1">{{ __("Manageable Stock") }}</option>
                                                        <option value="0">{{ __("Service/Digital Product") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div id="form_part">
                                        <div class="row gx-2 mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Unit Cost(Exc. Tax)") }}</b></label>
                                                    <div class="col-8">
                                                        <input type="number" step="any" name="product_cost" class="form-control fw-bold" id="product_cost" placeholder="0.00" data-next="tax_ac_id" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Unit Cost(Inc. Tax)") }}</b></label>
                                                    <div class="col-8">
                                                        <input type="number" step="any" readonly name="product_cost_with_tax" class="form-control fw-bold" id="product_cost_with_tax" placeholder="0.00" data-next="tax_ac_id" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            @if ($generalSettings['product__is_enable_price_tax'] == '1')
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b> {{ __("Vat/Tax") }}</b></label>
                                                        <div class="col-8">
                                                            <select class="form-control" name="tax_ac_id" id="tax_ac_id" data-next="tax_type">
                                                                <option data-tax_percent="0" value="">
                                                                    @lang('menu.no_tax')</option>
                                                                @foreach ($taxAccounts as $tax)
                                                                    <option data-tax_percent="{{ $tax->tax_percent }}" value="{{ $tax->id }}">
                                                                        {{ $tax->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Vat/Tax Type") }}</b> </label>
                                                        <div class="col-8">
                                                            <select name="tax_type" class="form-control" id="tax_type" data-next="profit">
                                                                <option value="1">{{ __("Exclusive") }}</option>
                                                                <option value="2">{{ __("Inclusive") }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Profit Margin(%)") }}</b></label>
                                                    <div class="col-8">
                                                        <input type="number" step="any" name="profit" class="form-control fw-bold" id="profit" value="{{ $generalSettings['business__default_profit'] > 0 ? $generalSettings['business__default_profit'] : 0 }}" data-next="product_price" placeholder="0.00" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Unit Price(Exc. Tax)") }}</b></label>
                                                    <div class="col-8">
                                                        <input type="number" step="any" name="product_price" class="form-control fw-bold" id="product_price" data-next="is_variant" placeholder="0.00" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row gx-2 mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Has Variant?") }}</b> </label>
                                                    <div class="col-8">
                                                        <select name="is_variant" class="form-control" id="is_variant" data-next="type">
                                                            <option value="0">{{ __("No") }}</option>
                                                            <option value="1">{{ __("Yes") }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __("Thumbnail Photo") }}</b> </label>
                                                    <div class="col-8">
                                                        <input type="file" name="photo" class="form-control" id="photo">
                                                        <span class="error error_photo"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="dynamic_variant_create_area d-hide">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="add_more_btn">
                                                            <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-end" href="#">{{ __("Add More") }}</a>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="table-responsive mt-1">
                                                            <table class="table modal-table table-sm">
                                                                <thead>
                                                                    <tr class="text-center bg-primary variant_header">
                                                                        <th class="text-white text-start">{{ __("Select Variant") }}</th>
                                                                        <th class="text-white text-start">{{ __("Variant Code") }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __("Also known as SKU. Variant code(SKU) must be unique.") }}" class="fas fa-info-circle tp"></i></th>
                                                                        <th colspan="2" class="text-white text-start">{{ __("Unit Cost (Exc. Tax) & (Inc. Tax)") }}</th>
                                                                        <th class="text-white text-start">{{ __("Profit(%)") }}</th>
                                                                        <th class="text-white text-start">{{ __("Unit Price(Exc. Tax)") }}</th>
                                                                        <th class="text-white text-start">{{ __('Variant Photo') }}</th>
                                                                        <th><i class="fas fa-trash-alt text-white"></i></th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody class="dynamic_variant_body">
                                                                    <tr>
                                                                        <td class="text-start">
                                                                            <select class="form-control form-control" name="" id="variants">
                                                                                <option value="">{{ __("Create Combination") }}</option>
                                                                                @foreach ($bulkVariants as $bulkVariant)
                                                                                    <option value="{{ $bulkVariant->id }}">{{ $bulkVariant->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <input type="text" name="variant_combinations[]" id="variant_combination" class="form-control reqireable fw-bold" placeholder="Variant Combination">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="text" name="variant_codes[]" id="variant_code" class="form-control reqireable fw-bold" placeholder="Variant Code">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="number" name="variant_costings[]" step="any" class="form-control requireable fw-bold" placeholder="Cost" id="variant_costing">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="number" step="any" name="variant_costings_with_tax[]" class="form-control requireable fw-bold" placeholder="Cost inc.tax" id="variant_costing_with_tax">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="number" step="any" name="variant_profits[]" class="form-control requireable fw-bold" placeholder="Profit" value="0.00" id="variant_profit">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="number" step="any" name="variant_prices_exc_tax[]" class="form-control requireable fw-bold" placeholder="@lang('menu.price_include_tax')" id="variant_price_exc_tax">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <input type="file" name="variant_image[]" class="form-control" id="variant_image">
                                                                        </td>

                                                                        <td class="text-start">
                                                                            <a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a>
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

                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                                <div class="element-body">
                                    <div class="row gx-2 gy-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Type") }}</b></label>
                                                <div class="col-8">
                                                    <select name="type" class="form-control" id="type" data-next="weight">
                                                        <option value="1">{{ __("General") }}</option>
                                                        <option value="2">{{ __("Combo") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Weight") }}</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="weight" class="form-control" id="weight" placeholder="{{ __("Weight") }}" data-next="is_show_in_ecom">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Displayed In E-com") }}</b></label>
                                                <div class="col-8">
                                                    <select name="is_show_in_ecom" class="form-control" id="is_show_in_ecom" data-next="is_show_emi_on_pos">
                                                        <option value="0">{{ __("No") }}</option>
                                                        <option value="1">{{ __("Yes") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Enable IMEI/SL No") }}</b></label>
                                                <div class="col-8">
                                                    <select name="is_show_emi_on_pos" class="form-control" id="is_show_emi_on_pos" data-next="is_for_sale">
                                                        <option value="0">{{ __("No") }}</option>
                                                        <option value="1">{{ __("Yes") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("Is For Sale") }}</b></label>
                                                <div class="col-8">
                                                    <select name="is_for_sale" class="form-control" id="is_for_sale" data-next="has_batch_no_expire_date">
                                                        <option value="1">{{ __("Yes") }}</option>
                                                        <option value="0">{{ __("No") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __("BatchNo/Expire Date") }}</b></label>
                                                <div class="col-8">
                                                    <select name="has_batch_no_expire_date" class="form-control" id="has_batch_no_expire_date" data-next="save_and_new">
                                                        <option value="0">{{ __("No") }}</option>
                                                        <option value="1">{{ __("Yes") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-2">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-2"><b>{{ __("Description") }}</b></label>
                                                <div class="col-10">
                                                    <textarea name="product_details" class="ckEditor form-control" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;" data-next="save_and_new"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button product_loading_btn d-hide"><i class="fas fa-spinner"></i> <span>{{ __("Loading") }}...</span> </button>
                                <button type="submit" name="action" value="save_and_new" class="btn btn-success product_submit_button p-1" id="save_and_new">{{ __("Save And Add Another") }}</button>
                                <button type="submit" name="action" value="save" class="btn btn-success product_submit_button p-1" id="save">{{ __("Save") }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>{{ __('List Of Products') }}</h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="table-responsive" id="data_list">
                                    <table class="display table-hover data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __("Name") }}</th>
                                                <th>{{ __("Unit Cost(Inc. Tax)") }}</th>
                                                <th>{{ __("Unit Price") }}</th>
                                                <th>{{ __("Action") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
    @include('product.products.partials.all-modals')
@endsection
@push('scripts')
    @include('product.products.js_partials.add_product_js')
@endpush
