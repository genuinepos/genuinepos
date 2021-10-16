@extends('layout.master')
@push('stylesheets')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .data_preloader{top:2.3%}
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
@section('title', 'Sold Products - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>Sold Product List</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                <div class="col-md-2 search_area">
                                                    <label><strong>Search Product :</strong></label>
                                                    <input type="text" name="search_product" id="search_product" class="form-control" placeholder="Search Product By name" autofocus autocomplete="off">
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
                                                        <div class="col-md-2">
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

                                                <div class="col-md-2">
                                                    <label><strong>Customer :</strong></label>
                                                    <select name="customer_id" class="form-control submit_able" id="customer_id" autofocus>
                                                        <option value="">All</option>
                                                        <option value="NULL">Walk-In-Customer</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>Category :</strong></label>
                                                    <select name="category_id" class="form-control submit_able"
                                                        id="category_id">
                                                        <option value="">All</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>Sub-Category :</strong></label>
                                                    <select name="sub_category_id" class="form-control submit_able" id="sub_category_id">
                                                        <option value="">All</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-2">
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

                                                <div class="col-md-2">
                                                    <label><strong>To Date :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="datepicker2"
                                                            class="form-control to_date"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="button" id="filter_button" class="btn text-white btn-sm btn-secondary float-start"><i class="fas fa-funnel-dollar"></i> Filter</button>
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
                                    <div class="section-header">
                                        <div class="col-md-10">
                                            <h6>Sold Product List</h6>
                                        </div>
                                        @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                            <div class="col-md-2">
                                                <a href="#" class="btn btn-sm btn-primary float-end" id="print_report"><i class="fas fa-print"></i> Print</a>
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
                                                        <th>Product</th>
                                                        <th>P.Code</th>
                                                        <th>Customer</th>
                                                        <th>Invoice ID</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                        <th>Subtotal({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr class="bg-secondary">
                                                        <th colspan="5" class="text-end text-white">Total :{{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                                        <th class="text-white">(<span id="total_qty"></span>)</th>
                                                        <th class="text-white">---</th>
                                                        <th class="text-white"> <span id="total_subtotal"></span></th>
                                                        <th></th>
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
    </div>
@endsection
@push('scripts')
<script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
<script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [ 
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary'},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary'},
        ],
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        //aaSorting: [[0, 'desc']],
        "ajax": {
            "url": "{{ route('sales.product.list') }}",
            "data": function(d) {
                d.product_id = $('#product_id').val();
                d.variant_id = $('#variant_id').val();
                d.branch_id = $('#branch_id').val();
                d.category_id = $('#category_id').val();
                d.sub_category_id = $('#sub_category_id').val();
                d.customer_id = $('#customer_id').val();
                d.from_date = $('.from_date').val();
                d.to_date = $('.to_date').val();
            }
        },
        columns: [
            {data: 'date', name: 'sales.date'},
            {data: 'product', name: 'products.name'},
            {data: 'sku', name: 'products.product_code'},
            {data: 'customer', name: 'customers.name'},
            {data: 'invoice_id', name: 'sales.invoice_id'},
            {data: 'quantity', name: 'quantity', className:'text-end'},
            {data: 'unit_price_inc_tax', name: 'unit_price_inc_tax', className:'text-end'},
            {data: 'subtotal', name: 'subtotal', className:'text-end'},
            {data: 'action'},
        ],
        fnDrawCallback: function() {
            var total_qty = sum_table_col($('.data_tbl'), 'qty');
            $('#total_qty').text(parseFloat(total_qty).toFixed(2));
            var total_subtotal = sum_table_col($('.data_tbl'), 'subtotal');
            var __total_subtotal = parseFloat(total_subtotal).toFixed(2)
            $('#total_subtotal').text(__total_subtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('.data_preloader').hide();
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

    $('#category_id').on('change', function() {
        var category_id = $(this).val();
        $.get("{{ url('product/all/sub/category/') }}"+"/"+category_id, function(subCategories) {
            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value="">Select Sub-Category</option>');
            $.each(subCategories, function(key, val) {
                $('#sub_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });
    });

    //Submit filter form by select input changing
    $(document).on('click', '#filter_button', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        table.ajax.reload();
    });

    //Submit filter form by date-range field blur 
    $(document).on('click', '#search_product', function () {
        $(this).val('');
        $('#product_id').val('');
        $('#variant_id').val('');
    });

    $('#search_product').on('input', function () {
        $('.search_result').hide();
        var product_name = $(this).val();
        if (product_name === '') {
            $('.search_result').hide();
            $('#product_id').val('');
            $('#variant_id').val('');
            return;
        }

        $.ajax({
            url:"{{ url('reports/product/purchases/search/product') }}"+"/"+product_name,
            async:true,
            type:'get',
            success:function(data){
                if (!$.isEmptyObject(data.noResult)) {
                    $('.search_result').hide();
                } else {
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
    });

    $('body').keyup(function(e){
        e.preventDefault();
        if (e.keyCode == 13 || e.keyCode == 9) {  
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
        var from_date = $('from_date').val();
        var to_date = $('to_date').val();
        $.ajax({
            url:url,
            type:'get',
            data: {branch_id, product_id, customer_id, variant_id, from_date, to_date},
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
        format: 'DD-MM-YYYY'
    });
</script>
@endpush