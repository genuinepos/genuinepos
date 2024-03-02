@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {
            font-size: 12px !important;
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
            background-color: #746e70;
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Add Stock Adjustment - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Add Stock Adjustment') }}</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="p-1">
            <form id="add_adjustment_form" action="{{ route('stock.adjustments.store') }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Expense Ledger') }}</b> <span class="text-danger">*</span></label>
                                        <div class="col-7">
                                            <select required name="expense_account_id" class="form-control" id="expense_account_id" data-next="date" autofocus>
                                                <option value="">{{ __('Select Expense Ledger A/c') }}</option>
                                                @foreach ($expenseAccounts as $expenseAccount)
                                                    <option value="{{ $expenseAccount->id }}">{{ $expenseAccount->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_expense_account_id"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Adjust. Date') }}</b> <span class="text-danger">*</span> </label>
                                        <div class="col-7">
                                            <input type="text" name="date" class="form-control" id="date" value="{{ date($generalSettings['business_or_shop__date_format']) }}" data-next="type" autocomplete="off">
                                            <span class="error error_date"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Adjustment Type') }}</b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Normal: like Leakage, Damage etc. Abnormal: like Fire, Accident, stolen etc." class="fas fa-info-circle tp"></i></label>
                                        <div class="col-7">
                                            <select name="type" class="form-control" id="type" data-next="search_product">
                                                @foreach (\App\Enums\StockAdjustmentType::cases() as $type)
                                                    <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error error_type"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Voucher No') }}</b></label>
                                        <div class="col-7">
                                            <input readonly type="text" name="voucher_no" id="voucher_no" class="form-control" placeholder="{{ __('Voucher No') }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content mb-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="content-inner">
                                        <div class="row align-items-end">
                                            <div class="col-xl-3">
                                                <div class="searching_area" style="position: relative;">
                                                    <label class="col-form-label">{{ __('Search Product') }}</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="{{ __('Search Product by Name/Code') }}" autofocus>
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area"></ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-9">
                                                <div class="hidden_field">
                                                    <input type="hidden" id="e_unique_id">
                                                    <input type="hidden" id="e_item_name">
                                                    <input type="hidden" id="e_base_unit_name">
                                                    <input type="hidden" id="e_product_id">
                                                    <input type="hidden" id="e_variant_id">
                                                </div>

                                                <div class="row mt-1 align-items-end">
                                                    <div class="col-xl-3 col-md-4">
                                                        <label class="fw-bold">{{ __('Quantity') }}</label>
                                                        <div class="input-group">
                                                            <input type="number" step="any" class="form-control fw-bold w-60" id="e_quantity" placeholder="0.00" value="0.00">
                                                            <input type="hidden" id="e_quantity" value="0.00">
                                                            <select id="e_unit_id" class="form-control w-40">
                                                                <option value="">{{ __('Unit') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-2 col-md-4">
                                                        <label class="fw-bold">{{ __('Unit Cost Inc. Tax') }}</label>
                                                        <input type="number" step="any" class="form-control fw-bold" id="e_unit_cost_inc_tax" value="0.00">
                                                    </div>

                                                    @if ($generalSettings['subscription']->features['warehouse_count'] > 0)
                                                        <div class="col-xl-2 col-md-4">
                                                            <label class="fw-bold">{{ __('Warehouse') }}</label>
                                                            <select id="e_warehouse_id" class="form-control w-40">
                                                                <option value="">{{ __('Select Warehouse') }}</option>
                                                                @foreach ($warehouses as $w)
                                                                    @php
                                                                        $isGlobal = $w->is_global == 1 ? ' (' . __('Global Access') . ')' : '';
                                                                    @endphp
                                                                    <option data-w_name="{{ $w->warehouse_name . '/' . $w->warehouse_code . $isGlobal }}" value="{{ $w->id }}">{{ $w->warehouse_name . '/' . $w->warehouse_code . $isGlobal }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    <div class="col-xl-2 col-md-4">
                                                        <label class="fw-bold">{{ __('Subtotal') }}</label>
                                                        <input readonly type="number" step="any" class="form-control fw-bold" id="e_subtotal" value="0.00" tabindex="-1">
                                                    </div>

                                                    <div class="col-xl-1 col-md-4">
                                                        <a href="#" class="btn btn-sm btn-success px-2" id="add_item">{{ __('Add') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>{{ __('Product') }}</th>
                                                                    <th>{{ __('Stock Location') }}</th>
                                                                    <th>{{ __('Quantity') }}</th>
                                                                    <th>{{ __('Unit') }}</th>
                                                                    <th>{{ __('Unit Cost Inc. Tax') }}</th>
                                                                    <th>{{ __('Subtotal') }}</th>
                                                                    <th><i class="fas fa-trash-alt text-danger"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="stock_adjustment_product_list"></tbody>
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
                </section>

                <section class="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form_element rounded m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group mt-1">
                                                        <div class="input-group">
                                                            <label class="col-4"><b>{{ __('Total Item & Quantity') }}</b></label>
                                                            <div class="col-8">
                                                                <div class="input-group">
                                                                    <input readonly type="number" step="any" name="total_item" class="form-control fw-bold" id="total_item" value="0.00" tabindex="-1">
                                                                    <input readonly type="number" step="any" name="total_qty" class="form-control fw-bold" id="total_qty" value="0.00" tabindex="-1">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group">
                                                            <label class=" col-4"><b>{{ __('Net Total Amount') }}</b> </label>
                                                            <div class="col-8">
                                                                <input readonly type="number" class="form-control fw-bold" step="any" step="any" name="net_total_amount" id="net_total_amount" value="0.00" tabindex="-1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class=" col-4"><b>{{ __('Reason') }}</b></label>
                                                        <div class="col-8">
                                                            <input type="text" name="reason" class="form-control" data-next="recovered_amount" autocomplete="off" placeholder="{{ __('Reason') }}">
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
                                                        <label class="col-4"><b>{{ __('Recovered Amount (Receipt)') }}</b> <strong>>></strong></label>
                                                        <div class="col-8">
                                                            <input type="number" step="any" name="recovered_amount" class="form-control fw-bold" id="recovered_amount" data-next="payment_method_id" value="0.00">
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Payment Method') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="payment_method_id" class="form-control" id="payment_method_id" data-next="account_id">
                                                                @foreach ($methods as $method)
                                                                    <option data-account_id="{{ $method->paymentMethodSetting ? $method->paymentMethodSetting->account_id : '' }}" value="{{ $method->id }}">
                                                                        {{ $method->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="error error_payment_method_id"></span>
                                                        </div>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <label class="col-4"><b>{{ __('Debit A/c') }}</b> <span class="text-danger">*</span></label>
                                                        <div class="col-8">
                                                            <select name="account_id" class="form-control" id="account_id" data-next="save">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area mt-2">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                <button type="button" id="save" class="btn btn-success submit_button float-end">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Add Product Modal End-->
@endsection
@push('scripts')
    @include('stock_adjustments.js_partials.add_js')
@endpush
