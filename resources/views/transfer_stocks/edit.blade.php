@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .input-group-text-sale {
            font-size: 7px !important;
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
            border: 1px solid #706a6d;
            margin-top: 1px;
            border-radius: 0px;
        }

        .select_area ul {
            list-style: none;
            margin-bottom: 0;
            padding: 0px 2px;
        }

        .select_area ul li a {
            color: #000000;
            text-decoration: none;
            font-size: 11px;
            padding: 2px 2px;
            display: block;
            border: 1px solid lightgray;
            margin: 2px 0px;
        }

        .select_area ul li a:hover {
            background-color: #999396;
            color: #fff;
        }

        .selectProduct {
            background-color: #746e70 !important;
            color: #fff !important;
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

        .checkbox_input_wrap {
            text-align: right;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('title', 'Edit Transfer Stock - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Edit Tranfer Stock') }}</h6>
                </div>

                <div class="col-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Bank') }}</a>
                </div>
            </div>
        </div>
        <div class="p-1">
            <form id="edit_transfer_branch_to_branch_form" action="{{ route('transfer.stocks.update', $transferStock->id) }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Sender Store/Company') }}</b></label>
                                        <div class="col-7">
                                            <input type="hidden" data-sender_currency="{{ $transferStock?->senderBranch?->branchCurrency?->country . '-' . $transferStock?->senderBranch?->branchCurrency?->currency . '-' . $transferStock?->senderBranch?->branchCurrency?->code . '-' . $transferStock?->senderBranch?->branchCurrency?->symbol }}" data-sender_currency_rate="{{ $transferStock?->senderBranch?->branchCurrency?->currency_rate }}" name="branch_id" id="branch_id" value="{{ $transferStock?->senderBranch?->id }}" data-sender_is_base_currency="{{ $transferStock?->senderBranch?->id == null ? 1 : 0 }}" data-sender_currency_type="{{ $transferStock?->senderBranch?->branchCurrency?->type }}">
                                            @php
                                                $branchName = '';
                                                if ($transferStock?->senderBranch) {
                                                    if ($transferStock?->senderBranch?->parent_branch_id) {
                                                        $branchName = $transferStock?->senderBranch?->parentBranch?->name . '(' . $transferStock?->senderBranch?->area_name . ')';
                                                    } else {
                                                        $branchName = $transferStock?->senderBranch?->name . '(' . $transferStock?->senderBranch?->area_name . ')';
                                                    }
                                                } else {
                                                    $branchName = $generalSettings['business_or_shop__business_name'];
                                                }
                                            @endphp

                                            <input readonly type="text" class="form-control fw-bold" value="{{ $branchName }}">
                                        </div>
                                    </div>

                                    @if ($generalSettings['subscription']->features['warehouse_count'] > 0)
                                        <div class="input-group mt-1">
                                            <label class="col-5"><b>{{ __('Send At') }}</b></label>
                                            <div class="col-7">
                                                <select name="sender_warehouse_id" class="form-control" id="sender_warehouse_id" data-next="receiver_branch_id" autofocus>
                                                    <option value="">{{ __('Select Warehouse') }}</option>
                                                    @foreach ($warehouses as $w)
                                                        <option {{ $w->id == $transferStock->sender_warehouse_id ? 'SELECTED' : '' }} value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if ($transferStock->total_received_qty == 0)

                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class="col-5"><b>{{ __('Receiver Store/Company') }}</b> <span class="text-danger">*</span></label>
                                            <div class="col-7">
                                                <select name="receiver_branch_id" class="form-control" id="receiver_branch_id" data-next="receiver_warehouse_id" autofocus>
                                                    <option value="" class="fw-bold">{{ __('Select Receiver Store/Company') }}</option>
                                                    @if ($generalSettings['subscription']->has_business == 1 || $transferStock->receiver_branch_id == null)
                                                        <option {{ $transferStock->receiver_branch_id == null ? 'SELECTED' : '' }} data-receiver_currency="---" data-receiver_is_base_currency="1" data-receiver_currency_rate="0" data-receiver_currency_type="1" data-receiver_currency_code="{{ $generalSettings['base_currency_code'] }}" data-receiver_currency_symbol="{{ $generalSettings['base_currency_symbol'] }}" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                    @endif

                                                    @foreach ($branches as $branch)
                                                        <option data-receiver_currency="{{ $transferStock?->receiverBranch?->branchCurrency?->country . '-' . $transferStock?->receiverBranch?->branchCurrency?->currency . '-' . $transferStock?->receiverBranch?->branchCurrency?->code . '-' . $transferStock?->receiverBranch?->branchCurrency?->symbol }}" data-receiver_is_base_currency="{{ $transferStock?->receiverBranch?->id == null ? 1 : 0 }}" data-receiver_currency_symbol="{{ $transferStock?->receiverBranch?->branchCurrency?->symbol ?? $generalSettings['base_currency_symbol'] }}" data-receiver_currency_code="{{ $transferStock?->receiverBranch?->branchCurrency?->code ?? $generalSettings['base_currency_code'] }}" data-receiver_currency_rate="{{ $transferStock?->receiverBranch?->branchCurrency?->currency_rate }}" data-receiver_currency_type="{{ $transferStock?->receiverBranch?->branchCurrency?->type }}" {{ $branch->id == $transferStock->receiver_branch_id ? 'SELECTED' : '' }}
                                                            value="{{ $branch->id }}">
                                                            @php
                                                                $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                $branchCode = '-' . $branch->branch_code;
                                                            @endphp
                                                            {{ $branchName . $areaName . $branchCode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="error error_receiver_warehouse_id"></span>
                                            </div>
                                        </div>

                                        @if ($generalSettings['subscription']->features['warehouse_count'] > 0)
                                            <div class="input-group mt-1">
                                                <label class="col-5"><b>{{ __('Receive At') }}</b></label>
                                                <div class="col-7">
                                                    <select name="receiver_warehouse_id" class="form-control" id="receiver_warehouse_id" data-next="date" autofocus>
                                                        <option value="">{{ __('Select Warehouse') }}</option>
                                                        @foreach ($selectedBranchWarehouses as $warehouse)
                                                            <option {{ $warehouse->id == $transferStock->receiver_warehouse_id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name . '/' . $warehouse->warehouse_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    @php
                                        $receiverBranchName = '';
                                        if ($transferStock?->receiverBranch) {
                                            if ($transferStock?->receiverBranch?->parent_branch_id) {
                                                $receiverBranchName = $transferStock?->receiverBranch?->parentBranch?->name . '(' . $transferStock?->receiverBranch?->area_name . ')';
                                            } else {
                                                $receiverBranchName = $transferStock?->receiverBranch?->name . '(' . $transferStock?->receiverBranch?->area_name . ')';
                                            }
                                        } else {
                                            $receiverBranchName = $generalSettings['business_or_shop__business_name'];
                                        }
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <label class="col-5"><b>{{ __('Receiver Store/Company') }}</b></label>
                                            <div class="col-7">
                                                <input readonly type="text" class="form-control fw-bold" value="{{ $receiverBranchName }}" autocomplete="off">
                                                <input type="hidden" data-receiver_currency="{{ $transferStock?->receiverBranch?->branchCurrency?->country . '-' . $transferStock?->receiverBranch?->branchCurrency?->currency . '-' . $transferStock?->receiverBranch?->branchCurrency?->code . '-' . $transferStock?->receiverBranch?->branchCurrency?->symbol }}" data-receiver_is_base_currency="{{ $transferStock?->receiverBranch?->id == null ? 1 : 0 }}" data-receiver_currency_symbol="{{ $transferStock?->receiverBranch?->branchCurrency?->symbol ?? $generalSettings['base_currency_symbol'] }}" data-receiver_currency_code="{{ $transferStock?->receiverBranch?->branchCurrency?->code ?? $generalSettings['base_currency_code'] }}" data-receiver_currency_rate="{{ $transferStock?->receiverBranch?->branchCurrency?->currency_rate }}" data-receiver_currency_type="{{ $transferStock?->receiverBranch?->branchCurrency?->type }}" name="receiver_branch_id" class="form-control fw-bold" id="receiver_branch_id"
                                                    value="{{ $transferStock->receiver_branch_id ? $transferStock->receiver_branch_id : 'NULL' }}">
                                            </div>
                                        </div>

                                        @if ($generalSettings['subscription']->features['warehouse_count'] > 0 && $transferStock->receiver_warehouse_id)
                                            <div class="input-group mt-1">
                                                <label class="col-5"><b>{{ __('Receive At') }}</b></label>
                                                <div class="col-7">
                                                    <input readonly type="text" class="form-control fw-bold" value="{{ $transferStock?->receiverWarehouse?->warehouse_name . '-(' . $transferStock?->receiverWarehouse?->warehouse_code . ')' }}" autocomplete="off">
                                                    <input type="hidden" name="receiver_warehouse_id" class="form-control fw-bold" value="{{ $transferStock->receiver_warehouse_id }}">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Transfer Date') }}</b></label>
                                        <div class="col-7">
                                            <input required type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business_or_shop__date_format'], strtotime($transferStock->date)) }}" data-next="search_product" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card mb-1 p-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row g-xxl-4 align-items-end">
                                    <div class="col-xl-6">
                                        <div class="searching_area" style="position: relative;">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-barcode text-dark input_f"></i>
                                                    </span>
                                                </div>

                                                <input type="text" name="search_product" class="form-control fw-bold" id="search_product" placeholder="{{ __('Search Product By Name/Code') }}" autocomplete="off">
                                            </div>

                                            <div class="select_area">
                                                <ul id="list" class="variant_list_area"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-xxl-4 align-items-end">
                                    <div class="hidden_fields">
                                        <input type="hidden" id="e_unique_id">
                                        <input type="hidden" id="e_item_name">
                                        <input type="hidden" id="e_product_id">
                                        <input type="hidden" id="e_variant_id">
                                        <input type="hidden" id="e_current_qty">
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label class="fw-bold">{{ __('Send Quantity') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" class="form-control w-60 fw-bold" id="e_quantity" placeholder="{{ __('Send Quantity') }}" value="0.00">
                                            <select id="e_unit_id" class="form-control w-40">
                                                <option value="">{{ __('Unit') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label class="fw-bold">{{ __('Unit Cost(Inc. Tax)') }}</label>
                                        <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_inc_tax" placeholder="{{ __('Unit Cost(Inc. Tax)') }}" value="0.00">
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label class="fw-bold">{{ __('Subtotal') }}</label>
                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                    </div>

                                    <div class="col-xl-1 col-md-6">
                                        <div class="btn-box-2">
                                            <a href="#" class="btn btn-sm btn-success" id="add_item">{{ __('Add') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="sale-item-sec">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="display data__table table sale-product-table">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">{{ __('Product') }}</th>
                                                                <th class="text-start">{{ __('Send Qty') }}</th>
                                                                <th class="text-start">{{ __('Unit') }}</th>
                                                                <th class="text-start">{{ __('Unit Cost Inc. Tax') }}</th>
                                                                <th class="text-start">{{ __('Subtotal') }}</th>
                                                                <th><i class="fas fa-trash-alt text-danger"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="transfer_product_list">
                                                            @php
                                                                $itemUnitsArray = [];
                                                            @endphp
                                                            @foreach ($transferStock->transferStockProducts as $transferStockProduct)
                                                                @php
                                                                    if (isset($transferStockProduct->product_id)) {
                                                                        $itemUnitsArray[$transferStockProduct->product_id][] = [
                                                                            'unit_id' => $transferStockProduct->product->unit->id,
                                                                            'unit_name' => $transferStockProduct->product->unit->name,
                                                                            'unit_code_name' => $transferStockProduct->product->unit->code_name,
                                                                            'base_unit_multiplier' => 1,
                                                                            'multiplier_details' => '',
                                                                            'is_base_unit' => 1,
                                                                        ];
                                                                    }

                                                                    $variant = $transferStockProduct?->variant ? ' - ' . $transferStockProduct?->variant?->variant_name : '';

                                                                    $variantId = $transferStockProduct->variant_id ? $transferStockProduct->variant_id : 'noid';

                                                                    $productCode = $transferStockProduct?->variant ? $transferStockProduct?->variant?->variant_code : $transferStockProduct->product->product_code;
                                                                @endphp

                                                                <tr id="select_item">
                                                                    <td class="text-start">
                                                                        <span class="product_name">{{ $transferStockProduct->product->name . $variant . ' (' . $productCode . ')' }}</span>
                                                                        <input type="hidden" id="item_name" value="{{ $transferStockProduct->product->name . $variant . ' (' . $productCode . ')' }}">
                                                                        <input type="hidden" name="product_ids[]" id="product_id" value="{{ $transferStockProduct->product_id }}">
                                                                        <input type="hidden" name="variant_ids[]" id="variant_id" value="{{ $variantId }}">
                                                                        <input type="hidden" name="transfer_stock_product_ids[]" value="{{ $transferStockProduct->id }}">
                                                                        <input type="hidden" class="unique_id" id="{{ $transferStockProduct->product_id . $variantId }}" value="{{ $transferStockProduct->product_id . $variantId }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_quantity" class="fw-bold">{{ $transferStockProduct->send_qty }}</span>
                                                                        <input type="hidden" name="quantities[]" id="quantity" value="{{ $transferStockProduct->send_qty }}">
                                                                        <input type="hidden" id="current_qty" value="{{ $transferStockProduct->send_qty }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_unit" class="fw-bold">{{ $transferStockProduct?->unit?->name }}</span>
                                                                        <input type="hidden" name="unit_ids[]" id="unit_id" value="{{ $transferStockProduct->unit_id }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_unit_cost_inc_tax" class="fw-bold">{{ $transferStockProduct->unit_cost_inc_tax }}</span>
                                                                        <input type="hidden" name="unit_costs_inc_tax[]" id="unit_cost_inc_tax" value="{{ $transferStockProduct->unit_cost_inc_tax }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_subtotal" class="fw-bold">{{ $transferStockProduct->subtotal }}</span>
                                                                        <input type="hidden" name="subtotals[]" id="subtotal" value="{{ $transferStockProduct->subtotal }}" tabindex="-1">
                                                                    </td>

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
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="form_element rounded my-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class=" col-4"><b>{{ __('Total Item & Qty') }}</b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input readonly type="number" step="any" name="total_item" class="form-control fw-bold" id="total_item" value="{{ $transferStock->total_item }}" tabindex="-1">
                                                <input readonly type="number" step="any" name="total_qty" class="form-control fw-bold" id="total_qty" value="{{ $transferStock->total_qty }}" tabindex="-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class=" col-4"><b>{{ __('Total Stock Value') }} :</b> </label>
                                        <div class="col-8">
                                            <input readonly type="number" step="any" name="total_stock_value" class="form-control fw-bold" id="total_stock_value" value="{{ $transferStock->total_stock_value }}" tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-2"><b>{{ __('Note') }}</b></label>
                                        <div class="col-10">
                                            <input name="transfer_note" type="text" class="form-control" id="transfer_note" data-next="save_changes" value="{{ $transferStock->transfer_note }}" placeholder="{{ __('Transfer Note') }}">
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
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>
                                <button type="button" id="save_changes" class="btn btn-success submit_button">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('transfer_stocks.js_partials.transfer_stock_edit_js')
@endpush
