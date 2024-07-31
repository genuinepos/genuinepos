@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .widget_content p {
            padding: 0px 0px;
        }
    </style>
@endpush
@section('title', 'Account Ledger - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Account Ledger') }} - <strong>{{ $account->name }}</strong></h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row g-1">
                            <div class="col-md-4">
                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <table class="display table modal-table table-sm m-0">
                                            <tbody>
                                                <tr>
                                                    <th colspan="3" class="text-center">{{ __('Account Summary') }}</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-end"></th>
                                                    <th class="text-end">{{ __('Debit') }}</th>
                                                    <th class="text-end">{{ __('Credit') }}</th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Opening Balance') }} :</th>
                                                    <th class="text-end" id="debit_opening_balance"></th>
                                                    <th class="text-end" id="credit_opening_balance"></th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Current Total') }} :</th>
                                                    <th class="text-end" id="total_debit"></th>
                                                    <th class="text-end" id="total_credit"></th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Closing Balance') }} :</th>
                                                    <th class="text-end" id="debit_closing_balance"></th>
                                                    <th class="text-end" id="credit_closing_balance"></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form_element rounded mt-0">
                                    <div class="element-body">
                                        <form id="filter_account_ledgers" method="get">
                                            <div class="form-group row g-2 align-items-end">
                                                @if ($account?->group?->is_global == 1)
                                                    {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && !auth()->user()->branch_id) --}}
                                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                                        <div class="col-md-3">
                                                            <label><strong>{{ location_label() }} </strong></label>
                                                            <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                                <option value="">{{ __('All') }}</option>

                                                                @if ($generalSettings['subscription__has_business']== 1)
                                                                    <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                                @endif

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
                                                @endif

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('From Date') }} :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('To Date') }} :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('Note/Remarks') }} :</strong></label>
                                                    <select name="note" class="form-control" id="note">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option selected value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('Voucher Details') }} :</strong></label>
                                                    <select name="voucher_details" class="form-control" id="voucher_details">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('Transaction Details') }} :</strong></label>
                                                    <select name="transaction_details" class="form-control" id="transaction_details">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>{{ __('Inventory List') }} :</strong></label>
                                                    <select name="inventory_list" class="form-control" id="inventory_list">
                                                        <option value="0">{{ __('No') }}</option>
                                                        <option value="1">{{ __('Yes') }}</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start m-0">
                                                                    <i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label></label>
                                                            <div class="input-group">
                                                                <a href="#" class="btn btn-sm btn-primary float-end m-0" id="print_report"><i class="fas fa-print "></i>{{ __('Print') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-1">
                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
                                </div>

                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">{{ __('Date') }}</th>
                                                <th class="text-start">{{ __('Particulars') }}</th>
                                                <th class="text-start">{{ __('Voucher Type') }}</th>
                                                <th class="text-start">{{ __('Voucher No') }}</th>
                                                <th class="text-start">{{ __('Debit') }}</th>
                                                <th class="text-start">{{ __('Credit') }}</th>
                                                <th class="text-start">{{ __('Running Balance') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="4" class="text-white" style="text-align: right!important;"> {{ __('Current Total') }} : </th>
                                                <th id="table_total_debit" class="text-white"></th>
                                                <th id="table_total_credit" class="text-white"></th>
                                                <th id="table_current_balance" class="text-white"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection

@push('scripts')
    @include('accounting.accounts.ledger.js_partial.index_js')
@endpush
