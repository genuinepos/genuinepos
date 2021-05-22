@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css" />
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>Purchase Returns</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name mt-1">
                                    <div class="col-md-12">
                                        <i class="fas fa-funnel-dollar ms-2"></i> <b>Filter</b>
                                        <form action="" method="get" class="px-2">
                                            <div class="form-group row">
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-3">
                                                        <label><strong>Branch :</strong></label>
                                                        <select name="branch_id"
                                                            class="form-control form-control-sm submit_able" id="branch_id"
                                                            data-live-search="true">
                                                            <option value="NULL">Head Office</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-3">
                                                    <label><strong>Supplier :</strong></label>
                                                    <select name="supplier_id"
                                                        class="form-control form-control-sm selectpicker submit_able"
                                                        id="supplier_id" data-live-search="true">
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>Date Range :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week text-navy-blue"></i></span>
                                                        </div>
                                                        <input readonly type="text" name="date_range" id="date_range"
                                                            class="form-control form-control-sm daterange submit_able_input"
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
                                        <h6>All Purchase Returns <small>Note: Initially current year's data is available here, if you need
                                                another year's data go to the data filter.</small></h6>
                                    </div>
                                    @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                        <div class="col-md-2">
                                            <div class="btn_30_blue float-end">
                                                <a href="{{ route('purchases.returns.supplier.return') }}"><i
                                                        class="fas fa-plus-square"></i> Add Return</a>
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
                                                    <th>Date</th>
                                                    <th>PR.Invoice ID</th>
                                                    <th>Parent Sale</th>
                                                    <th>Supplier Name</th>
                                                    <th>Return From</th>
                                                    <th>Payment Status</th>
                                                    <th>Total Amount</th>
                                                    <th>Payment Due</th>
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
    </div>

    <div id="purchase_return_details">

    </div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.js"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            aaSorting: [
                [3, 'asc']
            ],
            "ajax": {
                "url": "{{ route('purchases.returns.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.supplier_id = $('#supplier_id').val();
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
                {data: 'invoice_id',name: 'invoice_id'},
                {data: 'parent_invoice_id',name: 'parent_invoice_id'},
                {data: 'sup_name',name: 'sup_name'},
                {data: 'from',name: 'from'},
                {data: 'payment_status',name: 'payment_status'},
                {data: 'total_return_amount',name: 'total_return_amount'},
                {data: 'total_return_due',name: 'total_return_due'},
                {data: 'action'},
            ],
        });

        // Get all supplier for filter form
        function setSupplier(){
            $.ajax({
                url:"{{route('purchases.get.all.supplier')}}",
                async:true,
                type:'get',
                dataType: 'json',
                success:function(suppliers){
                    $('#supplier_id').append('<option value="">All</option>');
                    $.each(suppliers, function(key, val){
                        $('#supplier_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.phone+')'+'</option>');
                    });
                }
            });
        }
        setSupplier();

        function returnDetails(url) {
            $('.data_preloader').show();
            $.get(url, function(data) {
                $('#purchase_return_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        }

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            var url = $(this).closest('tr').data('href');
            returnDetails(url);
        });

        // Show details modal with data by clicking the row
        $(document).on('click', 'tr.clickable_row td:not(:last-child)', function(e) {
            e.preventDefault();
            var url = $(this).parent().data('href');
            returnDetails(url);
        });

        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            var purchaseReturn = $(this).closest('tr').data('info');
            purchaseReturnDetails(purchaseReturn);
            $('#detailsModal').modal('show'); 
        })

      
        // Show sweet alert for delete
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            swal({
                title: "Are you sure to delete ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) { 
                    $('#deleted_form').submit();
                } else {
                    swal("Your imaginary file is safe!");
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
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                    }else{
                        table.ajax.reload();
                        toastr.success(data, 'Succeed');
                    }
                }
            });
        });

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function () {
            table.ajax.reload();
        });

        //Submit filter form by date-range field blur 
        $(document).on('blur', '.submit_able_input', function () {
            setTimeout(function() {
                table.ajax.reload();
            }, 800);
        });

        //Submit filter form by date-range apply button
        $(document).on('click', '.applyBtn', function () {
            setTimeout(function() {
                $('.submit_able_input').addClass('.form-control:focus');
                $('.submit_able_input').blur();
            }, 700);
        });


        // Make print
        $(document).on('click', '.print_btn', function (e) {
        e.preventDefault(); 
            var body = $('.purchase_return_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,                   
                importCSS: true,                
                importStyle: true,          
                loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",                      
                removeInline: true, 
                printDelay: 100, 
                header: null,        
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
    </script>
@endpush
