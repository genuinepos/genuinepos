@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css" />
@endpush
@section('title', 'Sale Drafts - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-drafting-compass"></span>
                                <h5>Drafts</h5>
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
                                                            <select name="branch_id" class="form-control submit_able" id="branch_id">
                                                                <option value="">All</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                                @foreach ($branches as $b)
                                                                    <option value="{{ $b->id }}">{{ $b->name.'/'.$b->branch_code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-3">
                                                    <label><strong>Customer :</strong></label>
                                                    <select name="customer_id"
                                                        class="form-control selectpicker submit_able"
                                                        id="customer_id">
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
                                    <h6>All Drafts </h6>
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
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Draft ID</th>
                                                <th>Branch</th>
                                                <th>Customer</th>
                                                <th>Total Amount</th>
                                                <th>Created By</th>
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

    <div id="draft_details">
        
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

        // Filter toggle
        $('.filter_btn').on('click', function (e) {
            e.preventDefault();
            $('.filter_body').toggle(500);
        });

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
                "url": "{{ route('sales.drafts') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.customer_id = $('#customer_id').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                { data: 'date', name: 'date'},
                { data: 'invoice_id', name: 'invoice_id'},
                {data: 'from', name: 'from'},
                {data: 'customer', name: 'customer'},
                {data: 'total_payable_amount', name: 'total_payable_amount'},
                {data: 'user', name: 'user'},
                {data: 'action',},
            ],
        });

        // Get all supplier for filter form
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

        // Pass sale details in the details modal
        function draftDetails(url) {
            $('.data_preloader').show();
            $.ajax({
                url:url,
                type:'get',
                success:function(data){
                    $('#draft_details').html(data);
                    $('.data_preloader').hide();
                    $('#detailsModal').modal('show');
                }
            });
        }

        // Pass draft details in the details modal
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            draftDetails(url);
        });

        // Show details modal with data by clicking the row
        $(document).on('click', 'tr.clickable_row td:not(:first-child, :last-child)', function(e){
            e.preventDefault();
            var draft = $(this).parent().data('href');
            draftDetails(draft);
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

        // Make print
        $(document).on('click', '.print_btn',function (e) {
           e.preventDefault(); 
            var body = $('.draft_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,                   
                importCSS: true,                
                importStyle: true,          
                loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",                      
                removeInline: false, 
                printDelay: 800, 
                header: null,        
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
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            // alert('Deleted canceled.')
                        } 
                    }
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
                    table.ajax.reload();
                    toastr.error(data);
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