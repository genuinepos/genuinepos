@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/backend/asset/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .data_preloader{top:2.3%}
        /* Search Product area style */
        .selectProduct {background-color: #746e70;color: #fff !important;}
        .search_area{position: relative;}
        .search_result {position: absolute;width: 100%;border: 1px solid #E4E6EF;background: white;z-index: 1;padding: 8px;
            margin-top: 1px;}
        .search_result ul li {width: 100%;border: 1px solid lightgray;margin-top: 3px;}
        .search_result ul li a {color: #6b6262;font-size: 12px;display: block;padding: 3px;}
        .search_result ul li a:hover {color: white;background-color: #999396;}
        /* Search Product area style end */
    </style>
@endpush
@section('title', 'Manufacturing Report- ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul>
                                        @if (auth()->user()->permission->manufacturing['process_view'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.process.index') }}" class="text-white"><i class="fas fa-dumpster-fire"></i> <b>@lang('menu.process')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->manufacturing['production_view'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.productions.index') }}" class="text-white"><i class="fas fa-shapes"></i> <b>@lang('menu.productions')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->manufacturing['manuf_settings'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.settings.index') }}" class="text-white"><i class="fas fa-sliders-h"></i> <b>@lang('menu.manufacturing_setting')</b></a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->permission->manufacturing['manuf_report'] == '1')
                                            <li>
                                                <a href="{{ route('manufacturing.report.index') }}" class="text-white"><i class="fas fa-file-alt text-primary"></i> <b>@lang('menu.manufacturing_report')</b></a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="sec-name">
                                <div class="col-md-12">
                                    <form id="filter_form" class="px-2">
                                        <div class="form-group row">
                                            <div class="col-md-2 search_area">
                                                <label><strong>Search Product :</strong></label>
                                                <input type="text" name="search_product" id="search_product" class="form-control" placeholder="Search Product By name" autofocus autocomplete="off">
                                                <input type="hidden" name="product_id" id="product_id" value="">
                                                <input type="hidden" name="variant_id" id="variant_id" value="">
                                                <div class="search_result d-none">
                                                    <ul id="list" class="list-unstyled">
                                                        <li><a id="select_product" class="" data-p_id="" data-v_id="" href="">Samsung A30</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                            @if ($addons->branches == 1)
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-2">
                                                        <label><strong>Business Location :</strong></label>
                                                        <select name="branch_id"
                                                            class="form-control submit_able" id="branch_id" autofocus>
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
                                            @endif

                                            <div class="col-md-2">
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <label><strong>Warehouse :</strong></label>
                                                    <select name="warehouse_id" class="form-control submit_able" id="warehouse_id" autofocus>
                                                        <option value="">Select Business Location First</option>
                                                    </select>
                                                @else 
                                                    @php
                                                        $wh = DB::table('warehouses')
                                                        ->where('branch_id', auth()->user()->branch_id)
                                                        ->get(['id', 'warehouse_name', 'warehouse_code']);
                                                    @endphp

                                                    <label><strong>Warehouse :</strong></label>
                                                    <select name="warehouse_id" class="form-control submit_able" id="warehouse_id" autofocus>
                                                        <option value="">All</option>
                                                        @foreach ($wh as $row)
                                                            <option value="{{ $row->id }}">{{ $row->warehouse_name.'/'.$row->warehouse_code }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
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

                                            <div class="col-md-2">
                                                <label><strong>Status :</strong></label>
                                                <div class="input-group">
                                                    <select name="status" class="form-control" id="status" autofocus>
                                                        <option value="">All</option>
                                                        <option value="1">Final</option>
                                                        <option value="0">Hold</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <label><strong>From Date :</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fas fa-calendar-week input_i"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="from_date" id="datepicker"
                                                        class="form-control from_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label><strong>To Date :</strong></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">
                                                            <i class="fas fa-calendar-week input_i"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="to_date" id="datepicker2" class="form-control to_date" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label><strong></strong></label>
                                                <div class="input-group">
                                                    <button type="button" id="filter_button" class="btn text-white btn-sm btn-secondary float-start">
                                                        <i class="fas fa-funnel-dollar"></i> Filter
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row margin_row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6"><h6>Productions</h6></div>
                                @if (auth()->user()->permission->manufacturing['production_add'] == '1') 
                                    <div class="col-md-6">
                                        <div class="btn_30_blue float-end">
                                            <a href="{{ route('manufacturing.productions.create') }}"><i class="fas fa-plus-square"></i> Add</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                </div>
                                <div class="table-responsive">
                                    <form id="update_product_cost_form" action="">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th class="text-black">Date</th>
                                                    <th class="text-black">Voucher No</th>
                                                    <th class="text-black">Business Location</th>
                                                    <th class="text-black">Product</th>
                                                    <th class="text-black">Status</th>
                                                    <th class="text-black">Per Unit Cost(Inc.Tax)</th>
                                                    <th class="text-black">Selling Price(Exc.Tax)</th>
                                                    <th class="text-black">Output Qty</th>
                                                    <th class="text-black">Wasted Qty</th>
                                                    <th class="text-black">Final Qty</th>
                                                    <th class="text-black">Total Ingredient Cost</th>
                                                    <th class="text-black">Production Cost</th>
                                                    <th class="text-black">Total Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr class="bg-secondary">
                                                    <th colspan="7" class="text-white text-end">Total : ({{ json_decode($generalSettings->business, true)['currency'] }})</th>
                                                    <th id="quantity" class="text-white text-end"></th>
                                                    <th id="wasted_quantity" class="text-white text-end"></th>
                                                    <th id="total_final_quantity" class="text-white text-end"></th>
                                                    <th id="total_ingredient_cost" class="text-white text-end"></th>
                                                    <th id="production_cost" class="text-white text-end"></th>
                                                    <th id="total_cost" class="text-white text-end"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="production_details"></div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
    <script>
        var production_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: '<i class="fas fa-file-excel"></i> Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'pdf',text: '<i class="fas fa-file-pdf"></i> Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:first-child)'}},
                {extend: 'print',text: '<i class="fas fa-print"></i> Print',className: 'btn btn-primary',exportOptions: {columns: [1,2,3,4,5,6,7,8,9,10]}},
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('manufacturing.report.index') }}",
                "data": function(d) {
                    d.product_id = $('#product_id').val();
                    d.variant_id = $('#variant_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.warehouse_id = $('#warehouse_id').val();
                    d.category_id = $('#category_id').val();
                    d.sub_category_id = $('#sub_category_id').val();
                    d.status = $('#status').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columns: [
                {data: 'date', name: 'date'},
                {data: 'reference_no', name: 'reference_no'},
                {data: 'from', name: 'branches.name'},
                {data: 'product', name: 'products.name'},
                {data: 'status', name: 'productions.is_final'},
                {data: 'unit_cost_inc_tax', name: 'unit_cost_inc_tax', className: 'text-end'},
                {data: 'price_exc_tax', name: 'price_exc_tax', className: 'text-end'},
                {data: 'quantity', name: 'quantity', className: 'text-end'},
                {data: 'wasted_quantity', name: 'wasted_quantity', className: 'text-end'},
                {data: 'total_final_quantity', name: 'total_final_quantity', className: 'text-end'},
                {data: 'total_ingredient_cost', name: 'total_ingredient_cost', className: 'text-end'},
                {data: 'production_cost', name: 'production_cost', className: 'text-end'},
                {data: 'total_cost', name: 'total_cost', className: 'text-end'},
            ],fnDrawCallback: function() {

                var quantity = sum_table_col($('.data_tbl'), 'quantity');
                $('#quantity').text(bdFormat(quantity));
                var wasted_quantity = sum_table_col($('.data_tbl'), 'wasted_quantity');
                $('#wasted_quantity').text(bdFormat(wasted_quantity));
                var total_final_quantity = sum_table_col($('.data_tbl'), 'total_final_quantity');
                $('#total_final_quantity').text(bdFormat(total_final_quantity));
                var total_ingredient_cost = sum_table_col($('.data_tbl'), 'total_ingredient_cost');
                $('#total_ingredient_cost').text(bdFormat(total_ingredient_cost));
                var production_cost = sum_table_col($('.data_tbl'), 'production_cost');
                $('#production_cost').text(bdFormat(production_cost));
                var total_cost = sum_table_col($('.data_tbl'), 'total_cost');
                $('#total_cost').text(bdFormat(total_cost));
                $('.data_preloader').hide();
            }
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

        @if ($addons->branches == 1)

            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)

                $(document).on('change', '#branch_id', function () {

                    var branch_id = $(this).val();
                    $.ajax({
                        url:"{{ url('common/ajax/call/branch/warehouse') }}"+"/"+branch_id,
                        type:'get',
                        success:function(data){

                            $('#warehouse_id').empty();
                            $('#warehouse_id').append('<option value="">All</option>');
                            $.each(data, function (key, val) {

                                $('#warehouse_id').append('<option value="'+val.id+'">'+val.warehouse_name+'/'+val.warehouse_code+'</option>');
                            });
                        }
                    });
                })
            @endif
        @endif

        //Submit filter form by select input changing
        $(document).on('click', '#filter_button', function (e) {
            e.preventDefault();

            $('.data_preloader').show();
            production_table.ajax.reload();
        });

        // Show details modal with data
        $(document).on('click', '.details_button', function (e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {

                $('#production_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        });

        // Make print
        $(document).on('click', '.print_btn', function(e) {
            e.preventDefault();

            var body = $('.production_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('public/assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        //Submit filter form by date-range field blur 
        $(document).on('click', '#search_product', function () {
            $(this).val('');
            $('#product_id').val('');
            $('#variant_id').val('');
        });

        $('#search_product').on('input', function () {

            $('.search_result').hide();
            $('#list').empty();
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
        });

        $('body').keyup(function(e) {

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
    </script>
@endpush