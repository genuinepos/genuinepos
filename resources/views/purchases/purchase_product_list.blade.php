@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css" />
        <style>
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
@section('title', 'Purchase List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-basket"></span> <h5>Purchased Product List</h5>
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
                                                <div class="col-md-3 search_area">
                                                    <label><strong>Search Product :</strong></label>
                                                    <input type="text" name="search_product" id="search_product" class="form-control" placeholder="Search Product By name" autofocus>
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
                                                        <div class="col-md-3">
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
                                                
                                                <div class="col-md-3">
                                                    <label><strong>Supplier :</strong></label>
                                                    <select name="supplier_id" class="form-control submit_able"
                                                        id="supplier_id" autofocus>
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
                                    <h6>All Purchased Products</h6>
                                </div>
                                @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href="{{ route('purchases.create') }}"><i class="fas fa-plus-square"></i> Add</a>
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
                                                <th>Product</th>
                                                <th>P.Code</th>
                                                <th>Supplier</th>
                                                <th>P.Invoice ID</th>
                                                <th>Quantity</th>
                                                <th>Unit Cost</th>
                                                <th>Unit Price</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
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
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('public') }}/assets/plugins/custom/select_li/selectli.js"></script>
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary'},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary'},
                {extend: 'print',text: 'Print',className: 'btn btn-primary'},
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [[0, 'asc']],
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('purchases.product.list') }}",
                "data": function(d) {
                    d.product_id = $('#product_id').val();
                    d.variant_id = $('#variant_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.date_range = $('#date_range').val();
                }
            },
            columns: [
                {data: 'date', name: 'purchases.date'},
                {data: 'product', name: 'products.name'},
                {data: 'product_code', name: 'products.name'},
                {data: 'supplier_name', name: 'suppliers.name as supplier_name'},
                {data: 'invoice_id', name: 'purchases.invoice_id'},
                {data: 'quantity', name: 'quantity'},
                {data: 'net_unit_cost', name: 'net_unit_cost'},
                {data: 'price', name: 'purchase_products.selling_price'},
                {data: 'subtotal', name: 'subtotal'},
                {data: 'action', name: 'action'},
            ]
        });

        // Get all supplier for filter form
        function setSuppliers() {
            $.ajax({
                url: "{{ route('purchases.get.all.supplier') }}",
                type: 'get',
                dataType: 'json',
                success: function(suppliers) {
                    $('#supplier_id').append('<option value="">All</option>');
                    $.each(suppliers, function(key, val) {
                        $('#supplier_id').append('<option value="' + val.id + '">' + val.name + ' (' +
                            val.phone + ')' + '</option>');
                    });
                    $('#supplier_id').val('');
                }
            });
        }
        setSuppliers();

        $(document).on('click', '#delete',function(e) {
            e.preventDefault(); 
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);       
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        //Submit filter form by date-range field blur 
        $(document).on('click', '#search_product', function () {
            $(this).val('');
            $('#product_id').val('');
            table.ajax.reload();
        });

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function() {
            table.ajax.reload();
        });

        //Submit filter form by date-range field blur 
        $(document).on('blur', '.submit_able_input', function() {
            setTimeout(function() {
                table.ajax.reload();
            }, 800);
        });

        //Submit filter form by date-range apply button
        $(document).on('click', '.applyBtn', function() {
            setTimeout(function() {
                $('.submit_able_input').addClass('.form-control:focus');
                $('.submit_able_input').blur();
            }, 700);
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
                locale: {cancelLabel: 'Clear'},
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')],
                }
            });
            $('.daterange').val('');
        });

        $(document).on('click', '.cancelBtn ', function () {
           $('.daterange').val('');
        });

        $('#search_product').on('input', function () {
            $('.search_result').hide();
            $('#list').empty();
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
    </script>
@endpush