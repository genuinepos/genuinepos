@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css" />
    <link rel="stylesheet" href="{{ asset('public') }}/backend/asset/css/bootstrap-datepicker.min.css">
@endpush
@section('title', 'Purchase List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-basket"></span>
                                <h5>Purchases</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form action="" method="get" class="px-2">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-3">
                                                            <label><strong>Business Location :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">All</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch->id }}">
                                                                        {{ $branch->name . '/' . $branch->branch_code }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif
                                                
                                                <div class="col-md-3">
                                                    <label><strong>Supplier :</strong></label>
                                                    <select name="supplier_id"
                                                        class="form-control submit_able"
                                                        id="supplier_id" autofocus>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>Purchase Status :</strong></label>
                                                    <select name="status" id="status"
                                                        class="form-control  submit_able">
                                                        <option value="">All</option>
                                                        <option value="1">Received</option>
                                                        <option value="2">Pending</option>
                                                        <option value="3">Ordered</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>Date Range :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input readonly type="text" name="date_range" id="date_range"
                                                            class="form-control daterange submit_able_input"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>All Purchases</h6>
                                </div>
                                @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href="{{ route('purchases.create') }}"><i class="fas fa-plus-square"></i> Add</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>Actions</th>
                                                <th>Date</th>
                                                <th>P.Invoice ID</th>
                                                <th>Purchase From</th>
                                                <th>Supplier</th>
                                                <th>Purchase Status</th>
                                                <th>Payment Status</th>
                                                <th>Grand Total</th>
                                                <th>Paid</th>
                                                <th>Payment Due</th>
                                                <th>Return Amount</th>
                                                <th>Return Due</th>
                                                <th>Created By</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
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

    <div id="purchase_details">

    </div>

    <!-- Change purchase status modal-->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Update Purchase Status</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="change_status_modal_body">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div> 

    @if (auth()->user()->permission->purchase['purchase_payment'] == '1')
        <!--Payment list modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment List</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment_list_modal_body">

                    </div>
                </div>
            </div>
        </div>
        <!--Payment list modal-->

        <!--Add Payment modal-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">

        </div>
        <!--Add Payment modal-->

        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
            aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content payment_details_contant">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment Details (<span
                                class="payment_invoice"></span>)</h6>
                            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="payment_details_area">

                        </div>

                        <div class="row">
                            <div class="col-md-6 text-end">
                                <ul class="list-unstyled">
                                    <li class="mt-1" id="payment_attachment"></li>
                                </ul>
                            </div>
                            <div class="col-md-6 text-end">
                                <ul class="list-unstyled">
                                    <li class="mt-1">
                                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                                        <button type="submit" id="print_payment" class="c-btn me-0 btn_blue">Print</button>
                                    </li>
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
    <script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('public') }}/backend/asset/js/bootstrap-date-picker.min.js"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        purchase_table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('purchases.index_v2') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.status = $('#status').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columnDefs: [{"targets": [0, 5, 6],"orderable": false,"searchable": false}],
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id',name: 'invoice_id'},
                {data: 'from',name: 'branches.name'},
                {data: 'supplier_name', name: 'suppliers.name'},
                {data: 'status',name: 'status'},
                {data: 'payment_status',name: 'payment_status'},
                {data: 'total_purchase_amount',name: 'total_purchase_amount'},
                {data: 'paid',name: 'paid'},
                {data: 'due',name: 'due'},
                {data: 'purchase_return_amount',name: 'purchase_return_amount'},
                {data: 'purchase_return_due',name: 'purchase_return_due'},
                {data: 'created_by',name: 'created_by.name'},
            ],
        });

        // Get all supplier for filter form
        function setSuppliers() {
            $.ajax({
                url: "{{ route('purchases.get.all.supplier') }}",
                type: 'get',
                dataType: 'json',
                success: function(suppliers) {
                    $('#supplier_id').append('<option value="">All</option>');
                    $.each(suppliers, function(key, val) {
                        $('#supplier_id').append('<option value="' + val.id + '">' + val.name + ' (' +
                            val.phone + ')' + '</option>');
                    });
                    $('#supplier_id').val('');
                }
            });
        }
        setSuppliers();

        // Show details modal with data
        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url,  function(data) {
                $('#purchase_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            })
        });

        // Show change status modal and pass actual link in the change status form
        $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $("#change_status_modal_body").html(data);
                $('#changeStatusModal').modal('show');
                $('.data_preloader').hide();
            })
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault(); 
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);       
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    purchase_table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        //change purchase status requested by ajax
        $(document).on('submit', '#change_purchase_status_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $('.loading_button').show();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    purchase_table.ajax.reload();
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#changeStatusModal').modal('hide');
                }
            });
        });

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function() {
            purchase_table.ajax.reload();
        });

        //Submit filter form by date-range field blur 
        $(document).on('blur', '.submit_able_input', function() {
            setTimeout(function() {
                purchase_table.ajax.reload();
            }, 800);
        });

        //Submit filter form by date-range apply button
        $(document).on('click', '.applyBtn', function() {
            setTimeout(function() {
                $('.submit_able_input').addClass('.form-control:focus');
                $('.submit_able_input').blur();
            }, 700);
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();
            var body = $('.purchase_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('public/assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $(document).on('change', '#payment_method', function() {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#' + value).show();
        });

        $(document).on('click', '#add_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url,  function(data) {
                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        $(document).on('click', '#add_return_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url,  function(data) {
                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url,  function(data) {
                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_return_payment', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.get(url,  function(data) {
                $('#paymentModal').html(data);
                $('#paymentModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#view_payment', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#payment_list_modal_body').html(data);
                $('#paymentViewModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        //Add purchase payment request by ajax
        $(document).on('submit', '#payment_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var available = $('#p_available_amount').val();
            var paying_amount = $('#p_amount').val();
            if (parseFloat(paying_amount) > parseFloat(available)) {
                $('.error_p_amount').html('Paying amount must not be greater then due amount.');
                $('.loading_button').hide();
                return;
            }
            var url = $(this).attr('action');
            var inputs = $('.p_input');
            inputs.removeClass('is-invalid');
            $('.error').html('');
            var countErrorField = 0;
            $.each(inputs, function(key, val) {
                var inputId = $(val).attr('id');
                var idValue = $('#' + inputId).val();
                if (idValue == '') {
                    countErrorField += 1;
                    var fieldName = $('#' + inputId).data('name');
                    $('.error_' + inputId).html(fieldName + ' is required.');
                }
            });

            if (countErrorField > 0) {
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.', 'Some thing want wrong.');
                return;
            }

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg, 'ERROR');
                        $('.loading_button').hide();
                    } else {
                        $('.loading_button').hide();
                        $('#paymentModal').modal('hide');
                        $('#paymentViewModal').modal('hide');
                        toastr.success(data);
                        purchase_table.ajax.reload();
                    }
                }
            });
        });

        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault(); 
            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);       
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#payment_deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#payment_deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            console.log(url);
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    purchase_table.ajax.reload();
                    $('#paymentViewModal').modal('hide');
                    toastr.success(data);
                }
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#payment_details', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.get(url,  function(data) {
                $('.payment_details_area').html(data);
                $('#paymentDetailsModal').modal('show');
            });
        });

        $(document).on('click', '#print_payment', function(e) {
            e.preventDefault();
            var body = $('.sale_payment_print_area').html();
            var header = $('.print_header').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('public/assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: header,
                footer: footer
            });
        });

    </script>

    <script type="text/javascript">
        $(function() {
            var start = moment().startOf('year');
            var end = moment().endOf('year');
            $('.daterange').daterangepicker({
                buttonClasses: ' btn',
                applyClass: 'btn-primary',
                cancelClass: 'btn-secondary',
                startDate: start,
                endDate: end,
                locale: {cancelLabel: 'Clear'},
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')],
                }
            });
            $('.daterange').val('');
        });

        $(document).on('click', '.cancelBtn ', function () {
           $('.daterange').val('');
        });
    </script>
@endpush
