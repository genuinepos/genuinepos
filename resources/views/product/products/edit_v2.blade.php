@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link href="{{ asset('backend/asset/css/jquery.cleditor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('backend/asset/css/select2.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-edit"></span>
                    <h6>{{ __('Edit Product') }}</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-lg-3 p-1">
            <form id="edit_product_form" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data"
                method="POST">
                @csrf

                <div class="form_element rounded mt-0 mb-lg-3 mb-1">

                    <div class="element-body">
                        <div class="row gx-2 gy-1">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"><b>@lang('menu.product_name') :</b> <span class="text-danger">*</span></label>
                                    <div class="col-7">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="@lang('menu.product_name')" autofocus value="{{ $product->name }}">
                                        <span class="error error_name"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"><b>@lang('menu.product_code')
                                        <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Product code(SKU) must be unique. If you leave this field empty, it will be generated automatically." class="fas fa-info-circle tp"></i> :</b></label>
                                    <div class="col-7">
                                        <input type="text" name="code" class="form-control scanable" autocomplete="off" id="code" placeholder="@lang('menu.product_code')" value="{{ $product->product_code }}">
                                        <input type="hidden" name="auto_generated_code" id="auto_generated_code">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"><b>@lang('menu.unit') :</b> <span class="text-danger">*</span></label>
                                    <div class="col-7">
                                        <div class="input-group">
                                            <select class="form-control product_unit" name="unit_id" id="unit_id">
                                                <option value="">@lang('menu.select_unit')</option>
                                                @foreach ($units as $unit)
                                                    <option {{ $product->unit_id == $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name.' ('.$unit->code_name.')' }}</option>
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
                                    <label for="inputEmail3" class="col-5"> <b>@lang('menu.barcode_type')  :</b> </label>
                                    <div class="col-7">
                                        <select class="form-control" name="barcode_type" id="barcode_type">
                                            <option {{ $product->barcode_type == 'CODE128' ? 'SELECTED' : '' }} value="CODE128">Code 128 (C128)</option>
                                            <option {{ $product->barcode_type == 'CODE39' ? 'SELECTED' : '' }} value="CODE39">Code 39 (C39)</option>
                                            <option {{ $product->barcode_type == 'EAN13' ? 'SELECTED' : '' }} value="EAN13">EAN-13</option>
                                            <option {{ $product->barcode_type == 'UPC' ? 'SELECTED' : '' }} value="UPC">UPC</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if (json_decode($generalSettings->product, true)['is_enable_categories'] == '1')
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-5"><b>@lang('menu.category') :</b> <span class="text-danger">*</span></label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <select class="form-control category" name="category_id"
                                                    id="category_id">
                                                    <option value="">@lang('menu.select_category')</option>
                                                    @foreach ($categories as $category)
                                                        <option {{ $product->category_id == $category->id ? 'SELECTED' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
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
                                        <label for="inputEmail3" class="col-5"> <b>@lang('menu.sub_category') :</b> </label>
                                        <div class="col-7">
                                            <select class="form-control" name="child_category_id"
                                                id="child_category_id">
                                                @php
                                                    $subCategories = DB::table('categories')
                                                    ->where('parent_category_id', $product->category_id)->get();
                                                @endphp
                                                <option value="">@lang('menu.select_child_category')</option>
                                                @foreach ($subCategories as $subCategory)
                                                    <option {{ $product->parent_category_id == $subCategory->id ? 'SELECTED' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"><b>@lang('menu.brand'):</b> </label>
                                    <div class="col-7">
                                        <div class="input-group">
                                            <select class="form-control" name="brand_id" id="brand_id">
                                                <option value="">@lang('menu.select_brand')</option>
                                                @foreach ($brands as $brand)
                                                    <option {{ $product->brand_id == $brand->id ? 'SELECTED' : '' }} value="{{$brand->id}}">{{$brand->name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text add_button" data-bs-toggle="modal"
                                                    data-bs-target="#addBrandModal"><i class="fas fa-plus-square input_i"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"> <b>@lang('menu.alert_quantity')  :</b> </label>
                                    <div class="col-7">
                                        <input type="number" step="any" name="alert_quantity" class="form-control " autocomplete="off" id="alert_quantity" value="{{ $product->alert_quantity }}">
                                        <span class="error error_alert_quantity"></span>
                                    </div>
                                </div>
                            </div>

                            @if (json_decode($generalSettings->product, true)['is_enable_warranty'] == '1')
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="inputEmail3" class="col-5"><b>@lang('menu.warranty') :</b> </label>
                                        <div class="col-7">
                                            <div class="input-group">
                                                <select class="form-control" name="warranty_id" id="warranty_id">
                                                    <option value="">@lang('menu.select_warranty')</option>
                                                    @foreach ($warranties as $warranty)
                                                        @php
                                                            $type = $warranty->type == 1 ? 'Warranty' : 'Guaranty';
                                                        @endphp
                                                        <option {{$product->warranty_id == $warranty->id ? 'SELECTED' : '' }} value="{{ $warranty->id }}">
                                                            {{ $warranty->name.' ('.$type.')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text add_button" data-bs-toggle="modal" data-bs-target="#addWarrantyModal"><i class="fas fa-plus-square input_i"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($addons->branches == 1)
                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-5"><b>@lang('menu.business_location') :</b> </label>
                                            <div class="col-7">
                                                <input type="hidden" name="branch_count" value="branch_count">
                                                <select class="form-control select2" name="branch_ids[]" id="branch_ids" multiple>
                                                    <option
                                                        @foreach ($productBranches as $productBranch)
                                                            {{ $productBranch->branch_id == NULL ? 'SELECTED' : '' }}
                                                        @endforeach
                                                    value="">
                                                        {{ json_decode($generalSettings->business, true)['shop_name'] . '(HO)' }}
                                                    </option>
                                                    @foreach ($branches as $branch)
                                                        <option
                                                            @foreach ($productBranches as $productBranch)
                                                                {{ $productBranch->branch_id == $branch->id ? 'SELECTED' : '' }}
                                                            @endforeach
                                                        value="{{ $branch->id }}">
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

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"> <b>@lang('menu.condition')  :</b> </label>
                                    <div class="col-7">
                                        <select class="form-control" name="product_condition"
                                            id="product_condition">
                                            <option {{ $product->product_condition == 'New' ? 'SELECTED' : '' }} value="New">@lang('menu.new')</option>
                                            <option {{ $product->product_condition == 'Used' ? 'SELECTED' : '' }} value="Used">@lang('menu.used')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mt-1">
                                            <div class="col-12">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input {{ $product->is_manage_stock == 1 ? 'CHECKED' : '' }} type="checkbox" name="is_manage_stock" id="is_manage_stock"> &nbsp; <b>@lang('menu.manage_stock')</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Stock Management should be disable mostly for services/Digital Products. Example: Hair-Cutting, Repairing, PDF Books etc." class="fas fa-info-circle tp"></i></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group mt-1">
                                            <div class="col-12">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                    <input {{ $product->is_manage_stock == 0 ? 'CHECKED' : '' }} type="checkbox" name="digital_product" id="digital_product"> &nbsp; <b> @lang('menu.service')/ @lang('menu.product')</b> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form_element rounded mt-0 mb-lg-3 mb-1">
                    <div class="element-body">
                        <div class="form_part">
                            @if ($product->type == 1)
                                <div class="row gx-2 gy-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-5"><b>@lang('menu.unit_cost') :</b> <span class="text-danger">*</span></label>
                                            <div class="col-7">
                                                <input readonly type="number" step="any" name="product_cost" class="form-control"
                                                autocomplete="off" id="product_cost" placeholder="Unit cost" value="{{ $product->product_cost }}">
                                                <span class="error error_product_cost"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-5"><b>@lang('menu.price_exc_tax') :</b> <span class="text-danger">*</span></label>
                                            <div class="col-7">
                                                <input type="number" step="any" name="product_price" class="form-control" autocomplete="off" id="product_price" placeholder="@lang('menu.selling_price_exc_tax')" value="{{ $product->product_price }}">
                                            <span class="error error_product_price"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-5"><b>@lang('menu.unit_cost')(Inc.Tax) :</b> <span class="text-danger">*</span></label>
                                            <div class="col-7">
                                                <input type="number" step="any" readonly name="product_cost_with_tax" class="form-control" autocomplete="off" id="product_cost_with_tax" placeholder="@lang('menu.unit_cost_inc_tax')" value="{{ $product->product_cost_with_tax }}">
                                                <span class="error error_product_cost_with_tax"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-5"><b>@lang('menu.profit_margin')(%) :</b> <span class="text-danger">*</span></label>
                                            <div class="col-7">
                                                <input type="number" step="any" name="profit" class="form-control" autocomplete="off" id="profit" value="{{ $product->profit }}">
                                                <span class="error error_profit"></span>
                                            </div>
                                        </div>
                                    </div>

                                    @if (json_decode($generalSettings->product, true)['is_enable_price_tax'] == '1')
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-5"><b>@lang('menu.tax') :</b> </label>
                                                <div class="col-7">
                                                    <select class="form-control" name="tax_id" id="tax_id">
                                                        <option value="">@lang('menu.no_tax')</option>
                                                        @foreach ($taxes as $tax)
                                                        <option {{ $product->tax_id == $tax->id ? 'SELECTED' : '' }} value="{{ $tax->id . '-' . $tax->tax_percent }}">
                                                                {{ $tax->tax_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-5"><b>@lang('menu.tax_type') :</b> </label>
                                            <div class="col-7">
                                                <select name="tax_type" class="form-control" id="tax_type">
                                                    <option {{ $product->tax_type == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.exclusive')</option>
                                                    <option {{ $product->tax_type == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.exclusive')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-5"><b>@lang('menu.thumbnail_photo') <i data-bs-toggle="tooltip" data-bs-placement="top" title="Previous thumbnail photo (if exists) will be replaced." class="fas fa-info-circle tp"></i> :</b> </label>
                                            <div class="col-7">
                                                <input type="file" name="photo" class="form-control" id="photo">
                                                <span class="error error_photo"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($product->is_variant == 1)
                                    <div class="row mt-1">
                                        <div class="dynamic_variant_create_area">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="col-6">
                                                        <div class="form-group row">
                                                            <p class="checkbox_input_wrap"> <input type="checkbox" name="is_variant" CHECKED id="is_variant"> &nbsp; {{ __('This product has varient') }}. </p>
                                                        </div>
                                                    </div>

                                                    <div class="add_more_btn">
                                                        <a id="add_more_variant_btn" class="btn btn-sm btn-primary float-end" href="">Add More</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="table-responsive mt-1">
                                                        <table class="table modal-table table-sm">
                                                            <thead>
                                                                <tr class="text-center bg-primary variant_header">
                                                                    <th class="text-white text-start">@lang('menu.select_variant')</th>
                                                                    <th class="text-white text-start">Varient code <i data-bs-toggle="tooltip" data-bs-placement="top" title="Also known as SKU. Variant code(SKU) must be unique." class="fas fa-info-circle tp"></i>
                                                                    </th>
                                                                    <th colspan="2" class="text-white text-start">@lang('menu.default_cost')</th>
                                                                    <th class="text-white text-start">@lang('menu.profit')(%)</th>
                                                                    <th class="text-white text-start">@lang('menu.default_price') (Exc.Tax)</th>
                                                                    <th class="text-white text-start">@lang('menu.variant_image')</th>
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
                                                                            placeholder="@lang('menu.variant_code')">
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
                                @endif
                            @else
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-8 offset-2">
                                                <div class="add_combo_product_input">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control form-control-sm"
                                                            autocomplete="off" id="search_product"
                                                            placeholder="Product search/scan by product code">
                                                    </div>

                                                    <div class="select_area">
                                                        <ul class="variant_list_area">

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-10 offset-1 mt-1">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form_table_heading">
                                                            <p class="m-0 pb-1"><strong>@lang('menu.create_combo_product')</strong></p>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table modal-table table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th>@lang('menu.product')</th>
                                                                        <th>@lang('menu.quantity')</th>
                                                                        <th>@lang('menu.unit_price')</th>
                                                                        <th>@lang('menu.sub_total')</th>
                                                                        <th><i class="fas fa-trash-alt"></i></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="combo_products">

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="3" class="text-center">@lang('menu.net_total_amount') :</th>
                                                                        <th>
                                                                            {{ json_decode($generalSettings->business, true)['currency']}} <span class="span_total_combo_price">0.00</span>

                                                                            <input type="hidden" name="total_combo_price"
                                                                                id="total_combo_price"/>
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 offset-3">
                                        <label><b>@lang('menu.x_margin') :</b></label>
                                        <input type="text" name="profit" class="form-control form-control-sm" id="profit"
                                            value="{{ json_decode($generalSettings->business, true)['default_profit'] > 0 ? json_decode($generalSettings->business, true)['default_profit'] : 0 }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label><b>@lang('menu.default_price') Exc.Tax :</b></label>
                                        <input type="text" name="combo_price" class="form-control form-control-sm" id="combo_price">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form_element rounded mt-0 mb-lg-3 mb-1">
                    <div class="element-body">
                        <div class="row gx-2 g-1">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"><b>@lang('menu.type') :</b> </label>
                                    <div class="col-7">
                                        <input type="text" readonly class="form-control" value="{{$product->type == 1 ?'General'  : 'Combo'}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"> <b>Weight :</b> </label>
                                    <div class="col-7">
                                        <input type="text" name="weight" class="form-control" id="weight" placeholder="Weight" value="{{ $product->weight }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"><b>Custom Field1 :</b> </label>
                                    <div class="col-7">
                                        <input type="text" name="custom_field_1" class="form-control" placeholder="Custom field1" value="{{ $product->custom_field_1 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"><b>Custom Field2 :</b> </label>
                                    <div class="col-7">
                                        <input type="text" name="custom_field_2" class="form-control" placeholder="Custom field2" value="{{ $product->custom_field_2 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-5"><b>Custom Field3 :</b> </label>
                                    <div class="col-7">
                                        <input type="text" name="custom_field_3" class="form-control" placeholder="Custom field3" value="{{ $product->custom_field_3 }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="col-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input {{ $product->is_show_in_ecom == 1 ? 'CHECKED' : '' }} type="checkbox" name="is_show_in_ecom"> &nbsp; <b>{{ __('Product wil be displayed in E-Commerce') }}.</b></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="col-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input {{ $product->is_show_emi_on_pos == 1 ? 'CHECKED' : '' }} type="checkbox" name="is_show_emi_on_pos"> &nbsp; <b>{{ __('Enable Product IMEI or Serial Number') }}</b> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="col-12">
                                        <div class="row">
                                            <p class="checkbox_input_wrap">
                                            <input {{ $product->is_for_sale == 0 ? 'CHECKED' : '' }} type="checkbox" name="is_not_for_sale"> &nbsp; <b>@lang('menu.show_not_for_sale')</b> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form_element rounded mt-0 mb-3">
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-2"> <b>@lang('menu.description') :</b> </label>
                                    <div class="col-10">
                                        <textarea name="product_details" id="myEditor" class="myEditor form-control" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;">{{ $product->product_details }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label for="inputEmail3" class="col-2"> <b>@lang('menu.photo') <i data-bs-toggle="tooltip" data-bs-placement="top" title="This photo will be shown in e-commerce. You can upload multiple file. Per photo max size 2MB." class="fas fa-info-circle tp"></i> :</b> </label>
                                    <div class="col-10">
                                        <input type="file" name="image[]" class="form-control" id="image" accept="image" multiple>
                                        <span class="error error_image"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button btn-sm d-hide"><i class="fas fa-spinner text-primary"></i> <strong>@lang('menu.loading')</strong> </button>
                        <button type="submit" class="btn btn-success submit_button btn-sm">@lang('menu.save_changes')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Select modal  -->
    <div class="modal fade" id="VairantChildModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog select_variant_modal_dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.select_variant_child')</h6>
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_unit')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_unit_form" action="{{ route('products.add.unit') }}">
                        <div class="form-group">
                            <label><b>@lang('menu.name') :</b></label> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_unit_input"
                                data-name="Unit name" id="add_unit_name" placeholder="@lang('menu.unit_name')" />
                            <span class="error error_add_unit_name"></span>
                        </div>

                        <div class="form-group mt-1">
                           <label><b>{{ __('Unit Code') }} :</b></label>  <span class="text-danger">*</span>
                            <input type="text" name="code" class="form-control add_unit_input"
                                data-name="Unit code" id="add_unit_code" placeholder="{{ __('Unit Code') }}" />
                            <span class="error error_add_unit_code"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                </div>
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_category')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_category_form" action="{{ route('products.add.category') }}">
                        <div class="form-group">
                            <b>@lang('menu.name') :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_cate_input"
                                data-name="Category name" id="add_cate_name" placeholder="Category name" />
                            <span class="error error_add_cate_name"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" id="save" class="btn btn-sm btn-success submit_button">@lang('menu.save_changes')</button>
                                </div>
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_brand')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_brand_form" action="{{ route('products.add.brand') }}">
                        <div class="form-group">
                            <b>@lang('menu.name') :</b> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_brand_input"
                                data-name="Brand name" id="add_brand_name" placeholder="Brand name" />
                            <span class="error error_add_brand_name"></span>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                </div>
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
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_warranty')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>

                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_warranty_form" action="{{ route('products.add.warranty') }}">
                        <div class="form-group">
                            <label><b>@lang('menu.name') :</b> </label> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control add_warranty_input" id="add_warranty_name" data-name="Warranty name" placeholder="Warranty name"/>
                            <span class="error error_add_warranty_name"></span>
                        </div>

                        <div class="row mt-1">
                            <div class="col-lg-4">
                                <label><b>@lang('menu.type') : </b> </label> <span class="text-danger">*</span>
                                <select name="type" class="form-control" id="type">
                                    <option value="1">@lang('menu.warranty')</option>
                                    <option value="2">@lang('menu.guaranty')</option>
                                </select>
                            </div>

                            <div class="col-lg-8">
                                <label><b>@lang('menu.duration') :</b> </label> <span class="text-danger">*</span>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row g-0">
                                            <input type="number" name="duration" class="form-control w-50 add_warranty_input" data-name="Warranty duration" id="add_warranty_duration" placeholder="Warranty duration">
                                            <select name="duration_type" class="form-control w-50" id="duration_type">
                                                <option value="Months">@lang('menu.months')</option>
                                                <option value="Days">@lang('menu.days')</option>
                                                <option value="Year">@lang('menu.years')</option>
                                            </select>
                                        </div>
                                        <span class="error error_add_warranty_duration"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('menu.description') :</b></label>
                            <textarea name="description" id="description" class="form-control" cols="10" rows="3" placeholder="Warranty description"></textarea>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
                                </div>
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
<script src="{{asset('backend/asset/js/jquery.cleditor.js')}}"></script>
<script src="{{asset('backend/asset/js/select2.min.js')}}"></script>
<script>

    $('.select2').select2();

    // Set parent category in parent category form field
    $('.combo_price').hide();
    $('.combo_pro_table_field').hide();

    var tax_percent = "{{ $product->tax_percent ? $product->tax_percent : 0 }}";
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
        if (tax_type == 2){

            var __tax_percent = 100 + parseFloat(tax_percent);
            var calc_tax = parseFloat(product_cost) / parseFloat(__tax_percent) * 100;
            var calc_product_cost_tax = parseFloat(product_cost) - parseFloat(calc_tax);
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
        var calcTotalComboPrice = parseFloat(netTotalComboPrice) / 100 * parseFloat(profit) + parseFloat(netTotalComboPrice);
        $('#combo_price').val(parseFloat(calcTotalComboPrice).toFixed(2));
    }

    $(document).on('input', '#product_cost',function() {
        costCalculate();
    });

    $(document).on('input', '#product_price',function() {

        var selling_price = $(this).val() ? $(this).val() : 0;
        var product_cost = $('#product_cost').val() ? $('#product_cost').val() : 0;
        var profitAmount = parseFloat(selling_price) - parseFloat(product_cost);
        var __cost = parseFloat(product_cost) > 0 ? parseFloat(product_cost) : parseFloat(profitAmount);
        var calcProfit = parseFloat(profitAmount) / parseFloat(__cost) * 100;
        var __calcProfit = calcProfit ? calcProfit : 0
        $('#profit').val(parseFloat(__calcProfit).toFixed(2));
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
            async: false,
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
        var filter = child_value == '' ? '' : '-';
        var variant_combination = parent_tr.find('#variant_combination').val(child_value + filter + child);
        var product_code = $('#code').val() ? $('#code').val() : $('#auto_generated_code').val();
        parent_tr.find('#variant_code').val(parent_tr.find('#variant_combination').val().toLowerCase() + '-' +
            product_code);
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
        html += '<input type="hidden" name="variant_ids[]" id="variant_id" value="noid">';
        html += '<select class="form-control" name="" id="variants">';
        html += '<option value="">Create Combination</option>';
        $.each(variantsWithChild, function(key, val) {
            html += '<option value="' + val.id + '">' + val.bulk_variant_name + '</option>';
        });
        html += '</select>';
        html += '<input type="text" name="variant_combinations[]" id="variant_combination" class="form-control" placeholder="Variant Combination">';
        html += '</td>';
        html += '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control" placeholder="Variant Code">';
        html += '</td>';
        html += '<td>';
        html += '<input type="number" step="any" name="variant_costings[]" class="form-control" placeholder="Cost" id="variant_costing" value="' +
            parseFloat(product_cost).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input type="number" step="any" name="variant_costings_with_tax[]" class="form-control" placeholder="Cost inc.tax" id="variant_costing_with_tax" value="' +
            parseFloat(product_cost_with_tax).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input type="number" step="any" name="variant_profits[]" class="form-control" placeholder="Profit" value="' +
            parseFloat(profit).toFixed(2) + '" id="variant_profit">';
        html += '</td>';
        html += '<td>';
        html += '<input type="text" step="any" name="variant_prices_exc_tax[]" class="form-control" placeholder="Price inc.tax" id="variant_price_exc_tax" value="' +
            parseFloat(product_price).toFixed(2) + '">';
        html += '</td>';
        html += '<td>';
        html += '<input type="file" name="variant_image[]" class="form-control form-control" id="variant_image">';
        html += '</td>';
        html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a></td>';
        html += '</tr>';
        $('.dynamic_variant_body').prepend(html);
    });
    // Variant all functionality end

    // call jquery method
    $(document).ready(function() {
        // Automatic generate product code
        function autoGeneratedCode() {
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
            $('#auto_generated_code').val("{{ json_decode($generalSettings->product, true)['product_code_prefix'] }}" +code);
        };
        autoGeneratedCode();

        // Select Variant and show variant creation area
        $(document).on('change', '#is_variant', function() {
            $(this).prop('checked', true);
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
                                    tr += '<input type="hidden" value="noid" id="combo_id" name="combo_ids[]">';
                                    tr += '<span class="product_name">'+product.name+'</span><br>';
                                    tr += '<span class="product_code">('+product.product_code+')</span><br>';
                                    tr += '<span class="product_variant"></span>';
                                    tr += '<input value="'+product.id+'" type="hidden" class="productId-'+product.id+'" id="product_id" name="product_ids[]">';
                                    tr += '<input value="noid" type="hidden" id="variant_id" name="variant_ids[]">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="1" required name="combo_quantities[]" type="number" class="form-control" id="combo_quantity">';
                                    tr += '</td>';

                                    var unitPriceIncTax = product.product_price + tax_amount;
                                    tr += '<td>';
                                    tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="unit_prices_inc_tax[]" type="text" class="form-control" id="unit_price_inc_tax">';
                                    tr += '</td>';

                                    tr += '<td>';
                                    tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="subtotals[]" type="text" class="form-control" id="subtotal">';
                                    tr += '</td>';

                                    tr += '<td class="text-right">';
                                    tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger mt-1">-</a>';
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
                                    li += '<a class="select_variant_product" data-p_id="' + product.id + '" data-v_id="' + variant.id +
                                        '" data-p_name="' + product.name +
                                        '" data-v_code="' + variant.product_code +
                                        '" data-v_price="' + parseFloat(variantPriceIncTax).toFixed(2) +
                                        '" data-v_name="' + variant.variant_name +
                                        '" href="#">' + product.name + ' [' + variant.variant_name + ']' + '</a>';
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
                                    tr += '<input type="hidden" value="noid" id="combo_id" name="combo_ids[]">';
                                tr += '<span class="product_name">'+variant_product.product.name+'</span><br>';
                                tr += '<span class="product_code">('+variant_product.variant_code+')</span><br>';
                                tr += '<span class="product_variant">('+variant_product.variant_name+')</span>';
                                tr += '<input value="'+variant_product.product.id+'" type="hidden" class="productId-'+variant_product.product.id+'" id="product_id" name="product_ids[]">';
                                tr += '<input value="'+variant_product.id+'" type="hidden" class="variantId-'+variant_product.id+'" id="variant_id" name="variant_ids[]">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input value="1.00" required name="combo_quantities[]" type="text" class="form-control" id="combo_quantity">';
                                tr += '</td>';

                                var unitPriceIncTax = variant_product.variant_price + tax_amount;
                                tr += '<td>';
                                tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="unit_prices_inc_tax[]" type="text" class="form-control" id="unit_price_inc_tax">';
                                tr += '</td>';

                                tr += '<td>';
                                tr += '<input readonly value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" type="text" name="subtotal[]" id="subtotal" class="form-control">';
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
                tr += '<input value="1.00" required name="combo_quantities[]" type="number" class="form-control" id="combo_quantity">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly value="'+variant_price_inc_tax+'" required name="unit_prices_inc_tax[]" type="number" class="form-control" id="unit_price_inc_tax">';
                tr += '</td>';

                tr += '<td>';
                tr += '<input readonly value="'+variant_price_inc_tax+'" required name="subtotals[]" type="number" class="form-control" id="subtotal">';
                tr += '</td>';

                tr += '<td class="text-right">';
                tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger">-</a>';
                tr += '</td>';

                tr += '</tr>';
                $('#combo_products').append(tr);
                calculateTotalAmount();
            }
        });

        @if ($product->is_combo == 1)
            function getComboProducts() {
                $.ajax({
                    url: "{{ route('products.get.combo.products', $product->id) }}",
                    async: true,
                    type: 'get',
                    dataType: 'json',
                    success: function(comboProducts) {

                        $('.dynamic_variant_body').empty();

                        $.each(comboProducts, function(key, comboProduct) {

                            var tax_percent = comboProduct.parent_product.tax_id != null ? comboProduct.parent_product.tax.tax_percent : 0;
                            var tr = '';
                            tr += '<tr class="text-center">';
                            tr += '<td>';
                            tr += '<input type="hidden" value="'+comboProduct.id+'" id="combo_id" name="combo_ids[]">';
                            tr += '<span class="product_name">'+comboProduct.parent_product.name+'</span><br>';
                            var variantName = comboProduct.product_variant ? comboProduct.product_variant.variant_name : '';
                            var variantCode = comboProduct.product_variant ? comboProduct.product_variant.variant_code : '';
                            var variantId = comboProduct.product_variant ? comboProduct.product_variant.id : 'noid';
                            tr += '<span class="product_code">('+variantCode+')</span><br>';
                            tr += '<span class="product_variant">('+variantName+')</span>';
                            tr += '<input value="'+comboProduct.parent_product.id+'" type="hidden" class="productId-'+comboProduct.parent_product.id+'" id="product_id" name="product_ids[]">';
                            tr += '<input value="'+variantId+'" type="hidden" class="variantId-'+variantId+'" id="variant_id" name="variant_ids[]">';
                            tr += '</td>';

                            tr += '<td>';
                            tr += '<input value="'+comboProduct.quantity+'" required name="combo_quantities[]" type="text" class="form-control" id="combo_quantity">';
                            tr += '</td>';

                            var unitPriceIncTax = 0;

                            if (comboProduct.product_variant) {

                                unitPriceIncTax = (parseFloat(comboProduct.product_variant.variant_price) / 100 * parseFloat(tax_percent)) + parseFloat(comboProduct.product_variant.variant_price);
                            }else{

                                unitPriceIncTax = (parseFloat(comboProduct.parent_product.product_price) / 100 * parseFloat(tax_percent)) + parseFloat(comboProduct.parent_product.product_price);
                            }

                            tr += '<td>';
                            tr += '<input value="'+parseFloat(unitPriceIncTax).toFixed(2)+'" required name="unit_prices_inc_tax[]" type="text" class="form-control" id="unit_price_inc_tax">';
                            tr += '</td>';

                            var subTotal = parseFloat(unitPriceIncTax) * comboProduct.quantity;
                            tr += '<td>';
                            tr += '<input readonly value="'+parseFloat(subTotal).toFixed(2)+'" type="text" name="subtotal[]" id="subtotal" class="form-control">';
                            tr += '</td>';

                            tr += '<td class="text-right">';
                            tr += '<a href="" id="remove_combo_product_btn" class="btn btn-sm btn-danger">-</a>';
                            tr += '</td>';

                            tr += '</tr>';
                            $('#combo_products').append(tr);
                            calculateTotalAmount();
                        });
                    }
                });
            }
            getComboProducts();
        @endif

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

        // set sub category in form field
        $('#category_id').on('change', function() {

            var category_id = $(this).val();
            $.get("{{ url('common/ajax/call/category/subcategories/') }}"+"/"+category_id, function(subCategories) {

                $('#child_category_id').empty();
                $('#child_category_id').append('<option value="">Select Sub-Category</option>');

                $.each(subCategories, function(key, val) {

                    $('#child_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            });
        });

        // Add product by ajax
        $('#edit_product_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    $('.loading_button').hide();
                    if ($.isEmptyObject(data.errorMsg)) {

                        toastr.success(data);
                        window.location = "{{ url()->previous() }}";
                    } else {

                        toastr.error(data.errorMsg);
                        $('.error').html('');
                    }
                },error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }

                    toastr.error('Please check again all form fields.',
                        'Some thing went wrong.');

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
        $('.loading_button').show();
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
            $('.loading_button').hide();
            return;
        }
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.loading_button').hide();
                toastr.success('Successfully category is added.');
                $('#category_id').append('<option value="' + data.id + '">' + data.name +
                    '</option>');
                $('#category_id').val(data.id);
                $('#addCategoryModal').modal('hide');
                $('#add_category_form')[0].reset();
            }
        });
    });

    // Add category from create product by ajax
    $(document).on('submit', '#add_brand_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
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
                $('.loading_button').hide();
                toastr.success('Successfully brand is added.');
                $('#brand_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                $('#brand_id').val(data.id);
                $('#addBrandModal').modal('hide');
                $('#add_brand_form')[0].reset();
            }
        });
    });

    // Add category from create product by ajax
    $(document).on('submit', '#add_unit_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
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
            $('.loading_button').hide();
            return;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.loading_button').hide();
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
         $('.loading_button').removeClass('d-hide');
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
             $('.loading_button').addClass('d-hide');
            return;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.loading_button').addClass('d-hide');
                toastr.success('Successfully warranty is added.');
                $('#warranty_id').append('<option value="' + data.id + '">' + data.name + ' (' + data
                    .type+' '+data.duration_type+ ')' + '</option>');
                $('#warranty_id').val(data.id);
                $('#addWarrantyModal').modal('hide');
                $('#add_warranty_form')[0].reset();
            }
        });
    });

    @if ($product->is_variant == 1)
        function getProductVariants() {
            $.ajax({
                url: "{{ route('products.get.product.variants', $product->id) }}",
                async: true,
                type: 'get',
                dataType: 'json',
                success: function(variants) {

                    $('.dynamic_variant_body').empty();

                    $.each(variants, function(key, variant) {

                        var html = '';
                        html += '<tr id="more_new_variant">';
                        html += '<td>';
                        html += '<input type="hidden" name="variant_ids[]" id="variant_id"  value="'+variant.id+'">'
                        html += '<select class="form-control" name="" id="variants">';
                        html += '<option value="">Create Combination</option>';

                        $.each(variantsWithChild, function(key, val) {

                            html += '<option value="' + val.id + '">' + val.bulk_variant_name + '</option>';
                        });

                        html += '</select>';
                        html += '<input type="text" name="variant_combinations[]" id="variant_combination" class="form-control " placeholder="Variant Combination" value="'+variant.variant_name+'">';
                        html += '</td>';
                        html += '<td><input type="text" name="variant_codes[]" id="variant_code" class="form-control" placeholder="Variant Code" value="'+variant.variant_code+'">';
                        html += '</td>';
                        html += '<td>';
                        html += '<input type="number" step="any" name="variant_costings[]" class="form-control" placeholder="Cost" id="variant_costing" value="' +
                        variant.variant_cost + '">';
                        html += '</td>';
                        html += '<td>';
                        html += '<input type="number" step="any" name="variant_costings_with_tax[]" class="form-control" placeholder="Cost inc.tax" id="variant_costing_with_tax" value="' +variant.variant_cost_with_tax + '">';
                        html += '</td>';
                        html += '<td>';
                        html += '<input type="number" step="any" name="variant_profits[]" class="form-control" placeholder="Profit" value="'+variant.variant_profit + '" id="variant_profit">';
                        html += '</td>';
                        html += '<td>';
                        html += '<input type="number" step="any" name="variant_prices_exc_tax[]" class="form-control" placeholder="Price inc.tax" id="variant_price_exc_tax" value="' +
                        variant.variant_price + '">';
                        html += '</td>';
                        html += '<td>';
                        html += '<input type="file" name="variant_image[]" class="form-control" id="variant_image">';
                        html += '</td>';
                        html += '<td><a href="#" id="variant_remove_btn" class="btn btn-xs btn-sm btn-danger">X</a></td>';
                        html += '</tr>';

                        $('.dynamic_variant_body').prepend(html);
                    });
                }
            });
        }
        getProductVariants();
    @endif

    $('#myEditor').cleditor();

    $(document).on('click', '#digital_product',function () {

        if ($(this).is(':CHECKED')) {

            $('#is_manage_stock').prop('checked', false);
        }else{

            $('#is_manage_stock').prop('checked', true);
        }
    });

    $(document).on('click', '#is_manage_stock',function () {

        if ($(this).is(':CHECKED')) {

            $('#digital_product').prop('checked', false);
        }else{

            $('#digital_product').prop('checked', true);
        }
    });

    document.onkeyup = function () {
        var e = e || window.event; // for IE to cover IEs window event-object

        if (e.shiftKey && e.which == 13) {

            $('#save').click();
            return false;
        }
    }
</script>
@endpush
