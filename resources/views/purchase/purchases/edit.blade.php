@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area { position: relative; background: #ffffff; box-sizing: border-box; position: absolute; width: 100%; z-index: 9999999; padding: 0; left: 0%; display: none; border: 1px solid var(--main-color); margin-top: 1px; border-radius: 0px;}
        .select_area ul { list-style: none; margin-bottom: 0; padding: 4px 4px; }
        .select_area ul li a { color: #000000; text-decoration: none; font-size: 10px; padding: 2px 2px; display: block; border: 1px solid gray; }
        .select_area ul li a:hover { background-color: #999396; color: #fff; }
        .selectProduct{background-color: #746e70; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
        label.col-2,label.col-3,label.col-4,label.col-5,label.col-6 { text-align: right; padding-right: 10px;}
        .checkbox_input_wrap {text-align: right;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('title', 'Edit Purchase')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-edit"></span>
                    <h6>{{ __("Edit Purchase") }}</h6>
                </div>

                <div class="col-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                </div>
            </div>
        </div>
        <div class="p-1">
            <form id="edit_purchase_form" action="{{ route('purchases.update', $purchase->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="paid" id="paid" value="{{ $purchase->paid }}">
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row g-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __("Supplier") }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <div class="input-group flex-nowrap">
                                                <select name="supplier_account_id" class="form-control select2" id="supplier_account_id" data-next="invoice_id">
                                                    <option value="">{{ __("Select Supplier") }}</option>
                                                    @foreach ($supplierAccounts as $supplierAccount)
                                                        <option data-pay_term="{{ $supplierAccount->pay_term }}" data-pay_term_number="{{ $supplierAccount->pay_term_number }}" {{ $supplierAccount->id == $purchase->supplier_account_id ? 'SELECTED' : '' }} value="{{ $supplierAccount->id }}">{{ $supplierAccount->name.'/'.$supplierAccount->phone }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text add_button" id="addSupplier"><i class="fas fa-plus-square text-dark"></i></span>
                                                </div>
                                            </div>
                                            <span class="error error_supplier_account_id"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>@lang('menu.curr_balance') </b></label>
                                        <div class="col-8">
                                            <input readonly type="text" id="current_balance" class="form-control fw-bold" value="0.00" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.invoice_id') </b></label>
                                        <div class="col-8">
                                            <input readonly name="invoice_id" type="text" class="form-control fw-bold" id="invoice_id" value="{{ $purchase->invoice_id }}" data-next="warehouse_id">
                                        </div>
                                    </div>

                                    @if ($purchase->warehouse_id)
                                        <input name="warehouse_count" value="YES" type="hidden"/>
                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>{{ __("Warehouse") }}</b><span
                                                class="text-danger">*</span></label>
                                            <div class="col-8">
                                                <select class="form-control" name="warehouse_id" id="warehouse_id" data-next="date">
                                                    <option value="">{{ __("Select Warehouse") }}</option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option {{ $purchase->warehouse_id == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name.'/'.$warehouse->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_warehouse_id"></span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>{{ __('Business') }} </b> </label>
                                            <div class="col-8">
                                                <input readonly type="text" class="form-control fw-bold" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : $generalSettings['business__shop_name'] }}">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __("Date") }}</b></label>
                                        <div class="col-8">
                                            <input type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business__date_format'], strtotime($purchase->date)) }}" data-next="pay_term_number">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __("Pay-Term") }}</b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="text" name="pay_term_number" class="form-control"
                                                id="pay_term_number" placeholder="{{ __("Number") }}" value="{{ $purchase->pay_term_number }}" data-next="pay_term">
                                                <select name="pay_term" class="form-control" id="pay_term" data-next="purchase_account_id">
                                                    <option value="">{{ __("Pay-Term") }}</option>
                                                    <option {{ $purchase->pay_term == 1 ? 'SELECTED' : '' }} value="1">@lang('menu.days')</option>
                                                    <option {{ $purchase->pay_term == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.months')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __("Purchase A/c") }}<span class="text-danger">*</span></b></label>
                                        <div class="col-8">
                                            <select name="purchase_account_id" class="form-control" id="purchase_account_id" data-next="search_product">
                                                @foreach ($purchaseAccounts as $purchaseAccount)
                                                    <option {{ $purchaseAccount->id == $purchase->purchase_account_id ? 'SELECTED' : '' }} value="{{ $purchaseAccount->id }}">
                                                        {{ $purchaseAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_purchase_account_id"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card mb-1">
                        <div class="card-body p-2">
                            <div class="row mb-1">
                                <div class="col-md-12">
                                    <div class="row align-items-end">
                                        <input type="hidden" id="e_unique_id">
                                        <input type="hidden" id="e_item_name">
                                        <input type="hidden" id="e_product_id">
                                        <input type="hidden" id="e_variant_id">
                                        <input type="hidden" id="e_tax_amount">
                                        <input type="hidden" id="e_unit_cost_with_discount">
                                        <input type="hidden" id="e_subtotal">
                                        <input type="hidden" id="e_unit_cost_inc_tax">
                                        <input type="hidden" id="e_has_batch_no_expire_date">

                                        <div class="col-xl-4 col-md-4">
                                            <div class="searching_area" style="position: relative;">
                                                <label class="fw-bold">{{ __("Search Product") }}</label>
                                                <div class="input-group">
                                                    <input type="text" name="search_product" class="form-control fw-bold" autocomplete="off" id="search_product" onkeyup="event.preventDefault();" placeholder="{{ __("Search Product") }}">

                                                    @if (auth()->user()->can('product_add'))
                                                        <div class="input-group-prepend">
                                                            <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark input_f"></i></span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="select_area">
                                                    <ul id="list" class="variant_list_area"></ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __("Quantity") }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control w-60 fw-bold" id="e_quantity" value="0.00" placeholder="0.00" autocomplete="off">
                                                <select id="e_unit_id" class="form-control w-40">
                                                    <option value="">{{ __("Unit") }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __("Unit Cost (Exc. Tax)") }}</label>
                                            <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_exc_tax" value="0.00" placeholder="0.00" autocomplete="off">
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __("Unit Discount") }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control w-60 fw-bold" id="e_discount" value="0.00" placeholder="0.00" autocomplete="off">
                                                <select id="e_discount_type" class="form-control w-40">
                                                    <option value="1">{{ __("Fixed") }}(0.00)</option>
                                                    <option value="2">{{ __("Percentage") }}(%)</option>
                                                </select>
                                                <input type="hidden" id="e_discount_amount">
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __("Vat/Tax") }}</label>
                                            <div class="input-group">
                                                <select id="e_tax_ac_id" class="form-control w-50">
                                                    <option data-product_tax_percent="0.00" value="">{{ __("NoVat/Tax") }}</option>
                                                    @foreach ($taxAccounts as $taxAccount)
                                                        <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                            {{ $taxAccount->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select id="e_tax_type" class="form-control w-50">
                                                    <option value="1">{{ __("Exclusive") }}</option>
                                                    <option value="2">{{ __("Inclusive") }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-2 col-md-4 batch_no_expire_date_fields d-none">
                                            <label class="fw-bold">{{ __('Batch No & Expire Date') }}</label>
                                            <div class="input-group">
                                                <input readonly type="text" step="any" class="form-control fw-bold" id="e_batch_number" placeholder="{{ __("Batch No") }}" autocomplete="off">
                                                <input readonly type="text" step="any" class="form-control fw-bold" id="e_expire_date" placeholder="{{ __("Expire Date") }}" autocomplete="off">
                                            </div>
                                        </div>

                                        @if ($generalSettings['purchase__is_enable_lot_no'] == '1')
                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">{{ __("Lot Number") }}</label>
                                                <input type="text" step="any" class="form-control fw-bold" id="e_lot_number" placeholder="{{ __("Lot Number") }}" autocomplete="off">
                                            </div>
                                        @endif

                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __("Short Description") }}</label>
                                            <input type="text" step="any" class="form-control fw-bold" id="e_description" placeholder="{{ __("Short Description") }}" autocomplete="off">
                                        </div>

                                        @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                            <div class="col-xl-2 col-md-4">
                                                <label class="fw-bold">{{ __('Profit(%) & Selling Price') }}</label>
                                                <div class="input-group">
                                                    <input type="number" step="any" class="form-control fw-bold" id="e_profit_margin" placeholder="{{ __("Profit Margine") }}" autocomplete="off">
                                                    <input type="number" step="any" class="form-control fw-bold" id="e_selling_price" placeholder="{{ __("Selling Price Exc. Tax") }}" autocomplete="off">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __("Line-Total") }}</label>
                                            <input readonly type="number" step="any" class="form-control fw-bold" id="e_linetotal" value="0.00" placeholder="0.00" tabindex="-1">
                                        </div>

                                        <div class="col-xl-2 col-md-4">
                                            <a href="#" class="btn btn-sm btn-success me-2" id="add_item">{{ __("Add") }}</a>
                                            <a href="#" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger">{{ __("Reset") }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="sale-item-sec">
                                    <div class="sale-item-inner">
                                        <div class="table-responsive">
                                            <table class="display data__table table-striped">
                                                <thead class="staky">
                                                    <tr>
                                                        <th class="text-start">{{ __("Product") }}</th>
                                                        <th class="text-start">{{ __("Quantity") }}</th>
                                                        <th class="text-start">{{ __("Unit Cost(Exc. Tax)") }}</th>
                                                        <th class="text-start">{{ __("Unit Discount") }}</th>
                                                        <th class="text-start">{{ __("Unit Tax") }}</th>
                                                        <th class="text-start">{{ __('Net Unit Cost (Inc. Tax)') }}</th>
                                                        <th class="text-start">{{ __("Line-Total") }}</th>

                                                        @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                                            <th class="text-start">{{ __("Profit(%)") }}</th>
                                                            <th class="text-start">{{ __("Selling Price(Exc. Tax)") }}</th>
                                                        @endif
                                                        <th class="text-start"><i class="fas fa-trash-alt"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="purchase_list">
                                                    @php
                                                        $itemUnitsArray = [];
                                                    @endphp
                                                    @foreach ($purchase->purchaseProducts as $purchaseProduct)
                                                        @php
                                                            $variant = $purchaseProduct->variant ? ' - '.$purchaseProduct->variant->variant_name : '';
                                                            $variantId = $purchaseProduct->variant_id ? $purchaseProduct->variant_id : 'noid';

                                                            if (isset($purchaseProduct->product_id)) {
                                                                $itemUnitsArray[$purchaseProduct->product_id][] = [
                                                                    'unit_id' => $purchaseProduct->product->unit->id,
                                                                    'unit_name' => $purchaseProduct->product->unit->name,
                                                                    'unit_code_name' => $purchaseProduct->product->unit->code_name,
                                                                    'base_unit_multiplier' => 1,
                                                                    'multiplier_details' => '',
                                                                    'is_base_unit' => 1,
                                                                ];
                                                            }
                                                        @endphp

                                                        <tr id="select_item">
                                                            <td class="text-start">
                                                                <span id="span_item_name">{{ $purchaseProduct->product->name . $variant }}</span>
                                                                <input type="hidden" id="item_name" value="{{ $purchaseProduct->product->name . $variant }}">
                                                                <input type="hidden" name="descriptions[]" id="description" value="{{ $purchaseProduct->description }}">
                                                                <input type="hidden" name="product_ids[]" id="product_id" value="{{ $purchaseProduct->product_id }}">
                                                                <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                                <input type="hidden" name="purchase_product_ids[]" value="{{ $purchaseProduct->id }}">
                                                                <input type="hidden" id="{{ $purchaseProduct->product_id.$variantId }}" value="{{ $purchaseProduct->product_id.$variantId }}">
                                                            </td>

                                                            <td class="text-start">
                                                                <span id="span_quantity_unit" class="fw-bold">{{ $purchaseProduct->quantity . '/' . $purchaseProduct?->unit?->name }}</span>
                                                                <input type="hidden" name="quantities[]" id="quantity" value="{{ $purchaseProduct->quantity }}">
                                                                <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $purchaseProduct->unit_id }}">
                                                                @if ($generalSettings['purchase__is_enable_lot_no'] == '1')

                                                                    <p class="p-0 m-0 fw-bold">{{ __("Lot Number") }} : <span id="span_lot_number">{{ $purchaseProduct?->lot_number }}</span>
                                                                    <input type="hidden" name="lot_numbers[]" id="lot_number" value="{{ $purchaseProduct?->lot_number }}">
                                                                @endif
                                                            </td>

                                                            <td class="text-start">
                                                                @php
                                                                   $has_batch_no_expire_date = $purchaseProduct?->product?->has_batch_no_expire_date;
                                                                @endphp
                                                                <span id="span_unit_cost_exc_tax" class="fw-bold">{{ $purchaseProduct->unit_cost_exc_tax }}</span>
                                                                <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $purchaseProduct->unit_cost_exc_tax }}">
                                                                <p class="p-0 m-0 fw-bold">{{ __("Batch No/Exprie Date") }}: <span id="span_batch_expire_date"> {{ ($has_batch_no_expire_date == 1 ? $purchaseProduct->batch_number . '|' . ($purchaseProduct->expire_date ? date($generalSettings['business__date_format'], strtotime($purchaseProduct->expire_date)) : '') : 'N/a') }}</span>
                                                                <input type="hidden" name="batch_numbers[]" id="batch_number" value="{{ $purchaseProduct->batch_number }}">
                                                                <input type="hidden" name="expire_dates[]" id="expire_date" value="{{ ($purchaseProduct->expire_date ? date($generalSettings['business__date_format'], strtotime($purchaseProduct->expire_date)) : '') }}">
                                                                <input type="hidden" id="has_batch_no_expire_date" value="{{ $has_batch_no_expire_date }}">
                                                            </td>

                                                            <td class="text-start">
                                                                <span id="span_discount_amount" class="fw-bold">{{ $purchaseProduct->unit_discount_amount }}</span>
                                                                <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $purchaseProduct->unit_discount_type }}">
                                                                <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $purchaseProduct->unit_discount }}">
                                                                <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $purchaseProduct->unit_discount_amount }}">
                                                                <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ $purchaseProduct->unit_cost_with_discount }}">
                                                                <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $purchaseProduct->subtotal }}">
                                                            </td>

                                                            <td class="text-start">
                                                                <span id="span_tax_percent" class="fw-bold">{{ $purchaseProduct->unit_tax_percent.'%' }}</span>
                                                                <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $purchaseProduct->tax_ac_id }}">
                                                                <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $purchaseProduct->tax_type }}">
                                                                <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $purchaseProduct->unit_tax_percent }}">
                                                                <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $purchaseProduct->unit_tax_amount }}">
                                                            </td>

                                                            <td class="text-start">
                                                                <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $purchaseProduct->net_unit_cost }}</span>
                                                                <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $purchaseProduct->net_unit_cost }}">
                                                                <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ $purchaseProduct->net_unit_cost }}">
                                                            </td>

                                                            <td class="text-start">
                                                                <span id="span_linetotal" class="fw-bold">{{ $purchaseProduct->line_total }}</span>
                                                                <input type="hidden" name="linetotals[]" id="linetotal" value="{{ $purchaseProduct->line_total }}">
                                                            </td>

                                                            @if ($generalSettings['purchase__is_edit_pro_price'] == '1')

                                                                <td class="text-start">
                                                                    <span id="span_profit" class="fw-bold">{{ $purchaseProduct->profit_margin }}</span>
                                                                    <input type="hidden" name="profits[]" id="profit" value="{{ $purchaseProduct->profit_margin }}">
                                                                </td>

                                                                <td class="text-start">
                                                                    <span id="span_selling_price" class="fw-bold">{{ $purchaseProduct->selling_price }}</span>
                                                                    <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ $purchaseProduct->selling_price }}">
                                                                </td>
                                                            @endif

                                                            <td class="text-start">
                                                                <a href="#" id="remove_product_btn" tabindex="-1"><i class="fas fa-trash-alt text-danger mt-2"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row g-3 py-1">
                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Total Item") }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="{{ $purchase->total_item }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __("Total Quantity") }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="{{ $purchase->total_qty }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __("Net Total Amount") }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" name="net_total_amount" step="any" id="net_total_amount" class="form-control fw-bold" value="{{ $purchase->net_total_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __("Purchase Discount") }}</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select name="order_discount_type" class="form-control" id="order_discount_type" data-next="order_discount">
                                                                    <option {{ $purchase->order_discount_type == 1 ? 'SELECTED' : '' }} value="1">{{ __("Fixed") }}(0.00)</option>
                                                                    <option {{ $purchase->order_discount_type == 2 ? 'SELECTED' : '' }} value="2">{{ __("Percentage") }}(%)</option>
                                                                </select>

                                                                <input name="order_discount" type="number" class="form-control fw-bold" id="order_discount" data-next="purchase_tax_ac_id" value="{{ $purchase->order_discount }}">
                                                            </div>

                                                            <input name="order_discount_amount" type="number" step="any" class="d-hide" id="order_discount_amount" value="{{ $purchase->order_discount_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __("Purchase Tax") }}</b></label>
                                                        <div class="col-8">
                                                            <select name="purchase_tax_ac_id" class="form-control" id="purchase_tax_ac_id" data-next="shipment_charge">
                                                                <option data-purchase_tax_percent="0.00" value="">@lang('menu.no_tax')</option>
                                                                @foreach ($taxAccounts as $taxAccount)
                                                                    <option {{ $taxAccount->id == $purchase->purchase_tax_ac_id ? 'SELECTED' : '' }} data-purchase_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                        {{ $taxAccount->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="number" step="any" name="purchase_tax_percent" class="d-none" id="purchase_tax_percent" value="{{ $purchase->purchase_tax_percent }}">
                                                            <input name="purchase_tax_amount" type="number" step="any" class="d-hide" id="purchase_tax_amount" value="{{ $purchase->purchase_tax_amount }}" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __("Shipment Charge") }}</b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_charge" type="number" class="form-control fw-bold" id="shipment_charge" value="{{ $purchase->shipment_charge }}" data-next="purchase_tax">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>@lang('menu.shipment_details')</b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_details" type="text" class="form-control" id="shipment_details" data-next="purchase_note" value="{{ $purchase->shipment_details }}" placeholder="@lang('menu.shipment_details')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>{{ __("Total Invoice Amount") }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="total_purchase_amount" id="total_purchase_amount" class="form-control fw-bold" value="{{ $purchase->total_purchase_amount }}" tabindex="-1">
                                                            <input type="hidden" name="purchase_ledger_amount" id="purchase_ledger_amount" value="0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __("Paying Amount") }}</b> <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="paying_amount" class="form-control fw-bold" id="paying_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __("Payment Method") }}<span class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                                                @foreach ($methods as $method)
                                                                    <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method ->id }}">
                                                                        {{ $method->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_payment_method_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Credit A/c') }} <span class="text-danger">*</span></b></label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control" id="account_id" data-next="payment_note">
                                                                @foreach ($accounts as $ac)
                                                                    @if ($ac->is_bank_account == 1 && $ac->has_bank_access_branch == 0)
                                                                        @continue
                                                                    @endif

                                                                    <option value="{{ $ac->id }}">
                                                                        @php
                                                                            $acNo = $ac->account_number ? ', A/c No : '.$ac->account_number : '';
                                                                            $bank = $ac?->bank ? ', Bank : '.$ac?->bank?->name : '';
                                                                        @endphp
                                                                        {{ $ac->name . $acNo . $bank}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_account_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __("Payment Note") }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="purchase_note" placeholder="{{ __("Payment Note") }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Purchase Note') }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="purchase_note" id="purchase_note" class="form-control" data-next="save_and_print" value="{{ $purchase->purchase_note }}" placeholder="{{ __('Purchase Note') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Current Balance') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="text" name="current_balance" class="form-control fw-bold" id="current_balance" value="0.00" placeholder="{{ __('0.00') }}">
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
                </section>

                <div class="submit_button_area">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i
                                    class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                                <button id="save" class="btn btn-sm btn-success submit_button float-end">@lang('menu.save_changes')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (auth()->user()->can('product_add'))
        <div class="modal fade" id="addQuickProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif
@endsection
@push('scripts')
    @include('purchase.purchases.partials.purchaseEditJsScript')
    <script>
        var itemUnitsArray = @json($itemUnitsArray);
        $('select').on('select2:close', function (e) {

            var nextId = $(this).data('next');

            setTimeout(function () {

                $('#'+nextId).focus();
            }, 100);
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#'+nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e){

            var nextId = $(this).data('next');

            if (e.which == 13) {

                if (nextId == 'warehouse_id' && $('#warehouse_id').val() == undefined) {

                    $('#date').focus().select();
                    return;
                }

                if ($(this).attr('id') == 'paying_amount' && ($('#paying_amount').val() == 0 ||  $('#paying_amount').val() == '' )) {

                    $('#save_and_print').focus().select();
                    return;
                }

                $('#'+nextId).focus().select();
            }
        });
    </script>
@endpush
