@extends('layout.master')
@push('stylesheets')
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: 0.4rem 0.4rem;
        }
    </style>
@endpush
@section('title', 'Maange Supplier - ')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .contract_info_area ul li strong {
                color: #495677
            }

            .account_summary_area .heading h5 {
                background: #0F3057;
                color: white
            }

            .contract_info_area ul li strong i {
                color: #495b77;
                font-size: 13px;
            }
        </style>
    @endpush
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Manage Supplier') }} - (<strong>{{ $contact->name }}</strong>)</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>

                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" data-show="ledger" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                <i class="fas fa-scroll"></i> {{ __('Ledger') }}
                            </a>

                            <a id="tab_btn" data-show="contract_info_area" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-info-circle"></i> {{ __('Contract Info') }}
                            </a>

                            <a id="tab_btn" data-show="purchases" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> {{ __('Purchases') }}
                            </a>

                            <a id="tab_btn" data-show="purchase_orders" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> {{ __('Purchases Orders') }}
                            </a>

                            <a id="tab_btn" data-show="sale" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> {{ __('Sales') }}
                            </a>

                            <a id="tab_btn" data-show="sales_order" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-shopping-bag"></i> {{ __('Sales Orders') }}
                            </a>

                            <a id="tab_btn" data-show="payments" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="far fa-money-bill-alt"></i> {{ __('Payments') }}
                            </a>

                            <a id="tab_btn" data-show="receipts" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="far fa-money-bill-alt"></i> {{ __('Receipts') }}
                            </a>
                        </div>
                    </div>

                    <div class="tab_contant ledger">
                        <div class="row">
                            <div class="col-sm-12 col-lg-3" id="for_ledger">
                                @include('contacts.manage_suppliers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-9">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_supplier_ledgers" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                    <div class="col-lg-3 col-md-3">
                                                        <label><strong>{{ location_label() }}</strong></label>
                                                        <select name="branch_id" class="form-control select2" id="ledger_branch_id" autofocus>
                                                            <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                            <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                            @foreach ($branches as $branch)
                                                                @php
                                                                    $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                    $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                    $branchCode = '-' . $branch->branch_code;
                                                                @endphp
                                                                <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                    {{ $branchName . $areaName . $branchCode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-lg-3 col-md-6">
                                                    <label><strong>{{ __('From Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="ledger_from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3">
                                                    <label><strong>{{ __('To Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>

                                                        <input type="text" name="to_date" id="ledger_to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-3">
                                                    <label><strong>{{ __('Note/Remarks') }} :</strong></label>
                                                    <select name="note" class="form-control" id="ledger_note">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option selected value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-md-3">
                                                    <label><strong>{{ __('Voucher Details') }} :</strong></label>
                                                    <select name="voucher_details" class="form-control" id="ledger_voucher_details">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-md-3">
                                                    <label><strong>{{ __('Transaction Details') }} :</strong></label>
                                                    <select name="transaction_details" class="form-control" id="ledger_transaction_details">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-md-3">
                                                    <label><strong>{{ __('Inventory List') }} :</strong></label>
                                                    <select name="inventory_list" class="form-control" id="ledger_inventory_list">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-3 col-md-6">
                                                    <div class="row align-items-end">
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printLedger"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                                        <table id="ledger-table" class="display data_tbl data__table ledger_table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                <tr>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Particulars') }}</th>
                                                    <th>{{ __('Voucher Type') }}</th>
                                                    <th>{{ __('Voucher No') }}</th>
                                                    <th>{{ __('Debit') }}</th>
                                                    <th>{{ __('Credit') }}</th>
                                                    <th>{{ __('Running Balance') }}</th>
                                                </tr>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="4" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                    <th id="ledger_table_total_debit" class="text-white text-end"></th>
                                                    <th id="ledger_table_total_credit" class="text-white text-end"></th>
                                                    <th id="ledger_table_current_balance" class="text-white text-end"></th>
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
                            <div class="col-md-6">
                                <table class="display table table-sm">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">{{ __('Supplier ID') }}</td>
                                            <td class="text-start">: {{ $contact->contact_id }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Name') }}</td>
                                            <td class="text-start">: {{ $contact->name }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Phone') }}</td>
                                            <td class="text-start">: {{ $contact->phone }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Business') }}</td>
                                            <td class="text-start">: {{ $contact->business_name }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Alternative Phone Number') }}</td>
                                            <td class="text-start">: {{ $contact->alternative_phone }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Landline') }}</td>
                                            <td class="text-start">: {{ $contact->landline }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Email') }}</td>
                                            <td class="text-start">: {{ $contact->email }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Date Of Birth') }}</td>
                                            <td class="text-start">: {{ $contact->date_of_birth }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Tax Number') }}</td>
                                            <td class="text-start">: {{ $contact->tax_number }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Address') }}</td>
                                            <td class="text-start">: {{ $contact->address }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('City') }}</td>
                                            <td class="text-start">: {{ $contact->city }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('State') }}</td>
                                            <td class="text-start">: {{ $contact->state }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Zip-code') }}</td>
                                            <td class="text-start">: {{ $contact->zip_code }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Country') }}</td>
                                            <td class="text-start">: {{ $contact->country }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-start">{{ __('Pay-Term') }}</td>
                                            <td class="text-start">:
                                                {{ ($contact->pay_term_number ? $contact->pay_term_number : 0) . '/' . ($contact->pay_term == 1 ? __('Days') : __('Months')) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant purchases d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-4" id="for_purchases">
                                @include('contacts.manage_suppliers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_purchases" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                <div class="col-lg-9 col-md-6">
                                                    <div class="row">
                                                        {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                            <div class="col-lg-6 col-md-6">
                                                                <label><strong>{{ location_label() }}</strong></label>
                                                                <select name="branch_id" class="form-control select2" id="purchases_branch_id" autofocus>
                                                                    <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                                    <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                                    @foreach ($branches as $branch)
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                            $branchCode = '-' . $branch->branch_code;
                                                                        @endphp
                                                                        <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                            {{ $branchName . $areaName . $branchCode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('Payment Status') }}</strong></label>
                                                            <select name="payment_status" id="purchases_payment_status" class="form-control">
                                                                <option value="">{{ __('All') }}</option>
                                                                @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                                    <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('From Date') }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="from_date" id="purchases_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('To Date') }}</strong></label>
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
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printPurchasesReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                                        <table id="purchases-table" class="display data_tbl data__table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Action') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('P.Invoice ID') }}</th>
                                                    <th>{{ location_label() }}</th>
                                                    <th>{{ __('Supplier') }}</th>
                                                    <th>{{ __('Payment Status') }}</th>
                                                    <th>{{ __('Total Purchased Amount') }}</th>
                                                    <th>{{ __('Paid') }}</th>
                                                    <th>{{ __('Return') }}</th>
                                                    <th>{{ __('Due') }}</th>
                                                    <th>{{ __('Created By') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-end text-white">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                                    <th id="purchases_total_purchase_amount" class="text-white"></th>
                                                    <th id="purchases_paid" class="text-white"></th>
                                                    <th id="purchases_purchase_return_amount" class="text-white"></th>
                                                    <th id="purchases_due" class="text-white"></th>
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
                            <div class="col-sm-12 col-lg-4" id="for_purchase_orders">
                                @include('contacts.manage_suppliers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_purchase_orders" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                <div class="col-lg-9 col-md-6">
                                                    <div class="row">
                                                        {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                            <div class="col-lg-6 col-md-6">
                                                                <label><strong>{{ location_label() }}</strong></label>
                                                                <select name="branch_id" class="form-control select2" id="purchase_orders_branch_id" autofocus>
                                                                    <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                                    <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                                    @foreach ($branches as $branch)
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                            $branchCode = '-' . $branch->branch_code;
                                                                        @endphp
                                                                        <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                            {{ $branchName . $areaName . $branchCode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('Payment Status') }}</strong></label>
                                                            <select name="payment_status" id="purchases_orders_payment_status" class="form-control">
                                                                <option value="">{{ __('All') }}</option>
                                                                @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                                    <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('From Date') }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="from_date" id="purchase_orders_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('To Date') }}</strong></label>
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
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printPurchaseOrdersReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                                        <table id="purchase-orders-table" class="display data_tbl data__table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Action') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('P/o ID') }}</th>
                                                    <th>{{ location_label() }}</th>
                                                    <th>{{ __('Supplier') }}</th>
                                                    <th>{{ __('Created By') }}</th>
                                                    <th>{{ __('Receiving Status') }}</th>
                                                    <th>{{ __('Payment Status') }}</th>
                                                    <th>{{ __('Total Ordered Amount') }}</th>
                                                    <th>{{ __('Paid') }}</th>
                                                    <th>{{ __('Due') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="8" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                    <th id="purchase_orders_total_purchase_amount" class="text-white text-end"></th>
                                                    <th id="purchase_orders_paid" class="text-white text-end"></th>
                                                    <th id="purchase_orders_due" class="text-white text-end"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant sale d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-4" id="for_sales">
                                @include('contacts.manage_suppliers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_sales" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                <div class="col-lg-9 col-md-6">
                                                    <div class="row">
                                                        {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                            <div class="col-lg-6 col-md-6">
                                                                <label><strong>{{ location_label() }}</strong></label>
                                                                <select name="branch_id" class="form-control select2" id="sales_branch_id" autofocus>
                                                                    <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                                    <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                                    @foreach ($branches as $branch)
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                            $branchCode = '-' . $branch->branch_code;
                                                                        @endphp
                                                                        <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                            {{ $branchName . $areaName . $branchCode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('Payment Status') }}</strong></label>
                                                            <select name="payment_status" id="sales_payment_status" class="form-control">
                                                                <option value="">{{ __('All') }}</option>
                                                                @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                                    <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('From Date') }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="from_date" id="sales_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('To Date') }}</strong></label>
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
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printSalesReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                                        <table id="sales-table" class="display data_tbl data__table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Action') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Invoice ID') }}</th>
                                                    <th>{{ location_label() }}</th>
                                                    <th>{{ __('Customer') }}</th>
                                                    <th>{{ __('Payment Status') }}</th>
                                                    <th>{{ __('Total Item') }}</th>
                                                    <th>{{ __('Total Qty') }}</th>
                                                    <th>{{ __('Total Invoice Amt') }}</th>
                                                    <th>{{ __('Received Amount') }}</th>
                                                    <th>{{ __('Return') }}</th>
                                                    <th>{{ __('Due') }}</th>
                                                    <th>{{ __('Created By') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                    <th id="sales_total_item" class="text-white text-end"></th>
                                                    <th id="sales_total_qty" class="text-white text-end"></th>
                                                    <th id="sales_total_invoice_amount" class="text-white text-end"></th>
                                                    <th id="sales_received_amount" class="text-white text-end"></th>
                                                    <th id="sales_sale_return_amount" class="text-white text-end"></th>
                                                    <th id="sales_due" class="text-white text-end"></th>
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
                            <div class="col-sm-12 col-lg-4" id="for_sales_order">
                                @include('contacts.manage_suppliers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-8">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <form id="filter_sales_order" method="get" class="px-2">
                                            <div class="form-group row align-items-end justify-content-end g-3">
                                                <div class="col-lg-9 col-md-6">
                                                    <div class="row">
                                                        {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                            <div class="col-lg-6 col-md-6">
                                                                <label><strong>{{ location_label() }}</strong></label>
                                                                <select name="branch_id" class="form-control select2" id="sales_order_branch_id" autofocus>
                                                                    <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                                    <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                                    @foreach ($branches as $branch)
                                                                        @php
                                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                            $branchCode = '-' . $branch->branch_code;
                                                                        @endphp
                                                                        <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                            {{ $branchName . $areaName . $branchCode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('Payment Status') }}</strong></label>
                                                            <select name="payment_status" id="sales_order_payment_status" class="form-control">
                                                                <option value="">{{ __('All') }}</option>
                                                                @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                                    <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('From Date') }}</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="from_date" id="sales_order_from_date" class="form-control" autocomplete="off">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6 col-md-6">
                                                            <label><strong>{{ __('To Date') }}</strong></label>
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
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <a href="#" class="btn btn-sm btn-primary float-end" id="printSalesOrderReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                                        <table id="sales-order-table" class="display data_tbl data__table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Action') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Order ID') }}</th>
                                                    <th>{{ location_label() }}</th>
                                                    <th>{{ __('Customer') }}</th>
                                                    <th>{{ __('Payment Status') }}</th>
                                                    <th>{{ __('Total Item') }}</th>
                                                    <th>{{ __('Total Qty') }}</th>
                                                    <th>{{ __('Total Ordered Amt') }}</th>
                                                    <th>{{ __('Advance Received') }}</th>
                                                    <th>{{ __('Due') }}</th>
                                                    <th>{{ __('Created By') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                    <th id="sales_order_total_item" class="text-white text-end"></th>
                                                    <th id="sales_order_total_qty" class="text-white text-end"></th>
                                                    <th id="sales_order_total_invoice_amount" class="text-white text-end"></th>
                                                    <th id="sales_order_received_amount" class="text-white text-end"></th>
                                                    <th id="sales_order_due" class="text-white text-end"></th>
                                                    <th class="text-white text-end">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant payments d-hide">
                        <div class="row">
                            <div class="col-sm-12 col-lg-3" id="for_payments">
                                @include('contacts.manage_suppliers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-9">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <div class="row mt-2">
                                            <div class="col-md-10">
                                                <div class="card pb-5">
                                                    <form id="filter_payments" class="py-2 px-2 mt-2" method="get">
                                                        <div class="form-group row align-items-end">
                                                            {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                                <div class="col-lg-3 col-md-6">
                                                                    <label><strong>{{ location_label() }}</strong></label>
                                                                    <select name="branch_id" class="form-control select2" id="payments_branch_id" autofocus>
                                                                        <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                                        <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                                        @foreach ($branches as $branch)
                                                                            @php
                                                                                $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                                $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                                $branchCode = '-' . $branch->branch_code;
                                                                            @endphp
                                                                            <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                                {{ $branchName . $areaName . $branchCode }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            <div class="col-lg-3 col-md-6">
                                                                <label><strong>{{ __('From Date') }}</strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                    </div>
                                                                    <input type="text" name="from_date" id="payments_from_date" class="form-control" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3 col-md-6">
                                                                <label><strong>{{ __('To Date') }}</strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                    </div>
                                                                    <input type="text" name="to_date" id="payments_to_date" class="form-control" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="row align-items-end">
                                                                    <div class="col-md-6">
                                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <a href="#" class="btn btn-sm btn-primary" id="printPaymentReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                                                            <a href="{{ route('payments.create', ['debitAccountId' => $contact?->account?->id]) }}" class="btn btn-sm btn-success" id="addPayment"><i class="far fa-money-bill-alt text-white"></i> {{ __('Add Payment') }}</a>
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
                                        <table id="payments-table" class="display data_tbl data__table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Action') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Voucher') }}</th>
                                                    <th>{{ location_label() }}</th>
                                                    <th>{{ __('Reference') }}</th>
                                                    <th>{{ __('Remarks') }}</th>
                                                    {{-- <th>{{ __("Received From") }}</th> --}}
                                                    <th>{{ __('Paid From') }}</th>
                                                    <th>{{ __('Type/Method') }}</th>
                                                    <th>{{ __('Trans. No') }}</th>
                                                    <th>{{ __('Cheque No') }}</th>
                                                    {{-- <th>{{ __("Cheque S/L No") }}</th> --}}
                                                    <th>{{ __('Paid Amount') }}</th>
                                                    {{-- <th>{{ __("Created By") }}</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="10" class="text-end text-white">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                                    <th id="payments_total_amount" class="text-white"></th>
                                                    {{-- <th></th> --}}
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
                            <div class="col-sm-12 col-lg-3" id="for_receipts">
                                @include('contacts.manage_suppliers.partials.account_summery_area_by_ledgers')
                            </div>

                            <div class="col-sm-12 col-lg-9">
                                <div class="account_summary_area">
                                    <div class="heading py-1">
                                        <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                                    </div>

                                    <div class="account_summary_table">
                                        <div class="row mt-2">
                                            <div class="col-md-10">
                                                <div class="card pb-5">
                                                    <form id="filter_receipts" class="py-2 px-2 mt-2" method="get">
                                                        <div class="form-group row align-items-end">
                                                            {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                                <div class="col-lg-3 col-md-6">
                                                                    <label><strong>{{ location_label() }}</strong></label>
                                                                    <select name="branch_id" class="form-control select2" id="receipts_branch_id" autofocus>
                                                                        <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                                        <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                                        @foreach ($branches as $branch)
                                                                            @php
                                                                                $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                                $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                                $branchCode = '-' . $branch->branch_code;
                                                                            @endphp
                                                                            <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                                                {{ $branchName . $areaName . $branchCode }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif

                                                            <div class="col-lg-3 col-md-6">
                                                                <label><strong>{{ __('From Date') }}</strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                    </div>
                                                                    <input type="text" name="from_date" id="receipts_from_date" class="form-control" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3 col-md-6">
                                                                <label><strong>{{ __('To Date') }}</strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                                    </div>
                                                                    <input type="text" name="to_date" id="receipts_to_date" class="form-control" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-3 col-md-6">
                                                                <div class="row align-items-end">
                                                                    <div class="col-md-6">
                                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <a href="#" class="btn btn-sm btn-primary" id="printReceiptReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                                                            <a href="{{ route('receipts.create', ['creditAccountId' => $contact?->account?->id]) }}" class="btn btn-sm btn-success" id="addReceipt"><i class="far fa-money-bill-alt text-white"></i> {{ __('Add Receipt') }}</a>
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
                                        <table id="receipts-table" class="display data_tbl data__table common-reloader w-100">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Action') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Voucher') }}</th>
                                                    <th>{{ location_label() }}</th>
                                                    <th>{{ __('Reference') }}</th>
                                                    <th>{{ __('Remarks') }}</th>
                                                    {{-- <th>{{ __("Received From") }}</th> --}}
                                                    <th>{{ __('Received To') }}</th>
                                                    <th>{{ __('Type/Method') }}</th>
                                                    <th>{{ __('Trans. No') }}</th>
                                                    <th>{{ __('Cheque No') }}</th>
                                                    {{-- <th>{{ __("Cheque S/L No") }}</th> --}}
                                                    <th>{{ __('Received Amount') }}</th>
                                                    {{-- <th>{{ __("Created By") }}</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="10" class="text-end text-white">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                                    <th id="receipts_total_amount" class="text-white"></th>
                                                    {{-- <th></th> --}}
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

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <!-- Edit Shipping modal -->
    <div class="modal fade" id="editShipmentDetailsModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <div class="modal fade" id="addOrEditReceiptModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <div class="modal fade" id="addOrEditPaymentModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    @include('contacts.manage_suppliers.js_partials.manage_js')
@endpush
