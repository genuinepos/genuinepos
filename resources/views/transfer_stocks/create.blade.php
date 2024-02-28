@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text { font-size: 12px !important; }

        .input-group-text-sale { font-size: 7px !important; }

        .select_area { position: relative; background: #ffffff; box-sizing: border-box; position: absolute; width: 100%; z-index: 9999999; padding: 0; left: 0%; display: none; border: 1px solid #706a6d; margin-top: 1px; border-radius: 0px; }

        .select_area ul { list-style: none; margin-bottom: 0; padding: 0px 2px; }

        .select_area ul li a { color: #000000; text-decoration: none; font-size: 11px; padding: 2px 2px; display: block; border: 1px solid lightgray; margin: 2px 0px; }

        .select_area ul li a:hover { background-color: #999396; color: #fff; }

        .selectProduct { background-color: #746e70 !important; color: #fff !important; }

        b { font-weight: 500; font-family: Arial, Helvetica, sans-serif; }

        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 { text-align: right; padding-right: 10px; }

        .checkbox_input_wrap { text-align: right; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Add Transfer Stock')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-7">
                    <div class="name-head">
                        <h6>{{ __('Add Tranfer Stock') }}</h6>
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
                                            <option {{ $generalSettings['print_page_size__transfer_stock_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
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
            <form id="add_transfer_stock_form" action="{{ route('transfer.stocks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <input type="hidden" name="print_page_size" id="print_page_size" value="1">
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Sender Shop/Business') }}</b></label>
                                        <div class="col-7">
                                            <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
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
                                                        <option value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                   @endif
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Receiver Shop/Business') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-7">
                                            <select name="receiver_branch_id" class="form-control" id="receiver_branch_id" data-next="receiver_warehouse_id" autofocus>
                                                <option value="" class="fw-bold">{{ __('Select Receiver Shop/Business') }}</option>

                                                @if ($generalSettings['subscription']->has_business == 1)

                                                    <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                @endif

                                                @foreach ($branches as $branch)
                                                
                                                    <option value="{{ $branch->id }}">
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
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Transfer Date') }}</b></label>
                                        <div class="col-7">
                                            <input required type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business_or_shop__date_format']) }}" data-next="search_product" autocomplete="off">
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
                                                        <tbody id="transfer_product_list"></tbody>
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
                                        <label class=" col-4"><b>{{ __('Total Item') }}</b></label>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <input readonly type="number" step="any" name="total_item" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                <input readonly type="number" step="any" name="total_qty" class="form-control fw-bold" id="total_qty" tabindex="-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class=" col-4"><b>{{ __('Total Stock Value') }} :</b> </label>
                                        <div class="col-8">
                                            <input readonly type="number" step="any" name="total_stock_value" class="form-control fw-bold" id="total_stock_value" value="0.00" tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-2"><b>{{ __('Note') }}</b></label>
                                        <div class="col-10">
                                            <input name="transfer_note" type="text" class="form-control" id="transfer_note" data-next="save_and_print" placeholder="{{ __('Transfer Note') }}">
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
                                <button type="button" id="save_and_print" value="save_and_print" class="btn btn-success submit_button">{{ __('Save & Print') }}</button>
                                <button type="button" id="save" value="save" class="btn btn-success submit_button">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('transfer_stocks.js_partials.transfer_stock_add_js')
@endpush
