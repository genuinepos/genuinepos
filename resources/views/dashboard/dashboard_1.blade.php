@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link href="{{ asset('backend/asset/css/dashboard.css') }}" rel="stylesheet" type="text/css">
    <style>
        .widget_content .table-responsive {
            min-height: 40vh !important;
        }

        .card-counter-inner {
            display: flex;
        }

        .card-counter-inner .icon {
            margin-right: 5px;
            font-size: inherit;
        }

        .card-counter-inner .icon i {
            font-size: 20px;
        }

        .card-counter .title .card_amount {
            font-size: 13px;
        }

        .card-counter {
            height: auto;
        }

        a#addShortcutBtn {
            background: #0ec726 !important;
        }

        section.dashboard_table_section .table-responsive {
            min-height: 0vh !important;
        }
    </style>
@endpush
@section('title', 'Dashboard - ')
@section('content')
    @if (auth()->user()->can('view_dashboard_data'))
        <div id="dashboard" class="p-2">
            <div class="row mb-3">
                <div class="main__content">
                    <div class="welcome-user">
                        <div class="alert mb-1 py-0 w-100 h-auto alert-success">
                            <span>{{ __('Welcome') }} <strong>{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}</strong></span>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap mt-2 switch_bar_cards">
                        <div class="switch_bar">
                            <a href="{{ route('short.menus.modal.form', \App\Enums\ShortMenuScreenType::DashboardScreen->value) }}" class="bar-link" id="addShortcutBtn">
                                <span><i class="fa-light fa-plus-square text-white"></i></span>
                            </a>
                            <p>{{ __('Add Shortcut') }}</p>
                        </div>
                    </div>

                    <div class="">
                        <div class="row mt-3">
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <input type="hidden" id="date_range" value="{{ $toDay }}">
                                @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0)
                                    <div class="select-dropdown">
                                        <select name="branch_id" id="branch_id" autofocus>
                                            <option value="">{{ __('All Store/Company') }}</option>
                                            <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="select-dropdown">
                                    <select name="date" id="date">
                                        <option data-date_period="{{ __('Today\'s Status.') }}" value="{{ $toDay }}">{{ __('Current Day') }}</option>
                                        <option data-date_period="{{ __('This Week\'s Status.') }}" value="{{ $thisWeek }}">{{ __('This Week') }}</option>
                                        <option data-date_period="{{ __('This Month\'s Status.') }}" value="{{ $thisMonth }}">{{ __('This Month') }}</option>
                                        <option data-date_period="{{ __('This Year\'s Status.') }}" value="{{ $thisYear }}">{{ __('This Year') }}</option>
                                        <option data-date_period="{{ __('Status Of All Time.') }}" value="all_time">{{ __('All Time') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Cards --}}
                        <div class="mt-3">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card-counter-wrap bg-white rounded">
                                        <div class="part-txt">
                                            <h6 class="mb-1" id="status_period_name">{{ __('Today\'s Status.') }}</h6>
                                            <h6>{{ __('All Purchase, Sales & Due') }}</h6>
                                        </div>
                                        <div class="card-counter-row">
                                            <div class="card-counter-col">

                                                <div class="card-counter ">
                                                    <div class="card-counter-inner">
                                                        <div class="icon">
                                                            <i class="fa-light fa-receipt"></i>
                                                        </div>
                                                        <div class="numbers px-1">
                                                            <h3 class="sub-title">{{ __('Total Purchase') }}</h3>
                                                        </div>
                                                    </div>
                                                    <h1 class="title">
                                                        <i class="fas fa-sync fa-spin card_preloader"></i>
                                                        <span class="card_amount" id="total_purchase"></span>
                                                    </h1>
                                                </div>
                                            </div>

                                            <div class="card-counter-col">
                                                <div class="card-counter ">
                                                    <div class="card-counter-inner">
                                                        <div class="icon">
                                                            <i class="fa-light fa-money-check"></i>
                                                        </div>
                                                        <div class="numbers px-1">
                                                            <h3 class="sub-title">{{ __('Total Sale') }}</h3>
                                                        </div>
                                                    </div>
                                                    <h1 class="title">
                                                        <i class="fas fa-sync fa-spin card_preloader"></i>
                                                        <span class="card_amount" id="total_sale"></span>
                                                    </h1>
                                                </div>
                                            </div>

                                            <div class="card-counter-col">
                                                <div class="card-counter ">
                                                    <div class="card-counter-inner">
                                                        <div class="icon">
                                                            <i class="fa-light fa-clipboard"></i>
                                                        </div>
                                                        <div class="numbers px-1">
                                                            <h3 class="sub-title">{{ __('Purchase Due') }}</h3>
                                                        </div>
                                                    </div>
                                                    <h1 class="title">
                                                        <i class="fas fa-sync fa-spin card_preloader"></i>
                                                        <span class="card_amount" id="total_purchase_due"></span>
                                                    </h1>
                                                </div>
                                            </div>

                                            <div class="card-counter-col">
                                                <div class="card-counter ">
                                                    <div class="card-counter-inner">
                                                        <div class="icon">
                                                            <i class="fa-light fa-file-invoice"></i>
                                                        </div>
                                                        <div class="numbers px-1">
                                                            <h3 class="sub-title">{{ __('Sale Due') }}</h3>
                                                        </div>
                                                    </div>
                                                    <h1 class="title">
                                                        <i class="fas fa-sync fa-spin card_preloader"></i>
                                                        <span class="card_amount" id="total_sale_due"></span>
                                                    </h1>
                                                </div>
                                            </div>

                                            <div class="card-counter-col">
                                                <div class="card-counter ">
                                                    <div class="card-counter-inner">
                                                        <div class="icon">
                                                            <i class="fa-light fa-file-invoice-dollar"></i>
                                                        </div>
                                                        <div class="numbers px-1">
                                                            <h3 class="sub-title">{{ __('Expense') }}</h3>
                                                        </div>
                                                    </div>
                                                    <h1 class="title">
                                                        <i class="fas fa-sync fa-spin card_preloader"></i>
                                                        <span class="card_amount" id="total_expense"></span>
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card-counter-row-2">
                                        <div class="card-counter-col">
                                            <div class="card-counter ">
                                                <div class="card-counter-inner">
                                                    <div class="icon">
                                                        <i class="fa-light fa-user"></i>
                                                    </div>
                                                    <div class="numbers px-1">
                                                        <h3 class="sub-title">{{ __('Total Users') }}</h3>
                                                    </div>
                                                </div>
                                                <h1 class="title">
                                                    <i class="fas fa-sync fa-spin card_preloader"></i>
                                                    <span class="card_amount" id="total_user"></span>
                                                </h1>
                                            </div>
                                        </div>

                                        <div class="card-counter-col">
                                            <div class="card-counter ">
                                                <div class="card-counter-inner">
                                                    <div class="icon">
                                                        <i class="fa-light fa-list"></i>
                                                    </div>
                                                    <div class="numbers px-1">
                                                        <h3 class="sub-title">{{ __('Total Products') }}</h3>
                                                    </div>
                                                </div>
                                                <h1 class="title">
                                                    <i class="fas fa-sync fa-spin card_preloader"></i>
                                                    <span id="total_product"></span>
                                                </h1>
                                            </div>
                                        </div>

                                        <div class="card-counter-col">
                                            <div class="card-counter ">
                                                <div class="card-counter-inner">
                                                    <div class="icon">
                                                        <i class="fa-light fa-balance-scale"></i>
                                                    </div>
                                                    <div class="numbers px-1">
                                                        <h3 class="sub-title">{{ __('Total Adjustment') }}</h3>
                                                    </div>
                                                </div>
                                                <h1 class="title">
                                                    <i class="fas fa-sync fa-spin card_preloader"></i>
                                                    <span class="card_amount" id="total_adjustment"></span>
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <section class="dashboard_table_section">
                        <div class="form_element rounded m-0">
                            <div class="section-header justify-content-between">
                                <h6>
                                    <span class="fas fa-table"></span>{{ __('Stock Alert') }}
                                </h6>
                                <a href="#">{{ __('See More') }}</a>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">
                                    <table id="stock_alert_table" class="display data__table data_tble" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('S/L') }}</th>
                                                <th>{{ __('Product') }}</th>
                                                {{-- <th>{{ __('Product Code(SKU)') }}</th> --}}
                                                <th>{{ location_label() }}</th>
                                                <th>{{ __('Alert Qty') }}</th>
                                                <th>{{ __('Current Stock') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section class="dashboard_table_section">
                        <div class="form_element rounded m-0">
                            <div class="section-header justify-content-between">
                                <h6><span class="fas fa-table"></span> {{ __('Sales Order') }}</h6>
                                <a href="#">@lang('menu.see_more')</a>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">
                                    <table id="sales_order_table" class="display data__table data_tble" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Order ID') }}</th>
                                                <th>{{ location_label() }}</th>
                                                <th>{{ __('Customer') }}</th>
                                                <th>{{ __('Delivery Status') }}</th>
                                                <th>{{ __('Total Amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section class="dashboard_table_section">
                        <div class="form_element rounded m-0">
                            <div class="section-header justify-content-between">
                                <h6><span class="fas fa-table"></span>{{ __('Sales Due Invoices') }}</h6>
                                <a href="#">{{ __('See More') }}</a>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">

                                    <table id="sales_due_invoices" class="display data__table data_tble due_table" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Customer') }}</th>
                                                <th>{{ __('Invoice ID') }}</th>
                                                <th>{{ location_label() }}</th>
                                                <th>{{ __('Invoice Amount') }}</th>
                                                <th>{{ __('Curr. Due') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section class="dashboard_table_section">
                        <div class="form_element rounded m-0">
                            <div class="section-header justify-content-between">
                                <h6><span class="fas fa-table"></span>{{ __('Purchase Due Invoices') }}</h6>
                                <a href="#">{{ __('See More') }}</a>
                            </div>
                            <div class="widget_content">
                                <div class="table-responsive">

                                    <table id="purchase_due_invoices_table" class="display data__table data_tble" width="100%">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Supplier') }}</th>
                                                <th>{{ __('Invoice ID') }}</th>
                                                <th>{{ location_label() }}</th>
                                                <th>{{ __('Purchased Amount') }}</th>
                                                <th>{{ __('Curr. Due') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!--Add shortcut menu modal-->
        <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @else
        <div id="dashboard" class="pb-5">
            <div class="row">
                <div class="main__content"></div>
            </div>
            <br><br><br>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="text-primary display-5">{{ __('Welcome') }},
                        <strong>{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}!</strong>
                    </h1>
                </div>
            </div>
        </div>
    @endif

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    @if (auth()->user()->can('view_dashboard_data'))
        {{-- Dashboard issue solve for now --}}

        {{-- @if (auth()->user()->can('view_dashboard_data') and false) --}}
        <script>
            $(document).on('change', '#date', function() {
                var date_range = $(this).val();
                $('#date_range').val(date_range);
                var datePeriod = $(this).find('option:selected').data('date_period');
                $('#status_period_name').html(datePeriod);
                getCardAmount();
                saleOrderTable.ajax.reload();
                saleDueInvoices.ajax.reload();
                purchaseDueInvoicestable.ajax.reload();
            });

            $(document).on('change', '#branch_id', function() {
                getCardAmount();
                stockAlertTable.ajax.reload();
                saleOrderTable.ajax.reload();
                saleDueInvoices.ajax.reload();
                purchaseDueInvoicestable.ajax.reload();
            });

            var stockAlertTable = $('#stock_alert_table').DataTable({
                processing: true,
                serverSide: true,
                searchable: true,
                // "language": {
                //     "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
                // },
                "ajax": {
                    "url": "{{ route('dashboard.stock.alert') }}",
                    "data": function(d) {
                        d.branch_id = $('#branch_id').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    // { data: 'code', name: 'products.product_code' },
                    {
                        data: 'branch',
                        name: 'branches.name'
                    },
                    {
                        data: 'alert_quantity',
                        name: 'product_variants.variant_name',
                        className: 'fw-bold'
                    },
                    {
                        data: 'stock',
                        name: 'product_variants.variant_code',
                        className: 'fw-bold'
                    },
                ],
            });

            var saleOrderTable = $('#sales_order_table').DataTable({
                "processing": true,
                "serverSide": true,
                // "language": {
                //     "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
                // },
                "ajax": {
                    "url": "{{ route('dashboard.sales.order') }}",
                    "data": function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'date',
                        name: 'sales.date'
                    },
                    {
                        data: 'order_id',
                        name: 'sales.order_id'
                    },
                    {
                        data: 'branch',
                        name: 'branches.name'
                    },
                    {
                        data: 'customer',
                        name: 'customers.name'
                    },
                    {
                        data: 'delivery_status',
                        name: 'parentBranch.name',
                        className: 'text-danger fw-bold'
                    },
                    {
                        data: 'total_invoice_amount',
                        name: 'sales.total_invoice_amount',
                        className: 'fw-bold'
                    },
                ],
            });

            var saleDueInvoices = $('#sales_due_invoices').DataTable({
                "processing": true,
                "serverSide": true,
                // "language": {
                //     "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
                // },
                "ajax": {
                    "url": "{{ route('dashboard.sales.due.invoices') }}",
                    "data": function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'date',
                        name: 'sales.date'
                    },
                    {
                        data: 'customer',
                        name: 'customers.name'
                    },
                    {
                        data: 'invoice_id',
                        name: 'sales.invoice_id'
                    },
                    {
                        data: 'branch',
                        name: 'branches.name'
                    },
                    {
                        data: 'total_invoice_amount',
                        name: 'parentBranch.name',
                        className: 'fw-bold'
                    },
                    {
                        data: 'due',
                        name: 'sales.due',
                        className: 'text-danger fw-bold'
                    },
                ],
            });

            var purchaseDueInvoicestable = $('#purchase_due_invoices_table').DataTable({
                "processing": true,
                "serverSide": true,
                // "language": {
                //     "zeroRecords": '<img style="padding:100px 100px!important;" src="' + "{{ asset('images/data_not_found_default_photo.png') }}" + '">',
                // },
                "ajax": {
                    "url": "{{ route('dashboard.purchase.due.invoices') }}",
                    "data": function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'date',
                        name: 'purchases.date'
                    },
                    {
                        data: 'invoice_id',
                        name: 'purchases.invoice_id'
                    },
                    {
                        data: 'supplier',
                        name: 'suppliers.name'
                    },
                    {
                        data: 'branch',
                        name: 'branches.name'
                    },
                    {
                        data: 'total_purchase_amount',
                        name: 'parentBranch.name'
                    },
                    {
                        data: 'due',
                        name: 'purchases.due'
                    },
                ],
            });

            var __currency = "{{ $generalSettings['business_or_shop__currency_symbol'] }}";

            function getCardAmount() {

                var date_range = $('#date_range').val();
                var branch_id = $('#branch_id').val();
                $('.card_preloader').show();
                $('.card_amount').html('');
                $.ajax({
                    url: "{{ route('dashboard.card.data') }}",
                    type: 'get',
                    data: {
                        branch_id,
                        date_range
                    },
                    success: function(data) {
                        $('.card_preloader').hide();
                        $('#total_purchase').html(__currency + ' ' + data.totalPurchase);
                        $('#total_sale').html(__currency + ' ' + data.total_sale);
                        $('#total_purchase_due').html(__currency + ' ' + data.totalPurchaseDue);
                        $('#total_sale_due').html(__currency + ' ' + data.totalSaleDue);
                        $('#total_expense').html(__currency + ' ' + data.totalExpense);
                        $('#total_user').html(data.users);
                        $('#total_product').html(data.products);
                        $('#total_adjustment').html(__currency + ' ' + data.totalAdjustment);
                    }
                });
            }
            getCardAmount();

            $(document).on('click', '#addShortcutBtn', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.get(url, function(data) {
                    $('#shortcutMenuModal').html(data);
                    $('#shortcutMenuModal').modal('show');
                });
            });

            $(document).on('change', '#check_menu', function() {
                $('#add_shortcut_menu_form').submit();
            });

            $(document).on('submit', '#add_shortcut_menu_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        allShortcutMenus();
                        toastr.success(data);
                    }
                });
            });

            // Get all shortcut menus by ajax
            function allShortcutMenus() {
                $.ajax({
                    url: "{{ route('short.menus.show', \App\Enums\ShortMenuScreenType::DashboardScreen->value) }}",
                    type: 'get',
                    success: function(data) {
                        $('.switch_bar_cards').html(data);
                    }
                });
            }
            allShortcutMenus();

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

                            toastr.error("{{ __('Net Connection Error.') }}");
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        }
                    }
                });
            });
        </script>
    @endif
@endpush
