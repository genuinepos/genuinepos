@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Sale Returns - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-undo-alt"></span>
                                <h5>Sale Returns</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>


                    <div class="row margin_row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>Sale Return List </h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">Actions</th>
                                                <th class="text-start">Date</th>
                                                <th class="text-start">Invoice ID</th>
                                                <th class="text-start">Parent Sale</th>
                                                <th class="text-start">Customer Name</th>
                                                <th class="text-start">From</th>
                                                {{-- <th class="text-start">Payment Status</th> --}}
                                                <th class="text-start">Total Returned Amount</th>
                                                {{-- <th class="text-start">Payment Due</th> --}}
                                                <th class="text-start">Refunded Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

    <div id="sale_return_details"></div>

    @if (auth()->user()->permission->sale['sale_payment'] == '1')
        <!--Payment View modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment List</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment_view_modal_body"></div>
                </div>
            </div>
        </div>

        <!--Add Payment modal-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="payment_heading">Payment</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment-modal-body"></div>
                </div>
            </div>
        </div>

        <!--Payment list modal-->
        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment Details (<span class="payment_invoice"></span>)</h6>
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
                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                                    <button type="submit" id="print_payment" class="c-btn button-success">Print</button>
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
    <script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var sales_table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            // aaSorting: [[1, 'asc']],
            ajax: "{{ route('sales.returns.index') }}",
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            columnDefs: [{
                "targets": [0, 6,],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'sale_returns.invoice_id'},
                {data: 'parent_invoice_id', name: 'sales.invoice_id'},
                {data: 'customer', name: 'customers.name'},
                {data: 'from', name: 'branches.name'},
                // {data: 'payment_status', name: 'payment_status'},
                {data: 'total_return_amount', name: 'total_return_amount', className: 'text-end'},
                // {data: 'total_return_due', name: 'total_return_due', className: 'text-end'},
                 {data: 'total_return_due_pay', name: 'total_return_due_pay', className: 'text-end'},
            ],
        });

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();

            $.get(url, function(data) {

                $('#sale_return_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        });

        $(document).on('click', '#add_return_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Pay Return Amount');

            $.get(url, function(data) {

                $('.data_preloader').hide();
                $('#payment-modal-body').html(data);
                $('#paymentModal').modal('show');
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

        //Show payment view modal with data
        $(document).on('click', '#payment_details', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');

            $.get(url, function(data) {

                $('.payment_details_area').html(data);
                $('#paymentDetailsModal').modal('show');
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_return_payment', function (e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Return Payment');

            $.get(url, function(data) {

                $('.data_preloader').hide();
                $('#payment-modal-body').html(data);
                $('#paymentModal').modal('show');
            });
        });

        $(document).on('click', '#delete',function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);

            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    sales_table.ajax.reload();

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else {

                        toastr.error(data);
                    }
                }
            });
        });

        // Make print
        $(document).on('click', '.print_btn',function (e) {
           e.preventDefault();

            var body = $('.sale_return_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('public/assets/css/print/sale.print.css') }}",
                removeInline: false,
                printDelay: 1000,
                header: null,
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
                loadCSS: "{{asset('public/assets/css/print/purchase.print.css')}}",
                removeInline: true,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });

        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');

            $('#payment_deleted_form').attr('action', url);

            $.confirm({
                'title': 'Delete Confirmation',
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
@endpush
