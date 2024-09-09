@extends('layout.master')
@push('stylesheets')
    <style>
        .sale_and_purchase_amount_area table tbody tr th,
        td {
            color: #32325d;
        }

        .report_data_area {
            position: relative;
        }

        .data_preloader {
            top: 2.3%
        }

        .sale_and_purchase_amount_area table tbody tr th {
            text-align: left;
        }

        .sale_and_purchase_amount_area table tbody tr td {
            text-align: left;
        }
    </style>
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('title', 'Stock Report - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Stock Report') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="p-lg-1 p-1">
                <div class="card p-1 mb-lg-3 mb-1">
                    <div class="tab_list_area">
                        <div class="btn-group">
                            <a id="tab_btn" data-show="branch_stock" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                <i class="fas fa-scroll"></i>{{ location_label() }} {{ __('Stock') }}
                            </a>

                            <a id="tab_btn" data-show="warehouse_stock" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-info-circle"></i> {{ __('Warehouse Stock') }}
                            </a>
                        </div>
                    </div>

                    <div class="tab_contant branch_stock">
                        <div class="row g-lg-1 g-1">
                            <div class="col-12">
                                <form id="branch_stock_filter_form">
                                    @csrf
                                    <div class="form-group row">
                                        {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                            <div class="col-md-4">
                                                <label><strong>{{ location_label() }} </strong></label>
                                                <select name="branch_id" class="form-control select2" id="branch_stock_branch_id" autofocus>
                                                    <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                    <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ location_label('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ location_label('Company') }})</option>
                                                    @foreach ($branches as $branch)
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                            {{ $branchName . $areaName . $branchCode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="col-lg-2 col-md-4">
                                            <label><strong>{{ __('Category') }}</strong></label>
                                            <select name="category_id" class="form-control select2" id="branch_stock_category_id">
                                                <option data-category_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                @foreach ($categories as $category)
                                                    <option data-category_name="{{ $category->name }}" value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <label><strong>{{ __('Brand.') }}</strong></label>
                                            <select name="brand_id" class="form-control select2" id="branch_stock_brand_id">
                                                <option data-brand_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option data-brand_name="{{ $brand->name }}" value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <label><strong>{{ __('Unit') }}</strong></label>
                                            <select name="unit_id" class="form-control select2" id="branch_stock_unit_id">
                                                <option data-unit_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                @foreach ($units as $unit)
                                                    <option data-unit_name="{{ $unit->name . '/' . $unit->code_name }}" value="{{ $unit->id }}">{{ $unit->name . '/' . $unit->code_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <div class="row align-items-end">
                                                <div class="col-6">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="submit" id="filter_button" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group justify-content-end">
                                                        <a href="#" class="btn btn-sm btn-primary float-end m-0" id="branchStockPrint"><i class="fas fa-print "></i> {{ __('Print') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row g-lg-1 g-1">
                            <div class="col-12">
                                <div class="table-responsive" id="data_list1">
                                    <table class="display data_tbl data__table branch_stock_table">
                                        <thead>
                                            <tr class="text-start">
                                                <th>{{ __('Product') }}</th>
                                                <th>{{ __('Code(SKU)') }}</th>
                                                <th>{{ __('Stock Location') }}</th>
                                                <th>{{ __('Default Unit Cost Inc. Tax') }}</th>
                                                <th>{{ __('Default Unit Price Exc. Tax') }}</th>
                                                <th>{{ __('Current Stock') }}</th>
                                                <th>{{ __('Stock Value') }}<b><small>({{ __('By Unit Cost Inc. Tax') }})</small></b></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th class="text-white text-end" colspan="5">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                                <th class="text-white text-end" id="branch_stock"></th>
                                                <th class="text-white text-end" id="branch_stock_value"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab_contant warehouse_stock d-hide">
                        <div class="row g-lg-1 g-1">
                            <div class="col-12">
                                <form id="warehouse_stock_filter_form">
                                    @csrf
                                    <div class="form-group row justify-content-end">
                                        {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                        @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                            <div class="col-md-4">
                                                <label><strong>{{ __('Shop/Business') }} </strong></label>
                                                <select name="branch_id" class="form-control select2" id="warehouse_stock_branch_id" autofocus>
                                                    <option data-branch_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                    <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                    @foreach ($branches as $branch)
                                                        @php
                                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                            $branchCode = '-' . $branch->branch_code;
                                                        @endphp
                                                        <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                                            {{ $branchName . $areaName . $branchCode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="col-lg-2 col-md-4">
                                            {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_an_area == 0) --}}
                                            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                                <label><strong>{{ __('Warehouse') }}</strong></label>
                                                <select name="warehouse_id" class="form-control select2" id="warehouse_id" autofocus>
                                                    <option data-warehouse_name="All" value="">{{ __('Select Shop/Business First') }}</option>
                                                </select>
                                            @else
                                                @php
                                                    $wh = DB::table('warehouses')
                                                        ->where('branch_id', auth()->user()->branch_id)
                                                        ->orWhere('is_global', 1)
                                                        ->get(['id', 'warehouse_name', 'warehouse_code', 'is_global']);
                                                @endphp

                                                <label><strong>{{ __('Warehouse') }}</strong></label>
                                                <select name="warehouse_id" class="form-control select2" id="warehouse_id" autofocus>
                                                    <option data-warehouse_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                    @foreach ($wh as $row)
                                                        <option data-warehouse_name="{{ $row->warehouse_name . '/' . $row->warehouse_code }}" value="{{ $row->id }}">{{ $row->warehouse_name . '/' . $row->warehouse_code }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <label><strong>{{ __('Category') }}</strong></label>
                                            <select name="category_id" class="form-control select2" id="warehouse_stock_category_id">
                                                <option data-category_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                @foreach ($categories as $category)
                                                    <option data-category_name="{{ __('All') }}" value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <label><strong>{{ __('Brand.') }}</strong></label>
                                            <select name="brand_id" class="form-control select2" id="warehouse_stock_brand_id">
                                                <option data-brand_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                @foreach ($brands as $brand)
                                                    <option data-brand_name="{{ $brand->name }}" value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <label><strong>{{ __('Unit') }}</strong></label>
                                            <select name="unit_id" class="form-control select2" id="warehouse_stock_unit_id">
                                                <option data-unit_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                @foreach ($units as $unit)
                                                    <option data-unit_name="{{ $unit->name . '/' . $unit->code_name }}" value="{{ $unit->id }}">{{ $unit->name . '/' . $unit->code_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <div class="form-group row align-items-end mt-2">
                                                <div class="col-6">
                                                    <div class="input-group">
                                                        <a href="#" class="btn btn-sm btn-primary float-end" id="warehouseStockPrint"><i class="fas fa-print "></i> {{ __('Print') }}</a>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <button type="submit" id="filter_button" class="btn text-white btn-sm btn-info mt-1 float-end"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row g-lg-1 g-1">
                            <div class="col-12">
                                <div class="table-responsive" id="data_list">
                                    <table class="display data_tbl data__table warehouse_stock_table w-100">
                                        <thead>
                                            <tr class="text-start">
                                                <th>{{ __('Product') }}</th>
                                                <th>{{ __('Code(SKU)') }}</th>
                                                <th>{{ __('Stock Location') }}</th>
                                                <th>{{ __('Default Unit Cost Inc. Tax') }}</th>
                                                <th>{{ __('Default Unit Price Exc. Tax') }}</th>
                                                <th>{{ __('Current Stock') }}</th>
                                                <th>{{ __('Stock Value') }}<b><small>({{ __('By Unit Cost Inc. Tax') }})</small></b></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tr class="bg-secondary">
                                            <th class="text-white text-end" colspan="5">{{ __('Total') }} : </th>
                                            <th class="text-white text-end" id="warehouse_stock"></th>
                                            <th class="text-white text-end" id="warehouse_stock_value"></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="tab_list_area row pb-0">
                        <div class="col-12">
                            <div class="btn-group">
                                <a id="tab_btn" data-show="branch_stock" class="btn btn-sm btn-primary tab_btn tab_active" href="#"><i class="fas fa-info-circle"></i>@lang('menu.business_location_stock')</a>
                                <a id="tab_btn" data-show="warehouse_stock" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-scroll"></i>@lang('menu.warehouse_stock')</a>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var branchStocksTable = $('.branch_stock_table').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: 'Pdf',
                    className: 'btn btn-primary'
                }
            ],
            "processing": true,
            "serverSide": true,
            "language": {
                "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
            },
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.product.stock.branch.stock') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_stock_branch_id').val();
                    d.category_id = $('#branch_stock_category_id').val();
                    d.brand_id = $('#branch_stock_brand_id').val();
                    d.unit_id = $('#branch_stock_unit_id').val();
                }
            },
            columns: [{
                    data: 'product_name',
                    name: 'products.name'
                },
                {
                    data: 'product_code',
                    name: 'products.product_code'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'cost',
                    name: 'product_variants.variant_name',
                    className: 'text-end'
                },
                {
                    data: 'price',
                    name: 'parentBranch.name',
                    className: 'text-end'
                },
                {
                    data: 'stock',
                    name: 'product_stocks.stock',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'stock_value',
                    name: 'product_stocks.stock_value',
                    className: 'text-end fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var branch_stock = sum_table_col($('.branch_stock_table'), 'branch_stock');
                $('#branch_stock').text(bdFormat(branch_stock) + '/Nos');

                var branch_stock_value = sum_table_col($('.branch_stock_table'), 'branch_stock_value');
                $('#branch_stock_value').text(bdFormat(branch_stock_value));
                $('.data_preloader').hide();
            },
        });

        $(document).on('submit', '#branch_stock_filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            branchStocksTable.ajax.reload();
        });

        var warehouseStocksTable = $('.warehouse_stock_table').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: 'Pdf',
                    className: 'btn btn-primary'
                }
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.product.stock.warehouse.stock') }}",
                "data": function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.category_id = $('#warehouse_stock_category_id').val();
                    d.brand_id = $('#warehouse_stock_brand_id').val();
                    d.unit_id = $('#warehouse_stock_unit_id').val();
                }
            },
            columns: [{
                    data: 'product_name',
                    name: 'products.name'
                },
                {
                    data: 'product_code',
                    name: 'products.product_code'
                },
                {
                    data: 'stock_location',
                    name: 'branches.name'
                },
                {
                    data: 'cost',
                    name: 'product_variants.variant_name',
                    className: 'text-end'
                },
                {
                    data: 'price',
                    name: 'parentBranch.name',
                    className: 'text-end'
                },
                {
                    data: 'stock',
                    name: 'warehouses.warehouse_name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'stock_value',
                    name: 'product_stocks.stock_value',
                    className: 'text-end fw-bold'
                },
            ],
            fnDrawCallback: function() {

                var warehouse_stock = sum_table_col($('.warehouse_stock_table'), 'warehouse_stock');
                $('#warehouse_stock').text(bdFormat(warehouse_stock));

                var warehouse_stock_value = sum_table_col($('.warehouse_stock_table'), 'warehouse_stock_value');
                $('#warehouse_stock_value').text(bdFormat(warehouse_stock_value));
                $('.data_preloader').hide();
            },
        });

        $(document).on('submit', '#warehouse_stock_filter_form', function(e) {
            e.preventDefault();
            $('#w_data_preloader').show();
            warehouseStocksTable.ajax.reload();
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

        $(document).on('change', '#warehouse_stock_branch_id', function(e) {
            e.preventDefault();

            var branchId = $(this).val();
            getWarehouseByBranch(branchId);
        });


        function getWarehouseByBranch(branchId = '') {

            var branchId = branchId ? branchId : 'noid';

            // if (branchId == '') {
            //     return;
            // }

            var route = '';
            var url = "{{ route('warehouses.by.branch', [':branchId', 1]) }}";
            route = url.replace(':branchId', branchId);

            $.ajax({
                url: route,
                type: 'get',
                success: function(warehouses) {

                    $('#warehouse_id').empty();
                    $('#warehouse_id').append('<option data-warehouse_name="' + "{{ __('All') }}" + '" value="">' + "{{ __('All') }}" + '</option>');

                    $.each(warehouses, function(key, warehouse) {

                        $('#warehouse_id').append('<option data-warehouse_name="' + warehouse.warehouse_name + '/' + warehouse.warehouse_code + '" value="' + warehouse.id + '">' + warehouse.warehouse_name + '/' + warehouse.warehouse_code + '</option>');
                    });
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        }

        getWarehouseByBranch(branchId = '');


        $(document).on('click', '.tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').hide();
            var show_content = $(this).data('show');
            $('.' + show_content).show();
            $(this).addClass('tab_active');
        });

        // Branch Stock Print
        $(document).on('click', '#branchStockPrint', function(e) {
            e.preventDefault();

            // reports.stock.print.branch.stock
            var url = "{{ route('reports.product.stock.branch.stock.print') }}";

            var branch_id = $('#branch_stock_branch_id').val();
            var branch_name = $('#branch_stock_branch_id').find('option:selected').data('branch_name');
            var category_id = $('#branch_stock_category_id').val();
            var category_name = $('#branch_stock_category_id').find('option:selected').data('category_name');
            var brand_id = $('#branch_stock_brand_id').val();
            var brand_name = $('#branch_stock_brand_id').find('option:selected').data('brand_name');
            var unit_id = $('#branch_stock_unit_id').val();
            var unit_name = $('#branch_stock_unit_id').find('option:selected').data('unit_name');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    category_id,
                    category_name,
                    brand_id,
                    brand_name,
                    unit_id,
                    unit_name,
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 500,
                        header: "",
                        pageTitle: "",
                        // footer: 'Footer Text',
                    });
                }
            });
        });

        $(document).on('click', '#warehouoseStockPrint', function(e) {
            e.preventDefault();

            // reports.stock.print.branch.stock
            var url = "{{ route('reports.product.stock.warehouse.stock.print') }}";

            var branch_id = $('#warehosue_stock_branch_id').val();
            var branch_name = $('#warehouse_stock_branch_id').find('option:selected').data('branch_name');
            var warehouse_id = $('#warehouse_id').val();
            var warehouse_name = $('#warehouse_id').find('option:selected').data('warehouse_name');
            var category_id = $('#branch_stock_category_id').val();
            var category_name = $('#branch_stock_category_id').find('option:selected').data('category_name');
            var brand_id = $('#branch_stock_brand_id').val();
            var brand_name = $('#branch_stock_brand_id').find('option:selected').data('brand_name');
            var unit_id = $('#branch_stock_unit_id').val();
            var unit_name = $('#branch_stock_unit_id').find('option:selected').data('unit_name');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    branch_name,
                    warehouse_id,
                    warehouse_name,
                    category_id,
                    category_name,
                    brand_id,
                    brand_name,
                    unit_id,
                    unit_name,
                },
                success: function(data) {

                    $(data).printThis({
                        debug: false,
                        importCSS: true,
                        importStyle: true,
                        loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                        removeInline: false,
                        printDelay: 500,
                        header: "",
                        pageTitle: "",
                        // footer: 'Footer Text',
                    });
                }
            });
        });
    </script>
@endpush
