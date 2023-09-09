@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Search Product area style */
        .selectProduct { background-color: #ab1c59; color: #fff !important; }

        .search_area { position: relative; }

        .search_result { position: absolute; width: 100%; border: 1px solid #E4E6EF; background: white; z-index: 1; padding: 8px; margin-top: 1px; }

        .search_result ul li { width: 100%; border: 1px solid lightgray; margin-top: 3px; }

        .search_result ul li a { color: #6b6262; font-size: 12px; display: block; padding: 3px; }

        .search_result ul li a:hover { color: white; background-color: #ab1c59; }

        /* Search Product area style end */
    </style>
@endpush
@section('title', 'Purchase Product List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-basket"></span>
                                <h5>{{ __('Purchased Products') }}</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form action="" method="get" class="px-2">
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

                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                    <div class="col-md-2">
                                                        <label><strong>{{ __('Shop/Business') }} : </strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                            <option value="">{{ __('All') }}</option>
                                                            <option value="NULL">{{ $generalSettings['business__shop_name'] }} ({{ __('Business') }})</option>
                                                            @foreach ($branches as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    @php
                                                                        $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                                                        $areaName = $branch->area_name ? '('.$branch->area_name.')' : '';
                                                                        $branchCode = '-(' . $branch->branch_code.')';
                                                                    @endphp
                                                                    {{  $branchName.$areaName.$branchCode }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("Supplier") }} : </strong></label>
                                                    <select name="supplier_id" class="form-control select2" id="supplier_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($supplierAccounts as $supplierAccount)
                                                            <option data-supplier_account_name="{{ $supplierAccount->name.'/'.$supplierAccount->phone }}" value="{{ $supplierAccount->id }}">{{ $supplierAccount->name.'/'.$supplierAccount->phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("Category") }} : </strong></label>
                                                    <select name="category_id" class="form-control select2" id="category_id">
                                                        <option value="">{{ __('All') }}</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("Subcategory") }} : </strong></label>
                                                    <select name="sub_category_id" class="form-control select2" id="sub_category_id">
                                                        <option value="">{{ __('Select Category First') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-2">
                                                    <label><strong>{{ __("From Date") }} : </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __("To Date") }} : </strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong></strong></label>
                                                    <div class="input-group">
                                                        <button type="button" id="filter_button" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-search"></i> {{ __("Filter") }}</button>
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
                                <div class="col-9">
                                    <h6>{{ __('List Of Purchased Products') }}</h6>
                                </div>
                                @if (auth()->user()->can('purchase_add'))
                                    <div class="col-3 d-flex justify-content-end">
                                        <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus-square"></i> {{ __("Add") }}</a>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>{{ __("Date") }}</th>
                                                <th>{{ __("Shop/Business") }}</th>
                                                <th>{{ __("Product") }}</th>
                                                <th>{{ __("Supplier") }}</th>
                                                <th>{{ __('P.Invoice ID') }}</th>
                                                <th>{{ __("Quantity") }}</th>
                                                <th>{{ __("Unit Cost") }}</th>
                                                <th>{{ __("Subtotal") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="5" class="text-end text-white fw-bold">{{ __("Total") }} : {{ $generalSettings['business__currency'] }}</th>
                                                <th class="text-start text-white fw-bold">(<span id="total_qty"></span>)</th>
                                                <th class="text-start text-white">---</th>
                                                <th class="text-start text-white fw-bold"><span id="total_subtotal"></span></th>
                                            </tr>
                                        </tfoot>
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

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/plugins/custom/moment/moment.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                { extend: 'excel', text: 'Excel', className: 'btn btn-primary' },
                { extend: 'pdf', text: 'Pdf', className: 'btn btn-primary' },
                { extend: 'print', text: 'Print', className: 'btn btn-primary' },
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            "ajax": {
                "url": "{{ route('purchases.products.index') }}",
                "data": function(d) {
                    d.product_id = $('#product_id').val();
                    d.variant_id = $('#variant_id').val();
                    d.branch_id = $('#branch_id').val();
                    d.supplier_account_id = $('#supplier_account_id').val();
                    d.category_id = $('#category_id').val();
                    d.sub_category_id = $('#sub_category_id').val();
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [
                { data: 'date', name: 'purchases.date' },
                { data: 'branch', name: 'branch.name' },
                { data: 'product', name: 'products.name' },
                { data: 'supplier_name', name: 'suppliers.name as supplier_name' },
                { data: 'invoice_id', name: 'purchases.invoice_id' },
                { data: 'quantity', name: 'quantity', className: 'text-end fw-bold' },
                { data: 'net_unit_cost', name: 'net_unit_cost', className: 'text-end fw-bold' },
                { data: 'subtotal', name: 'subtotal', className: 'text-end fw-bold' },
            ], fnDrawCallback: function() {
                var total_qty = sum_table_col($('.data_tbl'), 'qty');
                $('#total_qty').text(bdFormat(total_qty));
                var total_subtotal = sum_table_col($('.data_tbl'), 'subtotal');
                $('#total_subtotal').text(bdFormat(total_subtotal));
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

        // Show details modal with data
        $(document).on('click', '#details_btn', function(e) {
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
                },error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    }else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });

        // Make print
        $(document).on('click', '#modalDetailsPrintBtn', function(e) {
            e.preventDefault();

            var body = $('.print_modal_details').html();

            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{ asset('assets/css/print/purchase.print.css') }}",
                removeInline: false,
                printDelay: 500,
                header: null,
            });
        });

        $('#category_id').on('change', function() {
            var categoryId = $(this).val();

            if (categoryId == '') {
                
                $('#sub_category_id').empty();
                $('#sub_category_id').append('<option value="">'+"{{ __('Select Category First') }}"+'</option>');
            }

            var url = "{{ route('subcategories.by.category.id', ':categoryId') }}";
            var route = url.replace(':categoryId', categoryId);

            $.get(route, function(subCategories) {

                $('#sub_category_id').empty();
                $('#sub_category_id').append('<option value="">'+"{{ __('All') }}"+'</option>');
                $.each(subCategories, function(key, val) {

                    $('#sub_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
                });
            });
        });

        //Submit filter form by select input changing
        $(document).on('click', '#filter_button', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            table.ajax.reload();
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

        $('#search_product').on('input', function() {

            $('.search_result').hide();

            $('#list').empty();
            var keyword = $(this).val();

            console.log(keyword);

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

        //Submit filter form by date-range field blur
        $(document).on('click', '#search_product', function() {
            $(this).val('');
            $('#product_id').val('');
            $('#variant_id').val('');
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
@endpush
