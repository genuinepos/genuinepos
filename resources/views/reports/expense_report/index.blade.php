@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
<link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .sale_and_purchase_amount_area table tbody tr th,td {color: #32325d;}
        .sale_purchase_and_profit_area {position: relative;}
        .data_preloader{top:2.3%}
        .sale_and_purchase_amount_area table tbody tr th{text-align: left;}
        .sale_and_purchase_amount_area table tbody tr td{text-align: left;}
    </style>
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
                                <h5>Expense Report</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="sale_purchase_profit_filter" action="{{ route('reports.profit.filter.sale.purchase.profit') }}" method="get">
                                            <div class="form-group row">
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-3 offset-md-3">
                                                        <label><strong>Branch :</strong></label>
                                                        <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                            <option value="">All</option>
                                                            <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                        </select>
                                                    </div>
                                                @else 
                                                    <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>Expense For :</strong></label>
                                                    <select name="admin_id" class="form-control submit_able" id="admin_id" autofocus>
                                                        <option value="">All</option>
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

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">Date</th>
                                                    <th class="text-start">Reference ID</th>
                                                    <th class="text-start">Branch</th>
                                                    <th class="text-start">Expanse For</th>
                                                    <th class="text-start">Payment Status</th>
                                                    <th class="text-start">Tax</th>
                                                    <th class="text-start">Net Total</th>
                                                    <th class="text-start">Paid</th>
                                                    <th class="text-start">Payment Due</th>
                                                </tr>
                                            </thead>
                                            <tbody>
    
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-start text-white">Total :</th>
                                                    <th class="text-start text-white">
                                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        <span id="tax_amount"></span>
                                                    </th>
                                                    <th class="text-start text-white">
                                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        <span id="net_total"></span>
                                                    </th>
                                                    <th class="text-start text-white">
                                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        <span id="paid"></span>
                                                    </th>
                                                    <th class="text-start text-white">
                                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                                        <span id="due"></span>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div> 
                            </div>
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
<script>
    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        // Set branch in form field
        function setBranches(){
            $.ajax({
                url:"{{route('sales.get.all.branches')}}",
                async:true,
                success:function(branches){
                    $.each(branches, function(key, val){
                        $('#branch_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.branch_code+')'+'</option>');
                    });
                }
            });
        }
        setBranches();
    @endif

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

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary'},
            {extend: 'print',text: 'Print',className: 'btn btn-primary'},
        ],
        "processing": true,
        "serverSide": true,
        aaSorting: [[3, 'asc']],
        "ajax": {
            "url": "{{ route('reports.expenses.index') }}",
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
            { data: 'date', name: 'date' },
            { data: 'invoice_id', name: 'invoice_id'},
            { data: 'from', name: 'from'},
            { data: 'user_name', name: 'user_name' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'tax_percent', name: 'tax_percent' },
            { data: 'net_total', name: 'net_total' },
            { data: 'paid', name: 'paid' },
            { data: 'due', name: 'due' },
        ],
        fnDrawCallback: function() {
            var tax_amount = sum_table_col($('.data_tbl'), 'tax_amount');
            $('#tax_amount').text(parseFloat(tax_amount).toFixed(2));
            var net_total = sum_table_col($('.data_tbl'), 'net_total');
            $('#net_total').text(parseFloat(net_total).toFixed(2));
            var paid = sum_table_col($('.data_tbl'), 'paid');
            $('#paid').text(parseFloat(paid).toFixed(2));
            var due = sum_table_col($('.data_tbl'), 'due');
            $('#due').text(parseFloat(paid).toFixed(2));
        },
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
    $(document).on('change', '.submit_able', function () {
        table.ajax.reload();
        getAdjustmentAmounts();
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            table.ajax.reload();
            getAdjustmentAmounts();
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
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')
                ],
            }
        });
    });
</script>
@endpush
