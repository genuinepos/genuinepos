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
@section('title', 'Stock Adjustment Reports - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-sliders-h"></span>
                                <h5>Stock Adjustment Report</h5>
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
                                                    <div class="col-md-3 offset-md-6">
                                                        <label><strong>Branch :</strong></label>
                                                        <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                            <option value="">All</option>
                                                            <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    {{ $branch->name . '/' . $branch->branch_code }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else 
                                                    <input type="hidden" name="branch_id" id="branch_id" value="{{ auth()->user()->branch_id }}">
                                                @endif

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
                            <div class="sale_purchase_and_profit_area">
                                <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                                <div id="data_list">
                                    <div class="sale_and_purchase_amount_area">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <div class="card">
                                                    <div class="card-body mt-1">  
                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="text-start">Total Normal : </th>
                                                                    <td class="text-start"> <span class="total_normal"></span></td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total Abnormal : </th>
                                                                    <td class="text-start"><span class="total_abnormal"></span></td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start"> Total Stock Adjustment : </th>
                                                                    <td class="text-start"> <span class="total_adjustment"></span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                    
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <div class="card">
                                                    <div class="card-body "> 
                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th class="text-start">Total Amount Recovered</th>
                                                                    <td class="text-start"><span class="total_recovered"></span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
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
                                                    <th class="text-start">Reference No</th>
                                                    <th class="text-start">Adjustment From</th>
                                                    <th class="text-start">Type</th>
                                                    <th class="text-start">Total Amount</th>
                                                    <th class="text-start">Total Recovered Amount</th>
                                                    <th class="text-start">Reason</th>
                                                    <th class="text-start">Created By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
    
                                            </tbody>
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
    var __currency_symbol = "{{ json_decode($generalSettings->business, true)['currency'] }}";
    function getAdjustmentAmounts() {
        $('.data_preloader').show();
        var branch_id = $('#branch_id').val();
        var date_range = $('#date_range').val();
        $.ajax({
            url: "{{ route('reports.stock.adjustments.index') }}",
            data:{ branch_id, date_range },
            type: 'get',
            success: function(data) {
                $('.total_normal').html(__currency_symbol+' '+(data[0].total_normal ? data[0].total_normal : parseFloat(0).toFixed(2)));
                $('.total_abnormal').html(__currency_symbol+' '+(data[0].total_abnormal ? data[0].total_abnormal : parseFloat(0).toFixed(2)));
                $('.total_adjustment').html(__currency_symbol+' '+(data[0].t_amount ? data[0].t_amount : parseFloat(0).toFixed(2)));
                $('.total_recovered').html(__currency_symbol+' '+(data[0].t_recovered_amount ? data[0].t_recovered_amount : parseFloat(0).toFixed(2)));
                $('.data_preloader').hide();
            }
        });
    }
    getAdjustmentAmounts();

    adjustment_table = $('.data_tbl').DataTable({
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
            "url": "{{ route('reports.stock.adjustments.all') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.type = $('#status').val();
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
            {data: 'type', name: 'type'},
            {data: 'net_total', name: 'net_total'},
            {data: 'recovered_amount', name: 'recovered_amount'},
            {data: 'reason', name: 'reason'},
            {data: 'created_by', name: 'created_by'},
        ],
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        adjustment_table.ajax.reload();
        getAdjustmentAmounts();
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            adjustment_table.ajax.reload();
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
