@extends('layout.master')
@push('stylesheets')
    <style>
        .card-body { flex: 1 1 auto; padding: 0.4rem 0.4rem; }
    </style>
@endpush
@section('title', 'Maange Customer - ')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <style>
            .contract_info_area ul li strong{color:#495677}
            .account_summary_area .heading h5{background:#0F3057;color:white}
            .contract_info_area ul li strong i {color: #495b77; font-size: 13px;}
        </style>
    @endpush
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-people-arrows"></span>
                    <h6><strong>{{ $contact->name }}</strong></h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                    </div>

                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" data-show="ledger" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                <i class="fas fa-scroll"></i> {{ __("Ledger") }}
                            </a>

                            <a id="tab_btn" data-show="contract_info_area" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-info-circle"></i> {{ __('Contract Info') }}
                            </a>

                            <a id="tab_btn" data-show="sale" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> {{ __("Sales") }}
                            </a>

                            <a id="tab_btn" data-show="sales_order" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> {{ __("Sales Orders") }}
                            </a>

                            <a id="tab_btn" data-show="purchases" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> {{ __("Purchases") }}
                            </a>

                            <a id="tab_btn" data-show="purchase_orders" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> {{ __("Purchases Orders") }}
                            </a>

                            <a id="tab_btn" data-show="receipts" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="far fa-money-bill-alt"></i> {{ __("Receipts") }}
                            </a>

                            <a id="tab_btn" data-show="payments" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="far fa-money-bill-alt"></i> {{ __("Payments") }}
                            </a>
                        </div>
                    </div>

                    <div class="tab_contant ledger">
                        <div class="row">
                            <div class="col-sm-12 col-lg-3">
                                @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-9">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __("Filter Area") }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_customer_ledgers" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                @if ($generalSettings['addons__branches'] == 1)

                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)

                                                        <div class="col-lg-3 col-md-6">
                                                            <label><strong>{{ __("Shop") }}</strong></label>
                                                            <select name="branch_id" class="form-control select2" id="ledger_branch_id" autofocus>
                                                                <option value="">@lang('menu.all')</option>
                                                                <option value="NULL"> {{ $generalSettings['business__shop_name'] }} </option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @else

                                                    <input type="hidden" name="branch_id" id="ledger_branch_id" value="{{ auth()->user()->branch_id ? auth()->user()->branch_id : 'NULL' }}">
                                                @endif


                                                <div class="col-lg-3 col-md-6">
                                                    <label><strong>{{ __("From Date") }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="ledger_from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <label><strong>{{ __("To Date") }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>

                                                        <input type="text" name="to_date" id="ledger_to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <div class="row align-items-end">
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="print_ledger"><i class="fas fa-print"></i> {{ __("Print") }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="ledger_table_area">
                                    <div class="table-responsive" id="payment_list_table">
                                        <table class="display data_tbl data__table ledger_table">
                                            <thead>
                                                <tr>
                                                    <tr>
                                                        <th>{{ __("Date") }}</th>
                                                        <th>{{ __("Particulars") }}</th>
                                                        <th>{{ __('Voucher Type') }}</th>
                                                        <th>{{ __('Voucher No') }}</th>
                                                        <th>{{ __("Debit") }}</th>
                                                        <th>{{ __("Credit") }}</th>
                                                        <th>{{ __("Running Balance") }}</th>
                                                    </tr>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="4" class="text-white text-end">{{ __("Total") }} : ({{ $generalSettings['business__currency'] }})</th>
                                                    <th id="debit" class="text-white text-end"></th>
                                                    <th id="credit" class="text-white text-end"></th>
                                                    <th class="text-white text-end">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant contract_info_area d-hide">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="list-unstyled">
                                    <li><strong>{{ __("Name") }}</strong></li>
                                    <li><span class="name">{{ $contact->name }}</span></li><br>
                                    <li><strong><i class="fas fa-map-marker-alt"></i> {{ __("Address") }}</strong></li>
                                    <li><span class="address">{{ $contact->address }}</span></li><br>
                                    <li><strong><i class="fas fa-briefcase"></i> {{ __("Business") }}</strong></li>
                                    <li><span class="business">{{ $contact->business_name }}</span></li>
                                </ul>
                            </div>

                            <div class="col-md-3">
                                <ul class="list-unstyled">
                                    <li><strong><i class="fas fa-phone-square"></i> {{ __("Phone") }}</strong></li>
                                    <li><span class="phone">{{ $contact->phone }}</span></li>
                                </ul>
                            </div>

                            <div class="col-md-3">
                                <ul class="list-unstyled">
                                    <li><strong><i class="fas fa-info"></i> {{ __("Tax Number") }}</strong></li>
                                    <li><span class="tax_number">{{ $contact->tax_number }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant sale d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-4">
                                @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __("Filter Area") }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_sales" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                <div class="col-lg-9 col-md-6">
                                                    <div class="row">
                                                        @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                            <div class="col-lg-6 col-md-6">
                                                                <label><strong>{{ __("Shop/Business") }}</strong></label>
                                                                <select name="branch_id" class="form-control select2" id="sales_branch_id" autofocus>
                                                                    <option data-branch_name="{{ __("All") }}" value="">{{ __("All") }}</option>
                                                                    <option data-branch_name="{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})" value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                                    @foreach ($branches as $branch)
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                            $branchCode = '-(' . $branch->branch_code.')';
                                                                        @endphp
                                                                        <option data-branch_name="{{ $branchName.$areaName.$branchCode }}" value="{{ $branch->id }}">
                                                                            {{  $branchName.$areaName.$branchCode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("Payment Status") }}</strong></label>
                                                            <select name="payment_status" id="sales_payment_status" class="form-control">
                                                                <option value="">{{ __("All") }}</option>
                                                                @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                                    <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("From Date") }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="from_date" id="sales_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("To Date") }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>

                                                                <input type="text" name="to_date" id="sales_to_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <div class="row align-items-end">
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printSalesReport"><i class="fas fa-print"></i> {{ __("Print") }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="table-responsive">
                                        <table id="sales-table" class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __("Action") }}</th>
                                                    <th>{{ __("Date") }}</th>
                                                    <th>{{ __("Invoice ID") }}</th>
                                                    <th>{{ __("Shop") }}</th>
                                                    <th>{{ __("Customer") }}</th>
                                                    <th>{{ __("Payment Status") }}</th>
                                                    <th>{{ __("Total Item") }}</th>
                                                    <th>{{ __("Total Qty") }}</th>
                                                    <th>{{ __("Total Invoice Amt") }}</th>
                                                    <th>{{ __("Received Amount") }}</th>
                                                    <th>{{ __('Return') }}</th>
                                                    <th>{{ __("Due") }}</th>
                                                    <th>{{ __("Created By") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white text-end">{{ __("Total") }} : ({{ $generalSettings['business__currency'] }})</th>
                                                    <th id="total_item" class="text-white text-end"></th>
                                                    <th id="total_qty" class="text-white text-end"></th>
                                                    <th id="total_invoice_amount" class="text-white text-end"></th>
                                                    <th id="received_amount" class="text-white text-end"></th>
                                                    <th id="sale_return_amount" class="text-white text-end"></th>
                                                    <th id="due" class="text-white text-end"></th>
                                                    <th class="text-white text-end">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant sales_order d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-4">
                                @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __("Filter Area") }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_sales_order" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                <div class="col-lg-9 col-md-6">
                                                    <div class="row">
                                                        @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                            <div class="col-lg-6 col-md-6">
                                                                <label><strong>{{ __("Shop/Business") }}</strong></label>
                                                                <select name="branch_id" class="form-control select2" id="sales_order_branch_id" autofocus>
                                                                    <option data-branch_name="{{ __("All") }}" value="">{{ __("All") }}</option>
                                                                    <option data-branch_name="{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})" value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                                    @foreach ($branches as $branch)
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                            $branchCode = '-(' . $branch->branch_code.')';
                                                                        @endphp
                                                                        <option data-branch_name="{{ $branchName.$areaName.$branchCode }}" value="{{ $branch->id }}">
                                                                            {{ $branchName . $areaName . $branchCode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("Payment Status") }}</strong></label>
                                                            <select name="payment_status" id="sales_order_payment_status" class="form-control">
                                                                <option value="">{{ __("All") }}</option>
                                                                @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                                    <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("From Date") }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="from_date" id="sales_order_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("To Date") }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>

                                                                <input type="text" name="to_date" id="sales_order_to_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <div class="row align-items-end">
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printSalesOrderReport"><i class="fas fa-print"></i> {{ __("Print") }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="table-responsive">
                                        <table id="sales-order-table" class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __("Action") }}</th>
                                                    <th>{{ __("Date") }}</th>
                                                    <th>{{ __("Order ID") }}</th>
                                                    <th>{{ __("Shop") }}</th>
                                                    <th>{{ __("Customer") }}</th>
                                                    <th>{{ __("Payment Status") }}</th>
                                                    <th>{{ __("Total Item") }}</th>
                                                    <th>{{ __("Total Qty") }}</th>
                                                    <th>{{ __("Total Ordered Amt") }}</th>
                                                    <th>{{ __("Advance Received") }}</th>
                                                    <th>{{ __("Due") }}</th>
                                                    <th>{{ __("Created By") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white text-end">{{ __("Total") }} : ({{ $generalSettings['business__currency'] }})</th>
                                                    <th id="total_item" class="text-white text-end"></th>
                                                    <th id="total_qty" class="text-white text-end"></th>
                                                    <th id="total_invoice_amount" class="text-white text-end"></th>
                                                    <th id="received_amount" class="text-white text-end"></th>
                                                    <th id="due" class="text-white text-end"></th>
                                                    <th class="text-white text-end">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant purchases d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-4">
                                @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __("Filter Area") }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_purchases" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                <div class="col-lg-9 col-md-6">
                                                    <div class="row">
                                                        @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                            <div class="col-lg-6 col-md-6">
                                                                <label><strong>{{ __("Shop/Business") }}</strong></label>
                                                                <select name="branch_id" class="form-control select2" id="purchases_branch_id" autofocus>
                                                                    <option data-branch_name="{{ __("All") }}" value="">{{ __("All") }}</option>
                                                                    <option data-branch_name="{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})" value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                                    @foreach ($branches as $branch)
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                            $branchCode = '-(' . $branch->branch_code.')';
                                                                        @endphp
                                                                        <option data-branch_name="{{ $branchName.$areaName.$branchCode }}" value="{{ $branch->id }}">
                                                                            {{ $branchName . $areaName . $branchCode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("Payment Status") }}</strong></label>
                                                            <select name="payment_status" id="purchases_payment_status" class="form-control">
                                                                <option value="">{{ __("All") }}</option>
                                                                @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                                    <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("From Date") }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="from_date" id="purchases_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("To Date") }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>

                                                                <input type="text" name="to_date" id="purchases_to_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <div class="row align-items-end">
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printPurchasesReport"><i class="fas fa-print"></i> {{ __("Print") }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="table-responsive">
                                        <table id="purchases-table" class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __("Action") }}</th>
                                                    <th>{{ __("Date") }}</th>
                                                    <th>{{ __('P.Invoice ID') }}</th>
                                                    <th>{{ __("Shop/Business") }}</th>
                                                    <th>{{ __("Supplier") }}</th>
                                                    <th>{{ __("Payment Status") }}</th>
                                                    <th>{{ __("Total Purchased Amount") }}</th>
                                                    <th>{{ __("Paid") }}</th>
                                                    <th>{{ __("Return") }}</th>
                                                    <th>{{ __("Due") }}</th>
                                                    <th>{{ __("Created By") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-end text-white">{{ __("Total") }} : {{ $generalSettings['business__currency'] }}</th>
                                                    <th id="total_purchase_amount" class="text-white"></th>
                                                    <th id="paid" class="text-white"></th>
                                                    <th id="purchase_return_amount" class="text-white"></th>
                                                    <th id="due" class="text-white"></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant purchase_orders d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-4">
                                @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __("Filter Area") }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_purchases" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                <div class="col-lg-9 col-md-6">
                                                    <div class="row">
                                                        @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                            <div class="col-lg-6 col-md-6">
                                                                <label><strong>{{ __("Shop/Business") }}</strong></label>
                                                                <select name="branch_id" class="form-control select2" id="purchase_orders_branch_id" autofocus>
                                                                    <option data-branch_name="{{ __("All") }}" value="">{{ __("All") }}</option>
                                                                    <option data-branch_name="{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})" value="NULL">{{ $generalSettings['business__shop_name'] }}({{ __("Business") }})</option>
                                                                    @foreach ($branches as $branch)
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                            $branchCode = '-(' . $branch->branch_code.')';
                                                                        @endphp
                                                                        <option data-branch_name="{{ $branchName.$areaName.$branchCode }}" value="{{ $branch->id }}">
                                                                            {{ $branchName . $areaName . $branchCode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("Payment Status") }}</strong></label>
                                                            <select name="payment_status" id="purchases_orders_payment_status" class="form-control">
                                                                <option value="">{{ __("All") }}</option>
                                                                @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                                    <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("From Date") }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="from_date" id="purchase_orders_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __("To Date") }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>

                                                                <input type="text" name="to_date" id="purchase_orders_to_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <div class="row align-items-end">
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printPurchaseOrdersReport"><i class="fas fa-print"></i> {{ __("Print") }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table_area">
                                    <div class="table-responsive">
                                        <table id="purchase-orders-table" class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __("Action") }}</th>
                                                    <th>{{ __("Date") }}</th>
                                                    <th>{{ __('P/o ID') }}</th>
                                                    <th>{{ __("Shop/Business") }}</th>
                                                    <th>{{ __("Supplier") }}</th>
                                                    <th>{{ __("Created By") }}</th>
                                                    <th>{{ __("Receiving Status") }}</th>
                                                    <th>{{ __("Payment Status") }}</th>
                                                    <th>{{ __("Total Ordered Amount") }}</th>
                                                    <th>{{ __("Paid") }}</th>
                                                    <th>{{ __("Due") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="8" class="text-white text-end">{{ __("Total") }} : ({{ $generalSettings['business__currency'] }})</th>
                                                    <th class="text-white text-end" id="total_purchase_amount"></th>
                                                    <th class="text-white text-end" id="paid"></th>
                                                    <th class="text-white text-end" id="due"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant receipts d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-3">
                                @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-9">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __("Filter Area") }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <div class="row mt-2">
                                            <div class="col-md-10">
                                                <div class="card pb-5">
                                                    <form id="filter_receipts" class="py-2 px-2 mt-2" method="get">
                                                        <div class="form-group row align-items-end">
                                                            @if ($generalSettings['addons__branches'] == 1)
                                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                                    <div class="col-lg-3 col-md-6">
                                                                        <label><strong>{{ __("Shop/Business") }} </strong></label>
                                                                        <select name="branch_id" class="form-control select2" id="recipts_branch_id" autofocus>
                                                                            <option value="">@lang('menu.all')</option>
                                                                            <option value="NULL">
                                                                                {{ $generalSettings['business__shop_name'] }}
                                                                            </option>
                                                                            @foreach ($branches as $branch)
                                                                                <option value="{{ $branch->id }}">
                                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                @endif
                                                            @endif

                                                            <div class="col-lg-3 col-md-6">
                                                                <label><strong>{{ __("From Date") }}</strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                    </div>
                                                                    <input type="text" name="p_from_date" id="recipts_from_date" class="form-control" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3 col-md-6">
                                                                <label><strong>{{ __("To Date") }}</strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                    </div>
                                                                    <input type="text" name="p_to_date" id="recipts_to_date" class="form-control" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="row align-items-end">
                                                                    <div class="col-md-6">
                                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __("Filter") }}</button>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <a href="#" class="btn btn-sm btn-primary" id="printReceiptReport"><i class="fas fa-print"></i> {{ __("Print") }}</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="col-md-2 mt-md-0 mt-2">
                                                <div class="col-md-12 col-sm-12 col-lg-12 d-md-block d-flex gap-2">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <a href="#" class="btn btn-sm btn-success"><i class="far fa-money-bill-alt text-white"></i> {{ __("Add Receipt") }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="widget_content table_area">
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table payments_table w-100">
                                            <thead>
                                                <tr class="text-start">
                                                    <th class="text-start">{{ __("Date") }}</th>
                                                    <th class="text-start">{{ __("Voucher No") }}</th>
                                                    <th class="text-start">{{ __("Reference") }}</th>
                                                    <th class="text-start">{{ __("Against Voucher") }}</th>
                                                    <th class="text-start">{{ __("Receipt Method") }}</th>
                                                    <th class="text-start">{{ __("Account") }}</th>
                                                    <th class="text-end">{{ __("Received Amount") }}</th>
                                                    <th class="text-start">{{ __("Action") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th class="text-end text-white" colspan="6">{{ __("Total") }} </th>
                                                    <th class="text-end text-white" id="received_amount"></th>
                                                    <th class="text-start text-white">---</th>
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

    {{-- <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="payment_deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <!-- Details Modal -->
    <div id="sale_details"></div> --}}

   {{-- <!-- Edit Shipping modal -->
   <div class="modal fade" id="editShipmentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="edit_shipment_modal_content"></div>
        </div>
    </div> --}}

    @if(auth()->user()->can('sale_payment'))
        {{-- <!--Payment View modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_list')</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment_view_modal_body"> </div>
                </div>
            </div>
        </div> --}}

        {{-- <!--Add Payment modal-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

        <!--Payment list modal-->
        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_details')(<span class="payment_invoice"></span>)</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="payment_details_area"></div>

                        <div class="row">
                            <div class="col-md-6 text-right">
                                <ul class="list-unstyled">
                                    <li class="mt-3" id="payment_attachment"></li>
                                </ul>
                            </div>
                            <div class="col-md-6 text-right">
                                <ul class="list-unstyled">
                                    <li class="mt-3"><a href="" id="print_payment" class="btn btn-sm btn-primary float-end">@lang('menu.print')</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    @endif

    <div id="details"></div>
    <div id="extra_details"></div>

    <!-- Edit Shipping modal -->
    <div class="modal fade" id="editShipmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
    @include('contacts.manage_customers.js_partials.manage_js')
@endpush