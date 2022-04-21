@extends('layout.master')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
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
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-people-arrows"></span>
                                <h5>Customer View OF <b>{!! $customer->name.'</b> (ID: '.$customer->contact_id.')' !!}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                            </div>

                            <div class="tab_list_area">
                                <ul class="list-unstyled">
                                    <li>
                                        <a id="tab_btn" data-show="ledger" class="tab_btn tab_active" href="#">
                                            <i class="fas fa-scroll"></i> Ledger
                                        </a>
                                    </li>

                                    <li>
                                        <a id="tab_btn" data-show="contract_info_area" class="tab_btn" href="#"><i class="fas fa-info-circle">
                                            </i> Contract Info
                                        </a>
                                    </li>

                                    <li>
                                        <a id="tab_btn" data-show="sale" class="tab_btn" href="#">
                                            <i class="fas fa-shopping-bag"></i> Sale
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab_contant contract_info_area d-none">
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="list-unstyled">
                                            <li><strong>Customer Name :</strong></li>
                                            <li><span class="name">{{ $customer->name }}</span></li><br>
                                            <li><strong><i class="fas fa-map-marker-alt"></i> Address :</strong></li>
                                            <li><span class="address">{{ $customer->address }}</span></li><br>
                                            <li><strong><i class="fas fa-briefcase"></i> Business Name :</strong></li>
                                            <li><span class="business">{{ $customer->business_name }}</span></li>
                                        </ul>
                                    </div>

                                    <div class="col-md-3">
                                        <ul class="list-unstyled">
                                            <li><strong><i class="fas fa-phone-square"></i> Phone</strong></li>
                                            <li><span class="phone">{{ $customer->phone }}</span></li>
                                        </ul>
                                    </div>

                                    <div class="col-md-3">
                                        <ul class="list-unstyled">
                                            <li><strong><i class="fas fa-info"></i> Tax Number</strong></li>
                                            <li><span class="tax_number">{{ $customer->tax_number }}</span></li>
                                        </ul>
                                    </div>

                                    <div class="col-md-3">
                                        <ul class="list-unstyled">
                                            <li><strong> Opening Balance : {{ json_decode($generalSettings->business, true)['currency'] }}</strong> {{ App\Utils\Converter::format_in_bdt($customer->opening_balance) }}</li>
                                            <li><strong> Total Sale : {{ json_decode($generalSettings->business, true)['currency'] }}</strong> {{ App\Utils\Converter::format_in_bdt($customer->total_sale) }}</li>
                                            <li><strong> Total Return : {{ json_decode($generalSettings->business, true)['currency'] }}</strong> {{ App\Utils\Converter::format_in_bdt($customer->total_return) }}</li>
                                            <li><strong> Total Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</strong> {{ App\Utils\Converter::format_in_bdt($customer->total_paid) }}</li>
                                            <li><strong> Total Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong> {{ App\Utils\Converter::format_in_bdt($customer->total_sale_due) }}</li>
                                            <li><strong> Total Returnable Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong> {{ App\Utils\Converter::format_in_bdt($customer->total_sale_return_due) }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab_contant ledger">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-lg-4">
                                        <div class="account_summary_area">
                                            <div class="heading py-2">
                                                <h4 class="py-2 pl-1">Account Summary</h4>
                                            </div>

                                            <div class="account_summary_table">
                                                <table class="table modal-table table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-end"><strong>Opening Balance : {{ json_decode($generalSettings->business, true)['currency'] }}</strong> </td>
                                                            <td class="text-end"> {{ App\Utils\Converter::format_in_bdt($customer->opening_balance) }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-end"><strong>Total Sale : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($customer->total_sale) }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-end"><strong>Total Return : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($customer->total_return) }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-end"><strong>Total Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                            <td class="text-end"> 
                                                                {{ App\Utils\Converter::format_in_bdt($customer->total_paid) }}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-end"><strong>Balance Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_due) }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-end"><strong>Returnable Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($customer->total_sale_return_due) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8 col-sm-12 col-lg-8">
                                        <div class="account_summary_area">
                                            <div class="heading py-2">
                                                <h4 class="py-2 pl-1">Filter Area</h4>
                                            </div>

                                            <div class="account_summary_table">
                                                <form id="filter_customer_ledgers" method="get" class="px-2">
                                                    <div class="form-group row mt-4">
                                                        <div class="col-md-3">
                                                            <label><strong>Voucher Type :</strong></label>
                                                            <select name="voucher_type" class="form-control submit_able" id="voucher_type" autofocus>
                                                                <option value="">All</option> 
                                                                @foreach (App\Utils\CustomerUtil::voucherTypes() as $key => $type)
                                                                    <option value="{{ $key }}">{{ $type }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
        
                                                        <div class="col-md-3">
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
        
                                                        <div class="col-md-3">
                                                            <label><strong>To Date :</strong></label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text" id="basic-addon1"><i
                                                                            class="fas fa-calendar-week input_f"></i></span>
                                                                </div>
                                                                <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                                            </div>
                                                        </div>
        
                                                        <div class="col-md-3">
                                                            <div class="row">
                                                                <div class="col-md-7">
                                                                    <label><strong></strong></label>
                                                                    <div class="input-group">
                                                                        <button type="submit" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
                                                                    </div>
                                                                </div>
                    
                                                                <div class="col-md-5 mt-3">
                                                                    <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> Print</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="ladger_table">
                                            <div class="table-responsive" id="payment_list_table">
                                                <table class="display data_tbl data__table ledger_table">
                                                    <thead>
                                                        <tr>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Particulars</th>
                                                                <th>Voucher/Invoice</th>
                                                                <th>Debit</th>
                                                                <th>Credit</th>
                                                                <th>Running Balance</th>
                                                            </tr>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                    <tfoot>
                                                        <tr class="bg-secondary">
                                                            <th colspan="3" class="text-white text-end">Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                            <th id="debit" class="text-white text-end"></th>
                                                            <th id="credit" class="text-white text-end"></th>
                                                            <th id="due" class="text-white text-end">---</th>
                                                        </tr>
                                                    </tfoot>
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
                                                <table class="display data_tbl data__table data_tbl2 w-100">
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
                    <div class="payment_details_area"></div>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        //Get all customer for filter form
        var sales_table = $('.data_tbl2').DataTable({
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
                {data: 'total_payable_amount', name: 'total_payable_amount', className: 'text-end'},
                {data: 'paid', name: 'paid', className: 'text-end'},
                {data: 'due', name: 'due', className: 'text-end'},
                {data: 'sale_return_amount', name: 'sale_return_amount', className: 'text-end'},
                {data: 'sale_return_due', name: 'sale_return_due', className: 'text-end'},
                {data: 'paid_status', name: 'paid_status'},
            ],
        });

        var ledger_table = $('.ledger_table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching" : false,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-primary'},
                {extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> Pdf', className: 'btn btn-primary'},
            ],

            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],

            "ajax": {
                "url": "{{ route('contacts.customer.ledger.list', $customer->id) }}",
                "data": function(d) {
                    d.voucher_type = $('#voucher_type').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },

            columns: [
                {data: 'date', name: 'customer_ledgers.report_date'},
                {data: 'particulars', name: 'particulars'},
                {data: 'voucher_no', name: 'voucher_no'},
                {data: 'debit', name: 'debit', className: 'text-end'},
                {data: 'credit', name: 'credit', className: 'text-end'},
                {data: 'running_balance', name: 'running_balance', className: 'text-end'},
            ],fnDrawCallback: function() {
                var debit = sum_table_col($('.data_tbl'), 'debit');
                $('#debit').text(bdFormat(debit));
                var credit = sum_table_col($('.data_tbl'), 'credit');
                $('#credit').text(bdFormat(credit));
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
        $(document).on('submit', '#filter_customer_ledgers', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            ledger_table.ajax.reload();
        });
        
        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });

        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
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

        //Print Customer ledger
        $(document).on('click', '#print_report', function (e) {
            e.preventDefault();
            var url = "{{ route('contacts.customer.ledger.print', $customerId) }}";
            var voucher_type = $('#voucher_type').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: { voucher_type, from_date, to_date },
                success:function(data){
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
    </script>
@endpush
