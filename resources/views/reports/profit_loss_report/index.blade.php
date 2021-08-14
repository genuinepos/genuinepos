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
                                <span class="fas fa-money-bill-wave"></span>
                                <h5>Profit / Loss Report</h5>
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
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-3 offset-md-6">
                                                            <label><strong>Business Location :</strong></label>
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
                                                                    <th class="text-start">
                                                                        Opening Stock : <br>
                                                                        <small class="text-muted">(By purchase price) </small>
                                                                    </th>
                                                                    <td class="text-start"> 700.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">
                                                                        Total purchase : <br>
                                                                        <small class="text-muted">(Exc. tax, Discount) </small>
                                                                    </th>
                                                                    <td class="text-start"> 700.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start"> Total Stock Adjustment : </th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start"> Total Expense : </th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start"> Total purchase shipping charge : </th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total transfer shipping charge : </th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total Sell discount : </th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start"> Total customer reward : </th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total Sell Return : </th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total Payroll :</th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total Production Cost :</th>
                                                                    <td class="text-start">0.00</td>
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
                                                                    <th class="text-start">
                                                                        Closing stock <br>
                                                                        <small>(By purchase price)</small>
                                                                    </th class="text-start">
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">
                                                                        Closing stock : <br>
                                                                        <small>(By sale price)</small>
                                                                    </th>
                                                                    <td class="text-start"> 0.0</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">
                                                                        Total Sales : <br>
                                                                        <small>((Exc. tax, Discount))</small>
                                                                    </th>
                                                                    <td class="text-start"> 0.0</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total sell shipping charge : </th>
                                                                    <td class="text-start"> 0.0</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total Stock Recovered : </th>
                                                                    <td class="text-start"> 0.0</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total Purchase Return : </th>
                                                                    <td class="text-start"> 0.0</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total Purchase discount : </th>
                                                                    <td class="text-start"> 0.0</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start">Total sell round off : </th>
                                                                    <td class="text-start"> 0.0</td>
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
                            <div class="by_profit_area">
                                <div class="filter_form mb-1">
                                    <form id="filter_by_profit_form" action="" method="get">
                                        @csrf
                                        <input type="hidden" id="profit_by" name="profit_by">
                                        <div class="row">
                                            <div class="col-md-3 offset-md-9">
                                                <label><strong>Date Range :</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                    </div>
                                                    <input readonly type="text" name="by_profit_range" id="by_profit_range" class="form-control submit_able_input2 daterange" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
            
                                <div class="card">
                                    <div class="card-body">
                                        <!--begin: Datatable-->
                                        <div class="tab_list_area">
                                            <ul class="list-unstyled">
                                                <li><a id="tab_btn" data-by="by_product" class="tab_btn tab_active" href=""><i
                                                            class="fas fa-info-circle"></i> Profit By Product</a></li>
                                                <li><a id="tab_btn" data-by="by_category" class="tab_btn" href=""><i class="fas fa-scroll"></i>
                                                        Profit by Category</a></li>
                                                <li><a id="tab_btn" data-by="by_brand" class="tab_btn" href=""><i
                                                            class="fas fa-shopping-bag"></i> Profit By Brand</a></li>
                                                <li><a id="tab_btn" data-by="by_branch" class="tab_btn" href=""><i
                                                                class="far fa-folder-open"></i> Profit By Branch</a></li>            
                                                <li><a id="tab_btn" data-by="by_invoice" class="tab_btn" href=""><i
                                                            class="far fa-folder-open"></i> Profit By Invoice</a></li>
                                            </ul>
                                        </div>
            
                                        <div class="tab_contant">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="by_profit_table">
                                                        <div class="data_preloader" id="by_profit_preloader">
                                                            <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                                        </div>
                                                        <div class="table-responsive" id="by_profit_list">
                                                            <table class="table" id="kt_datatable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product</th>
                                                                        <th>Gross Profit</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Product Name</td>
                                                                        <td>
                                                                            {{ json_decode($generalSettings->business, true)['currency'] }} 0.00
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
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
    function getSalePurchaseAndProfitData() {
        $('.data_preloader').show();
        $.ajax({
            url:"{{route('reports.profit.sale.purchase.profit')}}",
            type:'get',
            success:function(data){
                $('#data_list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getSalePurchaseAndProfitData();

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        $('#sale_purchase_profit_filter').submit();
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            $('#sale_purchase_profit_filter').submit();
        }, 500);
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.applyBtn', function () {
        setTimeout(function() {
            $('.submit_able_input').addClass('.form-control:focus');
            $('.submit_able_input').blur();
        }, 500);
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input2', function () {
        setTimeout(function() {
            $('#filter_by_profit_form').submit();
        }, 500);
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.by_profit_filter_btn', function () {
        setTimeout(function() {
            $('.submit_able_input2').addClass('.form-control:focus');
            $('.submit_able_input2').blur();
        }, 500);
    });

    //Send sale purchase profit filter request
    $('#sale_purchase_profit_filter').on('submit', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'get',
            data: request,
            success:function(data){
                $('#data_list').html(data);
                $('.data_preloader').hide();
            }
        }); 
    });

    function by_profit_data() {
        $('#by_profit_preloader').show();
        var profit_by = $('#profit_by').val();
        if (profit_by == '') {
            profit_by = 'by_product';
        }

        var by_profit_range = $('#by_profit_range').val();
        if (by_profit_range == '') {
            by_profit_range = 'current_year';
        }

        $.ajax({
            url:"{{url('reports/profit/loss/by')}}",
            type:'get',
            data:{profit_by: profit_by, by_profit_range : by_profit_range},
            success:function(data){
                console.log(data);
                $('#by_profit_list').html(data);
                $('#by_profit_preloader').hide();
            }
        });
    }
    by_profit_data();

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('.tab_btn').removeClass('tab_active');
        var by_profit = $(this).data('by');
        $('#profit_by').val(by_profit);
        $(this).addClass('tab_active');
        by_profit_data();
    });

    $(document).on('submit', '#filter_by_profit_form', function(e) {
        e.preventDefault();
        by_profit_data();
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
