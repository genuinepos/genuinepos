@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Stock Adjustment List- ')
@section('content')
    <div class="body-woaper">
        <div class="border-class">
            <div class="main__content">
                <div class="sec-name">
                    <div class="name-head">
                        <h5>{{ __('Stock Adjustments') }}</h5>
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
                                            <div class="col-md-2">
                                                <label><strong>{{ location_label() }}</strong></label>
                                                <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                    <option value="">{{ __('All') }}</option>
                                                    <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
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
                                            </div>
                                        @endif

                                        <div class="col-md-2">
                                            <label><strong>{{ __('Type') }}</strong></label>
                                            <select name="type" id="type" class="form-control" autofocus>
                                                <option value="">{{ __('All') }}</option>
                                                @foreach (\App\Enums\StockAdjustmentType::cases() as $type)
                                                    <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>{{ __('From Date') }}</strong></label>
                                            <div class="input-group">
                                                <input name="from_date" class="form-control" id="from_date" value="{{ $generalSettings['business_or_shop__financial_year_start_date'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>{{ __('To Date') }}</strong></label>
                                            <div class="input-group">
                                                <input name="to_date" class="form-control" id="to_date" value="{{ $generalSettings['business_or_shop__financial_year_end_date'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong></strong></label>
                                            <div class="input-group">
                                                <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="section-header">
                        <div class="col-6">
                            <h6>{{ __('List of Stock Adjustments') }}</h6>
                        </div>

                        <div class="col-6 d-flex justify-content-end">
                            @if (auth()->user()->can('stock_adjustment_add'))
                                <a href="{{ route('stock.adjustments.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus-square"></i> {{ __('Add') }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="widget_content">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                        </div>
                        <div class="table-responsive" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-start">{{ __('Action') }}</th>
                                        <th class="text-start">{{ __('Date') }}</th>
                                        <th class="text-start">{{ __('Voucher No') }}</th>
                                        <th class="text-start">{{ location_label() }}</th>
                                        <th class="text-start">{{ __('Ledger Account') }}</th>
                                        <th class="text-start">{{ __('Reason') }}</th>
                                        <th class="text-start">{{ __('Type') }}</th>
                                        <th class="text-start">{{ __('Total Amount') }}</th>
                                        <th class="text-start">{{ __('Recovered Amount') }}</th>
                                        <th class="text-start">{{ __('Created By') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <th colspan="7" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                        <th id="net_total_amount" class="text-white text-end"></th>
                                        <th id="recovered_amount" class="text-white text-end"></th>
                                        <th class="text-white text-end">---</th>
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

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    @include('stock_adjustments.js_partials.index_js')
@endpush
