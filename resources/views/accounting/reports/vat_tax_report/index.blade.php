@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .widget_content p {
            padding: 0px 0px;
        }
    </style>
@endpush
@section('title', 'Vat/Tax Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Vat/Tax Report') }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form id="filter_form">
                                            <div class="form-group row align-items-end">
                                                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                    <div class="col-md-2">
                                                        <label><strong>{{ __('Shop/Business') }}</strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                            <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                            <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
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

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Tax Ledger A/c') }}</strong></label>
                                                    <select name="tax_account_id" id="tax_account_id" class="form-control select2">
                                                        <option data-tax_account_name="All" value="">{{ __('All') }}</option>
                                                        @foreach ($taxAccounts as $taxAccount)
                                                            <option data-tax_account_name="{{ $taxAccount->name }}" value="{{ $taxAccount->id }}">
                                                                {{ $taxAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- <div class="col-md-2">
                                                    <label><strong>{{ __('Customer/Supplier') }}</strong></label>
                                                    <select name="contact_account_id" class="form-control select2" id="contact_account_id" autofocus>
                                                        <option data-contact_account_name="{{ __("All") }}" value="">{{ __('All') }}</option>
                                                        @foreach ($contacts as $contact)
                                                            <option data-contact_account_name="{{ $contact->name . '/' . $contact->phone }}" value="{{ $contact->id }}">{{ $contact->name . '/' . $contact->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row align-items-end">
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <button type="submit" id="filter_button" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <a href="#" class="btn btn-sm btn-primary float-end m-0" id="printReport"><i class="fas fa-print "></i> {{ __('Print') }}</a>
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
                                <div class="card">
                                    <div class="card-header">
                                        <h6>{{ __('Overall') }}({{ __('Output Vat/Tax - Input Vat/Tax') }})</h6>
                                    </div>
                                    <div class="card-body">
                                        <h4 class="text-muted">({{ __('Output - Input') }}) :
                                            (<span id="span_total_output_tax"></span> - <span id="span_total_input_tax"></span>) = <span id="net_amount"></span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-1">
                            <div class="tab_list_area p-1">
                                <div class="btn-group">
                                    <a id="tab_btn" data-show="input_tax" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                        <i class="fas fa-long-arrow-alt-down"></i> {{ __('Input Vat/Tax') }}
                                    </a>

                                    <a id="tab_btn" data-show="output_tax" class="btn btn-sm btn-primary tab_btn" href="#">
                                        <i class="fas fa-long-arrow-alt-up"></i> {{ __('Output Vat/Tax') }}
                                    </a>
                                </div>
                            </div>

                            <div class="tab_contant input_tax">
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table id="vat-tax-input-table" class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">{{ __('Date') }}</th>
                                                    <th class="text-start">{{ __('Particulars') }}</th>
                                                    <th class="text-start">{{ __('Shop/Business') }}</th>
                                                    <th class="text-start">{{ __('Voucher Type') }}</th>
                                                    <th class="text-start">{{ __('Voucher No') }}</th>
                                                    <th class="text-start">{{ __('Tax Ledger A/c') }}</th>
                                                    <th class="text-start">{{ __('Input Tax Amount') }}</th>
                                                    <th class="text-start">{{ __('On Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white" style="text-align: right!important;"> {{ __('Total') }} : </th>
                                                    <th id="table_total_input_tax" class="text-white"></th>
                                                    <th class="text-white" style="text-align: right!important;">---</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab_contant output_tax d-hide">
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table id="vat-tax-output-table" class="display data_tbl data__table w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">{{ __('Date') }}</th>
                                                    <th class="text-start">{{ __('Particulars') }}</th>
                                                    <th class="text-start">{{ __('Shop/Business') }}</th>
                                                    <th class="text-start">{{ __('Voucher Type') }}</th>
                                                    <th class="text-start">{{ __('Voucher No') }}</th>
                                                    <th class="text-start">{{ __('Tax Ledger A/c') }}</th>
                                                    <th class="text-start">{{ __('Output Tax Amount') }}</th>
                                                    <th class="text-start">{{ __('On Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="6" class="text-white" style="text-align: right!important;"> {{ __('Total') }} : </th>
                                                    <th id="table_total_output_tax" class="text-white"></th>
                                                    <th class="text-white" style="text-align: right!important;">---</th>
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

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection

@push('scripts')
    @include('accounting.reports.vat_tax_report.js_partial.index_js')
@endpush
