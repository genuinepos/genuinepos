@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css" />
@endpush
@section('title', 'Shipments - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shipping-fast"></span>
                                <h5>Shipments</h5>
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
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-3">
                                                        <label><strong>Branch :</strong></label>
                                                        <select name="branch_id"
                                                            class="form-control submit_able" id="branch_id"
                                                            data-live-search="true">
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

                                                <div class="col-md-3">
                                                    <label><strong>Customer :</strong></label>
                                                    <select name="customer_id"
                                                        class="form-control selectpicker submit_able"
                                                        id="customer_id">
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
                                    <h6>All Shipment </h6>
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
                                                <th>Date</th>
                                                <th>Invoice ID</th>
                                                <th>Sale From</th>
                                                <th>Customer</th>
                                                <th>Created By</th>
                                                <th>Shipment Status</th>
                                                <th>Payment Status</th>
                                                <th>Actions</th>
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

    <div id="sale_details">
        
    </div>

    <!-- Edit Shipping modal -->
    <div class="modal fade" id="editShipmentModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content" id="edit_shipment_modal_content">
                
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
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [[3, 'asc']],
            "ajax": {
                "url": "{{ route('sales.shipments') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.payment_status = $('#payment_status').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'from'},
                {data: 'customer', name: 'customer'},
                {data: 'created_by', name: 'created_by'},
                {data: 'shipment_status', name: 'shipment_status'},
                {data: 'paid_status', name: 'paid_status'},
                {data: 'action',},
            ],
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

        // Show details modal with data by clicking the row
        $(document).on('click', 'tr.clickable_row td:not(:last-child)', function(e){
            e.preventDefault();
            var sale = $(this).parent().data('href');
            saleDetails(sale);
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
                    table.ajax.reload();
                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#editShipmentModal').modal('hide'); 
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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year')
                        .subtract(1, 'year')
                    ],
                }
            });
        });

        function setCustomers(){
            $.get("{{route('sales.get.all.customer')}}", function(customers) {
                $('#customer_id').append('<option value="">All</option>');
                $('#customer_id').append('<option value="NULL">Walk-In-Customer</option>');
                $.each(customers, function(key, val){
                    $('#customer_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.phone+')'+'</option>');
                });
            });
        }
        setCustomers();

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
    </script>
@endpush