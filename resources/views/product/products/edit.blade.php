@extends('layout.master')
@push('stylesheets')
    <style>
        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        .dataTables_filter {
            width: 50% !important;
        }

        .dataTables_filter input {
            width: 50%;
        }

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

        .dropify-wrapper {
            height: 100px !important;
        }

        .form_element {
            margin: 0px !important;
        }
    </style>
    <link href="{{ asset('backend/asset/css/jquery.cleditor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/custom/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('title', 'Edit Product - ')

@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Edit Product') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <form id="edit_product_form" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="p-lg-1 p-1">
                <div class="row g-1">
                    <div class="col-md-8">
                        <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                            <div class="element-body">
                                <div class="row gx-2 gy-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Product Name') }}</b> <span class="text-danger">*</span></label>
                                            <div class="col-8">
                                                <input required type="text" name="name" class="form-control" id="name" data-next="code" value="{{ $product->name }}" placeholder="{{ __('Product Name') }}" autofocus>
                                                <span class="error error_name"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Product Code') }}</b> </label>
                                            <div class="col-8">
                                                <input type="text" name="code" class="form-control" autocomplete="off" id="code" data-next="unit_id" value="{{ $product->product_code }}" placeholder="{{ __('Product Code') }}">
                                                <input type="hidden" name="current_product_code" id="current_product_code" value="{{ $product->product_code }}">
                                                <span class="error error_code"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Unit') }}</b> <span class="text-danger">*</span></label>
                                            <div class="col-8">
                                                <div class="input-group flex-nowrap">
                                                    <select required class="form-control select2" name="unit_id" id="unit_id" data-next="barcode_type">
                                                        <option data-main_unit_name="" value="">{{ __('Select Unit') }}</option>
                                                        @foreach ($units as $unit)
                                                            <option data-main_unit_name="{{ $unit->name }}" {{ $product->unit_id == $unit->id ? 'SELECTED' : '' }} value="{{ $unit->id }}">{{ $unit->name . ' (' . $unit->code_name . ')' }}</option>
                                                        @endforeach
                                                    </select>

                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text {{ !auth()->user()->can('product_unit_add') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('product_unit_add') ? 'addUnit' : '' }}"><i class="fas fa-plus-square input_i"></i></span>
                                                    </div>
                                                </div>
                                                <span class="error error_unit_id"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Barcode Type') }}</b></label>
                                            <div class="col-8">
                                                <select class="form-control" name="barcode_type" id="barcode_type" data-next="category_id">
                                                    <option value="CODE128">{{ __('Code 128 (C128)') }}</option>
                                                    {{-- <option value="CODE39">{{ __("Code 39 (C39)") }}</option>
                                                    <option value="EAN13">{{ __("EAN-13") }}</option>
                                                    <option value="UPC">{{ __("UPC") }}</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    @if ($generalSettings['product__is_enable_categories'] == '1')
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Category') }}</b> </label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select class="form-control select2 flex-nowrap" name="category_id" id="category_id" data-next="sub_category_id">
                                                            <option value="">{{ __('Select Category') }}</option>
                                                            @foreach ($categories as $category)
                                                                <option {{ $category->id == $product->category_id ? 'SELECTED' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text {{ !auth()->user()->can('product_category_add') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('product_brand_add') ? 'addCategory' : '' }}"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                    <span class="error error_category_id"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group flex-nowrap">
                                                <label class="col-4"><b>{{ __('Subcategory') }}</b></label>
                                                <div class="col-8">
                                                    <select class="form-control select2" name="sub_category_id" id="sub_category_id" data-next="brand_id">
                                                        <option value="">{{ __('Select Subcategory') }}</option>
                                                        @foreach ($subCategories as $subCategory)
                                                            <option {{ $subCategory->id == $product->sub_category_id ? 'SELECTED' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                                        @endforeach
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
                                                <label class="col-4"><b>{{ __('Brand.') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select class="form-control select2" name="brand_id" id="brand_id" data-next="alert_quantity">
                                                            <option value="">{{ __('Select Brand') }}</option>
                                                            @foreach ($brands as $brand)
                                                                <option {{ $brand->id == $product->brand_id ? 'SELECTED' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                            @endforeach
                                                        </select>

                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text add_button {{ !auth()->user()->can('product_brand_add') ? 'disabled_element' : '' }}" id="{{ auth()->user()->can('product_brand_add') ? 'addBrand' : '' }}"><i class="fas fa-plus-square input_i"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Alert Quantity') }}</b></label>
                                            <div class="col-8">
                                                <input type="number" step="any" name="alert_quantity" class="form-control" id="alert_quantity" data-next="warranty_id" value="{{ $product->alert_quantity }}" autocomplete="off">
                                                <span class="error error_alert_quantity"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    @if ($generalSettings['product__is_enable_warranty'] == '1')
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Warranty') }}</b></label>
                                                <div class="col-8">
                                                    <div class="input-group flex-nowrap">
                                                        <select class="form-control select2" name="warranty_id" id="warranty_id" data-next="access_branch_id">
                                                            <option value="">{{ __('Select Warranty') }}</option>
                                                            @foreach ($warranties as $warranty)
                                                                <option {{ $warranty->id == $product->warranty_id ? 'SELECTED' : '' }} value="{{ $warranty->id }}">{{ $warranty->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text {{ !auth()->user()->can('product_warranty_add') ? 'disabled_element' : '' }} add_button" id="{{ auth()->user()->can('product_warranty_add') ? 'addWarranty' : '' }}"><i class="fas fa-plus-square input_i"></i><span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('has_access_to_all_area') == 1)
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Store Access') }}</b> </label>
                                                <div class="col-8">
                                                    <input type="hidden" name="access_branch_count" value="access_branch_count">
                                                    <select class="form-control select2" name="access_branch_ids[]" id="access_branch_id" multiple>
                                                        @foreach ($branches as $branch)
                                                            <option @foreach ($product->productAccessBranches as $productAccessBranche)
                                                                    {{ $branch->id == $productAccessBranche->branch_id ? 'SELECTED' : '' }} @endforeach value="{{ $branch->id }}">
                                                                {{ $branch->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error error_access_branch_ids"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Condition') }}</b></label>
                                            <div class="col-8">
                                                <select class="form-control" name="product_condition" id="product_condition" data-next="is_manage_stock">
                                                    <option value="New">{{ __('New') }}</option>
                                                    <option {{ $product->product_condition == 'Used' ? 'SELECTED' : '' }} value="Used">{{ __('Used') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Stock Type') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('menu.stock_type_msg')" class="fas fa-info-circle tp"></i></b> </label>
                                            <div class="col-8">
                                                <select class="form-control" name="is_manage_stock" id="is_manage_stock" data-next="product_cost">
                                                    <option value="1">{{ __('Manageable Stock') }}</option>
                                                    <option {{ $product->is_manage_stock == 0 ? 'SELECTED' : '' }} value="0">{{ __('Service/Digital Product') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                            <div class="element-body">
                                <div id="form_part">
                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Unit Cost(Exc. Tax)') }}</b></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="product_cost" class="form-control fw-bold" id="product_cost" data-next="{{$generalSettings['product__is_enable_price_tax'] == '1' ? 'tax_ac_id' : 'profit' }}" value="{{ $product->product_cost }}" placeholder="0.00" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Unit Cost(Inc. Tax)') }}</b></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" readonly name="product_cost_with_tax" class="form-control fw-bold" id="product_cost_with_tax" data-next="tax_ac_id" value="{{ $product->product_cost_with_tax }}" placeholder="0.00" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        @if ($generalSettings['product__is_enable_price_tax'] == '1')
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Vat/Tax') }}</b></label>
                                                    <div class="col-8">
                                                        <select class="form-control" name="tax_ac_id" id="tax_ac_id" data-next="tax_type">
                                                            <option data-tax_percent="0" value="">{{ __('NoTax') }}</option>
                                                            @foreach ($taxAccounts as $tax)
                                                                <option {{ $product->tax_ac_id == $tax->id ? 'SELECTED' : '' }} data-tax_percent="{{ $tax->tax_percent }}" value="{{ $tax->id }}">
                                                                    {{ $tax->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>{{ __('Vat/Tax Type') }}</b> </label>
                                                    <div class="col-8">
                                                        <select name="tax_type" class="form-control" id="tax_type" data-next="profit">
                                                            <option value="1">{{ __('Exclusive') }}</option>
                                                            <option {{ $product->tax_type == 2 ? 'SELECTED' : '' }} value="2">{{ __('Inclusive') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Profit Margin(%)') }}</b></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="profit" class="form-control fw-bold" id="profit" data-next="product_price" value="{{ $product->profit }}" placeholder="0.00" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Unit Price(Exc. Tax)') }}</b></label>
                                                <div class="col-8">
                                                    <input type="number" step="any" name="product_price" class="form-control fw-bold" id="product_price" data-next="is_variant" value="{{ $product->product_price }}" placeholder="0.00" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Has Multiple Unit?') }}</b> </label>
                                                <div class="col-8">
                                                    <select name="has_multiple_unit" class="form-control" id="has_multiple_unit" data-next="is_variant">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option {{ $product->has_multiple_unit == \App\Enums\BooleanType::True->value ? 'SELECTED' : '' }} value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>{{ __('Has Variant?') }}</b> </label>
                                                <div class="col-8">
                                                    <select name="is_variant" class="form-control" id="is_variant" data-next="type">
                                                        @if ($product->is_variant == \App\Enums\BooleanType::True->value)
                                                            <option value="1">{{ __('Yes') }}</option>
                                                        @else
                                                            <option value="0">{{ __('No') }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="multi_unit_create_area {{ $product->has_multiple_unit == 1 ? '' : 'd-hide' }}">
                                            @include('product.products.partials.edit_product_set_multiple_unit_partial')
                                        </div>
                                    </div>

                                    @if ($product->is_variant == \App\Enums\BooleanType::True->value)
                                        @include('product.products.partials.edit_product_variant_edit_partial')
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form_element rounded mt-0 mb-lg-1 mb-1">
                            <div class="element-body">
                                <div class="row gx-2 gy-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Type') }}</b></label>
                                            <div class="col-8">
                                                <select name="type" class="form-control" id="type" data-next="weight">
                                                    @if ($product->type == 1)
                                                        <option value="1">{{ __('General') }}</option>
                                                    @else
                                                        <option value="2">{{ __('Combo') }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Weight') }}</b></label>
                                            <div class="col-8">
                                                <input type="text" name="weight" class="form-control" id="weight" data-next="is_show_in_ecom" value="{{ $product->weight }}" placeholder="{{ __('Weight') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Displayed In E-com') }}</b></label>
                                            <div class="col-8">
                                                <select name="is_show_in_ecom" class="form-control" id="is_show_in_ecom" data-next="is_show_emi_on_pos">
                                                    <option value="0">{{ __('No') }}</option>
                                                    <option {{ $product->is_show_in_ecom == 1 ? 'SELECTED' : '' }} value="1">{{ __('Yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Enable IMEI/SL No') }}</b></label>
                                            <div class="col-8">
                                                <select name="is_show_emi_on_pos" class="form-control" id="is_show_emi_on_pos" data-next="is_for_sale">
                                                    <option value="0">{{ __('No') }}</option>
                                                    <option {{ $product->is_show_emi_on_pos == 1 ? 'SELECTED' : '' }} value="1">{{ __('Yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gx-2 mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Is For Sale') }}</b></label>
                                            <div class="col-8">
                                                <select name="is_for_sale" class="form-control" id="is_for_sale" data-next="has_batch_no_expire_date">
                                                    <option value="1">{{ __('Yes') }}</option>
                                                    <option {{ $product->is_for_sale == 0 ? 'SELECTED' : '' }} value="0">{{ __('No') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('BatchNo/Expire Date') }}</b></label>
                                            <div class="col-8">
                                                <select name="has_batch_no_expire_date" class="form-control" id="has_batch_no_expire_date" data-next="save_changes">
                                                    <option value="0">{{ __('No') }}</option>
                                                    <option {{ $product->has_batch_no_expire_date == 1 ? 'SELECTED' : '' }} value="1">{{ __('Yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form_element rounded mt-0">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="col-2"><b>{{ __('Thumbnail Photo') }}</b> </label>
                                            <div class="col-10">
                                                <input type="file" name="photo" class="form-control" id="photo" data-allowed-file-extensions="png jpeg jpg gif" data-default-file="{{ $product->thumbnail_photo ? file_link(fileType: 'productThumbnail', fileName: $product->thumbnail_photo) : '' }}">
                                                <span class="error error_photo"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="col-2"><b>{{ __('Description') }}</b></label>
                                            <div class="col-10">
                                                <textarea name="product_details" class="ckEditor form-control" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;" data-next="save_changes">{{ $product->product_details }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button product_loading_btn d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>
                            <button type="button" class="btn btn-success product_submit_button p-1" id="save_changes">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
    @include('product.products.partials.all-modals')
@endsection

@push('scripts')
    @include('product.products.js_partials.edit_product_js')
    @include('product.products.js_partials.edit_variant_js')
@endpush
