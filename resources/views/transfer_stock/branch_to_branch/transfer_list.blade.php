@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Transfer Stock (Business Location To Business Location) - ')
@section('content')
    <div class="body-woaper">
        <div class="border-class">
            <div class="main__content">

                <div class="sec-name">
                    <div class="name-head">
                        <span class="fas fa-shopping-cart"></span>
                        <h5>Transfer Stocks (Business Location To Business Location)</h5>
                    </div>

                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button">
                        <i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                </div>
            </div>

            <div class="p-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-3">
                            <div class="element-body">
                                <form id="filter_form">
                                    <div class="form-group row">
                                        @if ($addons->branches == 1)
                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="col-md-2">
                                                    <label><strong>Business Location :</strong></label>
                                                    <select name="branch_id"
                                                        class="form-control submit_able" id="branch_id" autofocus>
                                                        <option value="">All</option>

                                                        <option SELECTED value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>

                                                        @foreach ($branches as $branch)
                                                            @if (auth()->user()->branch_id != $branch->id)
                                                                <option value="{{ $branch->id }}">
                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        @endif

                                        <div class="col-md-2">
                                            <label><strong>Payment Status :</strong></label>
                                            <select name="receive_status" id="receive_status" class="form-control">
                                                <option value="">All</option>
                                                <option value="1">Pending</option>
                                                <option value="2">Partial</option>
                                                <option value="3">Completed</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>From Date :</strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="datepicker"
                                                    class="form-control from_date date"
                                                    autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong>To Date :</strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label><strong></strong></label>
                                            <div class="input-group">
                                                <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
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
                            <h6>Transfer List</h6>
                        </div>

                        <div class="col-md-2 d-flex justify-content-end">
                            <a href="{{ route('transfer.stock.branch.to.branch.create') }}" class="btn btn-sm btn-primary" id="add_btn"><i class="fas fa-plus-square"></i> Add (Ctrl + Enter)</a>
                        </div>

                    </div>

                    <div class="widget_content">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                        </div>
                        <div class="table-responsive" id="data-list">
                            {{-- <table class="display data_tbl data__table table-hover"> --}}
                            <table class="display data_tbl table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('menu.action')</th>
                                        <th>@lang('menu.date')</th>
                                        <th>Reference ID</th>
                                        <th>Sender B. Location</th>
                                        <th>Receiver @lang('menu.b_location')</th>
                                        <th>Receive Status</th>
                                        <th>Total Item</th>
                                        <th>Send Qty</th>
                                        <th>Received Qty</th>
                                        <th>Pending Qty</th>
                                        <th>Total Transfer Cost({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr class="bg-secondary">
                                        <th colspan="6" class="text-white text-end">Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                        <th id="total_item" class="text-white text-end"></th>
                                        <th id="total_send_qty" class="text-white text-end"></th>
                                        <th id="total_received_qty" class="text-white text-end"></th>
                                        <th id="total_pending_qty" class="text-white text-end"></th>
                                        <th id="transfer_cost" class="text-white text-end"></th>
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

    <div id="transfer_details"></div>

    <div class="modal fade" id="sendNotificationModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Send Notification</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="send-natification-modal-body"></div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var transfer_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),

            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],

            "ajax": {

                "url": "{{ route('transfer.stock.branch.to.branch.transfer.list') }}",

                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.receive_status = $('#receive_status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'ref_id', name: 'ref_id'},
                {data: 'sender_branch', name: 'sender_branch.name'},
                {data: 'receiver_branch', name: 'receiver_branch.name'},
                {data: 'receive_status', name: 'receive_status', className: 'text-end'},
                {data: 'total_item', name: 'total_item', className: 'text-end'},
                {data: 'total_send_qty', name: 'total_send_qty', className: 'text-end'},
                {data: 'total_received_qty', name: 'total_received_qty', className: 'text-end'},
                {data: 'total_pending_qty', name: 'total_pending_qty', className: 'text-end'},
                {data: 'transfer_cost', name: 'transfer_cost', className: 'text-end'},

            ],fnDrawCallback: function() {

                var total_item = sum_table_col($('.data_tbl'), 'total_item');
                $('#total_item').text(bdFormat(total_item));
                var total_send_qty = sum_table_col($('.data_tbl'), 'total_send_qty');
                $('#total_send_qty').text(bdFormat(total_send_qty));
                var total_received_qty = sum_table_col($('.data_tbl'), 'total_received_qty');
                $('#total_received_qty').text(bdFormat(total_received_qty));
                var total_pending_qty = sum_table_col($('.data_tbl'), 'total_pending_qty');
                $('#total_pending_qty').text(bdFormat(total_pending_qty));
                var transfer_cost = sum_table_col($('.data_tbl'), 'transfer_cost');
                $('#transfer_cost').text(bdFormat(transfer_cost));
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
        $(document).on('submit', '#filter_form', function (e) {
            e.preventDefault();

            $('.data_preloader').show();
            transfer_table.ajax.reload();
        });

        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#transfer_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        });

        // Make print
        $(document).on('click', '.print_btn',function (e) {
           e.preventDefault();

            var body = $('.transfer_print_template').html();
            var header = $('.heading_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                removeInline: false,
                printDelay: 500,
                header : null,
                footer : null,
            });
        });

        $(document).on('click', '.print_challan_btn',function (e) {
           e.preventDefault();
            var body = $('.challan_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                removeInline: false,
                printDelay: 800,
                header: null,
                footer: null,
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({

                'title': 'Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    transfer_table.ajax.reload();
                    toastr.error(data);
                }, error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                        return;
                    }
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

        document.onkeyup = function () {
            var e = e || window.event; // for IE to cover IEs window event-object
            // console.log(e);

            if(e.ctrlKey && e.which == 13) {

                // $('#add_btn').click();
                window.location = $('#add_btn').attr('href');
                return false;
            }
        }

    </script>
@endpush
