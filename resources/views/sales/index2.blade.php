@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'All Sale - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>@lang('menu.sales')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
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
                                                            <label><strong>@lang('menu.business_location') :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able select2" id="branch_id" autofocus>
                                                                <option value="">@lang('menu.all')</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (@lang('menu.head_office'))</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>@lang('menu.customer') :</strong></label>
                                                    <select name="customer_id" class="form-control submit_able select2" id="customer_id" autofocus>
                                                        <option value="">@lang('menu.all')</option>
                                                        <option value="NULL">{{ __('Walk-In-Customer') }}</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('menu.payment_status') :</strong></label>
                                                    <select name="payment_status" id="payment_status" class="form-control">
                                                        <option value="">@lang('menu.all')</option>
                                                        <option value="1">@lang('menu.paid')</option>
                                                        <option value="2">@lang('menu.due')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>@lang('menu.from_date') :</strong></label>
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
                                                    <label><strong>@lang('menu.to_date') :</strong></label>
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
                                                        <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> @lang('menu.filter')</button>
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
                                    <h6>{{ __('All Sale') }}</h6>
                                </div>

                                @if(auth()->user()->can('create_add_sale'))
                                    <div class="col-6 d-flex justify-content-end">
                                        <a href="{{ route('sales.create') }}" class="btn btn-sm btn-primary" id="add_btn"><i class="fas fa-plus-square"></i> @lang('menu.add') (Ctrl+Enter)</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    {{-- <table class="display data_tbl data__table table-hover"> --}}
                                    {{-- <table class="display data_tbl modal-table table-sm table-striped"> --}}
                                        <table class="display data_tbl data__table">
                                        <thead>
                                            {{-- <tr>
                                                <th>@lang('menu.action')</th>
                                                <th>@lang('menu.date')</th>
                                                <th>@lang('menu.invoice_id')</th>
                                                <th>@lang('menu.stock_location')</th>
                                                <th>@lang('menu.customer')</th>
                                                <th>Return Amount({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('menu.return_due')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('menu.payment_status')</th>
                                                <th>Sell Due({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('menu.total_amount')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th>@lang('menu.total_paid')({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                            </tr> --}}
                                            <tr>
                                                <th>@lang('menu.action')</th>
                                                <th>@lang('menu.date')</th>
                                                <th>@lang('menu.invoice_id')</th>
                                                <th>@lang('menu.stock_location')</th>
                                                <th>@lang('menu.customer')</th>
                                                <th>@lang('menu.payment_status')</th>
                                                <th>@lang('menu.total_payable')</th>
                                                <th>@lang('menu.paid')</th>
                                                <th>@lang('menu.due')</th>
                                                <th>{{ __('Return') }}</th>
                                                <th>@lang('menu.return_due')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="6" class="text-white text-end">@lang('menu.total') : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                <th id="total_payable_amount" class="text-white text-end"></th>
                                                <th id="paid" class="text-white text-end"></th>
                                                <th id="due" class="text-white text-end"></th>
                                                <th id="sale_return_amount" class="text-white text-end"></th>
                                                <th id="sale_return_due" class="text-white text-end"></th>
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
        </div>
    </div>

    <div id="sale_details"></div>

    <!-- Edit Shipping modal -->
    <div class="modal fade" id="editShipmentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="edit_shipment_modal_content">

            </div>
        </div>
    </div>

    @if(auth()->user()->can('sale_payment'))
        <!--Payment View modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_list')</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment_view_modal_body"></div>
                </div>
            </div>
        </div>

        <!--Add Payment modal-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

        <!--Payment list modal-->
        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.payment_details') (<span class="payment_invoice"></span>)</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="payment_details_area">

                        </div>

                        <div class="row">
                            <div class="col-md-6 text-right">
                                <ul class="list-unstyled">
                                    <li class="mt-3" id="payment_attachment"></li>
                                </ul>
                            </div>
                            <div class="col-md-6 text-end">
                                <ul class="list-unstyled">
                                    {{-- <li class="mt-3"><a href="" id="print_payment" class="btn btn-sm btn-primary">@lang('menu.print')</a></li> --}}
                                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                                    <button type="submit" id="print_payment" class="btn btn-sm btn-success">@lang('menu.print')</button>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var sales_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10]}},
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('sales.index2') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.payment_status = $('#payment_status').val();
                    d.user_id = $('#user_id').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [0, 5],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'branches.name'},
                {data: 'customer', name: 'customers.name'},
                {data: 'paid_status', name: 'paid_status', className: 'text-end'},
                {data: 'total_payable_amount', name: 'total_payable_amount', className: 'text-end'},
                {data: 'paid', name: 'paid', className: 'text-end'},
                {data: 'due', name: 'due', className: 'text-end'},
                {data: 'sale_return_amount', name: 'sale_return_amount', className: 'text-end'},
                {data: 'sale_return_due', name: 'sale_return_due', className: 'text-end'},

            ],fnDrawCallback: function() {
                var total_payable_amount = sum_table_col($('.data_tbl'), 'total_payable_amount');
                $('#total_payable_amount').text(bdFormat(total_payable_amount));

                var paid = sum_table_col($('.data_tbl'), 'paid');
                $('#paid').text(bdFormat(paid));

                var due = sum_table_col($('.data_tbl'), 'due');
                $('#due').text(bdFormat(due));

                var sale_return_amount = sum_table_col($('.data_tbl'), 'sale_return_amount');
                $('#sale_return_amount').text(bdFormat(sale_return_amount));

                var sale_return_due = sum_table_col($('.data_tbl'), 'sale_return_due');
                $('#sale_return_due').text(bdFormat(sale_return_due));

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
            sales_table.ajax.reload();
        });

        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#sale_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#view_payment', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');
            $.get(url, function(data) {

                $('#payment_view_modal_body').html(data);
                $('#paymentViewModal').modal('show');
            });
        });

        $(document).on('click', '#add_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
 
            $.get(url, function(data) {

                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        $(document).on('click', '#add_return_payment', function (e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Pay Return Amount');

            $.get(url, function(data) {

                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_payment', function (e) {

            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Payment');

            $.get(url, function(data) {

                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_return_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Return Payment');

            $.get(url, function(data) {

                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#payment_details', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');

            $.get(url, function(data) {

                $('.payment_details_area').html(data);
                $('#paymentDetailsModal').modal('show');
            });
        });

        // Get Edit Shipment Modal form
        $(document).on('click', '#edit_shipment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('.data_preloader').hide();
                $('#edit_shipment_modal_content').html(data);
                $('#editShipmentModal').modal('show');
            });
        });

        // Make print
        $(document).on('click', '.print_btn',function (e) {
           e.preventDefault();

            var body = $('.sale_print_template').html();
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

        // Print single payment details
        $('#print_payment').on('click', function (e) {
           e.preventDefault();

            var body = $('.sale_payment_print_area').html();
            var header = $('.print_header').html();
            var footer = $('.signature_area').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('assets/css/print/purchase.print.css')}}",
                removeInline: true,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });

        // Print Packing slip
        $(document).on('click', '#print_packing_slip', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){

                    $('.data_preloader').hide();
                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                        removeInline: false,
                        printDelay: 700,
                        header: null,
                    });
                }
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
                    sales_table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#payment_deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#payment_deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    sales_table.ajax.reload();
                    toastr.error(data);
                    $('#paymentViewModal').modal('hide');
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

        //Show payment view modal with data
        $(document).on('click', '#view_payment', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#payment_view_modal_body').html(data);
                $('#paymentViewModal').modal('show');
            });
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

        function getCustomer() {}
    </script>
@endpush
