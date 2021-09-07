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
        /* Search Product area style */
        .selectProduct {background-color: #ab1c59;color: #fff !important;}
        .search_area{position: relative;}
        .search_result {position: absolute;width: 100%;border: 1px solid #E4E6EF;background: white;z-index: 1;padding: 8px;
            margin-top: 1px;}
        .search_result ul li {width: 100%;border: 1px solid lightgray;margin-top: 3px;}
        .search_result ul li a {color: #6b6262;font-size: 12px;display: block;padding: 3px;}
        .search_result ul li a:hover {color: white;background-color: #ab1c59;}
        /* Search Product area style end */
    </style>
@endpush
@section('title', 'Product Sale Report - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>Product Sale Report</h5>
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
                                                <div class="col-md-3 search_area">
                                                    <label><strong>Search Product :</strong></label>
                                                    <input type="text" name="search_product" id="search_product" class="form-control" placeholder="Search Product By name" autofocus>
                                                    <input type="hidden" name="product_id" id="product_id" value="">
                                                    <input type="hidden" name="variant_id" id="variant_id" value="">
                                                    <div class="search_result d-none">
                                                        <ul id="list" class="list-unstyled">
                                                            <li><a id="select_product" data-p_id="" data-v_id="" href="">Samsung A30</a></li>
                                                        </ul>
                                                    </div>
                                                </div>

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
                                                    <label><strong>Customer :</strong></label>
                                                    <select name="customer_id" class="form-control submit_able" id="customer_id" autofocus>
                                                        <option value="">All</option>
                                                        <option value="NULL">Walk-In-Customer</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>Date Range :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input readonly type="text" name="date_range" id="date_range" class="form-control daterange submit_able_input" autocomplete="off">
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
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>P.Code</th>
                                                    <th>Customer</th>
                                                    <th>Invoice ID</th>
                                                    <th>Date</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
    
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" class="text-end">Total :</th>
                                                    <th>(<span id="total_qty"></span>)</th>
                                                    <th>{{ json_decode($generalSettings->business, true)['currency'] }} <span id="total_price_inc_tax"></span></th>
                                                    <th>{{ json_decode($generalSettings->business, true)['currency'] }} <span id="total_subtotal"></span></th>
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
<script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
<script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
<script>
    function setCustomers(){
        $.ajax({
            url:"{{route('sales.get.all.customer')}}",
            type:'get',
            success:function(customers){
                $.each(customers, function(key, val){
                    $('#customer_id').append('<option value="'+val.id+'">'+ val.name +' ('+val.phone+')'+'</option>');
                });
            }
        });
    }
    setCustomers();

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary'},
            {extend: 'print',text: 'Print',className: 'btn btn-primary'},
        ],
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        aaSorting: [[5, 'desc']],
        "ajax": {
            "url": "{{ route('reports.product.sales.index') }}",
            "data": function(d) {
                d.product_id = $('#product_id').val();
                d.variant_id = $('#variant_id').val();
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
            {data: 'product', name: 'product'},
            {data: 'sku', name: 'sku'},
            {data: 'customer', name: 'customer'},
            {data: 'invoice_id', name: 'invoice_id'},
            {data: 'date', name: 'date'},
            {data: 'qty', name: 'qty'},
            {data: 'unit_price_inc_tax', name: 'unit_price_inc_tax'},
            {data: 'subtotal', name: 'subtotal'},
        ],
        fnDrawCallback: function() {
            var total_qty = sum_table_col($('.data_tbl'), 'qty');
            $('#total_qty').text(parseFloat(total_qty).toFixed(2));
            var total_price_inc_tax = sum_table_col($('.data_tbl'), 'unit_price_inc_tax');
            $('#total_price_inc_tax').text(parseFloat(total_price_inc_tax).toFixed(2));
            var total_subtotal = sum_table_col($('.data_tbl'), 'subtotal');
            $('#total_subtotal').text(parseFloat(total_subtotal).toFixed(2));
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
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            table.ajax.reload();
        }, 500);
    });

     //Submit filter form by date-range field blur 
     $(document).on('click', '#search_product', function () {
        $(this).val('');
        $('#product_id').val('');
        table.ajax.reload();
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.applyBtn', function () {
        setTimeout(function() {
            $('.submit_able_input').addClass('.form-control:focus');
            $('.submit_able_input').blur();
        }, 500);
    });

    $('#search_product').on('input', function () {
        $('.search_result').hide();
        var product_name = $(this).val();
        if (product_name === '') {
            $('.search_result').hide();
            $('#product_id').val('');
            $('#variant_id').val('');
            table.ajax.reload();
            return;
        }

        $.ajax({
            url:"{{ url('reports/product/purchases/search/product') }}"+"/"+product_name,
            async:true,
            type:'get',
            success:function(data){
                if (!$.isEmptyObject(data.noResult)) {
                    $('.search_result').hide();
                }else{
                    $('.search_result').show();
                    $('#list').html(data);
                }
            }
        });
    });

    $(document).on('click', '#select_product', function (e) {
        e.preventDefault();
        var product_name = $(this).html();
        $('#search_product').val(product_name.trim());
        var product_id = $(this).data('p_id');
        var variant_id = $(this).data('v_id');
        $('#product_id').val(product_id);
        $('#variant_id').val(variant_id);
        $('.search_result').hide();
        table.ajax.reload();
    });

    $('body').keyup(function(e){
        if (e.keyCode == 13 || e.keyCode == 9){  
            $(".selectProduct").click();
            $('.search_result').hide();
            $('#list').empty();
        }
    });

    $(document).on('mouseenter', '#list>li>a',function () {
        $('#list>li>a').removeClass('selectProduct');
        $(this).addClass('selectProduct');
    });

    //Print purchase report
    $(document).on('click', '#print_report', function (e) {
        e.preventDefault();
        var url = "{{ route('reports.product.sales.print') }}";
        var branch_id = $('#branch_id').val();
        var product_id = $('#product_id').val();
        var variant_id = $('#variant_id').val();
        var customer_id = $('#customer_id').val();
        var date_range = $('#date_range').val();
        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, product_id, customer_id, variant_id, date_range},
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
                'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')],
            }
        });
    });
</script>
@endpush
