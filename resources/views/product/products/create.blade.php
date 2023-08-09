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
    </style>
    <link href="{{ asset('backend/asset/css/jquery.cleditor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/asset/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@php
    $productSerial = new App\Utils\InvoiceVoucherRefIdUtil();
@endphp

@section('title', 'Add Product - ')

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-plus-circle"></span>
                    <h6>@lang('menu.add_product')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <form id="add_product_form" action="{{ route('products.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" id="product_serial" value="{{ str_pad($productSerial->getLastId('products'), 4, '0', STR_PAD_LEFT) }}">
            <input type="hidden" id="code_prefix" value="{{ $generalSettings['product__product_code_prefix'] }}">
            <section class="p-lg-3 p-1">
                <div class="row g-3">
                    <div class="col-lg-8">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-lg-3 mb-1">
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
                                                    <input type="text" name="code" class="form-control" autocomplete="off" id="code" data-next="unit_id" placeholder="Product Code">
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
                                                            <span class="input-group-text add_button" id="addUnitModal"><i class="fas fa-plus-square input_i"></i></span>
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
                                                    <label class="col-4"><b>@lang('menu.category') </b> </label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select class="form-control select2 flex-nowrap" name="category_id" id="category_id" data-next="sub_category_id">
                                                                <option value="">@lang('menu.select_category')</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fas fa-plus-square input_i"></i></span>
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
                                                            <option value="">@lang('menu.select_category_first')</option>
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
                                                    <label class="col-4"><b>{{ __("menu.brand") }}</b></label>
                                                    <div class="col-8">
                                                        <div class="input-group flex-nowrap">
                                                            <select class="form-control select2" name="brand_id" id="brand_id" data-next="alert_quantity">
                                                                <option value="">{{ __("Select Brand") }}</option>
                                                                @foreach ($brands as $brand)
                                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                @endforeach
                                                            </select>

                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text add_button" data-bs-toggle="modal" data-bs-target="#addBrandModal"><i class="fas fa-plus-square input_i"></i></span>
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
                                                                <span class="input-group-text add_button" data-bs-toggle="modal" data-bs-target="#addWarrantyModal"><i class="fas fa-plus-square input_i"></i><span>
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
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name.'/'.$branch->branch_code }}
                                                                    </option>
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
                            <div class="form_element rounded mt-0 mb-lg-3 mb-1">
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
                                                        <label class="col-4"><b> {{ __("Tax") }}</b></label>
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
                                                        <label class="col-4"><b>{{ __("Tax Type") }}</b> </label>
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
                                                            <option value="0">@lang('menu.no')</option>
                                                            <option value="1">@lang('menu.yes')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.thumbnail_photo') </b> </label>
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
                                                            <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-end" href="#">@lang('menu.add_more')</a>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="table-responsive mt-1">
                                                            <table class="table modal-table table-sm">
                                                                <thead>
                                                                    <tr class="text-center bg-primary variant_header">
                                                                        <th class="text-white text-start">{{ __("Select Variant") }}</th>
                                                                        <th class="text-white text-start">{{ __("Variant Code") }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __("Also known as SKU. Variant code(SKU) must be unique.") }}" class="fas fa-info-circle tp"></i></th>
                                                                        <th colspan="2" class="text-white text-start">{{ __("Unit Cost (Exc. Tax)& (Inc. Tax)") }}</th>
                                                                        <th class="text-white text-start">{{ __("Profit(%)") }}</th>
                                                                        <th class="text-white text-start">{{ __("Unit Price(Exc. Tax)") }}</th>
                                                                        <th class="text-white text-start">{{ __('Variant Phone') }}</th>
                                                                        <th><i class="fas fa-trash-alt text-white"></i></th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody class="dynamic_variant_body">
                                                                    <tr>
                                                                        <td class="text-start">
                                                                            <select class="form-control form-control" name="" id="variants"></select>
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
                            <div class="form_element rounded mt-0 mb-lg-3 mb-1">
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
                                                <label class="col-4"><b>{{ __("Enable IMEI or SL No") }}</b></label>
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
                                                <label class="col-4"><b>{{ __("Batch No/Expire Date") }}</b></label>
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
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-2"> <b>@lang('menu.description') </b> </label>
                                                <div class="col-10">
                                                    <textarea name="product_details" class="ckEditor form-control" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;" data-next="save_and_new"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-2"> <b>@lang('menu.photo') <i data-bs-toggle="tooltip" data-bs-placement="top" title="This photo will be shown in e-commerce. You can upload multiple file. Per photo max size 2MB." class="fas fa-info-circle tp"></i> </b> </label>
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

                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button product_loading_btn d-hide"><i class="fas fa-spinner"></i> <span>@lang('menu.loading')</span> </button>
                                <button type="submit" name="action" value="save_and_new" class="btn btn-success product_submit_button btn-sm" id="save_and_new">@lang('menu.save_and_add_another')</button>
                                <button type="submit" name="action" value="save" class="btn btn-success product_submit_button btn-sm" id="save">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>{{ __('Product List') }}</h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="table-responsive" id="data_list">
                                    <table class="display table-hover data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.product')</th>
                                                <th>@lang('menu.unit_cost')</th>
                                                <th>@lang('menu.unit_price')</th>
                                                <th>@lang('menu.action')</th>
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
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script src="{{asset('backend/asset/js/select2.min.js')}}"></script>
<script>

    $('.select2').select2();

    var productListtable = $('.data_tbl').DataTable({
        processing: true,
        serverSide: true,
        searchable: true,
        "lengthMenu" : [25, 100, 500, 1000, 2000],
        ajax: "{{ route('products.create') }}",
        columns: [
            {data: 'name',name: 'products.name'},
            {data: 'product_cost',name: 'products.product_cost', className: 'fw-bold'},
            {data: 'product_price',name: 'products.product_price', className: 'fw-bold'},
            {data: 'action',name: 'action'},
        ],
    });

    // Set parent category in parent category form field
    $('.combo_price').hide();
    $('.combo_pro_table_field').hide();

    function costCalculate() {

        var tax_percent = $('#tax_ac_id').find('option:selected').data('tax_percent');
        var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var tax_type = $('#tax_type').val();
        var calc_product_cost_tax = parseFloat(product_cost) / 100 * parseFloat(tax_percent);

        if (tax_type == 2) {

            var __tax_percent = 100 + parseFloat(tax_percent);
            var calc_tax = parseFloat(product_cost) / parseFloat(__tax_percent) * 100;
            calc_product_cost_tax = parseFloat(product_cost) - parseFloat(calc_tax);
        }

        var product_cost_with_tax = parseFloat(product_cost) + calc_product_cost_tax;
        $('#product_cost_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        var profit = $('#profit').val() ? $('#profit').val() : 0;

        if (parseFloat(profit) > 0) {

            var calculate_profit = parseFloat(product_cost) / 100 * parseFloat(profit);
            var product_price = parseFloat(product_cost) + parseFloat(calculate_profit);
            $('#product_price').val(parseFloat(product_price).toFixed(2));
        }

        // calc package product profit
        var netTotalComboPrice = $('#total_combo_price').val() ? $('#total_combo_price').val() : 0;
        var calcTotalComboPrice = parseFloat(netTotalComboPrice) / 100 * parseFloat(profit) + parseFloat(
            netTotalComboPrice);
        $('#combo_price').val(parseFloat(calcTotalComboPrice).toFixed(2));
    }

    $(document).on('input', '#product_cost',function() {

        costCalculate();
    });

    $(document).on('input', '#product_price', function() {

        var selling_price = $(this).val() ? $(this).val() : 0;
        var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
        var __cost = parseFloat(product_cost) > 0 ? parseFloat(product_cost) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0;
        $('#profit').val(parseFloat(__calcProfit).toFixed(2));
    });

    $(document).on('change', '#tax_ac_id', function() {

        costCalculate();
    });

    $(document).on('change', '#tax_type', function() {

        costCalculate();
    });

    $(document).on('input', '#profit', function() {

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
                    $('#variants').append('<option value="' + val.id + '">' + val.bulk_variant_name + '</option>');
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

        $.each(variant[0].bulk_variant_child, function(key, child) {
            html += '<li class="modal_variant_child_list">';
            html += '<a class="select_variant_child" data-child="' + child.child_name + '" href="#">' + child.child_name + '</a>';
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
        var tax = $('#tax_ac_id').find('option:selected').data('tax_percent');
        var variant_costing = parent_tr.find('#variant_costing');
        var variant_costing_with_tax = parent_tr.find('#variant_costing_with_tax');
        var variant_profit = parent_tr.find('#variant_profit').val() ? parent_tr.find('#variant_profit').val() : 0.00;
        var variant_price_exc_tax = parent_tr.find('#variant_price_exc_tax');

        var tax_rate = parseFloat(variant_costing.val()) / 100 * tax;
        var cost_with_tax = parseFloat(variant_costing.val()) + tax_rate;
        variant_costing_with_tax.val(parseFloat(cost_with_tax).toFixed(2));

        var profit = parseFloat(variant_costing.val()) / 100 * parseFloat(variant_profit) + parseFloat(variant_costing.val());
        variant_price_exc_tax.val(parseFloat(profit).toFixed(2));
    }

    var variant_code_sequel = 0;
    // Select Variant and show variant creation area
    $(document).on('change', '#is_variant', function() {

        var product_cost = $('#product_cost').val();
        var product_cost_with_tax = $('#product_cost_with_tax').val();
        var profit = $('#profit').val();
        var product_price = $('#product_price').val();

        if ($(this).val() == 1 && (product_cost == '' || product_price == '')) {

            $(this).val(0);
            alert('After creating the variant, product cost and product price field must not be empty.');
            return;
        }

        var code = $('#code').val();
        var auto_generated_code = $('#auto_generated_code').val();
        var variant_code = code ? code + '-' + (++variant_code_sequel) : auto_generated_code + '-' + (++variant_code_sequel);

        $('#variant_code').val(variant_code);
        $('#variant_costing').val(parseFloat(product_cost).toFixed(2));
        $('#variant_costing_with_tax').val(parseFloat(product_cost_with_tax).toFixed(2));
        $('#variant_price_exc_tax').val(parseFloat(product_price).toFixed(2));
        $('#variant_profit').val(parseFloat(profit).toFixed(2));

        if ($(this).val() == 1) {

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

    // Get default profit
    var defaultProfit = {{ $generalSettings['business__default_profit'] > 0 ? $generalSettings['business__default_profit'] : 0 }};

    $(document).on('click', '#add_more_variant_btn', function(e) {
        e.preventDefault();

        // var variant_code = code ? code + '-' + (++variant_code_sequel) : auto_generated_code + '-' + (++variant_code_sequel);

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
        html += '<input type="text" name="variant_combinations[]" id="variant_combination" class="form-control fw-bold" placeholder="Variant Combination" required>';
        html += '</td>';
        html += '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control fw-bold" placeholder="Variant Code">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costings[]" class="form-control fw-bold" placeholder="Cost" id="variant_costing" value="' + parseFloat(product_cost).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_costings_with_tax[]" class="form-control fw-bold" placeholder="Cost inc.tax" id="variant_costing_with_tax" value="' + parseFloat(product_cost_with_tax).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_profits[]" class="form-control fw-bold" placeholder="Profit" value="' + parseFloat(profit).toFixed(2) + '" id="variant_profit">';
        html += '</td>';
        html += '<td>';
        html += '<input required type="number" step="any" name="variant_prices_exc_tax[]" class="form-control fw-bold" placeholder="Price inc.tax" id="variant_price_exc_tax" value="' + parseFloat(product_price).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input type="file" name="variant_image[]" class="form-control" id="variant_image">';
        html += '</td>';
        html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a></td>';
        html += '</tr>';
        $('.dynamic_variant_body').prepend(html);

        regenerateVariantCode();
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

        var url = "{{ route('products.form.part', ':type') }}";
        var route = url.replace(':type', type)
        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(html) {

                $('#form_part').html(html);
            }
        });
    }

    function regenerateVariantCode() {

        var code = $('#code').val();
        var auto_generated_code = $('#auto_generated_code').val();

        var variantCodes = document.querySelectorAll('input[name="variant_codes[]"]');
        var variantCodesArray = Array.from(variantCodes);
        var reversed = variantCodesArray.reverse();

        var length = variantCodesArray.length;
        var i = length;
        for (var index = length - 1; index >= 0; index--) {

            var variant_code = code ? code + '-' + (i) : auto_generated_code + '-' + (i);
            reversed[index].value = variant_code;
            i--;
        }
    }

    // Romove variant table row
    $(document).on('click', '#variant_remove_btn', function(e) {

        e.preventDefault();
        $(this).closest('tr').remove();
        regenerateVariantCode();
    });

    // call jquery method
    var action_direction = '';
    $(document).ready(function() {

        $(document).on('click', '.submit_button', function() {

            action_direction = $(this).val();
        });

        // Select product and show specific product creation fields or area
        $('#type').on('change', function() {

            var value = $(this).val();
            if (value == 2) {
                
                toastr.error('Add Combo product feature is temporary disabled. Comming soon.');
                $(this).val(1);
                return;
            }

            get_form_part(value);
        });

        // Dispose Select area
        $(document).on('click', '.remove_select_area_btn', function(e) {

            e.preventDefault();
            $('.select_area').hide();
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        // set sub category in form field
        $('#category_id').on('change', function() {

            var category_id = $(this).val();

            $.get("{{ url('common/ajax/call/category/subcategories/') }}"+"/"+category_id, function(subCategories) {

                $('#sub_category_id').empty();
                $('#sub_category_id').append('<option value="">Select Sub-Category</option>');

                $.each(subCategories, function(key, val) {

                    $('#sub_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            });
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.product_submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.product_submit_button', function() {

            var action_direction = $(this).val();

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        document.onkeyup = function () {
            var e = e || window.event; // for IE to cover IEs window event-object

            if(e.ctrlKey && e.which == 13) {

                $('#save_and_new').click();
                return false;
            }else if (e.shiftKey && e.which == 13) {

                $('#save').click();
                return false;
            }
        }

        // Add product by ajax
        $('#add_product_form').on('submit', function(e) {

            e.preventDefault();
            $('.loading_button').removeClass('d-hide');
            var url = $(this).attr('action');

            isAjaxIn = false;
            isAllowSubmit = false;

            $.ajax({
                beforeSend: function() {
                    isAjaxIn = true;
                },
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    $('.loading_button').addClass('d-hide');
                    $('.error').html('');

                    isAjaxIn = true;
                    isAllowSubmit = true;

                    if ($.isEmptyObject(data.errorMsg)) {

                        toastr.success(data);
                        variant_code_sequel = 0;

                        if (action_direction == 'save') {

                            window.location = "{{ route('products.index') }}";
                        } else {

                            $('#add_product_form')[0].reset();
                            get_form_part(1);
                            $('#profit').val(parseFloat(defaultProfit).toFixed(2));
                            document.getElementById('name').focus();
                            getLastid();
                            generateProductCode();
                            productListtable.ajax.reload();
                        }
                    } else {

                        toastr.error(data.errorMsg);
                    }
                },error: function(err) {

                    $('.loading_button').addClass('d-hide');
                    $('.error').html('');

                    isAjaxIn = true;
                    isAllowSubmit = true;

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error.');
                        return;
                    }else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }

                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });

            if (isAjaxIn == false) {

                isAllowSubmit = true;
            }
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

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

    $('select').on('select2:close', function(e) {

        var nextId = $(this).data('next');

        $('#' + nextId).focus();

        setTimeout(function() {

            $('#' + nextId).focus();
        }, 100);
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'is_variant' && $('#is_variant').val() == 0) {

                $('#type').focus().select();
            }

            $('#' + nextId).focus().select();
        }
    });

    function generateProductCode() {

        var product_serial = $('#product_serial').val();
        var code_prefix = $('#code_prefix').val();
        var productCode = code_prefix + product_serial;
        $('#auto_generated_code').val(productCode);
    }
    generateProductCode();

    function getLastid() {

        $.get("{{ route('common.ajax.call.get.last.id', ['products', 4]) }}", function(productSerial) {

            $('#product_serial').val(productSerial);
        });
    }

    // CkEditor
    window.editors = {};
    document.querySelectorAll('.ckEditor').forEach((node, index) => {
        ClassicEditor
            .create(node, {})
            .then(newEditor => {
                newEditor.editing.view.change(writer => {
                    var height = node.getAttribute('data-height');
                    writer.setStyle('min-height', height + 'px', newEditor.editing.view.document.getRoot());
                });
                window.editors[index] = newEditor
            });
    });
</script>
@endpush
