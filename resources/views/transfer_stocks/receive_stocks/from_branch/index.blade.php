@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', auth()->user()->branch_id ? 'Receive Stock From Store' : 'Receive Stock From Company')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>
                                    @if (auth()->user()->branch_id)
                                        {{ __('Receive Stock From Store') }}
                                    @else
                                        {{ __('Receive Stock From Company') }}
                                    @endif
                                </h5>
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
                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Reciving Status') }}</strong></label>
                                                    <select name="receive_status" id="receive_status" class="form-control">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach (\App\Enums\TransferStockReceiveStatus::cases() as $receiveStatus)
                                                            <option value="{{ $receiveStatus->value }}">{{ $receiveStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

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
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
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
                                    <h6>
                                        @if (auth()->user()->branch_id)
                                            {{ __('List of Receivable Transferred Stock (From Store)') }}
                                        @else
                                            {{ __('List of Receivable Transferred Stock (From Company)') }}
                                        @endif

                                    </h6>
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
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Voucher No') }}</th>
                                                <th>{{ __('Created From') }}</th>
                                                <th>{{ __('Send From') }}</th>
                                                <th>{{ __('Send To') }}</th>
                                                <th>{{ __('Receiving Status') }}</th>
                                                <th>{{ __('Total Item') }}</th>
                                                <th>{{ __('Total Qty') }}</th>
                                                <th>{{ __('Total Stock Value') }}</th>
                                                <th>{{ __('Total Send Qty') }}</th>
                                                <th>{{ __('Total Received Qty') }}</th>
                                                <th>{{ __('Total Pending Qty') }}</th>
                                                <th>{{ __('Created By') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="8" class="text-white text-end">{{ __('Total') }} : </th>
                                                <th id="total_qty" class="text-white text-end"></th>
                                                <th id="total_stock_value" class="text-white text-end"></th>
                                                <th id="total_send_qty" class="text-white text-end"></th>
                                                <th id="total_received_qty" class="text-white text-end"></th>
                                                <th id="total_pending_qty" class="text-white text-end"></th>
                                                <th class="text-white text-end">---</th>
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

    <div id="details"></div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('receive.stock.from.branch.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.receive_status = $('#receive_status').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'action'
                },
                {
                    data: 'date',
                    name: 'transfer_stocks.date'
                },
                {
                    data: 'voucher_no',
                    name: 'transfer_stocks.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'send_from',
                    name: 'sender_warehouse.warehouse_name'
                },
                {
                    data: 'send_to',
                    name: 'receiver_branch.name',
                    className: 'text-start'
                },
                {
                    data: 'receive_status',
                    name: 'send_by.last_name',
                    className: 'text-start fw-bold'
                },
                {
                    data: 'total_item',
                    name: 'receiver_branch_parent.name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_qty',
                    name: 'total_qty',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_stock_value',
                    name: 'total_stock_value',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_send_qty',
                    name: 'total_send_qty',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_received_qty',
                    name: 'total_received_qty',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'total_pending_qty',
                    name: 'total_pending_qty',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'send_by',
                    name: 'send_by.name',
                    className: 'text-start'
                },
            ],
            fnDrawCallback: function() {
                var total_qty = sum_table_col($('.data_tbl'), 'total_qty');
                $('#total_qty').text(bdFormat(total_qty));

                var total_stock_value = sum_table_col($('.data_tbl'), 'total_stock_value');
                $('#total_stock_value').text(bdFormat(total_stock_value));

                var total_send_qty = sum_table_col($('.data_tbl'), 'total_send_qty');
                $('#total_send_qty').text(bdFormat(total_send_qty));

                var total_received_qty = sum_table_col($('.data_tbl'), 'total_received_qty');
                $('#total_received_qty').text(bdFormat(total_received_qty));

                var total_pending_qty = sum_table_col($('.data_tbl'), 'total_pending_qty');
                $('#total_pending_qty').text(bdFormat(total_pending_qty));

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
            $('.data_preloader').show();
            table.ajax.reload();
        });

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
