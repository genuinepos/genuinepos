@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <style>
        .contract_info_area ul li strong{color:#495677}.account_summary_area .heading h4{background:#0F3057;color:white}.contract_info_area ul li strong i {color: #495b77;font-size: 13px;}
    </style>

<div class="body-woaper">
    <div class="container-fluid">
        <!--begin::Container-->
        <div class="row">
            <div class="border-class">
                <div class="main__content">
                    <div class="sec-name">
                        <div class="name-head">
                            <span class="fas fa-people-arrows"></span>
                            <h5>Supplier View OF <b>{!! $supplier->name.'</b> (ID: '.$supplier->contact_id.')' !!}</h5>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                    </div>
                </div>
           
                <div class="card">
                    <div class="card-body">
                        <div class="tab_list_area">
                            <ul class="list-unstyled">
                                <li>
                                    <a id="tab_btn" data-show="ledger" class="tab_btn tab_active" href="#">
                                        <i class="fas fa-scroll"></i>Ledger
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="contract_info_area" class="tab_btn" href="#">
                                        <i class="fas fa-info-circle"></i> Contract Info
                                    </a>
                                </li>

                                <li>
                                    <a id="tab_btn" data-show="purchases" class="tab_btn" href="#">
                                        <i class="fas fa-shopping-bag"></i> Purchases
                                    </a>
                                </li>
                            </ul>
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
                                                        <td class="text-end"><strong>Opening Balance : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($supplier->opening_balance) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>Total Purchase : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>Total Return : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($supplier->total_return) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>Total Paid : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                        <td class="text-end">
                                                            {{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>Balance Due : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                        <td class="text-end">{{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-end"><strong>Total Returnable/Refundable Amount : {{ json_decode($generalSettings->business, true)['currency'] }}</strong></td>
                                                        <td class="text-end">
                                                            {{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_return_due) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7 col-sm-12 col-lg-8">
                                    <div class="account_summary_area">
                                        <div class="heading py-2">
                                            <h4 class="py-2 pl-1">Filter Area</h4>
                                        </div>

                                        <div class="account_summary_table">
                                            <form id="filter_supplier_ledgers" method="get" class="px-2">
                                                <div class="form-group row mt-4">
                                                    <div class="col-md-3">
                                                        <label><strong>Voucher Type :</strong></label>
                                                        <select name="voucher_type" class="form-control submit_able" id="voucher_type" autofocus>
                                                            <option value="">All</option> 
                                                            @foreach (App\Utils\SupplierUtil::voucherTypes() as $key => $type)
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
                                <div class="data_preloader d-none">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="ledger_list_table">
                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table ledger_table">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Particulars</th>
                                                        <th>Voucher/P.Invoice</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                        <th>Running Balance</th>
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

                        <div class="tab_contant contract_info_area d-none">
                            <div class="row">
                                <div class="col-md-3">
                                    <ul class="list-unstyled"><br>
                                        <li><strong>Supplier Name :</strong></li>
                                        <li>{{ $supplier->name }}</li><br>
                                        <li><strong><i class="fas fa-map-marker-alt"></i> Address</strong></li>
                                        <li>{{ $supplier->address }}</li><br>
                                        <li><strong><i class="fas fa-briefcase"></i> Business Name</strong></li>
                                        <li>{{ $supplier->business_name }}</li>
                                    </ul>
                                </div>

                                <div class="col-md-3"><br>
                                    <ul class="list-unstyled">
                                        <li><strong><i class="fas fa-phone-square"></i> Phone</strong></li>
                                        <li>{{ $supplier->phone }}</li>
                                    </ul>
                                </div>

                                <div class="col-md-3"><br>
                                    <ul class="list-unstyled">
                                        <li><strong><i class="fas fa-info"></i> Tex Number</strong></li>
                                        <li><span class="tax_number">{{ $supplier->tax_number }}</span></li>
                                    </ul>
                                </div>

                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li>
                                            <strong> Total Purchase : </strong> 
                                        </li>

                                        <li>
                                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}</b> {{ App\Utils\Converter::format_in_bdt($supplier->total_purchase) }}
                                        </li>

                                        <li>
                                            <strong> Total Paid : </strong> 
                                        </li>

                                        <li>
                                            <b> {{ json_decode($generalSettings->business, true)['currency'] }}</b> {{ App\Utils\Converter::format_in_bdt($supplier->total_paid) }}
                                        </li>

                                        <li>
                                            <strong> Total Purchase Due :</strong> 
                                        </li>

                                        <li>
                                            <b> {{ json_decode($generalSettings->business, true)['currency'] }}</b> {{ App\Utils\Converter::format_in_bdt($supplier->total_purchase_due) }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab_contant purchases d-none">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table_area">
                                        <div class="table-responsive">
                                            <table class="display data_tbl data__table purchase_table">
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

    <div id="purchase_details"></div>

    @if (auth()->user()->permission->purchase['purchase_payment'] == '1')
        <!--Payment list modal-->
        <div class="modal fade" id="paymentViewModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment List</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body" id="payment_list_modal_body"></div>
                </div>
            </div>
        </div>
        <!--Payment list modal-->

        <!--Add Payment modal-->
        <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
        <!--Add Payment modal-->
        
        <div class="modal fade" id="paymentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog four-col-modal" role="document">
                <div class="modal-content payment_details_contant">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Payment Details (<span class="payment_invoice"></span>)</h6>
                        <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="payment_details_area"></div>

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
                                        <button type="submit" id="print_payment" class="c-btn me-0 button-success">Print</button>
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
    <script src="{{ asset('public') }}/assets/plugins/custom/barcode/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var table = $('.purchase_table').DataTable({
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            ajax:"{{ url('contacts/suppliers/view', $supplierId) }}",

            columnDefs: [{
                "targets": [0, 5, 6],
                "orderable": false,
                "searchable": false
            }],

            columns: [
                {data: 'action'},
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'branches.name'},
                {data: 'supplier_name', name: 'suppliers.name'},
                {data: 'status', name: 'status'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'total_purchase_amount', name: 'total_purchase_amount'},
                {data: 'paid', name: 'paid'},
                {data: 'due', name: 'due'},
                {data: 'return_amount', name: 'purchase_return_amount'},
                {data: 'return_due', name: 'purchase_return_due'},
                {data: 'created_by', name: 'created_by.name'},
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
                "url": "{{ route('contacts.supplier.ledgers', $supplier->id) }}",
                "data": function(d) {
                    d.voucher_type = $('#voucher_type').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },

            columns: [
                {data: 'date', name: 'supplier_ledgers.report_date'},
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
        $(document).on('submit', '#filter_supplier_ledgers', function (e) {
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
                        'action': function() {$('#deleted_form').submit();}
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {console.log('Deleted canceled.');} 
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
                    table.ajax.reload();
                    toastr.error(data);
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
                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        //Print Ledger
        $(document).on('click', '#print_report', function (e) {
            e.preventDefault();
            var url = "{{ route('contacts.supplier.ledger.print', $supplierId) }}";
            var voucher_type = $('#voucher_type').val();
            var from_date = $('.from_date').val();
            var to_date = $('.to_date').val();
            $.ajax({
                url: url,
                type: 'get',
                data: { voucher_type, from_date, to_date },
                success: function(data) {
                    $(data).printThis({
                        debug: false,                   
                        importCSS: true,                
                        importStyle: true,          
                        loadCSS: "{{ asset('public/assets/css/print/sale.print.css') }}",                      
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
