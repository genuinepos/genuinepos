@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        table.table.modal-table.table-sm.table-bordered.financial_report_table td {
            font-size: 13px;
            font-weight: 400;
        }
    </style>
@endpush
@section('title', 'Profit Loss A/C - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">

            <div class="sec-name">
                <div class="name-head">
                    <h5>@lang('menu.financial_report')</h5>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <form id="filter_financial_report">
                                <div class="form-group row">

                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                        <div class="col-md-2">
                                            <label><strong>@lang('menu.business_location') : </strong></label>
                                            <select name="branch_id" class="form-control" id="branch_id" autofocus>
                                                <option value="">@lang('menu.all')</option>
                                                <option SELECTED value="NULL">{{ $generalSettings['business_or_shop__business_name'] }} (@lang('menu.head_office'))</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif


                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.from_date') : </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.to_date') : </strong></label>
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

                                            <div class="col-6">
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

            <div class="col-md-8">
                <div class="card">

                    <div class="section-header">
                        <h6>@lang('menu.financial_report')</h6>
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
                                                    {{-- Cash Flow from investing --}}
                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.asset') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.fixed_asset') : </em>
                                                        </td>
                                                        <td class="text-start"><b><em>0.00</em></b> </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.sales') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_sale')</em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em></b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_sale_due') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em></b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_sale_return') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.purchase') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_purchase') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_purchase_due') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_purchase_return') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.expenses') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_direct_expense') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Indirect Expense') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.products') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.closing_stock') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_stock_adjustment') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Stock Adjustment Recovered Amount') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.loan_and_liabilities') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Loan Liabilities') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Loan & Liabilities Due Paid') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.payable_loan_liabilities_due') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.loan_and_advance') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.total_loan_advance') : </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Loan & Advance Due Received') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Receivable Loan & Advance Due') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.profit_loss') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Daily Profit') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Gross Profit') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Total Net Profit') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th class="text-start bg-secondary text-white" colspan="2">
                                                            <span>@lang('menu.account_balance') : </span>
                                                        </th>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>{{ __('Cash-In-Hand Balance') }} </em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-start">
                                                            <em>@lang('menu.bank_ac_balance')</em>
                                                        </td>

                                                        <td class="text-start">
                                                            <b><em>0.00</em> </b>
                                                        </td>
                                                    </tr>
                                                </tbody>
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

        function getFinancialReport() {

            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            console.log(branch_id);
            $.ajax({
                url: "{{ route('reports.financial.amounts') }}",
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
        getFinancialReport();

        // //Print purchase Payment report
        $(document).on('submit', '#filter_financial_report', function(e) {
            e.preventDefault();
            getFinancialReport();
        });

        //Print financial report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();
            var url = "{{ route('reports.financial.report.print') }}";
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
