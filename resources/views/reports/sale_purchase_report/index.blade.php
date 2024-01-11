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
@section('title', 'Purchases & Sales Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>@lang('menu.purchases') & @lang('menu.sales_report')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
                            </a>
                        </div>

                        <div class="p-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form_element rounded mt-0 mb-3">
                                        <div class="element-body">
                                            <div class="row align-items-end">
                                                <div class="col-md-10">
                                                    <form id="sale_purchase_filter" action="{{ route('reports.profit.sales.filter.purchases.amounts') }}" method="get">
                                                        <div class="form-group row">

                                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                                <div class="col-md-3">
                                                                    <label><strong>@lang('menu.business_location') : </strong></label>
                                                                    <select name="branch_id" class="form-control submit_able select2" id="branch_id" autofocus>
                                                                        <option value="">@lang('menu.all')</option>
                                                                        <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }} (@lang('menu.head_office'))</option>
                                                                        @foreach ($branches as $branch)
                                                                            <option value="{{ $branch->id }}">
                                                                                {{ $branch->name . '/' . $branch->branch_code }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @else
                                                                <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                                            @endif


                                                            <div class="col-md-3">
                                                                <label><strong>@lang('menu.from_date') : </strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                                    </div>
                                                                    <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label><strong>@lang('menu.to_date') : </strong></label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                                    </div>
                                                                    <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="row justify-content-between align-items-end">
                                                                    <div class="col-6">
                                                                        <label><strong></strong></label>
                                                                        <div class="input-group">
                                                                            <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label></label>
                                                                            <a href="#" class="btn btn-sm btn-primary float-end mt-1" id="print_report"><i class="fas fa-print"></i>@lang('menu.print')</a>
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
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div id="data_list">
                                    <div class="sale_and_purchase_amount_area">
                                        <div class="row g-3">
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="heading">
                                                            <h6 class="text-primary"><b>@lang('menu.purchases')</b></h6>
                                                        </div>

                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th>@lang('menu.total_purchase') : </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>@lang('menu.purchase_including_tax') : </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <th> @lang('menu.purchase_due')</th>
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
                                                            <h6 class="text-primary"><b>@lang('menu.sales')</b></h6>
                                                        </div>

                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th>@lang('menu.total_sale') : </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>@lang('menu.sale_including_tax') : </th>
                                                                    <td>{{ $generalSettings['business_or_shop__currency_symbol'] }} 0.00</td>
                                                                </tr>

                                                                <tr>
                                                                    <th>{{ __('Sale Due') }} </th>
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
        function salePurchaseDueAmounts() {
            $('.data_preloader').show();
            $.ajax({
                url: "{{ route('reports.profit.sales.purchases.amounts') }}",
                type: 'get',
                success: function(data) {
                    $('#data_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        salePurchaseDueAmounts();

        //Send sale purchase amount filter request
        $('#sale_purchase_filter').on('submit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'get',
                data: request,
                success: function(data) {
                    $('#data_list').html(data);
                    $('.data_preloader').hide();
                }
            });
        });

        //Print Profit/Loss
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.sales.purchases.print') }}";
            var branch_id = $('#branch_id').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
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
            element: document.getElementById('datepicker'),
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
            element: document.getElementById('datepicker2'),
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
