@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .widget_content p {
            padding: 0px 0px;
        }

        .filter-area {
            margin-bottom: 3px;
        }
    </style>
@endpush
@section('title', 'Product Ledger - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h5>{{ __('Product Ledger') }} - <strong>{{ $product->name }}</strong></h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="row g-1">
                            <div class="col-md-3">
                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <table class="display table modal-table table-sm m-0">
                                            <tbody>
                                                <tr>
                                                    <th colspan="3" class="text-center">{{ __('Product Summary') }}</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-end"></th>
                                                    <th class="text-end">{{ __('In') }}</th>
                                                    <th class="text-end">{{ __('Out') }}</th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Opening Stock') }} :</th>
                                                    <th class="text-end" id="in_opening_stock"></th>
                                                    <th class="text-end" id="out_opening_stock"></th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Current Total') }} :</th>
                                                    <th class="text-end" id="total_in"></th>
                                                    <th class="text-end" id="total_out"></th>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Closing Stock') }} :</th>
                                                    <th class="text-end" id="in_closing_stock"></th>
                                                    <th class="text-end" id="out_closing_stock"></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="form_element rounded mt-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="display table modal-table table-sm m-0">
                                                    <tr>
                                                        <th>{{ __('Product Name') }}</th>
                                                        <td>: {{ $product->name }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('Product Code') }}</th>
                                                        <td>: {{ $product->product_code }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('Unit') }}</th>
                                                        <td>: {{ $product?->unit?->name }}</td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <div class="col-md-6">
                                                <table class="display table modal-table table-sm m-0">
                                                    <tr>
                                                        <th>{{ __('.Brand') }}</th>
                                                        <td>: {{ $product?->brand ? $product?->brand?->name : 'N/A' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('Category') }}</th>
                                                        <td>: {{ $product?->category ? $product?->category?->name : 'N/A' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>{{ __('Subcategory') }}</th>
                                                        <td>: {{ $product?->subcategory ? $product?->subcategory?->name : 'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <form id="filter_product_ledgers" method="get">
                                            <div class="form-group row g-2 align-items-end filter-area">
                                                {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && !auth()->user()->branch_id) --}}
                                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->current_shop_count > 1)
                                                    <div class="col-md-4">
                                                        <label><strong>{{ __('Shop/Business') }} </strong></label>
                                                        <select name="branch_id" class="form-control select2" id="branch_id" autofocus>
                                                            <option value="">{{ __('All') }}</option>
                                                            @if ($generalSettings['subscription']->has_business == 1)
                                                                <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                            @endif

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

                                                @if (count($product->variants) > 0)
                                                    <div class="col-md-2">
                                                        <label><strong>{{ __('Variant') }}</strong></label>
                                                        <select name="variant_id" class="form-control select2" id="variant_id" autofocus>
                                                            <option data-variant_name="{{ __('All') }}" value="">{{ __('All') }}</option>
                                                            @foreach ($product->variants as $variant)
                                                                <option data-variant_name="{{ $variant->variant_name }}" value="{{ $variant->id }}">
                                                                    {{ $variant->variant_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('From Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="from_date" id="from_date" class="form-control" value="" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label><strong>{{ __('To Date') }}</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                        </div>
                                                        <input type="text" name="to_date" id="to_date" class="form-control" value="" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label><strong></strong></label>
                                                            <div class="input-group">
                                                                <button type="submit" class="btn text-white btn-sm btn-info float-start m-0">
                                                                    <i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label></label>
                                                            <div class="input-group">
                                                                <a href="#" class="btn btn-sm btn-primary float-end m-0" id="print_report"><i class="fas fa-print "></i> {{ __('Print') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
                                </div>

                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">{{ __('Date') }}</th>
                                                @if (count($product->variants) > 0)
                                                    <th class="text-start">{{ __('Variant') }}</th>
                                                @endif
                                                <th class="text-start">{{ __('Shop/Business') }}</th>
                                                <th class="text-start">{{ __('Voucher Type') }}</th>
                                                <th class="text-start">{{ __('Voucher No') }}</th>
                                                <th class="text-start">{{ __('In') }}</th>
                                                <th class="text-start">{{ __('Out') }}</th>
                                                <th class="text-start">{{ __('Running Stock') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr class="bg-secondary">
                                                <th colspan="4" class="text-white" style="text-align: right!important;"> {{ __('Current Stock') }} : </th>
                                                <th id="table_total_in" class="text-white"></th>
                                                <th id="table_total_out" class="text-white"></th>
                                                <th id="table_current_stock" class="text-white"></th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var productLedgerTable = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-primary'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Pdf',
                    className: 'btn btn-primary'
                },
            ],
            "lengthMenu": [
                [50, 100, 500, 1000, -1],
                [50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('products.ledger.index', [$product->id]) }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                    d.branch_name = $('#branch_id').find('option:selected').data('branch_name');
                    d.variant_id = $('#variant_id').val();
                    d.variant_name = $('#variant_id').find('option:selected').data('variant_name');
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 'date',
                    name: 'product_ledgers.date'
                },
                @if (count($product->variants) > 0)
                    {
                        data: 'variant_name',
                        name: 'variant_name'
                    },
                @endif {
                    data: 'branch',
                    name: 'branch_name'
                },
                {
                    data: 'voucher_type',
                    name: 'voucher_no'
                },
                {
                    data: 'voucher_no',
                    name: 'voucher_no'
                },
                {
                    data: 'in',
                    name: 'product_ledgers.in',
                    className: 'text-end'
                },
                {
                    data: 'out',
                    name: 'product_ledgers.out',
                    className: 'text-end'
                },
                {
                    data: 'running_stock',
                    name: 'product_ledgers.running_stock',
                    className: 'text-end'
                },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        // Submit filter form by select input changing
        $(document).on('submit', '#filter_product_ledgers', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            productLedgerTable.ajax.reload(null, false);
            getProductClosingStock();
        });

        function getProductClosingStock() {

            var branch_id = $('#branch_id').val();
            var variant_id = $('#variant_id').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            var url = "{{ route('products.stock', $product->id) }}";

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    branch_id,
                    variant_id,
                    from_date,
                    to_date,
                },
                success: function(data) {

                    $('#in_opening_stock').html('');
                    $('#out_opening_stock').html('');
                    $('#in_closing_stock').html('');
                    $('#out_closing_stock').html('');

                    $('#table_total_in').html(data.all_total_in > 0 ? data.all_total_in_string : '');
                    $('#table_total_out').html(data.all_total_out > 0 ? data.all_total_out_string : '');

                    $('#table_current_stock').html(data.closing_stock < 0 ? '(<span class="text-danger">' + data.closing_stock_string + '</span>)' : data.closing_stock_string);

                    if (data.opening_stock < 0) {

                        $('#out_opening_stock').html('(<span class="text-danger">' + data.opening_stock_string + '</span>)');
                    } else if (data.opening_stock >= 0) {

                        $('#in_opening_stock').html(data.opening_stock > 0 ? data.opening_stock_string : '');
                    }

                    $('#total_in').html(data.curr_total_in > 0 ? data.curr_total_in_string : '');
                    $('#total_out').html(data.curr_total_out > 0 ? data.curr_total_out_string : '');

                    if (data.closing_stock < 0) {

                        $('#out_closing_stock').html('(<span class="text-danger">' + data.closing_stock_string + '</span>)');
                    } else {

                        $('#in_closing_stock').html(data.closing_stock > 0 ? data.closing_stock_string : '');
                    }
                }
            });
        }
        getProductClosingStock();

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
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
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
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
