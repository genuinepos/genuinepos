@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
    <style>
        .contract_info_area ul li strong{color:#495677}.account_summary_area .heading h4{background:#0F3057;color:white}.contract_info_area ul li strong i {color: #495b77;font-size: 13px;}
    </style>
    <br><br><br>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->

            <div class="container">
                 <a href="{{ route('contacts.supplier.index') }}" class="btn btn-sm btn-success float-end"><i class="fas fa-long-arrow-alt-left"></i> Back</a>
                <h3 style="color: #32325d">View Contact</h3>
                <div class="row">
                    <div class="col-md-12">
                       
                        <div class="select_supplier_area float-left pb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <form action="" method="get">
                                        <select id="supplier_id" class="form-control form-control-sm">
        
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="card card-custom">
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <div class="data_preloader d-none">
                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                        </div>
                        <div class="tab_list_area">
                            <ul class="list-unstyled">
                                <li><a id="tab_btn" data-show="contract_info_area" class="tab_btn tab_active" href=""><i
                                            class="fas fa-info-circle"></i> Contract Info</a></li>
                                <li><a id="tab_btn" data-show="ledger" class="tab_btn" href=""><i class="fas fa-scroll"></i>
                                        Ledger</a></li>
                                <li><a id="tab_btn" data-show="purchases" class="tab_btn" href=""><i
                                            class="fas fa-shopping-bag"></i> Purchases</a></li>
                                <li><a id="tab_btn" data-show="documents_and_note" class="tab_btn" href=""><i
                                            class="far fa-folder-open"></i> Documents & Note</a></li>
                            </ul>
                        </div>
                        <div class="tab_contant contract_info_area">
                            <div class="row">
                                <div class="col-md-3">
                                    <ul class="list-unstyled"><br>
                                        <li><strong class="name">Jamal Hosain</strong></li><br>
                                        <li><strong><i class="fas fa-map-marker-alt"></i> Address</strong></li>
                                        <li><span class="address">Dhaka, Bangladesh.</span></li><br>
                                        <li><strong><i class="fas fa-briefcase"></i> Business Name</strong></li>
                                        <li><span class="business">Premium Multi Trade</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3"><br>
                                    <ul class="list-unstyled">
                                        <li><strong><i class="fas fa-phone-square"></i> Phone</strong></li>
                                        <li><span class="phone">+0881087555558</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3"><br>
                                    <ul class="list-unstyled">
                                        <li><strong><i class="fas fa-info"></i> Tex Number</strong></li>
                                        <li><span class="tax_number">Tx0881087555558</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li><strong> Total Purchase :</strong></li>
                                        <li><span class="total_purchase">2000000.00</span></li>
                                        <li><strong> Total Paid :</strong></li>
                                        <li><span class="total_paid">2000000.00</span></li>
                                        <li><strong> Total Purchase Due :</strong></li>
                                        <li><span class="total_purchase_due">2000000.00</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab_contant ledger d-none">
                            <div class="row">
                                <div class="col-md-5 offset-7">
                                    <div class="company_info text-right">
                                        <ul class="list-unstyled">
                                            <li><strong
                                                    class="company_name">{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong>
                                            </li>
                                            <li><span class="company_address">Motijeel, Arambagh, Road-144, Dhaka</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-lg-6">
                                    <div class="account_summary_area">
                                        <div class="heading py-2">
                                            <h4 class="py-2 pl-1">To :</h4>
                                        </div>
                                    </div>
                                    <div class="sand_info">
                                        <ul class="list-unstyled">
                                            <li><strong class="name">Jamal Hosain</strong></li><br>
                                            <li>Phone:<span class="phone"> 01122555545545</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-lg-6">
                                    <div class="account_summary_area">
                                        <div class="heading py-2">
                                            <h4 class="py-2 pl-1">Account Summary</h4>
                                        </div>

                                        <div class="account_summary_table">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Opening Balance :</strong></td>
                                                        <td><span class="opening_balance">0.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Total Purchase :</strong></td>
                                                        <td><span class="total_purchase">100000.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Total Paid :</strong></td>
                                                        <td><span class="total_paid">100000.00</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td><strong>Balance Due :</strong></td>
                                                        <td><span class="balance_due">0.00</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label> <strong>Supplier Ledger</strong></label>
                                    <div class="payment_table">
                                        <div class="table-responsive" id="payment_list_table">
                                            <table class="table">
                                                <thead>
                                                    <tr class="bg-navey-blue">
                                                        <th>Date</th>
                                                        <th>Invoice ID</th>
                                                        <th>Type</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                        <th>Payment Method</th>
                                                        <th>Others</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab_contant purchases d-none">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table_area">
                                        <div class="data_preloader" id="purchase_table_preloader">
                                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                        </div>
                                        <div class="table-responsive" id="purchase_list_table">
                                            <table class="display data_tbl data__table">
                                                <thead>
                                                    <tr class="text-left">
                                                        <th>Actions</th>
                                                        <th>Date</th>
                                                        <th>Reference ID</th>
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
                        <!--end: Datatable-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>

    <div id="purchase_details">
        
    </div>

     <!-- Change purchase status modal-->
     <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Update Purchase Status</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="change_purchase_status_form" action="" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Purchase Staus :</label>
                            <select name="purchase_status" class="form-control form-control-sm" id="purchase_status">
                                <option value="1">Received</option>
                                <option value="2">Pending</option>
                                <option value="3">Ordered</option>
                            </select>
                        </div>

                        <div class="form-group text-right">
                            <button type="button" class="btn btn-sm btn-light-danger font-weight-bold" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary font-weight-bold submit_button">Submit</button>
                            <span class="btn loading_button"><i class="fas fa-spinner"></i> <strong>Loading</strong> </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    @if (auth()->user()->permission->purchase['purchase_payment'] == '1')
        <!--Payment list modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
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
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            
        </div>
        <!--Add Payment modal-->
        
        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content payment_details_contant">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment Details (<span class="payment_invoice"></span>)</h6>
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
    <script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/barcode/JsBarcode.all.min.js"></script>
    <script>
        $('.data_preloader').show();
        purchase_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            aaSorting: [[3, 'asc']],
            ajax:"{{ url('contacts/suppliers/view', $supplierId) }}",
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'from'},
                {data: 'supplier_name', name: 'supplier_name'},
                {data: 'status', name: 'status'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'total_purchase_amount', name: 'total_purchase_amount'},
                {data: 'paid', name: 'paid'},
                {data: 'due', name: 'due'},
                {data: 'return_amount', name: 'return_amount'},
                {data: 'return_due', name: 'return_due'},
                {data: 'created_by', name: 'created_by'},
            ],
        });

        // Get all supplier for filter form
        function setSuppliers(){
            $.ajax({
                url:"{{route('purchases.get.all.supplier')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(suppliers){
                    $.each(suppliers, function(key, val){
                        $('#supplier_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.phone+')'+'</option>');
                    });
                    $('#supplier_id').val({{ $supplierId }});
                }
            });
        }
        setSuppliers();

         // Set accounts in payment and payment edit form
         function setAccount(){
            $.ajax({
                url:"{{route('accounting.accounts.all.form.account')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(accounts){
                    $.each(accounts, function (key, account) {
                        $('#p_account_id').append('<option value="'+account.id+'">'+ account.name +' (A/C: '+account.account_number+')'+' (Balance: '+account.balance+')'+'</option>');
                        $('#pe_account_id').append('<option value="'+account.id+'">'+ account.name +' (A/C: '+account.account_number+')'+' (Balance: '+account.balance+')'+'</option>');
                    });
                }
            });
        }
        setAccount();

        // Change supplier 
        $('#supplier_id').on('change', function () {
           var supplierId = $(this).val(); 
           window.location = "{{ url('contacts/suppliers/view') }}"+"/"+supplierId;
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });

        function getSupplierAllInformations() {
            // Supplier info
            $.ajax({
                url: "{{ route('contacts.supplier.all.info', $supplierId) }}",
                type: 'get',
                dataType: 'json',
                success: function(supplier) {
                    $('.name').html(supplier.name);
                    $('.address').html(supplier.address);
                    $('.business').html(supplier.business_name);
                    $('.phone').html(supplier.phone);
                    $('.tax_number').html(supplier.tax_number);
                    $('.total_purchase').html(supplier.total_purchase);
                    $('.total_paid').html(supplier.total_paid);
                    $('.total_purchase_due').html(supplier.total_purchase_due);
                    $('.balance_due').html(supplier.total_purchase_due);
                    $('.opening_balance').html(supplier.opening_balance);
                    $('#supplier_id').val(supplier.id);
                    $('.data_preloader').hide();
                }
            });

            // Supplier pyaments
            $.ajax({
                url: "{{ route('contacts.supplier.payment.list', $supplierId) }}",
                type: 'get',
                success: function(paymentList) {
                    $('#payment_list_table').html(paymentList);
                }
            });
        }
        getSupplierAllInformations();

         // Pass sale details in the details modal
         function purchaseDetails(url) {
            $('#purchase_table_preloader').show();
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#purchase_details').html(data);
                    $('#purchase_table_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        }
    
        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            purchaseDetails(url);
        });

        // Show details modal with data by clicking the row
        $(document).on('click', 'tr.clickable_row td:not(:first-child, :last-child, :nth-child(8))', function(e){
            e.preventDefault();
            var purchase = $(this).parent().data('href');
            purchaseDetails(purchase);
        });

         // Show change status modal and pass actual link in the change status form
         $(document).on('click', '#change_status', function (e) {
            e.preventDefault();
            var purchase = $(this).closest('tr').data('info');
            var url = $(this).attr('href');
            $('#change_purchase_status_form').attr('action', url);
            $('#purchase_status').val(purchase.purchase_status);
            $('#changeStatusModal').modal('show'); 
        });

        //change purchase status requested by ajax
        $(document).on('submit', '#change_purchase_status_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $('.loading_button').show();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    purchase_table.ajax.reload();
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#changeStatusModal').modal('hide'); 
                }
            });
        });

        // Show sweet alert for delete
        // $(document).on('click', '#delete', function(e) {
        //     e.preventDefault();
        //     var url = $(this).attr('href');
        //     $('#deleted_form').attr('action', url);
        //     swal({
        //         title: "Are you sure to delete ?",
        //         icon: "warning",
        //         buttons: true,
        //         dangerMode: true,
        //     }).then((willDelete) => {
        //         if (willDelete) {
        //             $('#deleted_form').submit();
        //         }
        //     });
        // });
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
                    toastr.success(data);
                    purchase_table.ajax.reload();
                    $('.loading_button').hide();
                    $('#changeStatusModal').modal('hide');
                }
            });
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
     
        $(document).on('change', '#payment_method', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });

        $(document).on('click', '#add_payment', function (e) {
            e.preventDefault();
            $('#purchase_table_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#paymentModal').html(data); 
                    $('#paymentModal').modal('show'); 
                    $('#purchase_table_preloader').hide();
                }
            });
        });

        $(document).on('click', '#add_return_payment', function (e) {
            e.preventDefault();
            $('#purchase_table_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#paymentModal').html(data); 
                    $('#paymentModal').modal('show'); 
                    $('#purchase_table_preloader').hide(); 
                }
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_payment', function (e) {
            e.preventDefault();
            $('#purchase_table_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#paymentModal').html(data); 
                    $('#paymentModal').modal('show'); 
                    $('#purchase_table_preloader').hide();
                }
            });
        });

        // show payment edit modal with data
        $(document).on('click', '#edit_return_payment', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#purchase_table_preloader').show();
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#paymentModal').html(data); 
                    $('#paymentModal').modal('show'); 
                    $('#purchase_table_preloader').hide(); 
                }
            });
        });

        // //Show payment view modal with data
        $(document).on('click', '#view_payment', function (e) {
           e.preventDefault();
           $('#purchase_table_preloader').show();
           var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(date){
                    $('#payment_list_modal_body').html(date);
                    $('#paymentViewModal').modal('show');
                    $('#purchase_table_preloader').hide();
                }
            });
        });

        //Add purchase payment request by ajax
        $(document).on('submit', '#payment_form',function(e){
            e.preventDefault();
            $('.loading_button').show();
            var available = $('#p_available_amount').val();
            var paying_amount = $('#p_amount').val();
            if (parseFloat(paying_amount)  > parseFloat(available)) {
                $('.error_p_amount').html('Paying amount must not be greater then due amount.');
                $('.loading_button').hide();
                return;
            }
            var url = $(this).attr('action');
            var inputs = $('.p_input');
                inputs.removeClass('is-invalid');
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
                        toastr.success(data); 
                        purchase_table.ajax.reload();
                    }
                }
            });
        });

        //Show payment view modal with data
        $(document).on('click', '#payment_details', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(date){
                    $('.payment_details_area').html(date);
                    $('#paymentDetailsModal').modal('show');
                }
            });
        });

        // Print single payment details
        $(document).on('click', '#print_payment', function (e) {
           e.preventDefault(); 
            var body = $('.sale_payment_print_area').html();
            var header = $('.print_header').html();
            var footer = $('.signature_area').html();
            $(body).printThis({
                debug: false,                   
                importCSS: true,                
                importStyle: true,          
                loadCSS: "{{asset('public/assets/css/print/purchase.print.css')}}",                      
                removeInline: false, 
                printDelay: 500, 
                header: header,  
                footer: footer
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#delete_payment',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#payment_deleted_form').attr('action', url);
            swal({
                title: "Are you sure to delete ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) { 
                    $('#payment_deleted_form').submit();
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
                    purchase_table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

    </script>
@endpush
