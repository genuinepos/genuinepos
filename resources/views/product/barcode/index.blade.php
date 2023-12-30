@extends('layout.master')
@push('stylesheets')
    <style>
        .select_area { position: relative; background: #ffffff; box-sizing: border-box; position: absolute; width: 100%; z-index: 9999999; padding: 0; left: 0%; display: none; border: 1px solid var(--main-color); margin-top: 1px; border-radius: 0px; }

        .select_area ul { list-style: none; margin-bottom: 0; padding: 4px 4px; }

        .select_area ul li a { color: #000000; text-decoration: none; font-size: 10px; padding: 2px 2px; display: block; border: 1px solid gray; }

        .select_area ul li a:hover { background-color: #999396; color: #fff; }

        .selectProduct { background-color: #746e70; color: #fff !important; }

        .table_product_list {
            max-height: 70vh;
            overflow-x: scroll;
        }
    </style>
@endpush
@section('title', 'Generate Barcode - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Generate Barcode') }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-lg-1 p-1">
                        <div class="row g-lg-1 g-1">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <p><b>{{ __('Barcode Page Size Settings') }}</b></p>
                                    </div>
                                    <form id="multiple_completed_form" class="d-hide" action="{{ route('barcode.multiple.generate.completed') }}" method="post">
                                        @csrf
                                        <table>
                                            <tbody id="deleteable_supplier_products"></tbody>
                                        </table>
                                    </form>

                                    <form action="{{ route('barcode.preview') }}" method="POST" target="_blank">
                                        @csrf
                                        <div class="card-body">
                                            <input type="hidden" id="business_name" value="{{ $generalSettings['business__business_name'] }}">
                                            <div class="form-group row">
                                                <div class="col-md-8">
                                                    <select name="br_setting_id" class="form-control">
                                                        @foreach ($barcodeSettings as $barcodeStting)
                                                            <option {{ $barcodeStting->is_default == 1 ? 'SELECTED' : '' }} value="{{ $barcodeStting->id }}">
                                                                {{ $barcodeStting->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="extra_label mt-1">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <ul class="list-unstyled">
                                                            <li>
                                                                <p><input checked type="checkbox" name="is_price" class="checkbox" id="is_price"> &nbsp; {{ __('Price Inc. Tax') }} &nbsp;</p>
                                                            </li>

                                                            <li>
                                                                <p><input checked type="checkbox" name="is_product_name" class="checkbox" id="is_product_name"> &nbsp; {{ __('Product Name') }} &nbsp; </p>
                                                            </li>

                                                            <li>
                                                                <p class="checkbox_input_wrap"><input checked type="checkbox" name="is_product_variant" class="checkbox" id="is_product_variant"> &nbsp; {{ __('Variant Name') }} &nbsp; </p>
                                                            </li>

                                                            <li>
                                                                <p class="checkbox_input_wrap"><input type="checkbox" name="is_tax" class="checkbox" id="is_tax"> &nbsp; {{ __('Tax') }} &nbsp; </p>
                                                            </li>

                                                            <li>
                                                                <p><input checked type="checkbox" name="is_business_name" class="checkbox" id="is_business_name"> &nbsp; {{ __('Shop Name') }} &nbsp; </p>
                                                            </li>

                                                            <li>
                                                                <p><input checked type="checkbox" name="is_supplier_prefix" class="checkbox" id="is_supplier_prefix"> &nbsp; {{ __('Supplier Prefix') }} &nbsp; </p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mt-3 row">
                                                <div class="col-md-12">
                                                    <div class="searching_area" style="position: relative;">
                                                        <label class="fw-bold">{{ __('Search Product') }}</label>
                                                        <div class="input-group">
                                                            <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="{{ __('Search Product By Name/Code') }}">
                                                        </div>

                                                        <div class="select_area">
                                                            <ul id="list" class="variant_list_area"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="barcode_product_table_area mt-2">
                                                        <div class="table_heading">
                                                            <p class="p-0 m-0"><strong>{{ __('Product List') }}</strong></p>
                                                        </div>
                                                        <div class="table_area">
                                                            <div class="data_preloader d-hide">
                                                                <h6><i class="fas fa-spinner"></i> {{ __('Processing') }}...</h6>
                                                            </div>
                                                            <div class="table-responsive">
                                                                <table class="display data__table table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-start">{{ __('Product') }}</th>
                                                                            <th class="text-start">{{ __('Supplier') }}</th>
                                                                            <th class="text-start">{{ __('Price Exc. Tax') }}</th>
                                                                            <th class="text-start">{{ __('Tax/Vat') }}</th>
                                                                            <th class="text-start">{{ __('Price Inc. Tax') }}</th>
                                                                            <th class="text-start">{{ __('Quantity') }}</th>
                                                                            <th class="text-start">{{ __('Action') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="barcode_product_list"></tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="5" class="text-end">{{ __('Total Prepired Qty') }}</th>
                                                                            <th class="text-start">(<span id="prepired_qty">0</span>)</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="multiple_cmp_btn_area">
                                                        <a href="" class="btn btn-sm btn-danger multiple_completed" style=""> {{ __('Delete Selected All') }} </a> <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Note : Delete all items from puchased products which is selected for generation the barcodes') }}" class="fas fa-info-circle tp"></i>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-sm btn-primary submit_button float-end">{{ __('Preview & Print') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="table_product_list">
                                    <div class="card">
                                        <div class="card-header">
                                            <p><strong>{{ __('Purchased Product List') }}</strong></p>
                                        </div>
                                        <div class="card-body p-1">
                                            <div class="table-responsive">
                                                <table class="display data__table table" id="data">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-start"><input type="checkbox" id="check_all"></th>
                                                            <th class="text-start">{{ __('Product') }}</th>
                                                            <th class="text-start">{{ __('Supplier') }}</th>
                                                            <th class="text-start">{{ __('Quantity') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="purchased_product_list">
                                                    <tbody id="purchased_product_list">
                                                        @php
                                                            $totalPendingQty = 0;
                                                        @endphp
                                                        @if (count($purchasedProducts) > 0)
                                                            @foreach ($purchasedProducts as $purchasedProduct)
                                                                @php
                                                                    $taxPercent = $purchasedProduct->tax_percent ? $purchasedProduct->tax_percent : 0;
                                                                    $priceExcTax = $purchasedProduct->variant_price ? $purchasedProduct->variant_price : $purchasedProduct->product_price;

                                                                    $priceIncTax = ($priceExcTax / 100) * $taxPercent + $priceExcTax;

                                                                    if ($purchasedProduct->tax_type == 2) {
                                                                        $inclusiveTax = 100 + $taxPercent;
                                                                        $calcAmount = ($priceExcTax / $inclusiveTax) * 100;
                                                                        $taxAmount = $priceExcTax - $calcAmount;
                                                                        $priceIncTax = $priceExcTax + $taxAmount;
                                                                    }
                                                                @endphp

                                                                <tr data-product_id="{{ $purchasedProduct->product_id }}" data-product_name="{{ $purchasedProduct->product_name }}" data-product_code="{{ $purchasedProduct->variant_code ? $purchasedProduct->variant_code : $purchasedProduct->product_code }}" data-variant_id="{{ $purchasedProduct->variant_id ? $purchasedProduct->variant_id : 'noid' }}" data-variant_name="{{ $purchasedProduct->variant_name }}" data-price_exc_tax="{{ $priceExcTax }}" data-tax_ac_id="{{ $purchasedProduct->tax_ac_id }}" data-tax_percent="{{ $purchasedProduct->tax_percent ? $purchasedProduct->tax_percent : 0 }}" data-tax_type="{{ $purchasedProduct->tax_type }}" data-price_inc_tax="{{ $priceIncTax }}" data-supplier_id="{{ $purchasedProduct->supplier_id }}" data-supplier_name="{{ $purchasedProduct->supplier_name }}" data-supplier_prefix="{{ 'AE1' }}" data-label_qty="{{ $purchasedProduct->total_left_qty }}">
                                                                    <td class="text-start">
                                                                        <input type="checkbox" class="check">
                                                                    </td>
                                                                    <td class="text-start">
                                                                        <span id="span_product_name">{{ Str::limit($purchasedProduct->product_name, 25, '') }}</span>
                                                                        @if ($purchasedProduct->variant_name)
                                                                            <span id="span_variant_name">{{ ' - ' . $purchasedProduct->variant_name }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-start">{{ $purchasedProduct->supplier_name . '-AE1' }}</td>
                                                                    <td class="text-end">{{ $purchasedProduct->total_left_qty }}</td>
                                                                    @php
                                                                        $totalPendingQty += $purchasedProduct->total_left_qty;
                                                                    @endphp
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <th colspan="4" class="text-center">{{ __('Data No Found') }}.</th>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" class="text-end">{{ __('Total Pending Qty') }} </th>
                                                            <th colspan="3" class="text-end">({{ $totalPendingQty }})</th>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
    <script>
        var taxes = @json($taxAccounts);

        var delay = (function() {

            var timer = 0;
            return function(callback, ms) {

                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();

        $('#search_product').on('input', function(e) {

            $('.variant_list_area').empty();
            $('.select_area').hide();
            var keyWord = $(this).val();
            var __keyWord = keyWord.replaceAll('/', '~');
            delay(function() {
                searchProduct(__keyWord);
            }, 200);
        });

        function searchProduct(keyWord) {

            $('.variant_list_area').empty();
            $('.select_area').hide();

            var isShowNotForSaleItem = 1;
            var url = "{{ route('general.product.search.common', [':keyWord', ':isShowNotForSaleItem']) }}";
            var route = url.replace(':keyWord', keyWord);
            route = route.replace(':isShowNotForSaleItem', isShowNotForSaleItem);

            $.ajax({
                url: route,
                dataType: 'json',
                success: function(product) {

                    if (!$.isEmptyObject(product.errorMsg)) {

                        toastr.error(product.errorMsg);
                        $('#search_product').val('');
                        return;
                    }

                    if (
                        !$.isEmptyObject(product.product) ||
                        !$.isEmptyObject(product.variant_product) ||
                        !$.isEmptyObject(product.namedProducts)
                    ) {

                        $('#search_product').addClass('is-valid');
                        if (!$.isEmptyObject(product.product)) {

                            var product = product.product;

                            if (product.variants.length == 0) {

                                $('.select_area').hide();

                                var taxAcId = product.tax_ac_id != null ? product.tax_ac_id : 0;
                                var taxPercent = product.tax != null ? product.tax.tax_percent : 0;
                                var priceExcTax = product.product_price;
                                var priceIncTax = (priceExcTax / 100 * taxPercent) + priceExcTax;

                                if (product.tax_type == 2) {

                                    var inclusiveTax = 100 + taxPercent;
                                    var calcAmount = priceExcTax / inclusiveTax * 100;
                                    var taxAmount = priceExcTax - calcAmount;
                                    priceIncTax = priceExcTax + taxAmount;
                                }

                                var name = product.name.length > 25 ? product.name.substring(0, 25) + '...' : product.name;

                                var tr = '';
                                tr += '<tr>';
                                tr += '<td class="text-start">';
                                tr += '<span id="span_product_name">' + name + '</span>';
                                tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + product.id + '">';
                                tr += '<input type="hidden" name="product_names[]" value="' + name + '">';
                                tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="noid">';
                                tr += '<input type="hidden" name="product_variants[]" value="">';
                                tr += '<input type="hidden" name="product_codes[]" value="' + product.product_cost + '">';
                                tr += '</td>';

                                tr += '<td class="text-start">';
                                tr += '<span class="span_supplier_name"></span>';
                                tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="">';
                                tr += '<input type="hidden" name="supplier_prefixes[]" id="supplier_prefix" value="">';
                                tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + taxPercent + '">';
                                tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + product.tax_type + '">';
                                tr += '</td>';

                                tr += '<td class="text-start">';
                                tr += '<input type="number" step="any" name="prices_exc_tax[]" class="form-control fw-bold" id="price_exc_tax" value="' + parseFloat(priceExcTax).toFixed(2) + '">';
                                tr += '</td>';

                                tr += '<td class="text-start">';
                                tr += '<select name="tax_ac_ids[]" class="form-control" id="tax_ac_id">';
                                tr += '<option data-tax_percent="0" value="">' + "{{ __('NoVat/Tax(0%)') }}" + '</option>';

                                taxes.forEach(function(tax) {

                                    var selectedOption = tax.id == taxAcId ? 'SELECTED' : '';
                                    tr += '<option ' + selectedOption + ' data-tax_percent="' + tax.tax_percent + '" value="' + tax.id + '">' + tax.name + '</option>>'
                                });

                                tr += '</select>';
                                tr += '</td>';

                                tr += '<td class="text-start">';
                                tr += '<input type="number" step="any" name="prices_inc_tax[]" class="form-control fw-bold" id="price_inc_tax" value="' + parseFloat(priceIncTax).toFixed(2) + '">';
                                tr += '</td>';

                                tr += '<td class="text-start">';
                                tr += '<input type="number" name="left_quantities[]" class="form-control fw-bold" id="left_quantity" value="1">';
                                tr += '</td>';
                                tr += '<td class="text-start">';
                                tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ms-1">X</a>';
                                tr += '</td>';
                                tr += '</tr>';

                                $('#barcode_product_list').prepend(tr);
                                $('#search_product').val('');
                                calculateQty();
                            } else {

                                var li = "";
                                var imgUrl = "{{ asset('uploads/product/thumbnail') }}";

                                $.each(product.variants, function(key, variant) {

                                    li += '<li>';
                                    li += '<a href="#" onclick="selectProduct(this); return false;" data-product_id="' + product.id + '" data-variant_id="' + variant.id + '" data-product_name="' + product.name + '" data-variant_name="' + variant.variant_name + '" data-tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-tax_percent="' + (product.tax != null ? product.tax.tax_percent : 0) + '"  data-product_code="' + variant.variant_code + '" data-price_exc_tax="' + variant.variant_price + '"><img style="width:20px; height:20px;" src="' + imgUrl + '/' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                    li += '</li>';
                                });

                                $('.variant_list_area').append(li);
                                $('.select_area').show();
                                $('#search_product').val('');
                            }
                        } else if(!$.isEmptyObject(product.namedProducts)) {

                            if (product.namedProducts.length > 0) {

                                var li = "";
                                var imgUrl = "{{ asset('uploads/product/thumbnail') }}";
                                var products = product.namedProducts;

                                $.each(products, function(key, product) {

                                    if (product.is_variant == 1) {

                                        li += '<li class="mt-1">';
                                        li += '<a  href="#" onclick="selectProduct(this); return false;" data-product_id="' + product.id + '" data-variant_id="' + product.variant_id + '" data-product_name="' + product.name + '" data-variant_name="' + product.variant_name + '" data-tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '" data-tax_type="' + product.tax_type + '" data-tax_percent="' + (product.tax_percent != null ? product.tax_percent : 0) + '" data-product_code="' + product.variant_code + '" data-price_exc_tax="' + product.variant_price + '"><img style="width:20px; height:20px;" src="' + imgUrl + '/' + product.thumbnail_photo + '"> ' + product.name + ' - ' + product.variant_name + '</a>';
                                        li += '</li>';
                                    } else {

                                        li += '<li class="mt-1">';
                                        li += '<a href="#" onclick="selectProduct(this); return false;" data-product_id="' + product.id + '" data-variant_id="noid" data-variant_name="" data-product_name="' + product.name + '" data-tax_ac_id="' + (product.tax_ac_id != null ? product.tax_ac_id : '') + '"  data-tax_type="' + product.tax_type + '" data-tax_percent="' + (product.tax_percent != null ? product.tax_percent : 0) + '" data-product_code="' + product.product_code + '" data-price_exc_tax="' + product.product_cost + '"><img style="width:20px; height:20px;" src="' + imgUrl + '/' + product.thumbnail_photo + '"> ' + product.name + '</a>';
                                        li += '</li>';
                                    }
                                });

                                $('.variant_list_area').html(li);
                                $('.select_area').show();
                            }
                        } else if(!$.isEmptyObject(product.variant_product)) {

                            $('.select_area').hide();

                            var variant = product.variant_product;

                            var taxAcId = variant.product.tax_ac_id != null ? variant.product.tax_ac_id : '';
                            var taxPercent = variant.product.tax != null ? variant.product.tax.tax_percent : 0;
                            var priceExcTax = variant.variant_price;
                            var priceIncTax = (priceExcTax / 100 * taxPercent) + priceExcTax;

                            if (product.tax_type == 2) {

                                var inclusiveTax = 100 + taxPercent;
                                var calcAmount = priceExcTax / inclusiveTax * 100;
                                var taxAmount = priceExcTax - calcAmount;
                                priceIncTax = priceExcTax + taxAmount;
                            }

                            var name = variant.product.name.length > 35 ? product.name.substring(0, 35) + '...' : variant.product.name;

                            var tr = '';
                            tr += '<tr>';
                            tr += '<td class="text-start">';
                            tr += '<span id="span_product_name">' + name + '</span>';
                            tr += '<span id="span_variant_name">' + ' - ' + variant.variant_name + '</span>';

                            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + variant.product.id + '">';

                            tr += '<input type="hidden" name="product_names[]" value="' + name + '">';
                            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variant.id + '">';
                            tr += '<input type="hidden" name="variant_names[]" value="' + variant.variant_name + '">';
                            tr += '<input type="hidden" name="product_codes[]" value="' + variant.variant_code + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<span class="span_supplier_name"></span>';
                            tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="">';
                            tr += '<input type="hidden" name="supplier_prefixes[]" id="supplier_prefix" value="">';
                            tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + taxPercent + '">';
                            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + product.tax_type + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="number" step="any" name="prices_exc_tax[]" class="form-control fw-bold" id="price_exc_tax" value="' + parseFloat(priceExcTax).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<select name="tax_ac_ids[]" class="form-control" id="tax_ac_id">';
                            tr += '<option data-tax_percent="0" value="">' + "{{ __('NoVat/Tax(0%)') }}" + '</option>';

                            taxes.forEach(function(tax) {

                                var selectedOption = tax.id == taxAcId ? 'SELECTED' : '';

                                tr += '<option ' + selectedOption + ' data-tax_percent="' + tax.tax_percent + '" value="' + tax.id + '">' + tax.name + '</option>>'
                            });

                            tr += '</select>';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="number" step="any" name="prices_inc_tax[]" class="form-control fw-bold" id="price_inc_tax" value="' + parseFloat(priceIncTax).toFixed(2) + '">';
                            tr += '</td>';

                            tr += '<td class="text-start">';
                            tr += '<input type="number" name="left_quantities[]" class="form-control fw-bold" id="left_quantity" value="1">';
                            tr += '</td>';
                            tr += '<td class="text-start">';
                            tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ms-1">X</a>';
                            tr += '</td>';
                            tr += '</tr>';

                            $('#barcode_product_list').prepend(tr);
                            $('#search_product').val('');
                            calculateQty();
                        }
                    } else {

                        $('#search_product').addClass('is-invalid');
                    }
                }
            });
        }

        function selectProduct(e) {

            $('.select_area').hide();
            $('#search_product').val('');

            var productId = e.getAttribute('data-product_id');
            var variantId = e.getAttribute('data-variant_id');
            var productName = e.getAttribute('data-product_name');
            var variantName = e.getAttribute('data-variant_name');
            var taxAcId = e.getAttribute('data-tax_ac_id');
            var taxType = e.getAttribute('data-tax_type');
            var taxPercent = e.getAttribute('data-tax_percent');
            var productCode = e.getAttribute('data-product_code');
            var productPrice = e.getAttribute('data-price_exc_tax');

            var priceExcTax = productPrice;
            var priceIncTax = (priceExcTax / 100 * taxPercent) + priceExcTax;

            if (taxType == 2) {

                var inclusiveTax = 100 + taxPercent;
                var calcAmount = priceExcTax / inclusiveTax * 100;
                var taxAmount = priceExcTax - calcAmount;
                priceIncTax = priceExcTax + taxAmount;
            }

            var name = productName.length > 35 ? productName.substring(0, 35) + '...' : productName;

            var tr = '';
            tr += '<tr>';
            tr += '<td class="text-start">';
            tr += '<span id="span_product_name">' + name + '</span>';
            tr += '<span id="span_variant_name">' + ' - ' + variantName + '</span>';

            tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + productId + '">';

            tr += '<input type="hidden" name="product_names[]" value="' + name + '">';
            tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variantId + '">';
            tr += '<input type="hidden" name="variant_names[]" value="' + variantName + '">';
            tr += '<input type="hidden" name="product_codes[]" value="' + productCode + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<span class="span_supplier_name"></span>';
            tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="">';
            tr += '<input type="hidden" name="supplier_prefixes[]" id="supplier_prefix" value="">';
            tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + taxPercent + '">';
            tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + taxType + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input type="number" step="any" name="prices_exc_tax[]" class="form-control fw-bold" id="price_exc_tax" value="' + parseFloat(priceExcTax).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<select name="tax_ac_ids[]" class="form-control" id="tax_ac_id">';
            tr += '<option data-tax_percent="0" value="">' + "{{ __('NoVat/Tax(0%)') }}" + '</option>';

            taxes.forEach(function(tax) {

                var selectedOption = tax.id == taxAcId ? 'SELECTED' : '';
                tr += '<option ' + selectedOption + ' data-tax_percent="' + tax.tax_percent + '" value="' + tax.id + '">' + tax.name + '</option>>'
            });

            tr += '</select>';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input type="number" step="any" name="prices_inc_tax[]" class="form-control fw-bold" id="price_inc_tax" value="' + parseFloat(priceIncTax).toFixed(2) + '">';
            tr += '</td>';

            tr += '<td class="text-start">';
            tr += '<input type="number" name="left_quantities[]" class="form-control fw-bold" id="left_quantity" value="1">';
            tr += '</td>';
            tr += '<td class="text-start">';
            tr += '<a href="#" class="btn btn-sm btn-danger remove_btn ms-1">X</a>';
            tr += '</td>';
            tr += '</tr>';

            $('#barcode_product_list').prepend(tr);
            $('#search_product').val('');
            calculateQty();

        }

        $(document).on('change', '#check_all', function() {

            if ($(this).is(':CHECKED', true)) {

                $('.check').click();
            } else {

                $('.check').click();
            }
        });

        $(document).on('click', '.check', function() {

            var tr = $(this).closest('tr');
            var product_id = tr.data('product_id');
            var product_code = tr.data('product_code');
            var product_name = tr.data('product_name');
            var variant_id = tr.data('variant_id');
            var variant_name = tr.data('variant_name');
            var tax_ac_id = tr.data('tax_ac_id');
            var tax_percent = tr.data('tax_percent');
            var tax_type = tr.data('tax_type');
            var price_exc_tax = tr.data('price_exc_tax');
            var price_inc_tax = tr.data('price_inc_tax');
            var supplier_id = tr.data('supplier_id');
            var supplier_name = tr.data('supplier_name');
            var supplier_prefix = tr.data('supplier_prefix');
            var label_qty = tr.data('label_qty');

            if ($(this).is(':CHECKED', true)) {

                var tr = '';
                tr += '<tr class="' + supplier_id + '' + product_id + '' + (variant_id ? variant_id : 'noid') + '">';
                tr += '<td class="text-start">';
                tr += '<span id="span_product_name">' + product_name + '</span>';

                if (variant_id) {

                    tr += '<span id="span_variant_name">' + ' - ' + variant_name + '</span>';
                }

                tr += '<input type="hidden" name="product_ids[]" id="product_id" value="' + product_id + '">';

                tr += '<input type="hidden" name="product_names[]" value="' + product_name + '">';
                tr += '<input type="hidden" name="variant_ids[]" id="variant_id" value="' + variant_id + '">';
                tr += '<input type="hidden" name="variant_names[]" value="' + variant_name + '">';
                tr += '<input type="hidden" name="product_codes[]" value="' + product_code + '">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<span class="span_supplier_name">' + supplier_name + '</span>';
                tr += '<input type="hidden" name="supplier_ids[]" id="supplier_id" value="' + supplier_id + '">';
                tr += '<input type="hidden" name="supplier_prefixes[]" id="supplier_prefix" value="' + supplier_prefix + '">';
                tr += '<input type="hidden" name="tax_types[]" id="tax_type" value="' + tax_type + '">';
                tr += '<input type="hidden" name="tax_percents[]" id="tax_percent" value="' + tax_percent + '">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<input type="number" step="any" name="prices_exc_tax[]" class="form-control fw-bold" id="price_exc_tax" value="' + parseFloat(price_exc_tax).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<select name="tax_ac_ids[]" class="form-control" id="tax_ac_id">';
                tr += '<option data-tax_percent="0" value="">NoVat/Tax(0%)</option>';
                taxes.forEach(function(tax) {

                    var selectedOption = tax.id == tax_ac_id ? 'SELECTED' : '';
                    tr += '<option ' + selectedOption + ' data-tax_percent="' + tax.tax_percent + '" value="' + tax.id + '">' + tax.name + '</option>>'
                });
                tr += '</select>';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<input type="number" step="any" name="prices_inc_tax[]" class="form-control fw-bold" id="price_inc_tax" value="' + parseFloat(price_inc_tax).toFixed(2) + '">';
                tr += '</td>';

                tr += '<td class="text-start">';
                tr += '<input type="number" name="left_quantities[]" class="form-control fw-bold" id="left_quantity" value="' + label_qty + '">';
                tr += '</td>';
                tr += '</tr>';
                $('#barcode_product_list').prepend(tr);
                calculateQty();
            } else {

                $('.' + supplier_id + '' + product_id + '' + (variant_id ? variant_id : 'noid')).remove();
                calculateQty();
            }
        });

        function calculateQty() {
            var left_quantities = document.querySelectorAll('#left_quantity');
            var total_qty = 0;

            left_quantities.forEach(function(left_qty) {
                total_qty += parseFloat(left_qty.value);
            });

            $('#prepired_qty').html(total_qty);

            if (parseFloat(total_qty) > 0) {

                $('.multiple_cmp_btn_area').show();
            } else {

                $('.multiple_cmp_btn_area').hide();
            }
        }

        function calculatePrice(tr) {

            var priceExcTax = tr.find('#price_exc_tax').val() ? tr.find('#price_exc_tax').val() : 0;
            var taxPercent = tr.find('#tax_ac_id').find('option:selected').data('tax_percent') ? tr.find('#tax_ac_id').find('option:selected').data('tax_percent') : 0;
            var taxType = tr.find('#tax_types').val();

            var priceIncTax = (parseFloat(priceExcTax) / 100 * parseFloat(taxPercent)) + parseFloat(priceExcTax);

            if (taxType == 2) {

                var inclusiveTax = 100 + parseFloat(taxPercent);
                var calcAmount = (parseFloat(priceExcTax) / parseFloat(inclusiveTax)) * 100;
                var taxAmount = parseFloat(priceExcTax) - parseFloat(calcAmount);
                priceIncTax = parseFloat(priceExcTax) + parseFloat(taxAmount);
            }

            tr.find('#price_inc_tax').val(parseFloat(priceIncTax).toFixed(2));
        }

        $(document).on('input', '#price_exc_tax', function() {
            var tr = $(this).closest('tr');
            calculatePrice(tr);
        });

        $(document).on('change', '#tax_ac_id', function() {

            var tr = $(this).closest('tr');

            var taxPercent = tr.find(this).find('option:selected').data('tax_percent') ? tr.find(this).find('option:selected').data('tax_percent') : 0;
            var res = tr.find('#tax_percent').val(parseFloat(taxPercent).toFixed(2));
            calculatePrice(tr);
        });

        $(document).on('input', '#left_quantity', function() {
            calculateQty();
        });

        $(document).on('click', '.remove_btn', function(e) {
            e.preventDefault();
            var tr = $(this).closest('tr').remove();
            calculateQty();
        });

        setInterval(function() {
            $('#search_product').removeClass('is-invalid');
        }, 500);

        setInterval(function(){
            $('#search_product').removeClass('is-valid');
        }, 1000);

        $(document).on('click input keypress', '#search_product', function(e) {

            if(e.which == 13) {

                e.preventDefault();
            }
        });

        $('body').keyup(function(e) {

            if (e.keyCode == 13 || e.keyCode == 9){

                $(".selectProduct").click();
                $('#list').empty();
            }
        });
    </script>
@endpush
