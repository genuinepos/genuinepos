@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .input-group-text-sale {
            font-size: 7px !important;
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
@endpush
@section('title', auth()->user()->branch_id ? 'Receive Stock From Store' : 'Receive Stock From Company')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>
                        @if (auth()->user()->branch_id)
                            {{ __('Receive Stock From Store') }}
                        @else
                            {{ __('Receive Stock From Company') }}
                        @endif
                    </h6>
                </div>

                <div class="col-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Bank') }}</a>
                </div>
            </div>
        </div>
        <div class="p-1">
            <form id="receive_from_branch_form" action="{{ route('receive.stock.from.branch.receive', $transferStock->id) }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Send From') }}</b></label>
                                        <div class="col-7">
                                            @php
                                                $sendFrom = '';
                                                if ($transferStock?->senderBranch) {
                                                    if ($transferStock?->senderBranch?->parentBranch) {
                                                        $sendFrom = $transferStock?->senderBranch?->parentBranch?->name . '(' . $transferStock?->senderBranch?->area_name . ')';
                                                    } else {
                                                        $sendFrom = $transferStock?->senderBranch?->area_name . '(' . $transferStock?->senderBranch?->area_name . ')';
                                                    }
                                                } else {
                                                    $sendFrom = $generalSettings['business_or_shop__business_name'];
                                                }
                                            @endphp

                                            <input readonly type="text" class="form-control fw-bold" value="{{ $sendFrom }}">
                                        </div>
                                    </div>

                                    @if ($transferStock?->senderWarehouse)
                                        <div class="input-group">
                                            <label class="col-5"><b>{{ __('Send At') }}</b></label>
                                            <div class="col-7">
                                                @php
                                                    $sendAt = $transferStock?->senderWarehouse?->warehouse_name . '-(' . $transferStock?->senderWarehouse?->warehouse_code . ')-(WH)';
                                                @endphp

                                                <input readonly type="text" class="form-control fw-bold" value="{{ $sendAt }}">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Send To') }}</b></label>
                                        <div class="col-7">
                                            @php
                                                $sendTo = '';
                                                if ($transferStock?->receiverBranch) {
                                                    if ($transferStock?->receiverBranch?->parentBranch) {
                                                        $sendTo = $transferStock?->receiverBranch?->parentBranch?->name . '(' . $transferStock?->receiverBranch?->area_name . ')';
                                                    } else {
                                                        $sendTo = $transferStock?->receiverBranch?->area_name . '(' . $transferStock?->receiverBranch?->area_name . ')';
                                                    }
                                                } else {
                                                    $sendTo = $generalSettings['business_or_shop__business_name'];
                                                }
                                            @endphp

                                            <input readonly type="text" class="form-control fw-bold" value="{{ $sendTo }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Received Date') }}</b></label>
                                        <div class="col-7">
                                            <input readonly type="text" name="receive_date" class="form-control fw-bold" id="receive_date" value="{{ $transferStock?->receive_date ? date($generalSettings['business_or_shop__date_format'], strtotime($transferStock->receive_date)) : date($generalSettings['business_or_shop__date_format']) }}" data-next="search_product" autocomplete="off">
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
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="sale-item-sec">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="display data__table table sale-product-table">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">{{ __('Product') }}</th>
                                                                <th class="text-start">{{ __('Unit') }}</th>
                                                                <th class="text-start">{{ __('Stock Location') }}</th>
                                                                <th class="text-start">{{ __('Send Qty') }}</th>
                                                                <th class="text-start">{{ __('Received Qty') }}</th>
                                                                <th class="text-start">{{ __('Pending Qty') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="transfer_product_list">

                                                            @foreach ($transferStock->transferStockProducts as $transferStockProduct)
                                                                @php
                                                                    $variant = $transferStockProduct?->variant ? ' - ' . $transferStockProduct?->variant?->variant_name : '';

                                                                    $productCode = $transferStockProduct?->variant ? $transferStockProduct?->variant?->variant_code : $transferStockProduct->product->product_code;

                                                                    $variantId = $transferStockProduct->variant_id ? $transferStockProduct->variant_id : 'noid';
                                                                @endphp
                                                                <tr id="select_item">
                                                                    <td class="text-start">
                                                                        <span class="product_name">{{ $transferStockProduct->product->name . $variant . ' (' . $productCode . ')' }}</span>
                                                                        <input type="hidden" id="unit_cost_inc_tax" value="{{ $transferStockProduct->unit_cost_inc_tax }}">
                                                                        <input type="hidden" name="transfer_stock_product_ids[]" value="{{ $transferStockProduct->id }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span class="fw-bold">{{ $transferStockProduct?->unit?->name }}</span>
                                                                    </td>

                                                                    <td class="text-start fw-bold">
                                                                        @php
                                                                            $storeLocation = '';
                                                                            if ($transferStock?->receiverBranch) {
                                                                                if ($transferStock?->receiverBranch) {
                                                                                    $storeLocation = $transferStock?->receiverBranch?->parentBranch?->name . '(' . $transferStock?->receiverBranch?->area_name . ')';
                                                                                } else {
                                                                                    $storeLocation = $transferStock?->receiverBranch?->name . '(' . $transferStock?->receiverBranch?->area_name . ')';
                                                                                }
                                                                            } else {
                                                                                $storeLocation = $generalSettings['business_or_shop__business_name'];
                                                                            }
                                                                        @endphp
                                                                        {{ $storeLocation }}
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_send_qty" class="fw-bold">{{ $transferStockProduct->send_qty }}</span>
                                                                        <input type="hidden" id="send_qty" value="{{ $transferStockProduct->send_qty }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <input type="number" step="any" name="received_quantities[]" class="form-control text-success" id="received_qty" value="{{ $transferStockProduct->received_qty }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_pending_qty" class="fw-bold text-danger">{{ $transferStockProduct->pending_qty }}</span>
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
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Received Stock Value') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" step="any" name="received_stock_value" class="form-control fw-bold" id="received_stock_value" value="{{ $transferStock->received_stock_value }}" tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Receiver Note') }}</b></label>
                                        <div class="col-8">
                                            <input name="receiver_note" type="text" class="form-control" id="receiver_note" data-next="save_changes" value="{{ $transferStock->receiver_note }}" placeholder="{{ __('Receiver Note') }}">
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
                                <button type="button" id="save_changes" class="btn btn-success submit_button">{{ __('Comfirm') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('transfer_stocks.receive_stocks.from_branch.js_partial.js')
@endpush
