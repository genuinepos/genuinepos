@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
    <link rel="stylesheet" href="{{ asset('public') }}/backend/asset/css/bootstrap-datepicker.min.css">
@endpush
@section('title', 'All Sale - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>Sales</h5>
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
                                                    <label><strong>Customer :</strong></label>
                                                    <select name="customer_id" class="form-control submit_able" id="customer_id" autofocus>
                                                        <option value="">All</option>
                                                        <option value="NULL">Walk-In-Customer</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>Payment Status :</strong></label>
                                                    <select name="payment_status" id="payment_status" class="form-control submit_able">
                                                        <option value="">All</option>
                                                        <option value="1">Paid</option>
                                                        <option value="2">Due</option>
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

                    <!-- =========================================top section button=================== -->
                    <div class="row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>All Sale</h6>
                                </div>
                                @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href="{{ route('sales.create') }}"><i class="fas fa-plus-square"></i> Add</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Actions</th>
                                                <th>Date</th>
                                                <th>Invoice ID</th>
                                                <th>Stock Location</th>
                                                <th>Customer</th>
                                                <th>Total Amount</th>
                                                <th>Total Paid</th>
                                                <th>Sell Due</th>
                                                <th>Payment Status</th>
                                                <th>Return Amount</th>
                                                <th>Return Due</th>
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

    <div id="sale_details"></div>

    <!-- Edit Shipping modal -->
    <div class="modal fade" id="editShipmentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="edit_shipment_modal_content">
                
            </div>
        </div>
    </div>

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
                    <div class="modal-body" id="payment_view_modal_body">
                    
                    </div>
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
                    <div class="modal-body" id="payment-modal-body">
                        <!--begin::Form-->
                        
                    </div>
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
                                    {{-- <li class="mt-3"><a href="" id="print_payment" class="btn btn-sm btn-primary">Print</a></li> --}}
                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                                    <button type="submit" id="print_payment" class="c-btn btn_blue">Print</button>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="sendNotificationModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Send Notification</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="send-natification-modal-body">
                
                </div>
            </div>
        </div>
    </div>
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

        sales_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10]}},
            ],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            aaSorting: [[0, 'desc']],
            "ajax": {
                "url": "{{ route('sales.index2') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.payment_status = $('#payment_status').val();
                    d.user_id = $('#user_id').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'action'},{data: 'date', name: 'date'},{data: 'invoice_id', name: 'invoice_id'},{data: 'from', name: 'from'},{data: 'customer', name: 'customer'},{data: 'total_payable_amount', name: 'total_payable_amount'},{data: 'paid', name: 'paid'},{data: 'due', name: 'due'},{data: 'paid_status', name: 'paid_status'},{data: 'sale_return_amount', name: 'sale_return_amount'},{data: 'sale_return_due', name: 'sale_return_due'},
            ],fnDrawCallback: function() {
                $('.data_preloader').hide();
            },
        });

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function () {
            $('.data_preloader').show();
            sales_table.ajax.reload();
        });

        //Submit filter form by date-range field blur 
        $(document).on('blur', '.submit_able_input', function () {
            setTimeout(function() {
                $('.data_preloader').show();
                sales_table.ajax.reload();
            }, 500);
        });

        //Submit filter form by date-range apply button
        $(document).on('click', '.applyBtn', function () {
            setTimeout(function() {
                $('.submit_able_input').addClass('.form-control:focus');
                $('.submit_able_input').blur();
            }, 500);
        });

        // Pass sale details in the details modal
        function saleDetails(url) {
            $('.data_preloader').show();
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#sale_details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        }
        
        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            saleDetails(url);
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
            $('#payment_heading').html('Add Payment');
            $.get(url, function(data) {
                $('#payment-modal-body').html(data); 
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
                $('.data_preloader').hide();
                $('#payment-modal-body').html(data); 
                $('#paymentModal').modal('show'); 
            });
        });

         // show payment edit modal with data
         $(document).on('click', '#edit_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Payment');
            $.get(url, function(data) {
                $('.data_preloader').hide();
                $('#payment-modal-body').html(data); 
                $('#paymentModal').modal('show'); 
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

        //Add sale payment request by ajax
        $(document).on('submit', '#sale_payment_form', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var available_amount = $('#available_amount').val();
            var paying_amount = $('#p_amount').val();
            if (parseFloat(paying_amount)  > parseFloat(available_amount)) {
                $('.error_p_amount').html('Paying amount must not be greater then due amount.');
                $('.loading_button').hide();
                return;
            }

            var url = $(this).attr('action');
            var inputs = $('.p_input');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                toastr.error('Please check again all form fields.','Some thing want wrong.'); 
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    if(!$.isEmptyObject(data.errorMsg)){
                        toastr.error(data.errorMsg,'ERROR'); 
                        $('.loading_button').hide();
                    }else{
                        $('.payment_method').hide();
                        $('.loading_button').hide();
                        $('#paymentModal').modal('hide');
                        $('#paymentViewModal').modal('hide');
                        sales_table.ajax.reload();
                        toastr.success(data); 
                    }
                }
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

        //change sale status requested by ajax
        $(document).on('submit', '#edit_shipment_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $('.loading_button').show();
            var inputs = $('.add_input');
                $('.error').html('');  
                var countErrorField = 0;  
            $.each(inputs, function(key, val){
                var inputId = $(val).attr('id');
                var idValue = $('#'+inputId).val();
                if(idValue == ''){
                    countErrorField += 1;
                    var fieldName = $('#'+inputId).data('name');
                    $('.error_'+inputId).html(fieldName+' is required.');
                }
            });

            if(countErrorField > 0){
                $('.loading_button').hide();
                return;
            }

            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    sales_table.ajax.reload();
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#editShipmentModal').modal('hide'); 
                }
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
                loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",                      
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
                loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",                      
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
                loadCSS: "{{asset('public/assets/css/print/purchase.print.css')}}",                      
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
                        loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",                      
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
                'title': 'Delete Confirmation',
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
        });

        $(document).on('change', '#payment_method', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
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
    </script>
@endpush