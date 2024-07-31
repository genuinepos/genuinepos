@extends('layout.master')
@push('stylesheets')
    <style>
        .data_preloader {
            top: 2.3%;
        }

        /* Search Product area style */
        .selectProduct {
            background-color: #5f555a;
            color: #fff !important;
        }

        .search_area {
            position: relative;
        }

        .search_result {
            position: absolute;
            width: 100%;
            border: 1px solid #E4E6EF;
            background: white;
            z-index: 1;
            padding: 8px;
            margin-top: 1px;
        }

        .search_result ul li {
            width: 100%;
            border: 1px solid lightgray;
            margin-top: 3px;
        }

        .search_result ul li a {
            color: #7b7676;
            font-size: 12px;
            display: block;
            padding: 3px;
        }

        .search_result ul li a:hover {
            color: white;
            background-color: #ccc1c6;
        }

        /* Search Product area style end */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Stock Issued Products - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Stock Issued Products') }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form id="filter_form">
                                            <div class="form-group row">
                                                <div class="col-md-2 search_area">
                                                    <label><strong>{{ __('Search Product') }} </strong></label>
                                                    <input type="text" name="search_product" id="search_product" class="form-control" placeholder="{{ __('Search Product') }}" autofocus autocomplete="off">
                                                    <input type="hidden" name="product_id" id="product_id" value="">
                                                    <input type="hidden" name="variant_id" id="variant_id" value="">
                                                    <div class="search_result d-hide">
                                                        <ul id="list" class="list-unstyled">
                                                            <li><a id="select_product" class="" data-p_id="" data-v_id="" href="">Samsung A30</a></li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                                    <div class="col-md-2">
                                                        <label><strong>{{ location_label() }}</strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                            <option value="">{{ __('All') }}</option>
                                                            <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    @php
                                                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                        $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                                                        $branchCode = '-' . $branch->branch_code;
                                                                    @endphp
                                                                    {{ $branchName . $areaName . $branchCode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('Department') }}</strong></label>
                                                    <select name="department_id" class="form-control select2" id="department_id" autofocus>
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <i class="fas fa-calendar-week input_f"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="button" id="filter_button" class="btn text-white btn-sm btn-info float-start m-0">
                                                            <i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="section-header">
                                <div class="col-10">
                                    <h6>{{ __('List of Stock Issued Products') }}</h6>
                                </div>

                                <div class="col-2 d-flex justify-content-end">
                                    <a href="{{ route('stock.issues.create') }}" class="btn btn-sm btn-success"><i class="fas fa-plus-square"></i> {{ __('Add') }}</a>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Product') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Voucher No') }}</th>
                                                <th>{{ location_label() }}</th>
                                                <th>{{ __('Department') }}</th>
                                                <th>{{ __('Reported By') }}</th>
                                                <th>{{ __('Issued Qty') }}</th>
                                                <th>{{ __('Unit Cost(Inc. Tax)') }}</th>
                                                <th>{{ __('Subtotal') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6" class="text-end text-dark">{{ __('Total') }} : </th>
                                                <th id="quantity" class="text-dark"></th>
                                                <th>---</th>
                                                <th id="subtotal" class="text-dark"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <form id="delete_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))

            toastr.success('{{ session('successMsg')[0] }}');
        @endif

        var stockIssuedProductsTable = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i>' + " {{ __('Excel') }}",
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i>' + " {{ __('Pdf') }}",
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i>' + " {{ __('Print') }}",
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: 'th:not(:first-child)'
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            //aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('stock.issued.products.index') }}",
                "data": function(d) {
                    d.product_id = $('#product_id').val();
                    d.variant_id = $('#variant_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.department_id = $('#department_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'product',
                    name: 'products.name'
                },
                {
                    data: 'date',
                    name: 'stock_issues.date'
                },
                {
                    data: 'voucher_no',
                    name: 'stock_issues.voucher_no',
                    className: 'fw-bold'
                },
                {
                    data: 'branch',
                    name: 'branches.name'
                },
                {
                    data: 'department_name',
                    name: 'hrm_departments.name'
                },
                {
                    data: 'reported_by',
                    name: 'reported_by.name',
                },
                {
                    data: 'quantity',
                    name: 'parentBranch.name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'unit_cost_inc_tax',
                    name: 'product_variants.name',
                    className: 'text-end fw-bold'
                },
                {
                    data: 'subtotal',
                    name: 'subtotal',
                    className: 'text-end fw-bold'
                }
            ],
            fnDrawCallback: function() {

                var quantity = sum_table_col($('.data_tbl'), 'quantity');
                $('#quantity').text(bdFormat(quantity));
                var subtotal = sum_table_col($('.data_tbl'), 'subtotal');
                $('#subtotal').text(bdFormat(subtotal));

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

        $(document).on('click', '#filter_button', function(e) {

            e.preventDefault();
            $('.data_preloader').show();
            stockIssuedProductsTable.ajax.reload();
        });

        // Show details modal with data
        $(document).on('click', '#detailsBtn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#details').html(data);
                    $('#detailsModal').modal('show');
                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        //Submit filter form by date-range field blur
        $(document).on('click', '#search_product', function() {
            $(this).val('');
            $('#product_id').val('');
            $('#variant_id').val('');
        });

        $('#search_product').on('input', function() {

            $('.search_result').hide();

            $('#list').empty();
            var keyword = $(this).val();

            if (keyword === '') {

                $('.search_result').hide();
                $('#product_id').val('');
                $('#variant_id').val('');
                return;
            }

            var branchId = "{{ $ownBranchIdOrParentBranchId }}";

            var url = "{{ route('general.product.search.by.only.name', ':keyword', ':branchId') }}";
            var route = url.replace(':keyword', keyword);
            route = route.replace(':branchId', branchId);

            $.ajax({
                url: route,
                async: true,
                type: 'get',
                success: function(data) {

                    if (!$.isEmptyObject(data.noResult)) {

                        $('.search_result').hide();
                    } else {

                        $('.search_result').show();
                        $('#list').html(data);
                    }
                }
            });
        });

        $(document).on('click', '#select_product', function(e) {
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
            if (e.keyCode == 13 || e.keyCode == 9) {
                $(".selectProduct").click();
                $('.search_result').hide();
                $('#list').empty();
            }
        });

        $(document).on('mouseenter', '#list>li>a', function() {
            $('#list>li>a').removeClass('selectProduct');
            $(this).addClass('selectProduct');
        });

        $(document).on('click', function(e) {

            if ($(e.target).closest(".search_result").length === 0) {

                $('.search_result').hide();
                $('#list').empty();
            }
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('from_date'),
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
            element: document.getElementById('to_date'),
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

        function getSupplier() {}
    </script>
@endpush
