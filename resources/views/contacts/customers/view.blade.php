@extends('layout.master')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
        <style>
            .contract_info_area ul li strong{color:#495677}.account_summary_area .heading h4{background:#0F3057;color:white}.contract_info_area ul li strong i {color: #495b77;font-size: 13px;}
        </style>
    @endpush
    <div class="body-woaper">
        <div class="container-fluid">
            <!--begin::Container-->
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-people-arrows"></span>
                                <h5>Customer View ({{ $customer->name.' - '.$customer->phone }})</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                
                    <div class="card">
                        <div class="card-body">
                            <!--begin: Datatable-->
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                            </div>

                            <div class="tab_list_area">
                                <ul class="list-unstyled">
                                    <li><a id="tab_btn" data-show="contract_info_area" class="tab_btn tab_active" href=""><i
                                                class="fas fa-info-circle"></i> Contract Info</a></li>
                                    <li><a id="tab_btn" data-show="ledger" class="tab_btn" href=""><i class="fas fa-scroll"></i>
                                            Ledger</a></li>
                                    <li><a id="tab_btn" data-show="sale" class="tab_btn" href=""><i
                                                class="fas fa-shopping-bag"></i> Sale</a></li>
                                </ul>
                            </div>

                            <div class="tab_contant contract_info_area">
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="list-unstyled">
                                            <li><strong class="name">Jamal Hosain</strong></li><br>
                                            <li><strong><i class="fas fa-map-marker-alt"></i> Address</strong></li>
                                            <li><span class="address">Dhaka, Bangladesh.</span></li><br>
                                            <li><strong><i class="fas fa-briefcase"></i> Business Name</strong></li>
                                            <li><span class="business">Premium Multi Trade</span></li>
                                        </ul>
                                    </div>

                                    <div class="col-md-3">
                                        <ul class="list-unstyled">
                                            <li><strong><i class="fas fa-phone-square"></i> Phone</strong></li>
                                            <li><span class="phone">+0881087555558</span></li>
                                        </ul>
                                    </div>

                                    <div class="col-md-3">
                                        <ul class="list-unstyled">
                                            <li><strong><i class="fas fa-info"></i> Tax Number</strong></li>
                                            <li><span class="tax_number">Tx0881087555558</span></li>
                                        </ul>
                                    </div>

                                    <div class="col-md-3">
                                        <ul class="list-unstyled">
                                            <li><strong> Total Sale :</strong></li>
                                            <li><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="total_sale">2000000.00</span></li>
                                            <li><strong> Total Paid :</strong></li>
                                            <li><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="total_paid">2000000.00</span></li>
                                            <li><strong> Total Sale Due :</strong></li>
                                            <li><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="total_sale_due">2000000.00</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab_contant ledger d-none">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                        <div class="account_summary_area">
                                            <div class="heading py-2">
                                                <h4 class="py-2 pl-1">Account Summary</h4>
                                            </div>

                                            <div class="account_summary_table">
                                                <table class="table modal-table table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-start"><strong>Opening Balance :</strong></td>
                                                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="opening_balance">0.00</span></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start"><strong>Total Sale :</strong></td>
                                                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="total_sale">100000.00</span></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start"><strong>Total Paid :</strong></td>
                                                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="total_paid">100000.00</span></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start"><strong>Balance Due :</strong></td>
                                                            <td class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> <span class="balance_due">0.00</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label> <strong>Customer Ledger</strong></label>
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

                            <div class="tab_contant sale d-none">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table_area">
                                            <div class="table-responsive">
                                                <table class="display data_tbl data__table w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Actions</th>
                                                            <th>Date</th>
                                                            <th>Invoice ID</th>
                                                            <th>Sale From</th>
                                                            <th>Customer</th>
                                                            <th>Total Amount</th>
                                                            <th>Total Paid</th>
                                                            <th>Sell Due</th>
                                                            <th>Return Amount</th>
                                                            <th>Return Due</th>
                                                            <th>Payment Status</th>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="sale_details"></div>

   <!-- Edit Shipping modal -->
   <div class="modal fade" id="editShipmentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="edit_shipment_modal_content"> </div>
        </div>
    </div>

    @if (auth()->user()->permission->sale['sale_payment'] == '1')
    <!--Payment View modal-->
    <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Payment List</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body" id="payment_view_modal_body"> </div>
            </div>
        </div>
    </div>

     <!--Add Payment modal-->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">Add Payment</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body" id="payment-modal-body"> </div>
            </div>
        </div>
    </div>

    <!--Payment list modal-->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Payment Details (<span class="payment_invoice"></span>)</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
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
                        <div class="col-md-6 text-right">
                            <ul class="list-unstyled">
                                <li class="mt-3"><a href="" id="print_payment" class="btn btn-sm btn-primary">Print</a></li>
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
    <script src="{{ asset('public') }}/assets/plugins/custom/barcode/JsBarcode.all.min.js"></script>
    <script>
        $('.data_preloader').show();
        //Get all customer for filter form
        sales_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            aaSorting: [[3, 'asc']],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            ajax:"{{ url('contacts/customers/view', $customerId) }}",
            columnDefs: [{
                "targets": [0, 10],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'action',},
                { data: 'date', name: 'date'},
                { data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'branches.name'},
                {data: 'customer', name: 'customers.name'},
                {data: 'total_payable_amount', name: 'total_payable_amount'},
                {data: 'paid', name: 'paid'},
                {data: 'due', name: 'due'},
                {data: 'sale_return_amount', name: 'sale_return_amount'},
                {data: 'sale_return_due', name: 'sale_return_due'},
                {data: 'paid_status', name: 'paid_status'},
            ],
        });
        
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

        // Change customer 
        $('#customer_id').on('change', function () {
           var customerId = $(this).val(); 
           window.location = "{{ url('contacts/customers/view') }}"+"/"+customerId;
        });

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });

        function getCustomerAllInformations() {
            // Supplier info
            $.ajax({
                url: "{{ route('contacts.customer.all.info', $customerId) }}",
                type: 'get',
                dataType: 'json',
                success: function(customer) {
                    console.log(customer);
                    $('.name').html(customer.name);
                    $('.address').html(customer.address);
                    $('.business').html(customer.business_name);
                    $('.phone').html(customer.phone);
                    $('.tax_number').html(customer.tax_number);
                    $('.total_sale').html(customer.total_sale);
                    $('.total_paid').html(customer.total_paid);
                    $('.total_sale_due').html(customer.total_sale_due);
                    $('.balance_due').html(customer.total_sale_due);
                    $('.opening_balance').html(customer.opening_balance);
                    $('#customer_id').val(customer.id);
                }
            });

            // customer pyaments
            $.ajax({
                url: "{{ route('contacts.customer.ledger.list', $customerId) }}",
                type: 'get',
                success: function(paymentList) {
                    $('#payment_list_table').html(paymentList);
                    $('.data_preloader').hide();
                }
            });
        }
        getCustomerAllInformations();

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
        //edit_shipment
        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            saleDetails(url);
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

        // Show change status modal and pass actual link in the change status form
        $(document).on('click', '#edit_shipment', function (e) {
            e.preventDefault();
            $('.data_preloader').show(); 
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('.data_preloader').hide();
                    $('#edit_shipment_modal_content').html(data);
                    $('#editShipmentModal').modal('show');
                }
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
                    'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-modal-primary','action': function() { console.log('Deleted canceled.');}}
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

        // Show change status modal and pass actual link in the change status form
        $(document).on('click', '#edit_shipment', function (e) {
            e.preventDefault();
            $('.data_preloader').show(); 
            var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('.data_preloader').hide();
                    $('#edit_shipment_modal_body').html(data);
                    $('#editShipmentModal').modal('show');
                }
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
                    console.log(data);
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
            });
        });

        $(document).on('click', '#add_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Add Payment');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#payment-modal-body').html(data); 
                    $('#paymentModal').modal('show'); 
                    $('.data_preloader').hide();
                }
            });
        });

        $(document).on('change', '#payment_method', function () {
            var value = $(this).val();
            $('.payment_method').hide();
            $('#'+value).show();
        });

        $(document).on('click', '#add_return_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Pay Return Amount');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('.data_preloader').hide();
                    $('#payment-modal-body').html(data); 
                    $('#paymentModal').modal('show'); 
                }
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
       
        // //Show payment view modal with data
        $(document).on('click', '#view_payment', function (e) {
           e.preventDefault();
           var url = $(this).attr('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(date){
                    $('#payment_view_modal_body').html(date);
                    $('#paymentViewModal').modal('show');
                }
            });
        });

        // // show payment edit modal with data
        $(document).on('click', '#edit_payment', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Payment');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('.data_preloader').hide();
                    $('#payment-modal-body').html(data); 
                    $('#paymentModal').modal('show'); 
                }
            });
        });

         // show payment edit modal with data
         $(document).on('click', '#edit_return_payment', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#payment_heading').html('Edit Return Payment');
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#payment-modal-body').html(data); 
                    $('#paymentModal').modal('show'); 
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
            var button = $(this);
            $('#payment_deleted_form').attr('action', url);
            swal({
                title: "Are you sure to delete ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) { 
                    $('#payment_deleted_form').submit();
                    button.closest('tr').remove();
                }
            });
        });
            
        //data delete by ajax
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
                    sales_table.ajax.reload();
                    toastr.error(data);
                }
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
    </script>
@endpush
