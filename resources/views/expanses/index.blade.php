@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css" />
@endpush
@section('title', 'Expense List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-money-bill"></span>
                                <h5>Expenses</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <i class="fas fa-funnel-dollar ms-2"></i> <b>Filter</b>
                                        <form action="" method="get" class="px-2">
                                            <div class="form-group row">
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-2">
                                                        <label><strong>Branch :</strong></label>
                                                        <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>Expense For :</strong></label>
                                                    <select name="admin_id" class="form-control submit_able" id="admin_id" >
                                                        <option value="">All</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>Date Range :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input readonly type="text" name="date_range" id="date_range" class="form-control daterange submit_able_input"
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
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-10">
                                        <h6>All Expense <small>Note: Initially current year's data is available here, if
                                                another year's data go to the data filter.</small></h6>
                                    </div>
                                    @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                        <div class="col-md-2">
                                            <div class="btn_30_blue float-end">
                                                <a href="{{ route('expanses.create') }}"><i
                                                        class="fas fa-plus-square"></i> Add</a>
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
                                                    <th class="text-start">Actions</th>
                                                    <th class="text-start">Date</th>
                                                    <th class="text-start">Reference ID</th>
                                                    <th class="text-start">Branch</th>
                                                    <th class="text-start">Payment Status</th>
                                                    <th class="text-start">Tax</th>
                                                    <th class="text-start">Net Total</th>
                                                    <th class="text-start">Payment Due</th>
                                                    <th class="text-start">Expanse For</th>
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
    </div>

    <!--Payment list modal-->
    <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Payment List</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="payment_view_modal_body">

                </div>
            </div>
        </div>
    </div>
    <!--Payment list modal-->

    <!--Add Payment modal-->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
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
    <!--Add Payment modal-->

    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog col-55-modal" role="document">
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
                                    {{-- <a href="" id="print_payment" class="btn btn-sm btn-primary">Print</a> --}}
                                    <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange">Close</button>
                                    <button type="submit" id="print_payment" class="c-btn btn_blue">Print</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.js"></script>

    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [[0, 'asc']],
            "ajax": {
                "url": "{{ route('expanses.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.admin_id = $('#admin_id').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                { data: 'action', },
                { data: 'date', name: 'date' },
                { data: 'invoice_id', name: 'invoice_id'},
                { data: 'from', name: 'from' },
                { data: 'payment_status', name: 'payment_status' },
                { data: 'tax_percent', name: 'tax_percent' },
                { data: 'net_total', name: 'net_total' },
                { data: 'due', name: 'due' },
                { data: 'user_name', name: 'user_name' },
            ],
        });

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function () {
            table.ajax.reload();
        });

        //Submit filter form by date-range field blur 
        $(document).on('blur', '.submit_able_input', function () {
            setTimeout(function() {
                table.ajax.reload();
            }, 500);
        });

        //Submit filter form by date-range apply button
        $(document).on('click', '.applyBtn', function () {
            setTimeout(function() {
                $('.submit_able_input').addClass('.form-control:focus');
                $('.submit_able_input').blur();
            }, 500);
        });

        $(document).on('click', '#add_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#payment_heading').html('Add Payment');
                $('#payment-modal-body').html(data); 
                $('#paymentModal').modal('show'); 
                $('.data_preloader').hide();
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_payment', function (e) {
            e.preventDefault();
            $('.modal_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Payment');
            $.get(url, function(data) {
                $('.modal_preloader').hide();
                $('#payment-modal-body').html(data); 
                $('#paymentModal').modal('show'); 
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#view_payment', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');
           $('.data_preloader').show();
            $.get(url, function(data) {
                $('#payment_view_modal_body').html(data);
                $('.data_preloader').hide();
                $('#paymentViewModal').modal('show');
            });
        });

            //Show payment view modal with data
        $(document).on('click', '#payment_details', function (e) {
           e.preventDefault();
           $('.modal_preloader').show();
           var url = $(this).attr('href');
            $.get(url, function(data) {
                $('.payment_details_area').html(data);
                $('.modal_preloader').hide();
                $('#paymentDetailsModal').modal('show');
            });
        });

        //Add sale payment request by ajax
        $(document).on('submit', '#payment_form', function(e){
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
                        $('.loading_button').hide();
                        $('#paymentModal').modal('hide');
                        $('#paymentViewModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(data); 
                    }
                }
            });
        });

        @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2) 
            function setBranches(){
                $.ajax({
                    url:"{{route('sales.get.all.branches')}}",
                    async:true,
                    type:'get',
                    dataType: 'json',
                    success:function(branches){
                        $('#branch_id').append('<option value="">All</option>');
                        var headOffice = "{{ json_decode($generalSettings->business, true)['shop_name'] }}"
                        $('#branch_id').append('<option value="NULL">'+headOffice+'(Head Office)'+'</option>');

                        $.each(branches, function(key, val){
                            $('#branch_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.branch_code+')'+'</option>');
                        });
                    }
                });
            }
            setBranches();
        @endif

         // Set accounts in payment and payment edit form
         function setExpanseCategory(){
            $.ajax({
                url:"{{route('expanses.all.categories')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(categories){
                    $.each(categories, function (key, category) {
                        $('#category_id').append('<option value="'+category.id+'">'+ category.name +' ('+category.code+')'+'</option>');
                    });
                }
            });
        }
        setExpanseCategory();

        // Set accounts in payment and payment edit form
        function setAdmin(){
            $.ajax({
                url:"{{route('expanses.all.admins')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(admins){
                    $.each(admins, function (key, admin) {
                        var prefix = admin.prefix ? admin.prefix : '';
                        var last_name = admin.last_name ? admin.last_name : '';
                        $('#admin_id').append('<option value="'+admin.id+'">'+ admin.name+' '+last_name+'</option>');
                    });
                }
            });
        }
        setAdmin();

        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var button = $(this);    
            $('#payment_deleted_form').attr('action', url);    
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#payment_deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            // alert('Deleted canceled.')
                        } 
                    }
                }
            });
        });
            
        $(document).on('submit', '#payment_deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            console.log(url);
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    table.ajax.reload();
                    toastr.error(data);
                    $('#paymentViewModal').modal('hide');
                }
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

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);           
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            // alert('Deleted canceled.')
                        } 
                    }
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
                async: false,
                data: request,
                success: function(data) {
                    if ($.isEmptyObject(data.errorMsg)) {
                        table.ajax.reload();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }else{
                        toastr.error(data.errorMsg);
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(function() {
            var start = moment().startOf('year');
            var end = moment().endOf('year');
            $('.daterange').daterangepicker({
                buttonClasses: 'btn',
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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year')
                        .subtract(1, 'year')
                    ],
                }
            });
        });

        $(document).on('change', '#payment_method', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });
    </script>
@endpush
