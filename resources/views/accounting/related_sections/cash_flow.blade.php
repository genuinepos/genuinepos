@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Cash Flow Statements - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">

            <div class="sec-name">
                <div class="name-head">
                    <h5>@lang('menu.total_cash_statement')</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <form id="filter_cash_flow">
                                <div class="form-group row">

                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                        <div class="col-md-2">
                                            <label><strong>@lang('menu.business_location') </strong></label>
                                            <select name="branch_id" class="form-control" id="branch_id" autofocus>
                                                <option SELECTED value="NULL">{{ $generalSettings['business__business_name'] }} (@lang('menu.head_office'))</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif


                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row align-items-end">
                                            <div class="col-6">
                                                <label><strong></strong></label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
                                                </div>
                                            </div>

                                            <div class="col-6 d-flex justify-content-end">
                                                <a href="#" class="btn btn-sm btn-primary float-end " id="print_report"><i class="fas fa-print "></i>@lang('menu.print')</a>
                                            </div>
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
                    <div class="col-md-10">
                        <h6>{{ __('All Cash Flow Statements') }}</h6>
                    </div>
                </div>

                <div class="widget_content mt-2">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="table modal-table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <td class="aiability_area">
                                        <table class="table table-sm">
                                            <tbody>
                                                {{-- Cash Flow from operations --}}
                                                <tr>
                                                    <th class="text-start" colspan="2">
                                                        <strong>@lang('menu.cash_flow_from_operations') </strong>
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.net_profit_before_tax') </em>
                                                    </td>

                                                    <td class="text-start">
                                                        <em>0.00</em>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.customer_balance') </em>
                                                    </td>

                                                    <td class="text-start">
                                                        <em>- 0.00</em>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.current_stock_value') </em>
                                                    </td>

                                                    <td class="text-start">
                                                        <em>0.00</em>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.current_asset') </em>
                                                    </td>

                                                    <td class="text-start">
                                                        <em>0.00</em>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.current_liability') </em>
                                                    </td>

                                                    <td class="text-start">
                                                        <em>0.00</em>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.tax_payable') </em>
                                                    </td>

                                                    <td class="text-start">
                                                        <em>0.00</em>
                                                    </td>
                                                </tr>

                                                <tr class="bg-info">
                                                    <td class="text-start text-white">
                                                        <b>@lang('menu.total_operations') </b>
                                                    </td>

                                                    <td class="text-start text-white">
                                                        <b>0.00</b>
                                                    </td>
                                                </tr>

                                                {{-- Cash Flow from investing --}}
                                                <tr>
                                                    <th class="text-start" colspan="2">
                                                        <strong>@lang('menu.cash_flow_from_investing') </strong>
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.fixed_asset') </em>
                                                    </td>
                                                    <td class="text-start">0.00</td>
                                                </tr>

                                                <tr class="bg-info">
                                                    <td class="text-start text-white">
                                                        <b><em>@lang('menu.total_investing') </em> </b>
                                                    </td>

                                                    <td class="text-start text-white">
                                                        <b><em>0.00</em> </b>
                                                    </td>
                                                </tr>

                                                {{-- Cash Flow from financing --}}
                                                <tr>
                                                    <th class="text-start" colspan="2">
                                                        <strong>@lang('menu.cash_flow_form_financing') </strong>
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.capital_ac') </em>
                                                    </td>
                                                    <td class="text-start">0.00</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">
                                                        <em>@lang('menu.loan_and_advance') </em>
                                                    </td>
                                                    <td class="text-start">0.00</td>
                                                </tr>

                                                <tr class="bg-info">
                                                    <td class="text-start text-white">
                                                        <b><em>@lang('menu.total_financing') </em> </b>
                                                    </td>

                                                    <td class="text-start text-white">
                                                        <b><em>0.00</em> </b>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th class="text-start text-white"><strong>@lang('menu.total_cash_flow') : ({{ $generalSettings['business__currency_symbol'] }} )</strong> </th>
                                                    <th class="text-start text-white">
                                                        <span class="total_cash_flow">0.00</span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function getCashFlow() {
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: "{{ route('accounting.cash.flow.amounts') }}",
                type: 'GET',
                data: {
                    branch_id,
                    from_date,
                    to_date
                },
                success: function(data) {
                    $('#data-list').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getCashFlow();

        //Print purchase Payment report
        $(document).on('submit', '#filter_cash_flow', function(e) {
            e.preventDefault();
            getCashFlow();
        });

        //Print purchase Payment report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('accounting.print.cash.flow') }}";
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
