@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                                    <div class="col-md-8">
                                        <form id="sale_purchase_profit_filter" action="{{ route('reports.profit.filter.sale.purchase.profit') }}" method="get">
                                            <div class="form-group row">
                                                @if ($addons->branches == 1)
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-3">
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
                                                    <label><strong>From Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="datepicker"
                                                            class="form-control from_date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>To Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button class="btn text-white btn-sm btn-secondary float-start">
                                                            <i class="fas fa-funnel-dollar"></i> Filter
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label></label>
                                            <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> Print</a>
                                        </div>
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
                                                                    <th class="text-start"> Total Stock Adjustment : </th>
                                                                    <td class="text-start"> 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th class="text-start"> Total Expense : </th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

    //Print Profit/Loss 
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('reports.profit.loss.print') }}";
        var branch_id = $('#branch_id').val();
        var from_date = $('.from_date').val();
        var to_date = $('.to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, from_date, to_date},
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
            success:function(data) {
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

    $(document).on('click', '.cancelBtn ', function () {
        $('.daterange').val('');
    });
</script>
@endpush
