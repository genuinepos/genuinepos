@extends('layout.master')
@push('stylesheets')
    <style>
        .select_area { position: relative; background: #ffffff; box-sizing: border-box; position: absolute; width: 100%; z-index: 9999999; padding: 0; left: 0%; display: none; border: 1px solid var(--main-color); margin-top: 1px; border-radius: 0px; }

        .select_area ul { list-style: none; margin-bottom: 0; padding: 4px 4px; }

        .select_area ul li a { color: #000000; text-decoration: none; font-size: 10px; padding: 2px 2px; display: block; border: 1px solid gray; }

        .select_area ul li a:hover { background-color: #999396; color: #fff; }

        .selectProduct { background-color: #746e70; color: #fff !important; }

        .table_product_list { max-height: 70vh; overflow-x: scroll; }
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
                                        <h6>{{ __('Barcode Page Size Settings') }}</h6>
                                    </div>

                                    <form action="{{ route('barcode.preview') }}" method="get" target="_blank">
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
                                                            <h6 class="p-0 m-0">{{ __('Product List') }}</h6>
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
                                                <div class="col-md-12">
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
                                            <h6>{{ __('Purchased Product List') }}</h6>
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
                                                            <th class="text-start">{{ __('...') }}</th>
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

                                                                <tr data-product_id="{{ $purchasedProduct->product_id }}" data-product_name="{{ $purchasedProduct->product_name }}" data-product_code="{{ $purchasedProduct->variant_code ? $purchasedProduct->variant_code : $purchasedProduct->product_code }}" data-variant_id="{{ $purchasedProduct->variant_id ? $purchasedProduct->variant_id : 'noid' }}" data-variant_name="{{ $purchasedProduct->variant_name }}" data-price_exc_tax="{{ $priceExcTax }}" data-tax_ac_id="{{ $purchasedProduct->tax_ac_id }}" data-tax_percent="{{ $purchasedProduct->tax_percent ? $purchasedProduct->tax_percent : 0 }}" data-tax_type="{{ $purchasedProduct->tax_type }}" data-price_inc_tax="{{ $priceIncTax }}" data-supplier_id="{{ $purchasedProduct->supplier_account_id }}" data-supplier_name="{{ $purchasedProduct->supplier_name }}" data-supplier_prefix="{{ $purchasedProduct->supplier_prefix }}" data-label_qty="{{ $purchasedProduct->total_left_qty }}">
                                                                    <td class="text-start">
                                                                        <input type="checkbox" class="check">
                                                                    </td>
                                                                    <td class="text-start">
                                                                        <span id="span_product_name">{{ Str::limit($purchasedProduct->product_name, 25, '') }}</span>
                                                                        @if ($purchasedProduct->variant_name)
                                                                            <span id="span_variant_name">{{ ' - ' . $purchasedProduct->variant_name }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-start">{{ $purchasedProduct->supplier_name . '-' . $purchasedProduct->supplier_prefix }}</td>
                                                                    <td class="text-end">{{ $purchasedProduct->total_left_qty }}</td>
                                                                    @php
                                                                        $totalPendingQty += $purchasedProduct->total_left_qty;
                                                                    @endphp
                                                                    <td>
                                                                        <a href="{{ route('barcode.empty.label.qty', [$purchasedProduct->supplier_account_id, $purchasedProduct->product_id, $purchasedProduct->variant_id]) }}" id="emptyLabelQtyBtn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                                    </td>
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

    <form id="lebel_empty_form" action="" method="post">
        @csrf
    </form>
@endsection
@push('scripts')
    @include('product.barcode.js_partial.barcode_generate_js')
@endpush
