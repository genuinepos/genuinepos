@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    </style>
@endpush
@section('title', 'Sales Vs Purchase Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Compare Sales Vs Purchase') }}</h5>
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
                                            <div class="row align-items-end">
                                                <div class="col-md-10">
                                                    <form id="sale_vs_purchase_filter_form" method="get">
                                                        <div class="form-group row">
                                                            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                                                                <div class="col-md-4">
                                                                    <label><strong>{{ location_label() }} </strong></label>
                                                                    <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                                        <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                                        <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
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

                                                            <div class="col-md-3">
                                                                <label><strong>{{ __('From Date') }} : </strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                                    </div>
                                                                    <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label><strong>{{ __('To Date') }} : </strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
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
                                                                            <a href="#" class="btn btn-sm btn-primary float-end m-0" id="print_report"><i class="fas fa-print "></i> {{ __('Print') }}</a>
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
                                    <div class="sale_and_purchase_amount_area">
                                        <div class="row g-3">
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="heading">
                                                            <h6 class="text-primary"><b>{{ __('Purchase') }}</b></h6>
                                                        </div>

                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th>{{ __('Total Purchase') }} : </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>{{ __('Total Purchase Return') }} : </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>{{ __('Total Purchase Included Return') }}</th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="heading">
                                                            <h6 class="text-primary"><b>{{ __('Sales') }}</b></h6>
                                                        </div>

                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th>{{ __('Total Sales') }} : </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>{{ __('Total Sales Return') }} : </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>{{ __('Total Sales Included Return') }} </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
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
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function salesVsPurchaseAmounts() {
            $('.data_preloader').show();

            var url = "{{ route('reports.sales.vs.purchase.amounts') }}";

            var branch_id = $('#branch_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    from_date,
                    to_date
                },
                success: function(data) {
                    $('#data_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        salesVsPurchaseAmounts();

        //Send sale purchase amount filter request
        $('#sale_vs_purchase_filter_form').on('submit', function(e) {
            e.preventDefault();
            salesVsPurchaseAmounts();
        });

        //Print Profit/Loss
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.sales.vs.purchase.print') }}";
            var branch_id = $('#branch_id').val();
            var branch_name = $('#branch_id').find('option:selected').data('branch_name');
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    from_date,
                    to_date
                },
                success: function(data) {
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('to_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
