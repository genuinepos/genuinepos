@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .sale_and_purchase_amount_area table tbody tr th,
        td {
            color: #32325d;
        }

        .sale_purchase_and_profit_area {
            position: relative;
        }

        .data_preloader {
            top: 2.3%
        }

        .sale_and_purchase_amount_area table tbody tr th {
            text-align: left;
        }

        .sale_and_purchase_amount_area table tbody tr td {
            text-align: left;
        }

        .daily_profit_loss_amount_area table tbody tr td {
            font-size: 12px;
            padding: 3px !important;
        }
    </style>
@endpush
@section('title', 'Profit/Loss - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Profit/Loss') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="p-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <form id="profit_loss_filter_form" method="get">
                                            <div class="form-group row">
                                                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                    <div class="col-md-3">
                                                        <label><strong>{{ location_label() }} </strong></label>
                                                        <select name="branch_id" id="branch_id" class="form-control select2" autofocus>
                                                            <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                            <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option data-branch_name="{{ $branch->name }}" value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label><strong>{{ __('Chain Store') }} </strong></label>
                                                        <select name="branch_id" class="form-control select2" id="child_branch_id">
                                                            <option data-child_branch_name="" value="">{{ __('Select Store First') }}</option>
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }} </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }} : </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
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
                                                                <a href="#" class="btn btn-sm btn-primary float-end m-0" id="printReport"><i class="fas fa-print "></i> {{ __('Print') }}</a>
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
                    </div>
                </div>

                <div class="sale_purchase_and_profit_area">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>
                    <div id="data_list">
                        <div class="daily_profit_loss_amount_area">
                            <div class="row g-3">
                                <div class="col-md-12 col-sm-12 col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="display table modal-table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-end">
                                                            <strong>{{ __('Total Sale') }} <small>({{ __('Inc. Tax') }})</small> : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                                        </td>

                                                        <td class="text-end text-success">
                                                            0.00
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Individual Sold Product Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Sale Tax') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end">
                                                            <strong>{{ __('Sold Product Total Unit Cost') }} <small>({{ __('Inc. Tax') }})</small> : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong>
                                                        </td>

                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end fw-bold"><strong>{{ __('Gross Profit') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>

                                                        <td class="text-end text-success fw-bold">
                                                            0.00
                                                        </td>
                                                    </tr>



                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Stock Adjustment') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Stock Adjustment Recovered') }} {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                                        <td class="text-end text-success">
                                                            0.00
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Expense') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Sales Return') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>{{ __('Total Payroll') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                                        <td class="text-end text-danger">
                                                            (0.00)
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end fw-bold"><strong>{{ __('Net Profit') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</strong></td>
                                                        <td class="text-end text-success fw-bold">
                                                            0.00
                                                        </td>
                                                    </tr>
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
        </div>
    </div>
@endsection
@push('scripts')
    @include('accounting.reports.profit_loss_report.js_partial.index_js')
@endpush
