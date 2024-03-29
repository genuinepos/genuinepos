@extends('layout.master')
@push('stylesheets')
    <style>
        .sale_and_purchase_amount_area table tbody tr th,
        td {
            color: #32325d;
        }

        .report_data_area {
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
@section('title', 'Customer Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">

                        <div class="sec-name">
                            <div class="name-head">
                                <h5>@lang('menu.customer_report')</h5>
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
                                            <div class="row justify-content-between">
                                                <div class="col-lg-4 col-md-6">
                                                    <form id="filter_tax_report_form" action="" method="get">
                                                        @csrf
                                                        <select name="customer_id" class="form-control submit_able  select2" id="customer_id" autofocus>
                                                            <option value="">@lang('menu.all')</option>
                                                            @foreach ($customers as $customer)
                                                                <option value="{{ $customer->id }}">{{ $customer->name . ' (' . $customer->phone . ')' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                </div>

                                                <div class="col-md-6 mt-md-0 mt-3">
                                                    <div class="form-group">
                                                        <label></label>
                                                        <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i>@lang('menu.print')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="report_data_area">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                    </div>
                                    <div class="card">
                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr class="text-start">
                                                        <th>@lang('menu.customer')</th>
                                                        <th>@lang('menu.opening_balance')</th>
                                                        <th>@lang('menu.total_sale')</th>
                                                        <th>@lang('menu.total_paid')</th>
                                                        <th>@lang('menu.total_due')</th>
                                                        <th>@lang('menu.total_return_due')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th class="text-end text-white">@lang('menu.total') : {{ $generalSettings['business_or_shop__currency'] }}</th>
                                                        <th id="total_op_blc_due" class="text-white">0.00</th>
                                                        <th id="total_sale" class="text-white">0.00</th>
                                                        <th id="total_paid" class="text-white">0.00</th>
                                                        <th id="total_sale_due" class="text-white">0.00</th>
                                                        <th id="total_return_due" class="text-white">0.00</th>
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
    </div>
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
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
                },
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [
                [3, 'asc']
            ],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.customer.index') }}",
                "data": function(d) {
                    d.customer_id = $('#customer_id').val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'opening_balance',
                    name: 'opening_balance',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_sale',
                    name: 'total_sale',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_paid',
                    name: 'total_paid',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_sale_due',
                    name: 'total_sale_due',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_sale_return_due',
                    name: 'total_sale_return_due',
                    className: 'text-end fw-bold'
                },
            ],
            fnDrawCallback: function() {
                var totalSale = sum_table_col($('.data_tbl'), 'total_sale');
                $('#total_sale').text(bdFormat(totalSale));
                var totalPaid = sum_table_col($('.data_tbl'), 'total_paid');
                $('#total_paid').text(bdFormat(totalPaid));
                var totalOpeningBalance = sum_table_col($('.data_tbl'), 'opening_balance');
                $('#total_op_blc_due').text(bdFormat(totalOpeningBalance));
                var totalDue = sum_table_col($('.data_tbl'), 'total_purchase_due');
                $('#total_sale_due').text(bdFormat(totalDue));
                var totalReturnDue = sum_table_col($('.data_tbl'), 'total_purchase_return_due');
                $('#total_return_due').text(bdFormat(totalReturnDue));
            },
        });

        $(document).on('change', '.submit_able', function() {
            table.ajax.reload();
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

        //Print supplier report
        $(document).on('click', '#print_report', function(e) {
            e.preventDefault();

            var url = "{{ route('reports.customer.print') }}";
            var customer_id = $('#customer_id').val();
            console.log(customer_id);
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    customer_id
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
@endpush
