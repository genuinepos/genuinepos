@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
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
                                <h5>Purchases & Sales Report</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="sale_purchase_filter" action="{{ route('reports.profit.sales.filter.purchases.amounts') }}" method="get">
                                            <div class="form-group row">
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-3 offset-md-6">
                                                        <label><strong>Branch :</strong></label>
                                                        <select name="branch_id" class="form-control submit_able" id="branch_id" autofocus>
                                                            <option value="">All</option>
                                                            <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
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
                                                    <div class="card-body">  
                                                        <div class="heading">
                                                            <h6 class="text-primary"><b>Purchases</b></h6>
                                                        </div>
                
                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th>Total Purchase :</th>
                                                                    <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th>Purchase Including Tax : </th>
                                                                    <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th> Purchase Due: </th>
                                                                    <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                    
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <div class="card">
                                                    <div class="card-body"> 
                                                        <div class="heading">
                                                            <h6 class="text-primary"><b>Sales</b></h6>
                                                        </div>
                
                                                        <table class="table modal-table table-sm">
                                                            <tbody>
                                                                <tr>
                                                                    <th>Total Sale :</th>
                                                                    <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th>Sale Including Tax : </th>
                                                                    <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
                                                                </tr>
                        
                                                                <tr>
                                                                    <th> Sale Due: </th>
                                                                    <td>{{ json_decode($generalSettings->business, true)['currency'] }} 0.00</td>
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
    function salePurchaseDueAmounts() {
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('reports.profit.sales.purchases.amounts') }}",
            type:'get',
            success:function(data){
                $('#data_list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    salePurchaseDueAmounts();

    //Send sale purchase amount filter request
    $('#sale_purchase_filter').on('submit', function (e) {
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

    // Set branch in form field
    function setBranches(){
        $.ajax({
            url:"{{route('sales.get.all.branches')}}",
            async:true,
            type:'get',
            dataType: 'json',
            success:function(branches){
                $.each(branches, function(key, val){
                    $('#branch_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.branch_code+')'+'</option>');
                });
            }
        });
    }
    setBranches();

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        $('#sale_purchase_filter').submit();
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            $('#sale_purchase_filter').submit();
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
