@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        table {
            font-family: Arial, Helvetica, sans-serif !important;
        }
    </style>
@endpush
@section('title', 'Supplier Report - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Supplier Report') }}</h5>
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
                                <div class="form-group row">
                                    @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                        <div class="col-md-4">
                                            <label><strong>{{ location_label() }} </strong></label>
                                            <select name="branch_id" id="branch_id" class="form-control select2" autofocus>
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

                                    <div class="col-md-4">
                                        <label><strong>{{ __('Supplier') }} </strong></label>
                                        <select name="supplier_account_id" class="form-control select2" id="supplier_account_id">
                                            <option data-supplier_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                            @foreach ($supplierAccounts as $supplier)
                                                <option data-supplier_name="{{ $supplier->name . '/' . $supplier->phone }}" value="{{ $supplier->id }}">{{ $supplier->name . '/' . $supplier->phone }}</option>
                                            @endforeach
                                        </select>
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
                                                    <a href="{{ route('reports.suppliers.print') }}" class="btn btn-sm btn-primary float-end m-0" id="printReport"><i class="fas fa-print "></i> {{ __('Print') }}</a>
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

            <div class="col-md-12">
                <div class="card">
                    <div class="widget_content mt-2">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Supplier') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Opening Balance') }}</th>
                                        <th>{{ __('Total Purchase') }}</th>
                                        <th>{{ __('Total Sale') }}</th>
                                        <th>{{ __('Total Return') }}</th>
                                        <th>{{ __('Total Paid') }}</th>
                                        <th>{{ __('Total Received') }}</th>
                                        <th>{{ __('Curr. Balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <th colspan="2" class="text-end text-white">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                        <th id="opening_balance" class="text-white text-end"></th>
                                        <th id="total_purchase" class="text-white text-end"></th>
                                        <th id="total_sale" class="text-white text-end"></th>
                                        <th id="total_return" class="text-white text-end"></th>
                                        <th id="total_paid" class="text-white text-end"></th>
                                        <th id="total_received" class="text-white text-end"></th>
                                        <th id="current_balance" class="text-white text-end"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('contacts.reports.supplier_report.js_partial.index_js')
@endpush
