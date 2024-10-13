@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <style>
        .input-group-text {
            font-size: 12px!important;
        }

        .select_area {
            position: relative;
            background: #ffffff;
            box-sizing: border-box;
            position: absolute;
            width: 100%;
            z-index: 9999999;
            padding: 0;
            left: 0%;
            display: none;
            border: 1px solid var(--main-color);
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 4px 4px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 10px;
            padding: 2px 2px;
            display: block;
            border: 1px solid gray;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selected_po {
            background-color: #645f61;
            color: #fff !important;
        }

        .po_search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 3px;
            margin-top: 1px;
        }

        .po_search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 2px;
        }

        .po_search_result ul li a {
            color: #6b6262;
            font-size: 10px;
            display: block;
            padding: 0px 3px;
        }

        .po_search_result ul li a:hover {
            color: var(--white-color);
            background-color: #ada9a9;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }

        .big_amount_field {
            height: 36px;
            font-size: 24px !important;
            margin-bottom: 3px;
        }
    </style>
@endpush
@section('title', 'P/o To Purchase Invoice- ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-7">
                    <div class="name-head">
                        <h6>{{ __('P/o To Purchase Invoice') }}</h6>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="row g-0">
                        <div class="col-md-10">
                            <div class="input-group">
                                <label class="col-4 offset-md-6"><b>{{ __('Print') }}</b></label>
                                <div class="col-2">
                                    <select id="select_print_page_size" class="form-control">
                                        @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                            <option {{ $generalSettings['print_page_size__purchase_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button d-inline"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-1">
            <form id="add_purchase_form" action="{{ route('purchase.order.to.invoice.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" class="hidden" id="action" value="">
                <input type="hidden" name="purchase_order_id" class="hidden" id="purchase_order_id" value="{{ $order?->id }}">
                <input type="hidden" name="print_page_size" id="print_page_size" value="1">
                <section>
                    <div class="form_element rounded mt-0 mb-2">
                        <div class="element-body">
                            <div class="row gx-2">
                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('P/o ID') }}</b></label>
                                        <div class="col-8">
                                            <div style="position: relative;">
                                                <input type="text" id="po_id" class="form-control fw-bold" placeholder="{{ __('Search Purchase Order') }}" value="{{ $order?->invoice_id }}" autocomplete="off">

                                                <div class="po_search_result d-hide">
                                                    <ul id="list" class="list-unstyled"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Supplier') }}</b></label>
                                        <input readonly type="text" id="supplier_name" class="form-control fw-bold" value="{{ $order?->supplier?->name . ($order?->supplier ? '/' . $order?->supplier?->phone : '') }}" placeholder="{{ __('Supplier Name') }}">
                                        <input type="hidden" name="supplier_account_id" class="hidden" id="supplier_account_id" value="{{ $order?->supplier_account_id }}">
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Closing Balance') }}</b></label>
                                        <input type="text" readonly class="form-control fw-bold text-danger" id="closing_balance" value="{{ isset($accountBalance['closing_balance_in_flat_amount']) ? $accountBalance['closing_balance_in_flat_amount'] : 0 }}">
                                        <input type="hidden" id="default_balance_type" class="hidden" value="{{ $order?->supplier?->group?->default_balance_type }}">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Invoice ID') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" name="invoice_id" id="invoice_id" class="form-control fw-bold" data-next="warehouse_id" value="{{ $invoiceId }}" placeholder="{{ __('Purchase Invoice ID') }}">
                                            <span class="error error_invoice_id"></span>
                                        </div>
                                    </div>

                                    @if ($generalSettings['subscription']->features['warehouse_count'] > 0 && count($warehouses) > 0)
                                        <input name="warehouse_count" value="YES" type="hidden" />
                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>{{ __('Warehouse') }}</b> <span class="text-danger">*</span></label>
                                            <div class="col-8">
                                                <select required class="form-control" name="warehouse_id" id="warehouse_id" data-next="date">
                                                    <option value="">{{ __('Select Warehouse') }}</option>
                                                    @foreach ($warehouses as $w)
                                                        @php
                                                            $isGlobal = $w->is_global == 1 ? ' (' . __('Global Access') . ')' : '';
                                                        @endphp
                                                        <option value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code . $isGlobal }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_warehouse_id"></span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="input-group mt-1">
                                            <label class="col-4"><b>{{ __('Stock Location') }}</b></label>
                                            <div class="col-8">
                                                <input readonly type="text" name="branch_id" class="form-control fw-bold" value="{{ auth()->user()->branch ? auth()->user()->branch->name . '/' . auth()->user()->branch->branch_code : $generalSettings['business_or_shop__business_name'] }}" />
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input required type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business_or_shop__date_format']) }}" data-next="pay_term_number" placeholder="dd-mm-yyyy" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class=" col-4"><b>{{ __('Pay-Term') }}</b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input type="text" name="pay_term_number" class="form-control" id="pay_term_number" data-next="pay_term" placeholder="{{ __('Number') }}">
                                                <select name="pay_term" class="form-control" id="pay_term" data-next="purchase_account_id">
                                                    <option value="">{{ __('Pay-Term') }}</option>
                                                    <option value="1">{{ __('Days') }}</option>
                                                    <option value="2">{{ __('Month') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Purchase A/c') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <select name="purchase_account_id" class="form-control" id="purchase_account_id" data-next="challan_no">
                                                @foreach ($purchaseAccounts as $purchaseAccount)
                                                    <option value="{{ $purchaseAccount->id }}">
                                                        {{ $purchaseAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error error_purchase_account_id"></span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-1">
                                        <label class="col-4"><b>{{ __('Challan No') }}</b></label>
                                        <div class="col-8">
                                            <input type="text" name="challan_no" class="form-control" id="challan_no" data-next="pay_term_number" placeholder="{{ __('Challan No') }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card ps-1 pb-1 pe-1">
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <div class="row align-items-end p-1">
                                    <input type="hidden" id="e_unique_id">
                                    <input type="hidden" id="e_product_name">
                                    <input type="hidden" id="e_product_id">
                                    <input type="hidden" id="e_variant_id">
                                    <input type="hidden" id="e_tax_amount">
                                    <input type="hidden" id="e_unit_cost_with_discount">
                                    <input type="hidden" id="e_subtotal">
                                    <input type="hidden" id="e_unit_cost_inc_tax">
                                    <input type="hidden" id="e_has_batch_no_expire_date">
                                    <input type="hidden" id="e_ordered_quantity">
                                    <input type="hidden" id="e_received_quantity">
                                    <input type="hidden" id="e_current_quantity">

                                    <div class="col-xl-4 col-md-4">
                                        <label class="fw-bold">{{ __('Product Name') }}</label>
                                        <input readonly type="text" class="form-control fw-bold" id="product_name" placeholder="{{ __('Product Name') }}">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Pending Quantity') }}</label>
                                        <div class="input-group">
                                            <input readonly type="number" step="any" class="form-control fw-bold text-danger" id="e_pending_quantity" value="0.00" placeholder="{{ __('0.00') }}" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Receive Quantity') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control fw-bold" id="e_quantity" value="0.00" placeholder="{{ __('0.00') }}" autocomplete="off">
                                            <select id="e_unit_id" class="form-control w-40">
                                                <option value="">{{ __('Unit') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Unit Cost(Exc. Tax)') }}</label>
                                        <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_exc_tax" value="0.00" placeholder="{{ __('0.00') }}" autocomplete="off">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Discount') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_discount" value="0.00" placeholder="{{ __('0.00') }}" autocomplete="off">
                                            <select id="e_discount_type" class="form-control w-40">
                                                <option value="1">{{ __('Fixed') }}(0.00)</option>
                                                <option value="2">{{ __('Percentage') }}(%)</option>
                                            </select>
                                            <input type="hidden" id="e_discount_amount">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Vat/Tax') }}</label>
                                        <div class="input-group">
                                            <select id="e_tax_ac_id" class="form-control w-50">
                                                <option data-product_tax_percent="0.00" value="">{{ __('NoVat/Tax') }}</option>
                                                @foreach ($taxAccounts as $taxAccount)
                                                    <option data-product_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                        {{ $taxAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select id="e_tax_type" class="form-control w-50">
                                                <option value="1">{{ __('Exclusive') }}</option>
                                                <option value="2">{{ __('Inclusive') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-4 batch_no_expire_date_fields d-none">
                                        <label class="fw-bold">{{ __('Batch No & Expire Date') }}</label>
                                        <div class="input-group">
                                            <input readonly type="text" step="any" class="form-control fw-bold" id="e_batch_number" placeholder="{{ __('Batch No') }}" autocomplete="off">
                                            <input readonly type="text" step="any" class="form-control fw-bold" id="e_expire_date" placeholder="{{ __('Expire Date') }}" autocomplete="off">
                                        </div>
                                    </div>

                                    @if ($generalSettings['purchase__is_enable_lot_no'] == '1')
                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __('+
                                             Number') }}</label>
                                            <input type="text" step="any" class="form-control fw-bold" id="e_lot_number" placeholder="{{ __('Lot Number') }}" autocomplete="off">
                                        </div>
                                    @endif

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Short Description') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control fw-bold" id="e_description" placeholder="{{ __('Short Description') }}" autocomplete="off">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text add_button" id="editDescription"><i class="fa-solid fa-text-width w-20"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                        <div class="col-xl-2 col-md-4">
                                            <label class="fw-bold">{{ __('Profit(%) & Selling Price') }}</label>
                                            <div class="input-group">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_profit_margin" placeholder="{{ __('Profit Margin(%)') }}" autocomplete="off">
                                                <input type="number" step="any" class="form-control fw-bold" id="e_selling_price" placeholder="{{ __('Selling Price (Exc. Tax)') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-xl-2 col-md-4">
                                        <label class="fw-bold">{{ __('Linetotal') }}</label>
                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_linetotal" value="0.00" placeholder="{{ __('0.00') }}" tabindex="-1">
                                    </div>

                                    <div class="col-xl-2 col-md-4">
                                        <a href="#" class="btn btn-sm btn-success me-2" id="add_product">{{ __('Add') }}</a>
                                        <a href="#" id="reset_add_or_edit_item_fields" class="btn btn-sm btn-danger">{{ __('Reset') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="sale-item-sec">
                                <div class="sale-item-inner">
                                    <div class="table-responsive">
                                        <table class="display data__table table sale-product-table">
                                            <thead class="staky">
                                                <tr>
                                                    <th class="text-start">{{ __('Product') }}</th>
                                                    <th class="text-start">{{ __('Ordered Qty') }}</th>
                                                    <th class="text-start">{{ __('Pending Qty') }}</th>
                                                    <th class="text-start">{{ __('Received Qty') }}</th>
                                                    <th class="text-start">{{ __('Unit Cost(Exc. Tax)') }}</th>
                                                    <th class="text-start">{{ __('Unit Discount') }}</th>
                                                    <th class="text-start">{{ __('Unit Tax') }}</th>
                                                    <th class="text-start">{{ __('Net Unit Cost (Inc. Tax)') }}</th>
                                                    <th class="text-start">{{ __('Line-Total') }}</th>
                                                    @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                                        <th class="text-start">{{ __('Profit Margine') }}</th>
                                                        <th class="text-start">{{ __('Selling Price(Exc. Tax)') }}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody id="purchase_product_list">
                                                @php
                                                    $itemUnitsArray = [];
                                                @endphp
                                                @foreach ($order?->purchaseOrderProducts ? $order?->purchaseOrderProducts : [] as $orderProduct)
                                                    @php
                                                        $variant = $orderProduct->variant ? ' - ' . $orderProduct->variant->variant_name : '';
                                                        $variantId = $orderProduct->variant_id ? $orderProduct->variant_id : 'noid';

                                                        $productCode = $orderProduct?->variant ? $orderProduct?->variant?->variant_code : $orderProduct->product->product_code;

                                                        if (isset($orderProduct->product_id)) {
                                                            $itemUnitsArray[$orderProduct->product_id][] = [
                                                                'unit_id' => $orderProduct->product->unit->id,
                                                                'unit_name' => $orderProduct->product->unit->name,
                                                                'unit_code_name' => $orderProduct->product->unit->code_name,
                                                                'base_unit_multiplier' => 1,
                                                                'multiplier_details' => '',
                                                                'is_base_unit' => 1,
                                                            ];
                                                        }
                                                    @endphp

                                                    <tr id="select_product">
                                                        <td>
                                                            <span id="span_product_name">{{ $orderProduct->product->name . $variant . ' (' . $productCode . ')' }}</span>
                                                            <input type="hidden" id="product_name" value="{{ $orderProduct->product->name . $variant . ' (' . $productCode . ')' }}">
                                                            <input type="hidden" name="descriptions[]" id="description">
                                                            <input type="hidden" name="product_ids[]" id="product_id" value="{{ $orderProduct->product_id }}">
                                                            <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                            <input type="hidden" name="purchase_order_product_ids[]" value="{{ $orderProduct->id }}">
                                                            <input type="hidden" id="{{ $orderProduct->product_id . $variantId }}" value="{{ $orderProduct->product_id . $variantId }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_ordered_quantity_unit" class="fw-bold">{{ $orderProduct->ordered_quantity . '/' . $orderProduct?->unit?->name }}</span>
                                                            <input type="hidden" id="ordered_quantity" value="{{ $orderProduct->ordered_quantity }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_pending_quantity_unit" class="fw-bold text-danger">{{ $orderProduct->pending_quantity . '/' . $orderProduct?->unit?->name }}</span>
                                                            <input type="hidden" id="pending_quantity" value="{{ $orderProduct->pending_quantity }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_quantity_unit" class="fw-bold">{{ '0.00' . '/' . $orderProduct?->unit?->name }}</span>
                                                            <input type="hidden" id="received_quantity" value="{{ $orderProduct->received_quantity }}">
                                                            <input type="hidden" name="quantities[]" id="quantity" value="0.00">
                                                            <input type="hidden" name="unit_ids[]" step="any" id="unit_id" value="{{ $orderProduct->unit_id }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_unit_cost_exc_tax" class="fw-bold">{{ $orderProduct->unit_cost_exc_tax }}</span>
                                                            <input type="hidden" name="unit_costs_exc_tax[]" id="unit_cost_exc_tax" value="{{ $orderProduct->unit_cost_exc_tax }}">
                                                            <p class="p-0 m-0 fw-bold">{{ __('Batch No/Exprie Date') }}: <span id="span_batch_expire_date"></span></p>
                                                            <input type="hidden" name="batch_numbers[]" id="batch_number">
                                                            <input type="hidden" name="expire_dates[]" id="expire_date">
                                                            <input type="hidden" id="has_batch_no_expire_date">
                                                        </td>

                                                        <td>
                                                            <span id="span_discount_amount" class="fw-bold">{{ $orderProduct->unit_discount_amount }}</span>
                                                            <input type="hidden" name="unit_discount_types[]" id="unit_discount_type" value="{{ $orderProduct->unit_discount_type }}">
                                                            <input type="hidden" name="unit_discounts[]" id="unit_discount" value="{{ $orderProduct->unit_discount }}">
                                                            <input type="hidden" name="unit_discount_amounts[]" id="unit_discount_amount" value="{{ $orderProduct->unit_discount_amount }}">
                                                            <input type="hidden" name="unit_costs_with_discount[]" id="unit_cost_with_discount" value="{{ $orderProduct->unit_cost_with_discount }}">
                                                            <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $orderProduct->subtotal }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_tax_percent" class="fw-bold">{{ $orderProduct->unit_tax_percent . '%' }}</span>
                                                            <input type="hidden" name="tax_ac_ids[]" id="tax_ac_id" value="{{ $orderProduct->tax_ac_id }}">
                                                            <input type="hidden" name="tax_types[]" id="tax_type" value="{{ $orderProduct->unit_tax_type }}">
                                                            <input type="hidden" name="unit_tax_percents[]" id="unit_tax_percent" value="{{ $orderProduct->unit_tax_percent }}">
                                                            <input type="hidden" name="unit_tax_amounts[]" id="unit_tax_amount" value="{{ $orderProduct->unit_tax_amount }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $orderProduct->net_unit_cost }}</span>
                                                            <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $orderProduct->net_unit_cost }}">
                                                            <input type="hidden" name="net_unit_costs[]" id="net_unit_cost" value="{{ $orderProduct->net_unit_cost }}">
                                                        </td>

                                                        <td>
                                                            <span id="span_linetotal" class="fw-bold">0.00</span>
                                                            <input type="hidden" name="linetotals[]" id="linetotal" value="0.00">
                                                        </td>

                                                        @if ($generalSettings['purchase__is_edit_pro_price'] == '1')
                                                            <td>
                                                                <span id="span_profit" class="fw-bold">{{ $orderProduct->profit_margin }}</span>
                                                                <input type="hidden" name="profits[]" id="profit" value="{{ $orderProduct->profit_margin }}">
                                                            </td>

                                                            <td>
                                                                <span id="span_selling_price" class="fw-bold">{{ $orderProduct->selling_price }}</span>
                                                                <input type="hidden" name="selling_prices[]" id="selling_price" value="{{ $orderProduct->selling_price }}">
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row g-3 py-2">
                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Total Item & Received Quantity') }}</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <input readonly name="total_item" type="number" step="any" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                                <input readonly name="total_qty" type="number" step="any" class="form-control fw-bold" id="total_qty" value="0.00" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-group mt-1">
                                                    <label class=" col-4"><b>{{ __('Net Total Amount') }}</b> {{ $generalSettings['business_or_shop__currency_symbol'] }}</label>
                                                    <div class="col-8">
                                                        <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Purchase Discount') }}</b></label>
                                                        <div class="col-8">
                                                            <div class="input-group">
                                                                <select name="order_discount_type" class="form-control" id="order_discount_type" data-next="order_discount">
                                                                    <option value="1">{{ __('Fixed') }}(0.00)</option>
                                                                    <option value="2">{{ __('Percentage') }}(%)</option>
                                                                </select>

                                                                <input name="order_discount" type="number" class="form-control fw-bold" id="order_discount" value="0.00" data-next="purchase_tax_ac_id">
                                                                <input name="order_discount_amount" type="number" step="any" class="d-hide" id="order_discount_amount" value="0.00" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Purchase Tax') }}</b></label>
                                                        <div class="col-8">
                                                            <select name="purchase_tax_ac_id" class="form-control" id="purchase_tax_ac_id" data-next="shipment_charge">
                                                                <option data-purchase_tax_percent="0.00" value="">@lang('menu.no_tax')</option>
                                                                @foreach ($taxAccounts as $taxAccount)
                                                                    <option data-purchase_tax_percent="{{ $taxAccount->tax_percent }}" value="{{ $taxAccount->id }}">
                                                                        {{ $taxAccount->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="number" step="any" name="purchase_tax_percent" class="d-none" id="purchase_tax_percent" value="0.00">
                                                            <input name="purchase_tax_amount" type="number" step="any" class="d-hide" id="purchase_tax_amount" value="0.00" tabindex="-1">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Shipment Charge') }}</b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_charge" type="number" class="form-control fw-bold" id="shipment_charge" value="0.00" data-next="shipment_details">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Shipment Details') }}</b></label>
                                                        <div class="col-8">
                                                            <input name="shipment_details" type="text" class="form-control" id="shipment_details" data-next="purchase_note" placeholder="{{ __('Shipment Details') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Purchase Note') }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="purchase_note" id="purchase_note" class="form-control" data-next="paying_amount" placeholder="{{ __('Purchase Note') }}">
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
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __('Total Invoice Amount') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="number" step="any" name="total_purchase_amount" id="total_purchase_amount" class="form-control fw-bold" value="0.00" tabindex="-1">
                                                            <input type="hidden" name="purchase_ledger_amount" id="purchase_ledger_amount" value="0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Paying Amount') }}</b> <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="paying_amount" class="form-control fw-bold big_amount_field" id="paying_amount" value="0.00" data-next="payment_method_id" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Payment Method') }}<span class="text-danger">*</span></b> </label>
                                                        <div class="col-8">
                                                            <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                                                @foreach ($methods as $method)
                                                                    <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">{{ $method->name }}
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
                                                                            $acNo = $ac->account_number ? ', A/c No : ' . $ac->account_number : '';
                                                                            $bank = $ac?->bank ? ', Bank : ' . $ac?->bank?->name : '';
                                                                        @endphp
                                                                        {{ $ac->name . $acNo . $bank }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_account_id"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __('Payment Note') }}</b> </label>
                                                        <div class="col-8">
                                                            <input type="text" name="payment_note" class="form-control" id="payment_note" data-next="save_and_print" placeholder="{{ __('Payment Note') }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Current Balance') }}</b></label>
                                                        <div class="col-8">
                                                            <input readonly type="text" name="current_balance" class="form-control text-danger fw-bold" id="current_balance" value="0.00" placeholder="{{ __('0.00') }}">
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

                <div class="row justify-content-center">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>
                            <button type="submit" id="save_and_print" value="1" class="btn btn-success submit_button">{{ __('Save & Print') }}</button>
                            <button type="submit" id="save" value="2" class="btn btn-success submit_button">{{ __('Save') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <input type="hidden" name="search_product" id="search_product" placeholder="{{ __('Search Product By Name/Code') }}" autocomplete="off">

    <div class="modal fade" id="editDescriptionModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <form id="description_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ __('Short Description') }}</h6>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea class="form-control fw-bold" id="edit_description" cols="30" rows="10"></textarea>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="btn-loading">
                                    <button type="submit" id="description_save" class="btn btn-sm btn-success">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('purchase.order_to_invoice.js_partial.js')
@endpush
