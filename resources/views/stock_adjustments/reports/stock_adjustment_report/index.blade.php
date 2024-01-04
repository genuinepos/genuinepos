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
@section('title', 'Stock Adjustment Report - ')
@section('content')
    <div class="body-woaper">
        <div class="border-class">
            <div class="main__content">
                <div class="sec-name">
                    <div class="name-head">
                        <h5>{{ __('Stock Adjustment Report') }}</h5>
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
                                    <form id="filter_form">
                                        <div class="form-group row align-items-end">
                                            @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0)
                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Shop/Business') }} </strong></label>
                                                    <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                        <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                        <option data-branch_name="{{ $generalSettings['business__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business__business_name'] }}({{ __('Business') }})</option>
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
                                                    <input name="from_date" class="form-control" id="from_date">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label><strong>{{ __('To Date') }}</strong></label>
                                                <div class="input-group">
                                                    <input name="to_date" class="form-control" id="to_date">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row align-items-end">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <button type="submit" class="btn text-white btn-sm btn-info float-start m-0">
                                                                <i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
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

                    <div class="sale_purchase_and_profit_area mb-1">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                        </div>
                        <div id="data_list">
                            <div class="sale_and_purchase_amount_area">
                                <div class="row g-3">
                                    <div class="col-md-12 col-sm-12 col-lg-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table modal-table table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <th style="padding: 5px;line-height:1;font-size:12px;" class="text-end">{{ __('Total Normal') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                                                            <td style="padding: 0px;line-height:1;font-size:12px;" class="text-end" id="total_normal"></td>
                                                        </tr>

                                                        <tr>
                                                            <th style="padding: 5px;line-height:1;font-size:12px;" class="text-end">{{ __('Total Abnormal') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                                                            <td style="padding: 5px;line-height:1;font-size:12px;" class="text-end" id="total_abnormal"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-lg-6">
                                        <div class="card">
                                            <div class="card-body ">
                                                <table class="table modal-table table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <th style="padding: 5px;line-height:1;font-size:12px;" class="text-end">{{ __('Total Stock Adjustment') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                                                            <td style="padding: 5px;line-height:1;font-size:12px;" class="text-end" id="total_adjustment"></td>
                                                        </tr>

                                                        <tr>
                                                            <th style="padding: 5px;line-height:1;font-size:12px;" class="text-end">{{ __('Total Amount Recovered') }} : {{ $generalSettings['business__currency_symbol'] }}</th>
                                                            <td style="padding: 5px;line-height:1;font-size:12px;" class="text-end" id="total_recovered"></td>
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

                    <div class="card">
                        <div class="table-responsive" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-start">{{ __('Date') }}</th>
                                        <th class="text-start">{{ __('Voucher No') }}</th>
                                        <th class="text-start">{{ __('Shop/Business') }}</th>
                                        <th class="text-start">{{ __('Created By') }}</th>
                                        <th class="text-start">{{ __('Type') }}</th>
                                        <th class="text-start">{{ __('Reason') }}</th>
                                        <th class="text-start">{{ __('Net Total Amount') }}</th>
                                        <th class="text-start">{{ __('Recovered Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <th colspan="6" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business__currency_symbol'] }})</th>
                                        <th id="net_total_amount" class="text-white text-end"></th>
                                        <th id="recovered_amount" class="text-white text-end"></th>
                                    </tr>
                                </tfoot>
                            </table>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var __currency_symbol = "";

        function getAdjustmentAmounts() {
            $('.data_preloader').show();
            var branch_id = $('#branch_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $.ajax({
                url: "{{ route('reports.stock.adjustments.report.all.amount') }}",
                data: {
                    branch_id,
                    from_date,
                    to_date
                },
                type: 'get',
                success: function(data) {
                    console.log(data);
                    $('#total_normal').html(data[0] ? bdFormat(data[0].total_normal) : parseFloat(0).toFixed(2));
                    $('#total_abnormal').html(data[0] ? bdFormat(data[0].total_abnormal) : parseFloat(0).toFixed(2));
                    $('#total_adjustment').html(data[0] ? bdFormat(data[0].total_net_amount) : parseFloat(0).toFixed(2));
                    $('#total_recovered').html(data[0] ? bdFormat(data[0].total_recovered_amount) : parseFloat(0).toFixed(2));
                    $('.data_preloader').hide();
                }
            });
        }
        getAdjustmentAmounts();

        var adjustment_table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: 'Pdf',
                    className: 'btn btn-primary'
                }
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.stock.adjustments.report.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.type = $('#type').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'stock_adjustments.date'
                },
                {
                    data: 'voucher_no',
                    name: 'stock_adjustments.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'created_by',
                    name: 'users.name'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'reason',
                    name: 'reason'
                },
                {
                    data: 'net_total_amount',
                    name: 'net_total_amount',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'recovered_amount',
                    name: 'recovered_amount',
                    className: 'text-end fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var net_total_amount = sum_table_col($('.data_tbl'), 'net_total_amount');
                $('#net_total_amount').text(bdFormat(net_total_amount));
                var recovered_amount = sum_table_col($('.data_tbl'), 'recovered_amount');
                $('#recovered_amount').text(bdFormat(recovered_amount));
                $('.data_preloader').hide();
            }
        });

        function sum_table_col(table, class_name) {
            var sum = 0;
            table.find('tbody').find('tr').each(function() {
                if (parseFloat($(this).find('.' + class_name).data('value'))) {
                    sum += parseFloat(
                        $(this).find('.' + class_name).data('value')
                    );
                }
            });
            return sum;
        }

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            adjustment_table.ajax.reload();
            getAdjustmentAmounts();
        });

        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.stock.adjustments.report.print') }}";

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
                        printDelay: 500,
                        header: "",
                        pageTitle: "",
                    });
                }
            });
        });

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('#detailsModal').modal('show');
                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        // Make print
        $(document).on('click', '#modalDetailsPrintBtn', function(e) {
            e.preventDefault();

            var body = $('.print_modal_details').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
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
